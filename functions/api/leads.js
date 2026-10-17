/* ShajarDost — /api/leads  (ADMIN — auth required)
 * GET    ?status=&q=     → list (metadata only) + stats
 * GET    ?key=lead:...   → single full lead
 * PATCH  {key, status?, notes?} → update lead
 * DELETE ?key=lead:...   → remove lead
 */

import { json, isAuthed, unauthorized } from '../lib/auth.js';

const STATUSES = ['new', 'contacted', 'in-progress', 'won', 'closed', 'spam'];
const OPEN_STATUSES = ['new', 'contacted', 'in-progress'];
const DAY = 86400000;

async function listAll(kv) {
  const out = [];
  let cursor;
  for (let page = 0; page < 5; page++) {
    const opts = { prefix: 'lead:', limit: 1000 };
    if (cursor) opts.cursor = cursor;
    const r = await kv.list(opts);
    for (const k of r.keys) out.push(k);
    if (r.list_complete) break;
    cursor = r.cursor;
  }
  return out;
}

export async function onRequestGet({ request, env }) {
  if (!(await isAuthed(request, env))) return unauthorized();
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);

  const url = new URL(request.url);
  const key = url.searchParams.get('key');
  if (key) {
    if (!/^lead:[0-9]+:[a-f0-9]+$/.test(key)) return json({ ok: false, error: 'bad key' }, 400);
    const v = await env.LEADS_KV.get(key, 'json');
    if (!v) return json({ ok: false, error: 'not found' }, 404);
    return json({ ok: true, lead: v });
  }

  const status = url.searchParams.get('status') || '';
  const q = (url.searchParams.get('q') || '').trim().toLowerCase();

  const entries = await listAll(env.LEADS_KV);

  /* Stats from metadata (no value reads) */
  const dayStartMs = new Date(new Date(Date.now() + 5 * 3600000).toISOString().slice(0, 10) + 'T00:00:00Z').getTime() - 5 * 3600000;
  const dayEndMs = dayStartMs + DAY;
  const weekAgo = Date.now() - 7 * 86400000;
  const now = Date.now();
  const stats = { total: entries.length, new: 0, today: 0, week: 0, spam: 0, due_today: 0, overdue: 0 };
  for (const e of entries) {
    const m = e.metadata || {};
    const t = m.t || 0;
    if (m.s === 'new') stats.new++;
    if (m.s === 'spam') stats.spam++;
    if (t >= dayStartMs) stats.today++;
    if (t >= weekAgo) stats.week++;
    /* v1.0.5 follow-up due counters (sirf open leads) */
    if (m.na && OPEN_STATUSES.includes(m.s)) {
      if (m.na < dayEndMs) stats.due_today++;
      if (m.na < now) stats.overdue++;
    }
  }

  let rows = entries.map(e => ({
    key: e.name,
    n: (e.metadata && e.metadata.n) || '(no name)',
    p: (e.metadata && e.metadata.p) || '',
    i: (e.metadata && e.metadata.i) || '',
    s: (e.metadata && e.metadata.s) || 'new',
    t: (e.metadata && e.metadata.t) || 0,
    na: (e.metadata && e.metadata.na) || 0
  }));

  /* newest first (key = lead:<ms>:<rand>, ms same length → lexicographic works) */
  rows.sort((a, b) => b.t - a.t);

  if (status && STATUSES.includes(status)) rows = rows.filter(r => r.s === status);
  if (q) {
    rows = rows.filter(r =>
      (r.n || '').toLowerCase().includes(q) ||
      (r.p || '').includes(q) ||
      (r.i || '').toLowerCase().includes(q)
    );
  }
  rows = rows.slice(0, 300);

  return json({ ok: true, leads: rows, stats, generated_at: Date.now() });
}

export async function onRequestPatch({ request, env }) {
  if (!(await isAuthed(request, env))) return unauthorized();
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);

  const b = await request.json().catch(() => ({}));
  const key = b.key || '';
  if (!/^lead:[0-9]+:[a-f0-9]+$/.test(key)) return json({ ok: false, error: 'bad key' }, 400);

  const lead = await env.LEADS_KV.get(key, 'json');
  if (!lead) return json({ ok: false, error: 'not found' }, 404);

  function hist(action, detail) {
    if (!Array.isArray(lead.history)) lead.history = [];
    lead.history.push({ t: Date.now(), a: action, d: detail });
    if (lead.history.length > 100) lead.history = lead.history.slice(-100);
  }

  let changed = false;

  /* v1.0.5 explicit action: "rabta ho gaya" → Day 3/7/7 cadence */
  if (b.action === 'log_contact') {
    lead.last_contact_at = Date.now();
    lead.followup_count = (lead.followup_count || 0) + 1;
    const days = [3, 7, 7][Math.min(lead.followup_count - 1, 2)];
    lead.next_action_at = lead.last_contact_at + days * DAY;
    lead.next_action_type = 'call';
    if (lead.status === 'new') lead.status = 'contacted';
    hist('contact', 'Rabta ho gaya — agla follow-up ' + days + ' din baad');
    changed = true;
  }

  if (b.next_action_at !== undefined) {
    const ms = parseInt(b.next_action_at, 10);
    if (Number.isFinite(ms) && ms > 0 && ms !== lead.next_action_at) {
      lead.next_action_at = ms;
      lead.next_action_type = 'call';
      hist('next-action', new Date(ms + 5 * 3600000).toISOString().slice(0, 16).replace('T', ' ') + ' PKT set ki gayi');
      changed = true;
    }
  }

  if (b.closed_reason !== undefined) {
    const cr = String(b.closed_reason || '').slice(0, 300);
    if ((lead.closed_reason || '') !== cr) { lead.closed_reason = cr; changed = true; }
  }

  if (b.status !== undefined) {
    if (!STATUSES.includes(b.status)) return json({ ok: false, error: 'bad status' }, 400);
    if (lead.status !== b.status) {
      hist('status', (lead.status || 'new') + ' → ' + b.status);
      lead.status = b.status;
      changed = true;
    }
  }
  if (b.notes !== undefined) {
    const notes = String(b.notes || '').slice(0, 2000);
    if (lead.notes !== notes) { lead.notes = notes; changed = true; }
  }
  if (changed) {
    const meta = {
      n: (lead.name || '(no name)').slice(0, 60),
      p: (lead.phone || '').slice(0, 30),
      i: (lead.interest || '').slice(0, 40),
      s: lead.status,
      t: lead.created_ms || Date.now(),
      na: lead.next_action_at || 0
    };
    await env.LEADS_KV.put(key, JSON.stringify(lead), { metadata: meta });
  }
  return json({ ok: true, lead });
}

export async function onRequestDelete({ request, env }) {
  if (!(await isAuthed(request, env))) return unauthorized();
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);

  const key = new URL(request.url).searchParams.get('key') || '';
  if (!/^lead:[0-9]+:[a-f0-9]+$/.test(key)) return json({ ok: false, error: 'bad key' }, 400);
  await env.LEADS_KV.delete(key);
  return json({ ok: true });
}
