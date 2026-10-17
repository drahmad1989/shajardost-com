/* ShajarDost — /api/lead  (PUBLIC intake endpoint)
 * Called by site forms (assets/js/script.js capture hook) as a redundant
 * backup alongside Web3Forms email delivery. Stores the lead in Cloudflare KV.
 *
 * Spam control: honeypot fields (botcheck / website / _hp) silently dropped.
 */

import { json } from '../lib/auth.js';

const CAP = { name: 120, phone: 40, email: 200, subject: 200, interest: 120, message: 4000, source: 120, user_agent: 300 };
const DAY = 86400000;
const RATE_LIMIT_PER_HOUR = 30;

function clean(v, cap) {
  return String(v == null ? '' : v).trim().slice(0, cap);
}

export async function onRequestPost({ request, env, context }) {
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);

  /* v1.0.5 soft rate limit — per IP per hour, fail-open (CGNAT-safe threshold) */
  try {
    const ip = request.headers.get('CF-Connecting-IP') || 'unknown';
    const hour = Math.floor(Date.now() / 3600000);
    const rk = 'rl:' + ip + ':' + hour;
    const cur = parseInt(await env.LEADS_KV.get(rk) || '0', 10);
    if (cur >= RATE_LIMIT_PER_HOUR) {
      return json({ ok: false, error: 'bohat zyada requests — thori dair baad koshish karein' }, 429);
    }
    await env.LEADS_KV.put(rk, String(cur + 1), { expirationTtl: 7200 });
  } catch (e) { /* rate limit fail-open — lead intake kabhi block na ho */ }

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
    notes: '',
    /* v1.0.5 follow-up engine — Day 1/3/7 cadence (reminders owner ke liye) */
    next_action_at: nowMs + DAY,
    next_action_type: 'call',
    last_contact_at: null,
    followup_count: 0,
    closed_reason: '',
    history: [{ t: nowMs, a: 'created', d: source || 'site' }]
  };

  const meta = { n: name.slice(0, 60) || '(no name)', p: lead.phone.slice(0, 30), i: lead.interest.slice(0, 40), s: 'new', t: nowMs, na: lead.next_action_at };

  try {
    await env.LEADS_KV.put(key, JSON.stringify(lead), { metadata: meta });
  } catch (e) {
    return json({ ok: false, error: 'storage failed' }, 500);
  }

  /* v1.0.5 auto-acknowledgment email (Resend) — fire-and-forget.
   * RESEND_API_KEY secret set na ho to silently skip (lead phir bhi safe).
   * Email fail ho to bhi lead intake kabi fail nahi hota. */
  if (lead.email && env.RESEND_API_KEY && context && context.waitUntil) {
    context.waitUntil(sendAck(env, lead).catch(function () { }));
  }
  return json({ ok: true, id: key });
}

async function sendAck(env, lead) {
  const ref = (lead.key || '').slice(-6).toUpperCase();
  const from = env.ACK_FROM || 'ShajarDost <no-reply@shajardost.com>';
  const nm = lead.name ? lead.name.split(' ')[0] : '';
  const html = '<div style="font-family:Arial,sans-serif;max-width:560px;margin:0 auto;border:1px solid #e8e5e0;border-radius:12px;overflow:hidden">'
    + '<div style="background:#1b4332;color:#fff;padding:18px 22px"><b style="font-size:17px">\uD83C\uDF31 ShajarDost</b><div style="font-size:12px;opacity:.85">shajardost.com \u2014 Plant Trees in Pakistan</div></div>'
    + '<div style="padding:22px;color:#1a1a1a;font-size:14px;line-height:1.7">'
    + '<p>Assalam-o-Alaikum' + (nm ? ' <b>' + nm + '</b>' : '') + ',</p>'
    + '<p>Aap ki request hum tak pohanch gayi hai. <b>24 ghantay ke andar</b> hamari team aap se rabta karegi (In Sha Allah).</p>'
    + '<p style="background:#fef9e7;border:1px solid #f0dc9a;border-radius:8px;padding:10px 14px"><b>Aap ka reference:</b> SD-' + ref + '</p>'
    + '<p>Foran rabta karna ho to call karein: <b><a href="tel:03333197977" style="color:#2d6a4f">0333-3197977</a></b></p>'
    + '<p style="color:#7a7872;font-size:12.5px;margin-top:18px">Shukriya ke aap ne ped lagane mein hamara saath diya \u2014 har ped zindagi deti hai.</p>'
    + '</div></div>';
  await fetch('https://api.resend.com/emails', {
    method: 'POST',
    headers: { 'Authorization': 'Bearer ' + env.RESEND_API_KEY, 'Content-Type': 'application/json' },
    body: JSON.stringify({
      from: from,
      to: [lead.email],
      subject: 'Shukriya' + (nm ? ' ' + nm : '') + '! ShajarDost ko aap ki request mil gayi [SD-' + ref + ']',
      html: html
    })
  });
}
