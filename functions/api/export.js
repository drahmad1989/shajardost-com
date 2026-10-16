/* ShajarDost — /api/export  (ADMIN — auth required)
 * Full CSV backup of all leads (Excel-friendly UTF-8 BOM).
 */

import { isAuthed, unauthorized, json } from '../lib/auth.js';

const COLS = ['key', 'created_at', 'name', 'phone', 'email', 'subject', 'interest', 'message', 'source', 'status', 'notes', 'user_agent'];

function csvCell(v) {
  const s = String(v == null ? '' : v);
  return '"' + s.replace(/"/g, '""') + '"';
}

export async function onRequestGet({ request, env }) {
  if (!(await isAuthed(request, env))) return unauthorized();
  if (!env.LEADS_KV) return json({ ok: false, error: 'KV_NOT_BOUND' }, 503);

  const lines = [COLS.join(',')];
  let cursor;
  for (let page = 0; page < 5; page++) {
    const opts = { prefix: 'lead:', limit: 1000 };
    if (cursor) opts.cursor = cursor;
    const r = await env.LEADS_KV.list(opts);
    for (const e of r.keys) {
      const v = await env.LEADS_KV.get(e.name, 'json');
      if (v) lines.push(COLS.map(c => csvCell(v[c])).join(','));
    }
    if (r.list_complete) break;
    cursor = r.cursor;
  }

  const csv = '\uFEFF' + lines.join('\r\n');
  const date = new Date(Date.now() + 5 * 3600000).toISOString().slice(0, 10);
  return new Response(csv, {
    headers: {
      'Content-Type': 'text/csv; charset=utf-8',
      'Content-Disposition': 'attachment; filename="shajardost-leads-' + date + '.csv"',
      'Cache-Control': 'no-store'
    }
  });
}
