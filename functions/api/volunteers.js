/* ShajarDost — /api/volunteers  (v1.0.24 — Volunteer tracking, ADMIN ONLY)
 *
 * GOLDEN RULES:
 *   1. Volunteer records KABHI public nahi hote — is API ka koi public endpoint hi nahi hai.
 *      (phone/email/safeguarding data sirf admin dashboard par.)
 *   2. Certificates sirf verified contribution par — issue action backend hi ID banata hai.
 *   3. Har change history[] mein record (audit trail).
 *
 * v1.0.24 (ChatGPT-approved design: Option B+D — admin tracking + 3 levels):
 *   - Records lead.js se auto-bante hain (volunteer form submit) — status 'new'.
 *   - Admin: status (new/contacted/active/waitlist/inactive), task assign,
 *     activity log (task + hours + verified), 3 levels, certificate issue.
 *   - Certificate register: cert:<ms>:<hex4> records (ID, type, recipient, contribution).
 *     IDs: SD-VOL-YYYY-NNN (volunteer cert) / SD-AL-YYYY-NNN (student activity letter).
 *     Public verification page INTENTIONALLY NAHI (volume ke baad decide hoga).
 *
 * GET              → sab volunteers (admin) + stats
 * GET ?certs=1     → certificate register (admin, read-only)
 * POST  (admin)    → manual add (phone-par-aaya applicant) {name, phone, city, age, availability}
 * PATCH (admin)    → {key, action:'status'|'task'|'log'|'level'|'cert'|'cert-eligible',
 *                     status?|task?|deadline?|logTask?|logHours?|logVerified?|logOutcome?|level?|certType?|
 *                     notes?|guardian?|consent?|phone?|email?|city?|availability?|role?}
 * DELETE ?key=...  → remove (admin)
 */

import { json, isAuthed, unauthorized } from '../lib/auth.js';

const KRE = /^vol:[0-9]+:[a-f0-9]+$/;
const STATUSES = ['new', 'contacted', 'active', 'waitlist', 'inactive'];
const LEVELS = ['supporter', 'active', 'team-lead'];
const CONSENTS = ['n/a', 'pending', 'received'];
const CERT_TYPES = { 'volunteer': 'SD-VOL', 'activity-letter': 'SD-AL' };
const CAP = { name: 80, city: 60, phone: 40, email: 200, age: 10, availability: 60, role: 60, skills: 400, message: 2000, task: 200, deadline: 40, outcome: 500, notes: 1000, guardian: 120 };

function clean(v, cap) {
  return String(v == null ? '' : v).trim().slice(0, cap);
}

function pkt(ms) {
  return new Date(ms + 5 * 3600000).toISOString().slice(0, 19).replace('T', ' ');
}

function yearPKT(ms) {
  return new Date(ms + 5 * 3600000).getUTCFullYear();
}

function hex4() {
  return [...crypto.getRandomValues(new Uint8Array(4))].map(b => b.toString(16).padStart(2, '0')).join('');
}

function meta(v) {
  return { n: (v.name || '').slice(0, 60), s: v.status || 'new', t: v.ts || Date.now(), c: (v.city || '').slice(0, 40) };
}

