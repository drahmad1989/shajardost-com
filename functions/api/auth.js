/* ShajarDost — /api/auth
 * GET    → session check  {ok, configured}
 * POST   → login {password} → sets HttpOnly cookie
 * DELETE → logout (clears cookie)
 */

import { json, isAuthed, makeSessionCookie, clearSessionCookie, safeEqual } from '../lib/auth.js';

export async function onRequestGet({ request, env }) {
  return json({ ok: await isAuthed(request, env), configured: !!env.ADMIN_PASSWORD });
}

export async function onRequestPost({ request, env }) {
  if (!env.ADMIN_PASSWORD) {
    return json({ ok: false, error: 'ADMIN_PASSWORD secret not set — deploy config missing' }, 500);
  }
  let body = {};
  try { body = await request.json(); } catch (e) { /* noop */ }
  const pw = body && body.password;
  if (!pw || !(await safeEqual(pw, env.ADMIN_PASSWORD))) {
    return json({ ok: false, error: 'Galat password — dobara koshish karein' }, 401);
  }
  return json({ ok: true }, 200, { 'Set-Cookie': await makeSessionCookie(env.ADMIN_PASSWORD) });
}

export async function onRequestDelete() {
  return json({ ok: true }, 200, { 'Set-Cookie': clearSessionCookie() });
}
