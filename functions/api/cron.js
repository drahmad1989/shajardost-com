/* ShajarDost — /api/cron  (v1.0.5 — scheduled tasks endpoint)
 * Auth: X-Cron-Secret header (CRON_SECRET secret) YA admin cookie.
 * POST { task: 'backup' }  → poora leads snapshot BACKUP_KV me versioned save
 *                            (40 snapshots retention) + 'latest' pointer update
 * POST { task: 'report' }  → weekly report generate; OWNER_EMAIL + RESEND_API_KEY
 *                            set hon to email bhi jata hai; report KV me save hoti hai
 * Backup independent namespace me hota hai taake LEADS_KV corrupt hone par bhi
 * recovery possible rahe. Restore test: scripts/backup-restore-test.py
 */

import { json, isAuthed, safeEqual, unauthorized } from '../lib/auth.js';

const DAY = 86400000;
const OPEN_STATUSES = ['new', 'contacted', 'in-progress'];
const SNAP_PREFIX = 'snap:';
const KEEP_SNAPSHOTS = 40;
const MAX_LEADS = 1000;

function pktDate(ms) {
  return new Date(ms + 5 * 3600000).toISOString().slice(0, 19).replace('T', ' ');
}

async function listKeys(kv, prefix) {
  const out = [];
  let cursor;
  for (let page = 0; page < 10; page++) {
    const opts = { prefix, limit: 1000 };
    if (cursor) opts.cursor = cursor;
    const r = await kv.list(opts);
    for (const k of r.keys) out.push(k);
    if (r.list_complete) break;
    cursor = r.cursor;
  }
  return out;
}

async function readAllLeads(kv) {
  const keys = await listKeys(kv, 'lead:');
  const leads = [];
  for (const k of keys.slice(0, MAX_LEADS)) {
    const v = await kv.get(k.name, 'json');
    if (v) leads.push(v);
  }
  return leads;
}

/* ---------------- BACKUP ---------------- */
async function doBackup(env) {
  if (!env.BACKUP_KV) return json({ ok: false, error: 'BACKUP_KV not bound — project config patch zaroori hai' }, 503);

  const leads = await readAllLeads(env.LEADS_KV);
  const now = Date.now();
  const payload = JSON.stringify({ ts: now, count: leads.length, leads });

  /* checksum (detection: agar backup khud corrupt ho) */
  const digest = await crypto.subtle.digest('SHA-256', new TextEncoder().encode(payload));
  const checksum = [...new Uint8Array(digest)].map(b => b.toString(16).padStart(2, '0')).join('');

  const key = SNAP_PREFIX + new Date(now + 5 * 3600000).toISOString().slice(0, 16).replace('T', '-').replace(':', '-');
  await env.BACKUP_KV.put(key, payload);
  await env.BACKUP_KV.put('latest', JSON.stringify({ key, count: leads.length, ts: now, checksum }));

  /* retention: 40 se purane snapshots delete */
  const snaps = await listKeys(env.BACKUP_KV, SNAP_PREFIX);
  snaps.sort((a, b) => a.name.localeCompare(b.name));
  let deleted = 0;
  for (let i = 0; i < snaps.length - KEEP_SNAPSHOTS; i++) {
    await env.BACKUP_KV.delete(snaps[i].name);
    deleted++;
  }

  return json({ ok: true, task: 'backup', count: leads.length, snapshot: key, deleted_old: deleted, at: pktDate(now) + ' PKT' });
}

