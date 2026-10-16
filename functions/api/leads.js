/* ShajarDost — /api/leads  (ADMIN — auth required)
 * GET    ?status=&q=     → list (metadata only) + stats
 * GET    ?key=lead:...   → single full lead
 * PATCH  {key, status?, notes?} → update lead
 * DELETE ?key=lead:...   → remove lead
 */

import { json, isAuthed, unauthorized } from '../lib/auth.js';

const STATUSES = ['new', 'contacted', 'in-progress', 'won', 'closed', 'spam'];

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
  const weekAgo = Date.now() - 7 * 86400000;
  const stats = { total: entries.length, new: 0, today: 0, week: 0, spam: 0 };
  for (const e of entries) {
    const m = e.metadata || {};
    const t = m.t || 0;
    if (m.s === 'new') stats.new++;
    if (m.s === 'spam') stats.spam++;
    if (t >= dayStartMs) stats.today++;
    if (t >= weekAgo) stats.week++;
  }

  let rows = entries.map(e => ({
    key: e.name,
    n: (e.metadata && e.metadata.n) || '(no name)',
    p: (e.metadata && e.metadata.p) || '',
    i: (e.metadata && e.metadata.i) || '',
    s: (e.metadata && e.metadata.s) || 'new',
    t: (e.metadata && e.metadata.t) || 0
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

  let changed = false;
  if (b.status !== undefined) {
    if (!STATUSES.includes(b.status)) return json({ ok: false, error: 'bad status' }, 400);
    if (lead.status !== b.status) { lead.status = b.status; changed = true; }
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
      t: lead.created_ms || Date.now()
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
