/* ShajarDost — /api/mission50  (v1.0.24 — Mission Fifty submissions, ADMIN ONLY)
 *
 * Mission Fifty = public participation movement (har insan apni zindagi mein
 * kam az kam 50 poday lagaye, videos ke saath #ShajarDost #MissionFifty share kare).
 * Ye volunteer programme se ALAG hai — participants ko volunteer nahi banaya jata.
 *
 * GOLDEN RULES (ChatGPT-approved design):
 *   1. Submissions sirf admin verification ke baad aage barhti hain.
 *   2. Planting count != surviving trees — dono alag rakhe jate hain.
 *      verifiedPlants = admin-reviewed quantity; survival claim KABHI nahi hota.
 *   3. Completion certificate (SD-M50-YYYY-NNN) sirf verifiedPlants >= 50 par.
 *   4. Koi public endpoint NAHI — featuring manual hai (Facebook/YouTube, permission ke saath).
 *
 * Status flow: submitted → review → verified → milestone50 (ya rejected)
 *
 * GET              → sab submissions (admin) + stats
 * PATCH (admin)    → {key, action:'status'|'verify'|'cert'|'note',
 *                     status?|qty?|video?|notes?|city?|name?|period?|locCat?}
 * DELETE ?key=...  → remove (admin)
 */

import { json, isAuthed, unauthorized } from '../lib/auth.js';

const KRE = /^m50:[0-9]+:[a-f0-9]+$/;
const STATUSES = ['submitted', 'review', 'verified', 'milestone50', 'rejected'];
const CERT_PREFIX = 'SD-M50';
const CAP = { name: 80, city: 60, phone: 40, email: 200, qty: 20, period: 60, locCat: 60, video: 300, message: 1500, notes: 600 };

function clean(v, cap) {
  return String(v == null ? '' : v).trim().slice(0, cap);
}

function pkt(ms) {
  return new Date(ms + 5 * 3600000).toISOString().slice(0, 19).replace('T', ' ');
}

function hex4() {
  return [...crypto.getRandomValues(new Uint8Array(4))].map(b => b.toString(16).padStart(2, '0')).join('');
}

function meta(m) {
  return { n: (m.name || '').slice(0, 60), s: m.status || 'submitted', t: m.ts || Date.now(), c: (m.city || '').slice(0, 40) };
}

async function listAll(kv, prefix, cap) {
  const keys = [];
  let cursor;
  for (let page = 0; page < 5; page++) {
    const opts = { prefix, limit: 1000 };
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

function parseQty(s) {
  const m = /([\d,]+)/.exec(String(s == null ? '' : s).replace(/,/g, ','));
  if (!m) return 0;
  const n = parseInt(m[1].replace(/,/g, ''), 10);
  return Number.isFinite(n) ? n : 0;
}

async function nextM50Id(kv) {
  const yr = new Date(Date.now() + 5 * 3600000).getUTCFullYear();
  const all = await listAll(kv, 'cert:', 1000);
  let max = 0;
  for (const c of all) {
    if (c.type === 'mission50' && c.id && c.id.indexOf('-' + yr + '-') !== -1) {
      const m = /(\d+)$/.exec(c.id);
      if (m) { const n = parseInt(m[1], 10); if (n > max) max = n; }
    }
  }
  return CERT_PREFIX + '-' + yr + '-' + String(max + 1).padStart(3, '0');
}

export async function onRequestGet({ request, env }) {
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);
  if (!(await isAuthed(request, env))) return unauthorized();

  const all = await listAll(env.LEADS_KV, 'm50:', 500);
  all.sort((a, b) => (b.ts || 0) - (a.ts || 0));
  const stats = { total: all.length, submitted: 0, review: 0, verified: 0, milestone50: 0, rejected: 0, plants_claimed: 0, plants_verified: 0 };
  for (const m of all) {
    if (stats[m.status] !== undefined) stats[m.status]++;
    stats.plants_claimed += parseQty(m.qty);
    stats.plants_verified += m.verifiedPlants || 0;
  }
  return json({ ok: true, subs: all, stats, generated_at: Date.now() });
}

export async function onRequestPatch({ request, env }) {
  if (!(await isAuthed(request, env))) return unauthorized();
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);

  const b = await request.json().catch(() => ({}));
  const key = b.key || '';
  if (!KRE.test(key)) return json({ ok: false, error: 'bad key' }, 400);
  const m = await env.LEADS_KV.get(key, 'json');
  if (!m) return json({ ok: false, error: 'not found' }, 404);

  if (!Array.isArray(m.history)) m.history = [];
  function hist(a, dd) {
    m.history.push({ t: Date.now(), a, d: dd });
    if (m.history.length > 100) m.history = m.history.slice(-100);
  }

  let changed = false;

  switch (b.action) {
    case 'status':
      if (!STATUSES.includes(b.status)) return json({ ok: false, error: 'bad status' }, 400);
      if (b.status === 'milestone50') return json({ ok: false, error: 'milestone sirf verify ke zariye (50+ plants)' }, 400);
      if (m.status !== b.status) { m.status = b.status; hist('status', '→ ' + b.status); changed = true; }
      break;

    case 'verify': {
      let q = parseInt(b.qty, 10);
      if (!Number.isFinite(q) || q < 0) q = 0;
      if (q > 100000) q = 100000;
      m.verifiedPlants = q;
      m.status = 'verified';
      hist('verify', 'Verified plants: ' + q + (b.note ? ' — ' + clean(b.note, 200) : ''));
      changed = true;
      break;
    }

    case 'cert': {
      if ((m.verifiedPlants || 0) < 50) return json({ ok: false, error: 'Mission 50 certificate sirf 50+ verified plants par (abhi: ' + (m.verifiedPlants || 0) + ')' }, 400);
      if (m.certId) return json({ ok: false, error: 'certificate pehle se issue ho chuka hai (' + m.certId + ')' }, 400);
      const id = await nextM50Id(env.LEADS_KV);
      const nowMs = Date.now();
      m.status = 'milestone50';
      m.certId = id;
      const certRec = {
        key: 'cert:' + nowMs + ':' + hex4(),
        id,
        type: 'mission50',
        recipient: m.name || '(naam nahi)',
        city: m.city || '',
        contribution: 'Mission Fifty milestone — ' + (m.verifiedPlants || 0) + ' plants verified (planting record; survival claim nahi)',
        issued_ms: nowMs,
        issued: pkt(nowMs),
        ref: key,
        authority: 'Muhammad Ahmad Baloch — Founder, ShajarDost'
      };
      await env.LEADS_KV.put(certRec.key, JSON.stringify(certRec), { metadata: { n: certRec.recipient.slice(0, 60), ty: 'mission50', t: nowMs } });
      hist('cert', 'Mission 50 completion certificate — ' + id);
      changed = true;
      break;
    }
  }

  /* Field edits */
  for (const f of ['notes', 'video', 'qty', 'city', 'name', 'period', 'locCat']) {
    if (b[f] !== undefined) {
      const val = clean(b[f], CAP[f]);
      if ((m[f] || '') !== val) { m[f] = val; hist('edit', f + ' update'); changed = true; }
    }
  }

  if (changed) await env.LEADS_KV.put(key, JSON.stringify(m), { metadata: meta(m) });
  return json({ ok: true, sub: m });
}

export async function onRequestDelete({ request, env }) {
  if (!(await isAuthed(request, env))) return unauthorized();
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);
  const key = new URL(request.url).searchParams.get('key') || '';
  if (!KRE.test(key)) return json({ ok: false, error: 'bad key' }, 400);
  await env.LEADS_KV.delete(key);
  return json({ ok: true });
}