/* verified hours = sirf verified:true log entries ka sum (self-reported alag) */
function verifiedHours(v) {
  let h = 0;
  for (const e of (v.log || [])) {
    const n = parseFloat(e.hours);
    if (e.verified && Number.isFinite(n)) h += n;
  }
  return Math.round(h * 10) / 10;
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

/* Certificate ID — us type + saal ke maujooda certs ginti ka agla number */
async function nextCertId(kv, type) {
  const yr = yearPKT(Date.now());
  const all = await listAll(kv, 'cert:', 1000);
  let max = 0;
  for (const c of all) {
    if (c.type === type && c.id && c.id.indexOf('-' + yr + '-') !== -1) {
      const m = /(\d+)$/.exec(c.id);
      if (m) { const n = parseInt(m[1], 10); if (n > max) max = n; }
    }
  }
  return { id: CERT_TYPES[type] + '-' + yr + '-' + String(max + 1).padStart(3, '0'), yr };
}

export async function onRequestGet({ request, env }) {
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);
  if (!(await isAuthed(request, env))) return unauthorized();
  const url = new URL(request.url);

  /* Certificate register (read-only) */
  if (url.searchParams.get('certs') === '1') {
    const all = await listAll(env.LEADS_KV, 'cert:', 1000);
    all.sort((a, b) => (b.issued_ms || 0) - (a.issued_ms || 0));
    return json({ ok: true, certs: all, generated_at: Date.now() });
  }

  const all = await listAll(env.LEADS_KV, 'vol:', 500);
  all.sort((a, b) => (b.ts || 0) - (a.ts || 0));
  const stats = { total: all.length, new: 0, contacted: 0, active: 0, waitlist: 0, inactive: 0, hours_verified: 0, certs_issued: 0 };
  for (const v of all) {
    if (stats[v.status] !== undefined) stats[v.status]++;
    stats.hours_verified += verifiedHours(v);
    if (v.certStatus === 'issued') stats.certs_issued++;
  }
  stats.hours_verified = Math.round(stats.hours_verified * 10) / 10;
  return json({ ok: true, volunteers: all, stats, generated_at: Date.now() });
}

/* Manual add — direct call par aaya applicant (48-hour callback flow) */
export async function onRequestPost({ request, env }) {
  if (!(await isAuthed(request, env))) return unauthorized();
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);

  const b = await request.json().catch(() => ({}));
  const name = clean(b.name, CAP.name);
  if (!name) return json({ ok: false, error: 'naam zaroori hai' }, 400);

  const nowMs = Date.now();
  const ageNum = parseInt(clean(b.age, CAP.age), 10);
  const rec = {
    key: 'vol:' + nowMs + ':' + hex4(),
    name,
    phone: clean(b.phone, CAP.phone),
    email: clean(b.email, CAP.email),
    city: clean(b.city, CAP.city),
    age: clean(b.age, CAP.age),
    ageGroup: ageNum ? (ageNum < 18 ? '14-17' : '18+') : '',
    availability: clean(b.availability, CAP.availability),
    role: clean(b.role, CAP.role),
    skills: '',
    message: '',
    status: 'new',
    level: 'supporter',
    task: '',
    taskDeadline: '',
    log: [],
    certStatus: 'none',
    certType: '',
    certId: '',
    guardian: '',
    consent: ageNum && ageNum < 18 ? 'pending' : 'n/a',
    notes: '',
    source: 'admin-manual',
    ts: nowMs,
    created_at: pkt(nowMs),
    history: [{ t: nowMs, a: 'created', d: 'admin manual (phone applicant)' }]
  };
  try {
    await env.LEADS_KV.put(rec.key, JSON.stringify(rec), { metadata: meta(rec) });
  } catch (e) {
    return json({ ok: false, error: 'storage failed' }, 500);
  }
  return json({ ok: true, volunteer: rec });
}

