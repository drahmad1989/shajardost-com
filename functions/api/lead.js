/* ShajarDost — /api/lead  (PUBLIC intake endpoint)
 * Called by site forms (assets/js/script.js capture hook) as a redundant
 * backup alongside Web3Forms email delivery. Stores the lead in Cloudflare KV.
 *
 * Spam control: honeypot fields (botcheck / website / _hp) silently dropped.
 */

import { json } from '../lib/auth.js';
/* v1.0.25: single source of truth — projects list + 12-task menu + interest blocks
 * (functions/lib/catalog.js) — auto-ack email isi se populate hota hai. */
import { PROJECTS, firstTaskFor, interestBlock } from '../lib/catalog.js';

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
      /* v1.0.24: skills[] jaise multi-value fields ab array mein jama hote hain
       * (pehle loop overwrite karta tha — sirf aakhri skill bachti thi) */
      for (const [k, v] of fd.entries()) {
        if (k.length > 2 && k.slice(-2) === '[]') {
          const bk = k.slice(0, -2);
          if (!Array.isArray(d[bk])) d[bk] = [];
          d[bk].push(v);
        } else {
          d[k] = v;
        }
      }
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

  /* v1.0.9 Demand Wall → v1.0.11 Free Plants: interest "Plant Demand" YA subject
   * mein "demand" YA source path /demand-wall|/free-plants → wall queue (PENDING —
   * admin approve karega tab public hoga; phone kabhi public nahi).
   * v1.0.11 BUG FIX: form mein interest field nahi tha, isliye asli form se
   * demand record nahi banta tha — ab subject + source path bhi check hote hain.
   * Demand record fail ho to bhi lead intake fail nahi hota. */
  if (/demand/i.test(lead.interest) || /demand/i.test(lead.subject) || /demand-wall|free-plants/i.test(lead.source)) {
    try {
      const dMs = Date.now();
      const dRnd = [...crypto.getRandomValues(new Uint8Array(4))].map(b => b.toString(16).padStart(2, '0')).join('');
      const dKey = 'demand:' + dMs + ':' + dRnd;
      const demand = {
        key: dKey,
        type: 'demand',
        name: name || '(naam nahi)',
        city: clean(d.city, 60),
        phone: lead.phone,
        /* v1.0.23: email bhi store (sirf admin contact ke liye — publicView whitelist se public kabhi nahi jata) */
        email: lead.email,
        /* v1.0.23 fix: form ka district ab demand record mein bhi save hota hai (v1.0.22 mein drop ho raha tha) */
        district: clean(d.district, 40),
        plants: clean(d.plants, 150),
        qty: clean(d.qty, 40),
        detail: message,
        public_detail: '',
        status: 'pending',
        ts: dMs,
        created_at: new Date(dMs + 5 * 3600000).toISOString().slice(0, 19).replace('T', ' '),
        approved_ts: null,
        source: source,
        history: [{ t: dMs, a: 'created', d: 'form: ' + (source || 'site') }]
      };
      await env.LEADS_KV.put(dKey, JSON.stringify(demand), {
        metadata: { n: demand.name.slice(0, 60), ty: 'demand', s: 'pending', t: dMs, c: demand.city.slice(0, 40) }
      });
    } catch (e) { /* demand wall enqueue fail-open */ }
  }

  /* v1.0.24 Volunteer record — volunteer form (source /volunteer ya interest Volunteer).
   * GOLDEN RULE: volunteer records KABHI public nahi hote — sirf /api/volunteers (admin) par.
   * Under-18 applicant → guardian consent 'pending' (48h call par verbal consent lena hai).
   * Fail-open — volunteer enqueue fail ho to bhi lead intake fail nahi hota. */
  if (/volunteer/i.test(lead.source) || /volunteer/i.test(lead.interest)) {
    try {
      const vMs = Date.now();
      const vRnd = [...crypto.getRandomValues(new Uint8Array(4))].map(b => b.toString(16).padStart(2, '0')).join('');
      const vKey = 'vol:' + vMs + ':' + vRnd;
      const ageNum = parseInt(clean(d.age, 10), 10);
      const under18 = ageNum ? ageNum < 18 : false;
      const skillsStr = Array.isArray(d.skills)
        ? d.skills.map(function (s) { return clean(s, 60); }).filter(Boolean).join(', ').slice(0, 400)
        : clean(d.skills, 400);
      /* v1.0.25: form ka role → suggested pehla task (playbook mapping) —
       * email mein yehi task jata hai; owner call par is ko confirm/adjust
       * karega (📋 modal mein pehle se bhari milegi, sirf deadline add karni hai). */
      const sug = firstTaskFor(clean(d.role, 60));
      const vol = {
        key: vKey,
        name: name || '(naam nahi)',
        phone: lead.phone,
        email: lead.email,
        city: clean(d.city, 60),
        age: clean(d.age, 10),
        ageGroup: under18 ? '14-17' : (ageNum ? '18+' : ''),
        availability: clean(d.availability, 60),
        role: clean(d.role, 60),
        skills: skillsStr,
        message: message,
        status: 'new',
        level: 'supporter',
        task: sug.title,
        taskDeadline: '',
        log: [],
        certStatus: 'none',
        certType: '',
        certId: '',
        guardian: '',
        consent: under18 ? (clean(d.guardian_ok, 10) ? 'received' : 'pending') : 'n/a',
        notes: '',
        source: source,
        ts: vMs,
        created_at: new Date(vMs + 5 * 3600000).toISOString().slice(0, 19).replace('T', ' '),
        history: [
          { t: vMs, a: 'created', d: 'form: ' + (source || 'site') },
          { t: vMs, a: 'task-suggested', d: sug.title + ' (email)' }
        ]
      };
      await env.LEADS_KV.put(vKey, JSON.stringify(vol), {
        metadata: { n: vol.name.slice(0, 60), s: 'new', t: vMs, c: vol.city.slice(0, 40) }
      });
    } catch (e) { /* volunteer enqueue fail-open */ }
  }

  /* v1.0.24 Mission Fifty submission — mission-fifty page ka progress form.
   * Planting count != surviving trees (dono alag); sirf admin verification ke baad
   * aage barhta hai. Public wall NAHI — featuring manual hai (FB/YouTube). Fail-open. */
  if (/mission-fifty/i.test(lead.source) || /mission ?50 (update|progress)/i.test(lead.interest)) {
    try {
      const mMs = Date.now();
      const mRnd = [...crypto.getRandomValues(new Uint8Array(4))].map(b => b.toString(16).padStart(2, '0')).join('');
      const mKey = 'm50:' + mMs + ':' + mRnd;
      const m50 = {
        key: mKey,
        name: name || '(naam nahi)',
        phone: lead.phone,
        email: lead.email,
        city: clean(d.city, 60),
        qty: clean(d.qty, 20),
        period: clean(d.period, 60),
        locCat: clean(d.locCat, 60),
        video: clean(d.video, 300),
        plantType: /historical|earlier/i.test(clean(d.plant_type, 20)) ? 'historical' : 'new',
        permission: /^(yes|on|true|1)$/i.test(clean(d.permission, 10)),
        message: message,
        status: 'submitted',
        verifiedPlants: 0,
        certId: '',
        notes: '',
        source: source,
        ts: mMs,
        created_at: new Date(mMs + 5 * 3600000).toISOString().slice(0, 19).replace('T', ' '),
        history: [{ t: mMs, a: 'created', d: 'form: ' + (source || 'site') }]
      };
      await env.LEADS_KV.put(mKey, JSON.stringify(m50), {
        metadata: { n: m50.name.slice(0, 60), s: 'submitted', t: mMs, c: m50.city.slice(0, 40) }
      });
    } catch (e) { /* mission50 enqueue fail-open */ }
  }

  /* v1.0.5 auto-acknowledgment email (Resend) — fire-and-forget.
   * RESEND_API_KEY secret set na ho to silently skip (lead phir bhi safe).
   * Email fail ho to bhi lead intake kabi fail nahi hota.
   * v1.0.27: failures ab console.error par visible (wrangler tail se pakde ja sakte hain). */
  if (lead.email && env.RESEND_API_KEY && context && context.waitUntil) {
    context.waitUntil(sendAck(env, lead, { role: clean(d.role, 60) }).catch(function (e) {
      console.error('[ack-email] FAILED:', (e && (e.stack || e.message)) || String(e));
    }));
  }
  return json({ ok: true, id: key });
}

