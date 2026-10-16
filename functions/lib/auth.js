/* ShajarDost — admin auth helpers (HMAC-signed cookie session)
 * Password itself never leaves the server (Pages secret: ADMIN_PASSWORD).
 * Token format: "<expiry_ms>.<hmac_sha256(password, 'sd-admin:'+expiry_ms)>"
 */

const enc = new TextEncoder();

function bytesToHex(buf) {
  return [...new Uint8Array(buf)].map(b => b.toString(16).padStart(2, '0')).join('');
}

export function json(obj, status = 200, extra = {}) {
  return new Response(JSON.stringify(obj), {
    status,
    headers: {
      'Content-Type': 'application/json; charset=utf-8',
      'Cache-Control': 'no-store',
      ...extra
    }
  });
}

export function getCookie(request, name) {
  const c = request.headers.get('Cookie') || '';
  const m = c.match(new RegExp('(?:^|;\\s*)' + name + '=([^;]+)'));
  return m ? decodeURIComponent(m[1]) : null;
}

async function hmacHex(secret, msg) {
  const key = await crypto.subtle.importKey('raw', enc.encode(secret), { name: 'HMAC', hash: 'SHA-256' }, false, ['sign']);
  const sig = await crypto.subtle.sign('HMAC', key, enc.encode(msg));
  return bytesToHex(sig);
}

export async function sha256Hex(str) {
  const d = await crypto.subtle.digest('SHA-256', enc.encode(str));
  return bytesToHex(d);
}

/* Constant-time-ish string compare via digest equality */
export async function safeEqual(a, b) {
  const ha = await sha256Hex(String(a == null ? '' : a));
  const hb = await sha256Hex(String(b == null ? '' : b));
  let diff = 0;
  for (let i = 0; i < 64; i++) diff |= ha.charCodeAt(i) ^ hb.charCodeAt(i);
  return diff === 0;
}

export async function makeSessionCookie(password, days = 7) {
  const exp = Date.now() + days * 86400000;
  const sig = await hmacHex(password, 'sd-admin:' + exp);
  const token = exp + '.' + sig;
  return 'sd_admin=' + token + '; HttpOnly; Secure; SameSite=Strict; Path=/; Max-Age=' + (days * 86400);
}

export function clearSessionCookie() {
  return 'sd_admin=; HttpOnly; Secure; SameSite=Strict; Path=/; Max-Age=0';
}

export async function isAuthed(request, env) {
  const pw = env.ADMIN_PASSWORD;
  if (!pw) return false;
  const tok = getCookie(request, 'sd_admin');
  if (!tok) return false;
  const i = tok.indexOf('.');
  if (i < 1) return false;
  const exp = parseInt(tok.slice(0, i), 10);
  const sig = tok.slice(i + 1);
  if (!Number.isFinite(exp) || exp < Date.now() || !/^[a-f0-9]{64}$/.test(sig)) return false;
  const want = await hmacHex(pw, 'sd-admin:' + exp);
  let diff = 0;
  for (let k = 0; k < 64; k++) diff |= want.charCodeAt(k) ^ sig.charCodeAt(k);
  return diff === 0;
}

export function unauthorized() {
  return json({ ok: false, error: 'unauthorized' }, 401);
}