export async function onRequestPatch({ request, env }) {
  if (!(await isAuthed(request, env))) return unauthorized();
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);

  const b = await request.json().catch(() => ({}));
  const key = b.key || '';
  if (!KRE.test(key)) return json({ ok: false, error: 'bad key' }, 400);
  const v = await env.LEADS_KV.get(key, 'json');
  if (!v) return json({ ok: false, error: 'not found' }, 404);

  if (!Array.isArray(v.history)) v.history = [];
  function hist(a, dd) {
    v.history.push({ t: Date.now(), a, d: dd });
    if (v.history.length > 100) v.history = v.history.slice(-100);
  }

  let changed = false;

  switch (b.action) {
    case 'status':
      if (!STATUSES.includes(b.status)) return json({ ok: false, error: 'bad status' }, 400);
      if (v.status !== b.status) { v.status = b.status; hist('status', '→ ' + b.status); changed = true; }
      break;

    case 'task': {
      const t = clean(b.task, CAP.task);
      const dl = clean(b.deadline, CAP.deadline);
      if (!t) return json({ ok: false, error: 'task likhna zaroori hai' }, 400);
      v.task = t; v.taskDeadline = dl;
      hist('task', 'Task: ' + t + (dl ? ' (deadline: ' + dl + ')' : ''));
      changed = true;
      break;
    }

    case 'log': {
      const t = clean(b.logTask, CAP.task);
      const outcome = clean(b.logOutcome, CAP.outcome);
      let h = parseFloat(b.logHours);
      if (!Number.isFinite(h)) h = 0;
      if (h < 0) h = 0; if (h > 24) h = 24;
      if (!t) return json({ ok: false, error: 'task likhna zaroori hai' }, 400);
      if (!Array.isArray(v.log)) v.log = [];
      v.log.push({ t: Date.now(), task: t, hours: h, verified: b.logVerified === true, outcome });
      if (v.log.length > 100) v.log = v.log.slice(-100);
      hist('log', 'Activity: ' + t + ' — ' + h + 'h' + (b.logVerified === true ? ' (verified)' : ' (unverified)'));
      changed = true;
      break;
    }

    case 'level':
      if (!LEVELS.includes(b.level)) return json({ ok: false, error: 'bad level' }, 400);
      if (v.level !== b.level) { v.level = b.level; hist('level', '→ ' + b.level); changed = true; }
      break;

    case 'cert-eligible':
      if (v.certStatus !== 'issued') { v.certStatus = 'eligible'; hist('cert', 'eligible mark kiya'); changed = true; }
      break;

    case 'cert': {
      const type = b.certType;
      if (!CERT_TYPES[type]) return json({ ok: false, error: 'bad certType' }, 400);
      if (v.certStatus === 'issued') return json({ ok: false, error: 'certificate pehle se issue ho chuka hai (' + v.certId + ')' }, 400);
      const vh = verifiedHours(v);
      const acts = (v.log || []).length;
      const { id } = await nextCertId(env.LEADS_KV, type);
      const nowMs = Date.now();
      v.certStatus = 'issued'; v.certType = type; v.certId = id;
      const certRec = {
        key: 'cert:' + nowMs + ':' + hex4(),
        id,
        type,
        recipient: v.name || '(naam nahi)',
        city: v.city || '',
        contribution: acts + ' logged activities, ' + vh + ' verified hours',
        issued_ms: nowMs,
        issued: pkt(nowMs),
        ref: key,
        authority: 'Muhammad Ahmad Baloch — Founder, ShajarDost'
      };
      await env.LEADS_KV.put(certRec.key, JSON.stringify(certRec), { metadata: { n: certRec.recipient.slice(0, 60), ty: type, t: nowMs } });
      hist('cert', type + ' issue hua — ' + id);
      changed = true;
      break;
    }
  }

  /* Field edits */
  for (const f of ['notes', 'guardian', 'phone', 'email', 'city', 'availability', 'role']) {
    if (b[f] !== undefined) {
      const val = clean(b[f], CAP[f]);
      if ((v[f] || '') !== val) { v[f] = val; hist('edit', f + ' update'); changed = true; }
    }
  }
  if (b.consent !== undefined) {
    if (!CONSENTS.includes(b.consent)) return json({ ok: false, error: 'bad consent' }, 400);
    if (v.consent !== b.consent) { v.consent = b.consent; hist('consent', '→ ' + b.consent); changed = true; }
  }

  if (changed) await env.LEADS_KV.put(key, JSON.stringify(v), { metadata: meta(v) });
  return json({ ok: true, volunteer: v });
}

export async function onRequestDelete({ request, env }) {
  if (!(await isAuthed(request, env))) return unauthorized();
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);
  const key = new URL(request.url).searchParams.get('key') || '';
  if (!KRE.test(key)) return json({ ok: false, error: 'bad key' }, 400);
  await env.LEADS_KV.delete(key);
  return json({ ok: true });
}