async function sendAck(env, lead, extra) {
  const ref = (lead.key || '').slice(-6).toUpperCase();
  const from = env.ACK_FROM || 'ShajarDost <no-reply@shajardost.com>';
  const nm = lead.name ? lead.name.split(' ')[0] : '';

  /* v1.0.25: interest-specific block — catalog se (static content, XSS-safe) */
  const blk = interestBlock(lead, extra || {});
  let blockHtml = '';
  if (blk) {
    blockHtml = '<h3 style="margin:20px 0 8px;font-size:15px;color:#1b4332">' + blk.h + '</h3>';
    if (blk.task) {
      const t = blk.task;
      blockHtml += '<div style="background:#eef7f0;border:1px solid #bcd9c4;border-radius:8px;padding:10px 14px;margin:0 0 8px">'
        + '<b>' + t.title + '</b> <span style="color:#7a7872;font-size:12px">(' + t.hours + (t.where === 'on-site' ? ' \u2014 Kasur/Pattoki/Phool Nagar qareeb' : '') + ')</span><br>'
        + t.what + '<br>'
        + '<i style="font-size:12.5px;color:#7a7872">Saboot: ' + t.proof + ' \u2014 7 din ke andar. Call par yehi task confirm ya adjust hoga.</i>'
        + '</div>';
      if (t.where === 'on-site') {
        blockHtml += '<p style="margin:4px 0 8px;color:#7a7872;font-size:12.5px">Agar aap in sites se door hain to is email par reply karein \u2014 aap ko remote task de dein ge.</p>';
      }
    }
    blockHtml += '<ul style="margin:0 0 4px;padding-left:20px">'
      + blk.items.map(function (li) { return '<li style="margin:4px 0">' + li + '</li>'; }).join('')
      + '</ul>';
  }

  /* v1.0.25: projects list — HAR form filler ko (saray projects, 1-line each) */
  const projHtml = '<div style="margin-top:18px;padding-top:14px;border-top:1px solid #e8e5e0">'
    + '<h3 style="margin:0 0 8px;font-size:15px;color:#1b4332">ShajarDost ke Projects</h3>'
    + '<ul style="margin:0;padding-left:20px">'
    + PROJECTS.map(function (p) {
        return '<li style="margin:4px 0"><a href="https://shajardost.com' + p.url + '" style="color:#2d6a4f;font-weight:bold">' + p.name + '</a> \u2014 ' + p.line + '</li>';
      }).join('')
    + '</ul></div>';

  const html = '<div style="font-family:Arial,sans-serif;max-width:560px;margin:0 auto;border:1px solid #e8e5e0;border-radius:12px;overflow:hidden">'
    + '<div style="background:#1b4332;color:#fff;padding:18px 22px"><b style="font-size:17px">\uD83C\uDF31 ShajarDost</b><div style="font-size:12px;opacity:.85">shajardost.com \u2014 Plant Trees in Pakistan</div></div>'
    + '<div style="padding:22px;color:#1a1a1a;font-size:14px;line-height:1.7">'
    + '<p>Assalam-o-Alaikum' + (nm ? ' <b>' + nm + '</b>' : '') + ',</p>'
    + '<p>Aap ki request hum tak pohanch gayi hai. <b>24 ghantay ke andar</b> hamari team aap se rabta karegi (In Sha Allah).</p>'
    + '<p style="background:#fef9e7;border:1px solid #f0dc9a;border-radius:8px;padding:10px 14px"><b>Aap ka reference:</b> SD-' + ref + '</p>'
    + blockHtml
    + projHtml
    + '<p style="margin-top:16px">Foran rabta karna ho to call karein: <b><a href="tel:03333197977" style="color:#2d6a4f">0333-3197977</a></b></p>'
    + '<p style="color:#7a7872;font-size:12.5px;margin-top:18px">Shukriya ke aap ne ped lagane mein hamara saath diya \u2014 har ped zindagi deti hai.</p>'
    + '</div></div>';
  /* v1.0.27: Resend response ab log hota hai — 2xx par id, warna status+body
   * (wrangler pages deployment tail se foran nazar aata hai ke email gayi ya nahi). */
  const r = await fetch('https://api.resend.com/emails', {
    method: 'POST',
    headers: { 'Authorization': 'Bearer ' + env.RESEND_API_KEY, 'Content-Type': 'application/json' },
    body: JSON.stringify({
      from: from,
      to: [lead.email],
      /* v1.0.25: body mein projects list + interest block bhi (upar html) */
      subject: 'Shukriya' + (nm ? ' ' + nm : '') + '! ShajarDost ko aap ki request mil gayi [SD-' + ref + ']',
      html: html
    })
  });
  if (!r.ok) {
    const t = await r.text().catch(function () { return '(no body)'; });
    console.error('[ack-email] resend ' + r.status + ' for SD-' + ref + ':', String(t).slice(0, 400));
  } else {
    const j = await r.json().catch(function () { return {}; });
    console.log('[ack-email] sent SD-' + ref + ' id=' + (j.id || '?') + ' to=' + lead.email);
  }
}
