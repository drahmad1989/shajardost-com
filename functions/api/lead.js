/* ShajarDost — /api/lead  (PUBLIC intake endpoint)
 * Called by site forms (assets/js/script.js capture hook) as a redundant
 * backup alongside Web3Forms email delivery. Stores the lead in Cloudflare KV.
 *
 * Spam control: honeypot fields (botcheck / website / _hp) silently dropped.
 */

import { json } from '../lib/auth.js';

const CAP = { name: 120, phone: 40, email: 200, subject: 200, interest: 120, message: 4000, source: 120, user_agent: 300 };

function clean(v, cap) {
  return String(v == null ? '' : v).trim().slice(0, cap);
}

export async function onRequestPost({ request, env }) {
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);

  const ct = request.headers.get('Content-Type') || '';
  let d = {};
  if (ct.includes('application/json')) {
    try { d = await request.json(); } catch (e) { return json({ ok: false, error: 'bad json' }, 400); }
  } else {
    try {
      const fd = await request.formData();
      for (const [k, v] of fd.entries()) d[k] = v;
    } catch (e) { return json({ ok: false, error: 'bad form' }, 400); }
  }

  /* Honeypot — bots fill hidden fields; humans never see them */
  if (clean(d.botcheck, 10) || clean(d.website, 10) || clean(d._hp, 10)) {
    return json({ ok: true }); /* pretend success, store nothing */
  }

  const name = clean(d.name, CAP.name);
  const message = clean(d.message, CAP.message);
  const email = clean(d.email, CAP.email);
  /* name/message/email — koi ek bhi kaafi hai (newsletter forms sirf email bhejte hain) */
  if (!name && !message && !email) return json({ ok: false, error: 'name, message ya email zaroori hai' }, 400);

  /* Source page: explicit _source field, else Referer path */
  let source = clean(d._source, CAP.source);
  if (!source) {
    const ref = request.headers.get('Referer') || '';
    try { source = new URL(ref).pathname; } catch (e) { source = ''; }
  }

  const nowMs = Date.now();
  const created = new Date(nowMs + 5 * 3600000).toISOString().slice(0, 19).replace('T', ' ');
  const rnd = [...crypto.getRandomValues(new Uint8Array(4))].map(b => b.toString(16).padStart(2, '0')).join('');
  const key = 'lead:' + nowMs + ':' + rnd;

  const lead = {
    key,
    created_ms: nowMs,
    created_at: created, /* Pakistan time (UTC+5) */
    name,
    phone: clean(d.phone, CAP.phone),
    email: clean(d.email, CAP.email),
    subject: clean(d.subject, CAP.subject),
    interest: clean(d.interest, CAP.interest),
    message,
    source,
    user_agent: clean(request.headers.get('User-Agent'), CAP.user_agent),
    status: 'new',
    notes: ''
  };

  const meta = { n: name.slice(0, 60) || '(no name)', p: lead.phone.slice(0, 30), i: lead.interest.slice(0, 40), s: 'new', t: nowMs };

  try {
    await env.LEADS_KV.put(key, JSON.stringify(lead), { metadata: meta });
  } catch (e) {
    return json({ ok: false, error: 'storage failed' }, 500);
  }
  return json({ ok: true, id: key });
}
