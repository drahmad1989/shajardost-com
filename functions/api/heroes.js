/* ShajarDost — /api/heroes  (v1.0.11 — Green Heroes of Pakistan)
 *
 * Neutral honor wall: Pakistan ke shajar doston ko promote karna —
 * ShajarDost affiliation NAHI dikhani (owner rule). Suggestion form se
 * aane wali entries PENDING rehti hain — admin approve kare tabhi public.
 * Suggester ka naam/phone PUBLIC response mein KABHI nahi jata.
 *
 * GET  ?public=1            → sirf approved heroes (public fields only)
 * GET   (admin)             → sab heroes + stats
 * POST  ?mode=suggest       → PUBLIC suggestion (honeypot + consent + rate limit)
 * POST   (admin)            → manual add {name, city, detail, photo, trees, since, award, approve}
 * PATCH (admin)             → {key, action:'approve'|'reject'|'unpublish'|'hide',
 *                              name?, city?, detail?, photo?, trees?, since?, award?}
 * DELETE ?key=hero:...      → remove (admin)
 */

import { json, isAuthed, unauthorized } from '../lib/auth.js';

const KRE = /^hero:[0-9]+:[a-f0-9]+$/;
const CAP = { name: 80, city: 60, detail: 1500, photo: 300, trees: 40, since: 40, award: 120, suggester_name: 80, suggester_phone: 40, source: 120 };
const DAY = 86400000;
const RATE_LIMIT_PER_HOUR = 10;

function clean(v, cap) {
  return String(v == null ? '' : v).trim().slice(0, cap);
}

function pkt(ms) {
  return new Date(ms + 5 * 3600000).toISOString().slice(0, 19).replace('T', ' ');
}

function hex4() {
  return [...crypto.getRandomValues(new Uint8Array(4))].map(b => b.toString(16).padStart(2, '0')).join('');
}

/* Public text par phone-jaisi sequences scrub (suggester galti se apna number
 * detail mein likh de to bhi public na ho) */
function scrub(t) {
  return String(t == null ? '' : t).replace(/(\+?\d[\d\s\-().]{7,}\d)/g, '[number hidden]');
}

function meta(h) {
  return {
    n: (h.name || '').slice(0, 60),
    s: h.status || 'pending',
    t: h.ts || Date.now(),
    c: (h.city || '').slice(0, 40)
  };
}

async function listAll(kv, cap) {
  const keys = [];
  let cursor;
  for (let page = 0; page < 5; page++) {
    const opts = { prefix: 'hero:', limit: 1000 };
    if (cursor) opts.cursor = cursor;
    const r = await kv.list(opts);
    for (const k of r.keys) keys.push(k.name);
    if (r.list_complete) break;
    cursor = r.cursor;
  }
  keys.sort();
  const slice = keys.slice(-cap);
  const vals = await Promise.all(slice.map(k => kv.get(k, 'json')));
  return vals.filter(Boolean);
}

function publicView(h) {
  return {
    key: h.key,
    name: clean(h.name, CAP.name) || '(naam nahi)',
    city: clean(h.city, CAP.city),
    detail: scrub(h.detail || ''),
    photo: clean(h.photo, CAP.photo),
    trees: clean(h.trees, CAP.trees),
    since: clean(h.since, CAP.since),
    award: scrub(clean(h.award, CAP.award)),
    status: h.status,
    ts: h.ts || 0,
    approved_ts: h.approved_ts || null
    /* suggester_name / suggester_phone / email — PUBLIC MEIN KABHI NAHI */
  };
}

export async function onRequestGet({ request, env }) {
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);
  const url = new URL(request.url);

  if (url.searchParams.get('public') === '1') {
    const all = await listAll(env.LEADS_KV, 500);
    const rows = all.filter(h => h.status === 'approved').map(publicView);
    rows.sort((a, b) => (b.approved_ts || b.ts) - (a.approved_ts || a.ts));
    return json({ ok: true, heroes: rows.slice(0, 300), generated_at: Date.now() });
  }

  if (!(await isAuthed(request, env))) return unauthorized();

  const key = url.searchParams.get('key');
  if (key) {
    if (!KRE.test(key)) return json({ ok: false, error: 'bad key' }, 400);
    const v = await env.LEADS_KV.get(key, 'json');
    if (!v) return json({ ok: false, error: 'not found' }, 404);
    return json({ ok: true, hero: v });
  }

  const all = await listAll(env.LEADS_KV, 500);
  all.sort((a, b) => (b.ts || 0) - (a.ts || 0));
  const stats = { total: all.length, pending: 0, approved: 0, hidden: 0, rejected: 0 };
  for (const h of all) if (stats[h.status] !== undefined) stats[h.status]++;
  return json({ ok: true, heroes: all, stats, generated_at: Date.now() });
}

