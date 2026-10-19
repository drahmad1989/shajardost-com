/* ShajarDost — /api/demands  (v1.0.22 — Demand Wall + district)
 *
 * Public board: plant demands (mang) + received plants (supply).
 * GOLDEN RULES:
 *   1. Kuch bhi public nahi hota jab tak admin APPROVE na kare (status pending).
 *   2. Mobile number / email PUBLIC response mein KABHI nahi jata (defence-in-depth scrub bhi hai).
 *   3. Public text par phone-jaisi sequences auto-scrub hoti hain.
 *
 * v1.0.22: optional `district` field (admin-assigned, Punjab zila).
 *   - Old records bina district ke bhi poora chalte hain (public: district='').
 *   - Public summary figures client-side same public data se banti hain — ONE source of truth.
 *
 * GET  ?public=1            → sirf approved/matched/fulfilled (public fields only)
 * GET   (admin)             → sab demands (phone included) + stats
 * GET  ?key=demand:...      → single (admin)
 * POST  (admin)             → manual add {type:'demand'|'supply', name, city, district, phone, plants, qty, detail, approve}
 * PATCH (admin)             → {key, action:'approve'|'reject'|'match'|'fulfill'|'reopen'|'unpublish',
 *                              name?, city?, district?, plants?, qty?, detail?, public_detail?}
 * DELETE ?key=demand:...    → remove (admin)
 */

import { json, isAuthed, unauthorized } from '../lib/auth.js';

const KRE = /^demand:[0-9]+:[a-f0-9]+$/;
const PUBLIC_STATUSES = ['approved', 'matched', 'fulfilled'];
const STATUSES = ['pending', 'approved', 'matched', 'fulfilled', 'rejected'];
const CAP = { name: 80, city: 60, district: 40, phone: 40, plants: 150, qty: 40, detail: 1500, public_detail: 1500, source: 120, cost: 40, sponsor: 60 };

function clean(v, cap) {
  return String(v == null ? '' : v).trim().slice(0, cap);
}

function pkt(ms) {
  return new Date(ms + 5 * 3600000).toISOString().slice(0, 19).replace('T', ' ');
}

function hex4() {
  return [...crypto.getRandomValues(new Uint8Array(4))].map(b => b.toString(16).padStart(2, '0')).join('');
}

/* Phone-like runs (03xx-xxxxxxx, +92..., 0300 1234567, 11-digit etc) public text se nikal do */
function scrub(t) {
  return String(t == null ? '' : t).replace(/(\+?\d[\d\s\-().]{7,}\d)/g, '[number hidden]');
}

function meta(d) {
  return {
    n: (d.name || '').slice(0, 60),
    ty: d.type || 'demand',
    s: d.status || 'pending',
    t: d.ts || Date.now(),
    c: (d.city || '').slice(0, 40)
  };
}

async function listAll(kv, cap) {
  const keys = [];
  let cursor;
  for (let page = 0; page < 5; page++) {
    const opts = { prefix: 'demand:', limit: 1000 };
    if (cursor) opts.cursor = cursor;
    const r = await kv.list(opts);
    for (const k of r.keys) keys.push(k.name);
    if (r.list_complete) break;
    cursor = r.cursor;
  }
  keys.sort(); /* newest last (ms in key) */
  const slice = keys.slice(-cap);
  const vals = await Promise.all(slice.map(k => kv.get(k, 'json')));
  return vals.filter(Boolean);
}

function publicView(d) {
  return {
    key: d.key,
    ref: 'FP-' + (d.key || '').slice(-4).toUpperCase(),
    type: d.type === 'supply' ? 'supply' : 'demand',
    name: clean(d.name, 80) || '(naam nahi)',
    city: clean(d.city, 60),
    district: clean(d.district, 40),
    plants: clean(d.plants, 150),
    qty: clean(d.qty, 40),
    detail: scrub(d.public_detail || d.detail || ''),
    cost: clean(d.cost, 40),
    sponsor: clean(d.sponsor, 60),
    status: d.status,
    ts: d.ts || 0,
    approved_ts: d.approved_ts || null
  };
}

export async function onRequestGet({ request, env }) {
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);
  const url = new URL(request.url);

  /* PUBLIC wall — sirf approved entries, phone/email kahin nahi */
  if (url.searchParams.get('public') === '1') {
    const all = await listAll(env.LEADS_KV, 500);
    const rows = all.filter(d => PUBLIC_STATUSES.includes(d.status)).map(publicView);
    rows.sort((a, b) => (b.approved_ts || b.ts) - (a.approved_ts || a.ts));
    return json({ ok: true, demands: rows.slice(0, 300), generated_at: Date.now() });
  }

  if (!(await isAuthed(request, env))) return unauthorized();

  const key = url.searchParams.get('key');
  if (key) {
    if (!KRE.test(key)) return json({ ok: false, error: 'bad key' }, 400);
    const v = await env.LEADS_KV.get(key, 'json');
    if (!v) return json({ ok: false, error: 'not found' }, 404);
    return json({ ok: true, demand: v });
  }

  const all = await listAll(env.LEADS_KV, 500);
  all.sort((a, b) => (b.ts || 0) - (a.ts || 0));
  const stats = { total: all.length, pending: 0, approved: 0, matched: 0, fulfilled: 0, rejected: 0, today: 0 };
  const dayStart = new Date(new Date(Date.now() + 5 * 3600000).toISOString().slice(0, 10) + 'T00:00:00Z').getTime() - 5 * 3600000;
  for (const d of all) {
    if (stats[d.status] !== undefined) stats[d.status]++;
    if ((d.ts || 0) >= dayStart) stats.today++;
  }
  return json({ ok: true, demands: all, stats, generated_at: Date.now() });
}