/* ---------------- WEEKLY REPORT ---------------- */
async function doReport(env) {
  const now = Date.now();
  const dayStartMs = new Date(new Date(now + 5 * 3600000).toISOString().slice(0, 10) + 'T00:00:00Z').getTime() - 5 * 3600000;
  const dayEndMs = dayStartMs + DAY;
  const weekAgo = now - 7 * DAY;

  const keys = await listKeys(env.LEADS_KV, 'lead:');
  const byStatus = { new: 0, contacted: 0, 'in-progress': 0, won: 0, closed: 0, spam: 0 };
  let total = 0, today = 0, week = 0, dueToday = 0, overdue = 0, ackPending = 0;
  const overdueList = [];

  /* value reads ek hi loop me */
  for (const e of keys) {
    const v = await env.LEADS_KV.get(e.name, 'json');
    if (!v) continue;
    total++;
    byStatus[v.status] = (byStatus[v.status] || 0) + 1;
    const t = v.created_ms || 0;
    if (t >= dayStartMs) today++;
    if (t >= weekAgo) week++;
    const na = v.next_action_at || 0;
    if (na && OPEN_STATUSES.includes(v.status)) {
      if (na < dayEndMs) {
        dueToday++;
        if (na < now) {
          overdue++;
          if (overdueList.length < 20) {
            overdueList.push({ name: v.name || '(no name)', phone: v.phone || '', days_over: Math.floor((now - na) / DAY) });
          }
        }
      }
    }
    if (v.ack_email === 'failed') ackPending++;
  }

  let lastBackup = null;
  if (env.BACKUP_KV) {
    try { lastBackup = await env.BACKUP_KV.get('latest', 'json'); } catch (e) { /* noop */ }
  }

  const report = {
    ok: true,
    task: 'report',
    generated_at: pktDate(now) + ' PKT',
    week: { new_leads: week, today: today },
    total_leads: total,
    by_status: byStatus,
    followups: { due_today: dueToday, overdue: overdue, overdue_list: overdueList },
    backup: lastBackup ? { last: lastBackup.key, count: lastBackup.count, ts: pktDate(lastBackup.ts) + ' PKT' } : 'koi backup nahi — /api/cron task=backup chalayein',
    note: 'Payments manual confirm hote hain — form inquiry revenue NAHI hai (verified collections alag track karein)'
  };

  /* report KV me save (list 'lead:' prefix ko touch nahi karti) */
  try { await env.LEADS_KV.put('report:last', JSON.stringify(report)); } catch (e) { /* noop */ }

  /* email bhejo agar Resend configured hai */
  let emailed = false;
  if (env.RESEND_API_KEY && env.OWNER_EMAIL) {
    try {
      const rows = overdueList.map(o => '<tr><td style="padding:6px 10px;border:1px solid #e8e5e0">' + o.name + '</td><td style="padding:6px 10px;border:1px solid #e8e5e0">' + o.phone + '</td><td style="padding:6px 10px;border:1px solid #e8e5e0">' + o.days_over + ' din</td></tr>').join('');
      const html = '<div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;border:1px solid #e8e5e0;border-radius:12px;overflow:hidden">'
        + '<div style="background:#1b4332;color:#fff;padding:16px 22px"><b>ShajarDost Weekly Report</b><div style="font-size:12px;opacity:.85">' + report.generated_at + '</div></div>'
        + '<div style="padding:20px;font-size:14px;line-height:1.8;color:#1a1a1a">'
        + '<p><b>Is hafte naye lead:</b> ' + week + ' &nbsp;•&nbsp; <b>Total:</b> ' + total + '</p>'
        + '<p><b>Naya:</b> ' + byStatus.new + ' • <b>Rabta kiya:</b> ' + byStatus.contacted + ' • <b>Chal raha:</b> ' + byStatus['in-progress'] + ' • <b>Muafaqiyat:</b> ' + byStatus.won + '</p>'
        + '<p><b>Aaj ke follow-ups:</b> ' + dueToday + ' &nbsp;•&nbsp; <b>Overdue:</b> <span style="color:#c0392b;font-weight:700">' + overdue + '</span></p>'
        + (rows ? '<table style="border-collapse:collapse;font-size:13px;margin:10px 0"><tr><th style="padding:6px 10px;border:1px solid #e8e5e0;background:#d8e9df">Naam</th><th style="padding:6px 10px;border:1px solid #e8e5e0;background:#d8e9df">Phone</th><th style="padding:6px 10px;border:1px solid #e8e5e0;background:#d8e9df">Kitne din late</th></tr>' + rows + '</table>' : '')
        + '<p style="font-size:12.5px;color:#7a7872">Backup: ' + (lastBackup ? lastBackup.key + ' (' + lastBackup.count + ' leads)' : 'ABHI TAK Nahi — backup chalayein!') + '</p>'
        + '<p style="font-size:12px;color:#7a7872;margin-top:14px">Dashboard: https://shajardost.com/admin</p>'
        + '</div></div>';
      const r = await fetch('https://api.resend.com/emails', {
        method: 'POST',
        headers: { 'Authorization': 'Bearer ' + env.RESEND_API_KEY, 'Content-Type': 'application/json' },
        body: JSON.stringify({ from: env.ACK_FROM || 'ShajarDost <no-reply@shajardost.com>', to: [env.OWNER_EMAIL], subject: 'ShajarDost weekly report — ' + week + ' naye lead, ' + overdue + ' overdue', html })
      });
      emailed = r.ok;
    } catch (e) { emailed = false; }
  }
  report.emailed = emailed;
  return json(report);
}

/* ---------------- ROUTER ---------------- */
export async function onRequestPost({ request, env }) {
  const secret = request.headers.get('X-Cron-Secret') || '';
  const bySecret = env.CRON_SECRET ? await safeEqual(secret, env.CRON_SECRET) : false;
  const byCookie = bySecret ? false : await isAuthed(request, env);
  if (!bySecret && !byCookie) return unauthorized();
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);

  const b = await request.json().catch(() => ({}));
  if (b.task === 'backup') return doBackup(env);
  if (b.task === 'report') return doReport(env);
  return json({ ok: false, error: 'unknown task — backup ya report' }, 400);
}