/* PUBLIC suggestion — koi bhi kisi shajar dost ko suggest kar sakta hai.
 * Suggester phone sirf admin ke paas. Consent checkbox ZAROORI. */
export async function onRequestPost({ request, env }) {
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);
  const url = new URL(request.url);
  const suggesting = url.searchParams.get('mode') === 'suggest';

  if (!suggesting) {
    if (!(await isAuthed(request, env))) return unauthorized();
  } else {
    /* soft rate limit — per IP per hour, fail-open */
    try {
      const ip = request.headers.get('CF-Connecting-IP') || 'unknown';
      const hour = Math.floor(Date.now() / 3600000);
      const rk = 'rlh:' + ip + ':' + hour;
      const cur = parseInt(await env.LEADS_KV.get(rk) || '0', 10);
      if (cur >= RATE_LIMIT_PER_HOUR) return json({ ok: false, error: 'bohat zyada requests — thori dair baad koshish karein' }, 429);
      await env.LEADS_KV.put(rk, String(cur + 1), { expirationTtl: 7200 });
    } catch (e) { /* fail-open */ }
  }

  const b = await request.json().catch(() => ({}));

  if (suggesting) {
    if (clean(b.botcheck, 10)) return json({ ok: true }); /* honeypot — pretend ok */
    if (clean(b.consent, 10) !== 'yes') return json({ ok: false, error: 'ijazat (consent) zaroori hai' }, 400);
  }

  const nowMs = Date.now();
  const key = 'hero:' + nowMs + ':' + hex4();
  const approve = b.approve === true;
  const rec = {
    key,
    name: clean(b.name, CAP.name),
    city: clean(b.city, CAP.city),
    detail: clean(b.detail, CAP.detail),
    photo: clean(b.photo, CAP.photo),
    trees: clean(b.trees, CAP.trees),
    since: clean(b.since, CAP.since),
    award: clean(b.award, CAP.award),
    suggester_name: suggesting ? clean(b.suggester_name, CAP.suggester_name) : '',
    suggester_phone: suggesting ? clean(b.suggester_phone, CAP.suggester_phone) : '',
    status: approve ? 'approved' : 'pending',
    ts: nowMs,
    created_at: pkt(nowMs),
    approved_ts: approve ? nowMs : null,
    source: suggesting ? 'suggest-form' : 'admin-manual',
    history: [{ t: nowMs, a: 'created', d: suggesting ? 'suggest form' : 'admin manual' }]
  };
  if (!rec.name) return json({ ok: false, error: 'naam zaroori hai' }, 400);

  try {
    await env.LEADS_KV.put(key, JSON.stringify(rec), { metadata: meta(rec) });
  } catch (e) {
    return json({ ok: false, error: 'storage failed' }, 500);
  }
  return json({ ok: true, hero: rec });
}

export async function onRequestPatch({ request, env }) {
  if (!(await isAuthed(request, env))) return unauthorized();
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);

  const b = await request.json().catch(() => ({}));
  const key = b.key || '';
  if (!KRE.test(key)) return json({ ok: false, error: 'bad key' }, 400);
  const h = await env.LEADS_KV.get(key, 'json');
  if (!h) return json({ ok: false, error: 'not found' }, 404);

  if (!Array.isArray(h.history)) h.history = [];
  function hist(a, dd) {
    h.history.push({ t: Date.now(), a, d: dd });
    if (h.history.length > 100) h.history = h.history.slice(-100);
  }

  let changed = false;
  const nowMs = Date.now();

  switch (b.action) {
    case 'approve':
      if (h.status !== 'approved') { h.status = 'approved'; h.approved_ts = nowMs; hist('approve', 'PUBLIC ho gaya'); changed = true; }
      break;
    case 'reject':
      if (h.status !== 'rejected') { h.status = 'rejected'; hist('reject', 'Reject'); changed = true; }
      break;
    case 'unpublish':
    case 'hide':
      if (h.status === 'approved') { h.status = 'hidden'; hist('hide', 'Wall se hataya'); changed = true; }
      break;
  }

  for (const f of ['name', 'city', 'detail', 'photo', 'trees', 'since', 'award']) {
    if (b[f] !== undefined) {
      const v = clean(b[f], CAP[f] || 200);
      if ((h[f] || '') !== v) { h[f] = v; hist('edit', f + ' update'); changed = true; }
    }
  }

  if (changed) await env.LEADS_KV.put(key, JSON.stringify(h), { metadata: meta(h) });
  return json({ ok: true, hero: h });
}

export async function onRequestDelete({ request, env }) {
  if (!(await isAuthed(request, env))) return unauthorized();
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);
  const key = new URL(request.url).searchParams.get('key') || '';
  if (!KRE.test(key)) return json({ ok: false, error: 'bad key' }, 400);
  await env.LEADS_KV.delete(key);
  return json({ ok: true });
}