/* Admin manual add — supply (aaye hue pouday) ya phone-par-aayi demand */
export async function onRequestPost({ request, env }) {
  if (!(await isAuthed(request, env))) return unauthorized();
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);

  const b = await request.json().catch(() => ({}));
  const name = clean(b.name, CAP.name);
  const detail = clean(b.detail, CAP.detail);
  if (!name) return json({ ok: false, error: 'naam zaroori hai' }, 400);

  const nowMs = Date.now();
  const key = 'demand:' + nowMs + ':' + hex4();
  const approve = b.approve === true;
  const rec = {
    key,
    type: b.type === 'supply' ? 'supply' : 'demand',
    name,
    city: clean(b.city, CAP.city),
    district: clean(b.district, CAP.district),
    phone: clean(b.phone, CAP.phone),
    plants: clean(b.plants, CAP.plants),
    qty: clean(b.qty, CAP.qty),
    detail,
    public_detail: '',
    status: approve ? 'approved' : 'pending',
    ts: nowMs,
    created_at: pkt(nowMs),
    approved_ts: approve ? nowMs : null,
    source: 'admin-manual',
    history: [{ t: nowMs, a: 'created', d: approve ? 'admin manual + approve' : 'admin manual (pending)' }]
  };
  try {
    await env.LEADS_KV.put(key, JSON.stringify(rec), { metadata: meta(rec) });
  } catch (e) {
    return json({ ok: false, error: 'storage failed' }, 500);
  }
  return json({ ok: true, demand: rec });
}

export async function onRequestPatch({ request, env }) {
  if (!(await isAuthed(request, env))) return unauthorized();
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);

  const b = await request.json().catch(() => ({}));
  const key = b.key || '';
  if (!KRE.test(key)) return json({ ok: false, error: 'bad key' }, 400);
  const d = await env.LEADS_KV.get(key, 'json');
  if (!d) return json({ ok: false, error: 'not found' }, 404);

  if (!Array.isArray(d.history)) d.history = [];
  function hist(a, dd) {
    d.history.push({ t: Date.now(), a, d: dd });
    if (d.history.length > 100) d.history = d.history.slice(-100);
  }

  let changed = false;
  const nowMs = Date.now();

  switch (b.action) {
    case 'approve':
      if (d.status !== 'approved' && d.status !== 'matched' && d.status !== 'fulfilled') {
        d.status = 'approved'; d.approved_ts = nowMs; hist('approve', 'PUBLIC ho gaya'); changed = true;
      }
      break;
    case 'reject':
      if (d.status !== 'rejected') { d.status = 'rejected'; hist('reject', 'Reject — public nahi hoga'); changed = true; }
      break;
    case 'match':
      if (d.status === 'approved' || d.status === 'pending') {
        d.status = 'matched'; if (!d.approved_ts) d.approved_ts = nowMs;
        const sp = clean(b.sponsor, CAP.sponsor);
        if (sp) d.sponsor = sp;
        hist('match', 'Sponser ban gaya' + (sp ? ': ' + sp : '')); changed = true;
      }
      break;
    case 'fulfill':
      if (d.status !== 'fulfilled') { d.status = 'fulfilled'; if (!d.approved_ts) d.approved_ts = nowMs; hist('fulfill', 'Poora ho gaya'); changed = true; }
      break;
    case 'reopen':
      if (d.status === 'matched' || d.status === 'fulfilled') { d.status = 'approved'; d.sponsor = ''; hist('reopen', 'Dobara open'); changed = true; }
      break;
    case 'unpublish':
      if (PUBLIC_STATUSES.includes(d.status)) { d.status = 'pending'; d.approved_ts = null; hist('unpublish', 'Wall se hataya — wapis pending'); changed = true; }
      break;
  }

  /* Field edits (admin public text saaf karta hai — phone wagera) */
  for (const f of ['name', 'city', 'district', 'plants', 'qty', 'detail', 'public_detail', 'cost', 'sponsor']) {
    if (b[f] !== undefined) {
      const v = clean(b[f], CAP[f] || 200);
      if ((d[f] || '') !== v) { d[f] = v; hist('edit', f + ' update'); changed = true; }
    }
  }

  if (changed) await env.LEADS_KV.put(key, JSON.stringify(d), { metadata: meta(d) });
  return json({ ok: true, demand: d });
}

export async function onRequestDelete({ request, env }) {
  if (!(await isAuthed(request, env))) return unauthorized();
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);
  const key = new URL(request.url).searchParams.get('key') || '';
  if (!KRE.test(key)) return json({ ok: false, error: 'bad key' }, 400);
  await env.LEADS_KV.delete(key);
  return json({ ok: true });
}
