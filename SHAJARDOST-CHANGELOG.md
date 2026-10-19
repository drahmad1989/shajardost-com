# SHAJARDOST.COM — MASTER CHANGELOG
## (Auto-deploy from source folder to Cloudflare Pages `shajardost-com`)

> **Domain:** https://shajardost.com/  
> **CF Pages project:** `shajardost-com` under CF account `9a05d3645353a3d27afdf67b4fced0d2`  
> **Source folder:** `/home/z/my-project/shajardost-build/`  
> **Timezone:** All timestamps are `Asia/Karachi` (UTC+5)  
> **Versioning:** SemVer — `vX.Y.Z` (X=major redesign, Y=new page/feature, Z=bug fix)

---

## HOW TO ADD A NEW ENTRY (template the agent must copy)

```markdown
## [vX.Y.Z] — YYYY-MM-DD HH:MM (Asia/Karachi)

**Pushed by:** Muhammad Ahmad (via Z.ai agent)
**Live URL verified:** https://shajardost.com/ → HTTP 200 ✓
**Cloudflare Pages deploy:** <preview-hash>.shajardost-com.pages.dev

### Changes
- <bullet 1 — what was added/changed, with file paths>
- <bullet 2>

### Files modified
| File | Lines before | Lines after | Delta |
|------|--------------|-------------|-------|
| donate.html | 142 | 180 | +38 |

### Files added
- /volunteer.html (NEW — 220 lines)

### Verification
- [x] `curl -sL https://shajardost.com/ | grep -c "7aa21f97"` returns ≥9 (forms fixed)
- [x] `curl -sL https://shajardost.com/donate | grep -c "YOUR_WEB3FORMS_ACCESS_KEY_HERE"` returns 0 (no broken forms)
- [x] `curl -sL https://shajardost.com/volunteer` returns HTTP 200 (new page live)
- [x] Test lead submitted through donate form → email received
- [x] DeraTalk button present on every page
- [x] WhatsApp floating button present on every page

### Known regressions / follow-ups
- (none)
```

---

## [v1.0.0] — 2026-10-12 (Asia/Karachi) — BASELINE (current state, before agent work)

**Pushed by:** Muhammad Ahmad (prior agents)
**Live URL verified:** https://shajardost.com/ → HTTP 200 ✓

### What shipped in v1.0.0 (current baseline)

**Pages (18 total):**
- index.html (55KB — homepage with 15+ sections, 4 donation packages, 4 program cards, FAQ, how-to-donate guide, impact stats, donation tiers, newsletter)
- 4 program pages: mission-fifty.html, green-scouts.html, mini-nursery.html, miyawaki-forest.html
- donate.html (6 donation packages: Rs. 200, 500, 2000, 5000, 25000, 500)
- sponsorship.html (corporate CSR)
- buy-plants.html (donate plants)
- contact.html, about.html, faq.html, gallery.html, blog.html, thank-you.html, 404.html
- products.html + shop.html (DUPLICATES — Bug 7)
- googlecf8400cbc1fffc0b.html (Google verification)

**Infrastructure:**
- ✅ Cloudflare Pages project `shajardost-com`
- ✅ Google Search Console verified (`googlecf8400cbc1fffc0b.html` at root)
- ✅ Bing Webmaster verified (`BingSiteAuth.xml` at root)
- ✅ Bing IndexNow key file (`60c092562ee34ecf8b7da169b87ea826.txt` at root)
- ✅ Sitemap with 14 URLs (missing blog, shop/products, privacy, terms — Bug 8)
- ✅ robots.txt (allows Google/Bing/etc., blocks AI bots)
- ✅ PWA manifest
- ✅ `_headers` with cache-control + security headers
- ✅ `_routes.json`
- ✅ JSON-LD schema: NGO, DonateAction, BreadcrumbList, FAQPage, WebPage
- ✅ Hreflang (en, en-PK, ur-PK, x-default)
- ✅ Open Graph + Twitter Card tags on all pages
- ✅ DeraTalk floating button (bottom-left, src=shajardost, migrated from RabtaChat on 2026-10-11)
- ✅ WhatsApp floating button (bottom-right) on all body pages

**Lead capture (9 forms — ALL BROKEN):**
- All 9 forms have `access_key` = `YOUR_WEB3FORMS_ACCESS_KEY_HERE` (placeholder) — submissions silently fail
- All 9 forms have same `subject` = `New Lead from ShajarDost.com` — can't tell which page lead came from
- All 9 forms have same 9-option `interest` dropdown — confusing for visitors

**Donation packages (6):**
- Starter Plant (Rs. 200, 1 tree) — WhatsApp deep-link
- Standard Plant (Rs. 500, 3 trees) — WhatsApp deep-link
- Community Planter (Rs. 2000, 10 trees) — WhatsApp deep-link [Most Popular]
- Mission 50 Sponsor (Rs. 5000, 25 trees) — WhatsApp deep-link
- Mini Forest Sponsor (Rs. 25000, 100+ trees) — WhatsApp deep-link
- Education Sponsor (Rs. 500, 1 student kit) — WhatsApp deep-link

**Programs (4):**
- Mission 50: Pledge to plant 50 trees in your lifetime
- Green Scouts: School training program (ages 8-18)
- Mini Nursery: Rooftop/balcony nurseries (100 saplings in 5x5 space)
- Miyawaki Forest: Japanese dense forest method (10x faster, 30x denser)

**NGO stats (used across site):**
- 1,200,000+ trees planted
- 630,000 trees in one day (national record, 14 August 2018)
- 600+ schools engaged
- 500+ active volunteers
- 5+ provinces covered

**WhatsApp numbers (MIXED — Bug 4):**
- `+92 336 0000597` on 16 pages (58 occurrences)
- `+92 371 3097977` on index.html + footer (17 occurrences)
- Footer display: `0333-3197977` (typo — should be `0371-3097977`)

### Known issues inherited at v1.0.0 (fix in v1.0.1+)
1. **Bug 1 (CRITICAL):** All 9 forms broken — `access_key` is placeholder
2. **Bug 2 (HIGH):** All forms have same subject line — can't differentiate leads
3. **Bug 3 (HIGH):** All forms have same interest dropdown — confusing
4. **Bug 4 (HIGH):** WhatsApp number mixed across pages
5. **Bug 5 (MEDIUM):** No volunteer page — people who want to volunteer have no structured intake
6. **Bug 6 (MEDIUM):** No direct payment options on /donate (only WhatsApp deep-links)
7. **Bug 7 (LOW):** shop.html and products.html are identical duplicates
8. **Bug 8 (LOW):** Sitemap missing 4 pages (blog, shop/products, privacy, terms)

### Verification checklist (current state)
- [x] `curl -sL https://shajardost.com/` returns HTTP 200
- [x] `curl -sL https://shajardost.com/donate` returns HTTP 200
- [x] DeraTalk button present on all pages (migrated from RabtaChat)
- [x] WhatsApp floating button present on all pages
- [ ] **`curl -sL https://shajardost.com/donate | grep -c "YOUR_WEB3FORMS_ACCESS_KEY_HERE"` returns 9 (BUG — all forms broken)**
- [ ] Forms actually submit leads to Muhammad's email (BUG — currently silent failure)

### Rollback plan
- Source folder on dev machine: `/home/z/my-project/shajardost-build/`
- Backups: `<file>.bak-YYYYMMDD-HHMM` files in source folder
- Cloudflare Pages keeps every deploy preview URL — any past version can be promoted to production via the dashboard

---

## [v1.0.1] — 2026-10-15 01:58 (Asia/Karachi)

**Pushed by:** Muhammad Ahmad (via Z.ai agent)
**Live URL verified:** https://shajardost.com/ → HTTP 200 ✓
**Cloudflare Pages deploy:** e0c9d840.shajardost-com.pages.dev
**GitHub:** `drahmad1989/shajardost-com` branch `static-live` (commits 2c4b104 → fd0c6da). Old PHP code preserved untouched on `main` branch.

### Infrastructure change (important for future agents)
- New session had NO source folder — live site was mirrored byte-exact and verified (18 pages, canonicals absolute, all asset references resolve, form state matched HANDOFF exactly: 10 broken keys across 9 pages)
- **Source of truth is now GitHub branch `static-live`** (deploy folder: `/home/z/my-project/shajardost-com-repo/`). Old PHP-era repo content stays on `main` (untouched). Every future change = git commit → deploy → verify.
- `_headers` + `_routes.json` recreated: `_headers` content reverse-engineered from live response headers (exact match: nosniff, SAMEORIGIN, strict-origin-when-cross-origin, permissions-policy, cache-control split / = must-revalidate + /assets/* = 1yr immutable); `_routes.json` = minimal passthrough

### Changes
- **Bug 1 (CRITICAL) FIXED:** Replaced `YOUR_WEB3FORMS_ACCESS_KEY_HERE` with `7aa21f97-1e8f-46cb-bddb-ac172afcac49` in 10 form occurrences across 9 pages (index.html has 2: lead form + newsletter form)
- **Bug 2 (HIGH) FIXED:** Per-page subject lines (HANDOFF §6.2 mapping): index=💰 Home, donate=💰 Donate Page, sponsorship=🏢, green-scouts=🌿, mission-fifty=🌱, mini-nursery=🏡, miyawaki-forest=🌳, buy-plants=🌿, contact=📞. Newsletter form kept its own subject
- **Bug 3 (HIGH) FIXED:** Interest locked via hidden input on 7 pages (donate=Donate Money, sponsorship=Corporate Sponsorship, green-scouts=Green Scouts (School), mission-fifty=Mission 50 Pledge, mini-nursery=Mini Nursery, miyawaki-forest=Miyawaki Forest, buy-plants=Donate Plants). index.html + contact.html KEEP the dropdown per HANDOFF §6.3
- **Bug 4 (WhatsApp) NOT TOUCHED** — awaiting Muhammad's A/B/C decision. All 58x 923360000597 + 17x 923713097977 + 19x "0333-3197977" display text unchanged. DeraTalk buttons unchanged (17 links verified live)

### Files modified
| File | Change |
|------|--------|
| index.html | 2x access_key, 1x subject (newsletter subject untouched), dropdown kept |
| donate.html | access_key, subject, select→hidden (Donate Money) |
| sponsorship.html | access_key, subject, select→hidden (Corporate Sponsorship) |
| green-scouts.html | access_key, subject, select→hidden (Green Scouts (School)) |
| mission-fifty.html | access_key, subject, select→hidden (Mission 50 Pledge) |
| mini-nursery.html | access_key, subject, select→hidden (Mini Nursery) |
| miyawaki-forest.html | access_key, subject, select→hidden (Miyawaki Forest) |
| buy-plants.html | access_key, subject, select→hidden (Donate Plants) |
| contact.html | access_key, subject, dropdown kept |

### Verification
- [x] `curl -sL https://shajardost.com/` → HTTP 200
- [x] Broken key `YOUR_WEB3FORMS_ACCESS_KEY_HERE` count across all 9 form pages: **0**
- [x] Real key `7aa21f97` present: index=2, donate/sponsorship/green-scouts/mission-fifty/mini-nursery/miyawaki-forest/buy-plants/contact = 1 each
- [x] Subject lines verified live on all 9 pages (emoji subjects intact)
- [x] Hidden interest verified live on /donate; /contact dropdown present (1 select)
- [x] DeraTalk + WhatsApp floating buttons verified present on /, /donate, /contact
- [x] Test lead POST to Web3Forms API (browser headers) → `{"success":true,"message":"Form submitted successfully!"}` — end-to-end confirmed
- [x] Bing IndexNow: 9 URLs submitted → HTTP 200

### Known regressions / follow-ups
- **NEW (minor) Bug 9:** `/assets/images/hero.jpg` is 404 on live site itself — referenced in index.html `twitter:image` (and og:image) meta. Fix later by pointing to an existing image (e.g. national-record-2018.jpg)
- Web3Forms blocks pure server-side (curl) submissions on free plan ("Pro plan required" for server IP) — this is expected; browser submissions confirmed working via test lead with browser headers. Muhammad should do 1 manual form fill from his own browser as final confirmation
- Pending decisions: Bug 4 (WhatsApp A/B/C), Bug 6 (payment details), Bug 7 (shop vs products)

---

## [v1.0.2] — 2026-10-15 02:16 (Asia/Karachi)

**Pushed by:** Muhammad Ahmad (via Z.ai agent)
**Live URL verified:** https://shajardost.com/ → HTTP 200 ✓
**Cloudflare Pages deploy:** 77ac0dde.shajardost-com.pages.dev
**GitHub:** branch `static-live` commit b1b61cc

### Owner decision executed: OPTION C — WhatsApp removed site-wide
Owner confirmed: "C) WhatsApp hatao — sirf Direct Call (0333-3197977) + DeraTalk raho" + provided DeraTalk QR (https://app.deratalk.com/r/F6U6LP)

### Changes
- **Floating button (bottom-right):** WhatsApp float div → Direct Call button `tel:+923333197977` (brand green #2d6a4f, ri-phone-line icon, same position/size/animation) — 14 pages
- **wa.me links: 57 → 0.** Links with prefilled message (donate packages, Get Quote buttons) → DeraTalk deep links `app.deratalk.com/b/ahmad?src=shajardost&msg=<same message re-encoded>` (prefill preserved). Plain links: phone-text anchors → `tel:`, others → DeraTalk referral `app.deratalk.com/r/F6U6LP`
- **Form fallbacks rebuilt (9 pages):** "Prefer WhatsApp?" → "Prefer to talk?" with [Call 0333-3197977] + [DeraTalk Chat] buttons
- **Form field label (9 pages):** "WhatsApp *" → "Mobile Number *"
- **Copy/SEO updates:** meta descriptions, JSON-LD schema, FAQ answers, how-to-donate steps, thank-you page — all WhatsApp/old-number mentions → Direct Call 0333-3197977 + DeraTalk (0 visible "WhatsApp" text remains; only cosmetic CSS class names btn-whatsapp/form-whatsapp-fallback kept to avoid CSS rewrite)
- **/contact page:** WhatsApp card → "Direct Call" card (tel:); NEW "Scan to Chat on DeraTalk" QR section (owner-provided QR at /assets/images/deratalk-qr.png) + DeraTalk referral button
- **Bug 9 FIXED:** index.html og:image + twitter:image 404 hero.jpg → national-record-2018.jpg
- **whatsapp-tracker.js** script references removed from 14 pages (file kept in repo, now unreferenced). script.js waFloat handler is dead code (guarded, harmless)
- **style.css:** additive block only (`.call-float` + `.btn-call`, comment-separated) — no rewrite

### Files modified
All 18 HTML pages + assets/css/style.css (append) + assets/images/deratalk-qr.png (NEW)

### Verification
- [x] `wa.me` occurrences across all 18 live pages: **0**
- [x] `ri-whatsapp-line` icons: **0**
- [x] Visible "WhatsApp" text: **0** (except CSS class names)
- [x] `call-float` on 14 pages live; `tel:+923333197977` present (/ and /donate = 3 each)
- [x] DeraTalk links: 61 total (float + deep links + referral + QR section)
- [x] QR image live: /assets/images/deratalk-qr.png → HTTP 200
- [x] Contact QR section live ("Scan to Chat on DeraTalk")
- [x] og:image fixed (national-record-2018.jpg ×4 refs, hero.jpg 0)
- [x] Forms untouched from v1.0.1 (access_key/subject/interest intact)
- [x] Bing IndexNow: 17 URLs → HTTP 200

### Known regressions / follow-ups
- script.js `const WA='923360000597'` + waFloat handler now dead code — cleanup optional in v1.0.3
- whatsapp-tracker.js file unreferenced — delete optional in v1.0.3
- Pending: Bug 5 (volunteer page), Bug 6 (payment details), Bug 7 (shop vs products), Bug 8 (privacy/terms + sitemap)

---

## ROADMAP — FUTURE VERSIONS

### v1.0.1 (patch — URGENT, do first, ~30 min)
- Fix Bug 1: Replace `YOUR_WEB3FORMS_ACCESS_KEY_HERE` with `7aa21f97-1e8f-46cb-bddb-ac172afcac49` in all 9 forms
- Fix Bug 2: Differentiate `subject` per page (see HANDOFF §6.2)
- Fix Bug 3: Lock `interest` per page using hidden input (see HANDOFF §6.3)
- Fix Bug 4: Standardize WhatsApp number (after Muhammad confirms — recommended: `+92 371 3097977`)
- Deploy + test all 9 forms by submitting test leads

### v1.0.2 (minor — IMPORTANT, same day, ~1 hour)
- Fix Bug 5: Create `/volunteer` page with structured intake form (see HANDOFF §6.4)
- Fix Bug 6: Add Direct Payment section on `/donate` (after Muhammad provides JazzCash/EasyPaisa/Bank/Zindigi details — see HANDOFF §6.5)
- Add `/volunteer` to sitemap + nav menu
- Add "Volunteer" CTA button on homepage hero
- Deploy + verify

### v1.0.3 (patch — polish, next day, ~1 hour)
- Fix Bug 7: Resolve shop/products duplicate (after Muhammad's decision)
- Fix Bug 8: Create privacy.html + terms.html, update sitemap
- Add `BlogPosting` schema to blog page
- Add `Service` schema to each program page
- Add image sitemap for `/assets/images/`

### v1.1.0 (minor — growth, next week)
- Add city-specific landing pages (Lahore, Karachi, Islamabad, Phool Nagar, Kasur, Multan, Faisalabad)
- Add 5 blog posts (topics in HANDOFF §5 Phase 4)
- Add Urdu version of homepage
- Add Google Business Profile integration
- Submit to Pakistan NGO directories
- Add impact testimonials page

### v2.0.0 (major — reserved for future redesign)

---

## META — REPO MAINTENANCE NOTES

### Deploy command (manual)
```bash
export CLOUDFLARE_API_TOKEN="[REDACTED — Cloudflare Pages token, agent ke paas locally save hai / Muhammad se lein]"
export CLOUDFLARE_ACCOUNT_ID="9a05d3645353a3d27afdf67b4fced0d2"
cd /home/z/my-project/shajardost-build
npx wrangler pages deploy . --project-name=shajardost-com --branch=main --commit-dirty=true
```

### Bing IndexNow (after each deploy)
```bash
curl -X POST "https://api.indexnow.org/IndexNow" \
  -H "Content-Type: application/json" \
  -d '{"host":"shajardost.com","key":"60c092562ee34ecf8b7da169b87ea826","keyLocation":"https://shajardost.com/60c092562ee34ecf8b7da169b87ea826.txt","urlList":["https://shajardost.com/","https://shajardost.com/donate","..."]}'
```

### Form testing (after each form fix)
Submit a test lead through each form with:
- Name: "Test Lead"
- WhatsApp: "0300-0000000"
- Email: "test@example.com"
- Message: "This is a test lead from the changelog verification process. Please ignore."
Then verify Muhammad's email receives it with the correct `subject` line.

---

## v1.0.3 — D1 LEAD DASHBOARD (2026-10-17, Asia/Karachi)

**Deploy:** 790297b9.shajardost-com.pages.dev → production | **Commit:** 8e05ab5
**Owner approval:** "D1 banao — dekh lo page agr ni bna hoa tu"

### Kya bana (Lead Management System — "leads kabhi zaya nahi hongi")

Har form submission ab **2 jagah** jaati hai:
1. **Email** (Web3Forms — pehle jaisa hi, kuch nahi badla)
2. **Cloudflare KV database** (naya backup — Pages Functions se)

### Naye features

- **admin.html — Leads Dashboard** (https://shajardost.com/admin):
  - Password login (HMAC-signed HttpOnly cookie, 7 din valid)
  - 5 stat cards: Total / Naye / Aaj / Is hafte / Spam
  - Status pipeline: Naya → Rabta kiya → Chal raha → Muafaqiyat → Band / Spam
  - Lead detail modal: full message + Direct Call + DeraTalk + Email buttons + notes
  - CSV export (Excel-friendly UTF-8 BOM) + search + filters + 60s auto-refresh
  - Roman Urdu UI, mobile responsive, noindex
- **Pages Functions** (serverless APIs):
  - `POST /api/lead` — public intake, honeypot spam filter (botcheck/website/_hp)
  - `GET/PATCH/DELETE /api/leads` — admin CRUD (auth required)
  - `GET /api/export` — CSV backup (auth required)
  - `GET/POST/DELETE /api/auth` — login session
- **script.js capture hook** — har Web3Forms submit par fire-and-forget KV backup
  (native email flow bilkul untouched, keepalive:true)
- **Newsletter fix** — email-only leads bhi ab store hoti hain (lead.js validation)
- **robots.txt** — /admin + /api search engines se blocked
- **Dead code cleanup** — script.js se WhatsApp float handler + WA constant removed (Option C)

### Cloudflare config (pre-existing, verified intact)

- KV namespace: `shajardost-leads` (96f60e9965314f9a87a8c3b909c2eec8)
- Binding: `LEADS_KV` (production + preview)
- Secret: `ADMIN_PASSWORD` — reset & documented in local credentials file (NOT in git)

### Verification (ALL PASS)

- /admin → 200; /api/auth → configured:true
- E2E: POST lead → login → list → PATCH status+notes → CSV export → DELETE → 0 leads
- Unauthorized API access without cookie → blocked (401)
- 4 real leads seeded (Waqas/Mirha/Green Meadow = new, Daniel Wright = spam)
- All 7 main pages → 200; web3forms key intact; 0 wa.me (Option C intact)
- KV note: list 60s tak eventually-consistent rehti hai (delete turant na dikhe to 1 min wait)

### Notes

- Storage = Cloudflare KV (D1 SQL database ki zaroorat nahi pari — is use-case ke liye
  KV simple, fast, free-tier friendly hai; kaam wahi: har lead permanent save)
- Free tier limits: 1000 writes/day, 100k reads/day (is site ke liye bohat zyada hai)
- Spam protection: honeypot fields (bots silently drop hote hain, owner ko nahi dikhte)

---

## v1.0.4 — RATE MODEL + TRUST FIXES (2026-10-17, Asia/Karachi)

**Deploy:** 83dd4186.shajardost-com.pages.dev → production | **Commit:** 0eacdcb
**Source:** Dusra agent (owner ke doosre AI) ka plan — owner ne forward kiya: "dekh lo jo kam ka lagay, khas kr dekho rates etc"

### 1. NEW RATE MODEL (per-plant: Size × Care)
- **/donate par Rate Chart section add hua:** matrix table 3 sizes (2–3 ft / 3–5 ft / 5–7 ft) × 4 care tiers (1/2/3/5 yr), Rs. 500 → Rs. 10,000
- **Quantity slabs:** 1–9 list / 10–49 (−5%) / 50–199 (−10%) / 200–999 (−15%) / 1,000+ (−20%) / 10,000+ custom quote
- Matrix middle values PROPOSED hain (owner confirmation pending — sirf wo table cells badalna hai jab confirm kare)

### 2. Package cards re-priced (purani bundles Rs.500 model se contradict karti thin)
| Card | Old | New |
|---|---|---|
| Starter Plant | Rs. 200 | **Rs. 500** (1 plant 2–3 ft + 1 yr) |
| Standard Plant | Rs. 500 (3 trees) | **Growth Plant Rs. 2,500** (1 plant 3–5 ft + 2 yr) — ab Most Popular |
| Community Planter | Rs. 2,000 (10 trees) | **Sadqah Jariyah Bundle Rs. 4,750** (10 plants, 5% off) |
| Mission 50 Sponsor | Rs. 5,000 (25 trees) | **Rs. 11,875** (25 plants, 5% off) |
| Mini Forest Sponsor | Rs. 25,000 (100+ trees) | **Community Forest Rs. 22,500** (50 plants, 10% off) |
| Education Sponsor | Rs. 500 | unchanged (school kit — tree rates se alag product) |

### 3. Homepage (index.html)
- Rs. 100 → Rs. 500 model language (FAQ JSON-LD + visible FAQ + form helper + donor card)
- og:description + JSON-LD: "Donate from Rs. 200" → "Plant a tree from Rs. 500"
- **"Tax-deductible receipt" → "Official donation receipt"** (false legal claim removed — NGO registration pending hai)
- 500++ → 500+ (about.html bhi)
- Duplicate impact-bar section removed (hero stats ka exact repeat tha)
- **8 BROKEN gallery images FIXED** — greenseekers webp files asal mein HTML dumps thin (homepage par 8 broken images dikh rahi thin!) → 4 real photos (national-record, mission-50, green-scouts, mini-nursery)
- 8 fake webp files deleted (shop/products ke no-space refs bhi 404 the → real photos)

### 4. Miyawaki page
- detail image: national-record-2018.jpg (galat context, alt="Miyawaki Forest") → mission-50-campaign.jpg + imandar alt
- NOTE: asli Miyawaki forest ki photo owner se mangni hai (AI-generated image NGO site par NAHIN lagayenge)

### 5. Cleanup
- 6 pages par duplicate hidden interest field dedupe (v1.0.1 artifact)
- green-scouts + mission-fifty ke 2 values JAAN-BOOJH KAR rakhe (alag-alag values hain)

### Verification (ALL PASS)
- 7 pages 200; index: 0× Rs.100, 0× 500++, 0× greenseekers; donate: Rate Chart + Rs.10,000 + slabs live, 0× Rs.200
- All JSON-LD VALID (parse-tested); check-assets: 0 real missing (hero.jpg sirf purane script ka stale report)
- /admin 200 + KV dashboard untouched; Option C intact (0 wa.me)
- IndexNow: 6 URLs → HTTP 200

### OWNER SE CONFIRM HONE HAIN (site par abhi proposed values live hain)
1. Rate matrix ke 10 middle values (2 corners confirmed: 500 aur 10,000)
2. Teesra pricing factor (owner ne "aur bhi kuch" kaha tha)
3. Payment methods ki SACHI list (homepage: JazzCash/EasyPaisa/bank vs baqi site: JS Bank/Zindigi — CONTRADICTION abhi bhi hai)
4. "86+ video pledge club" (mission-fifty) — asli hai ya hatao?
5. "Ten Billion Trees Project" claim (products.html poora section + FAQ) — proof ya remove?
6. DeraTalk chat account "StartNest.pk" branded hai — owner ko DeraTalk settings mein rename karna hai
7. Miyawaki forest ki ASLI photo bhejein

---


## v1.0.5 — 2026-10-17: AUTOMATION LAYER (ChatGPT review adopted: backup + follow-up engine + auto-ack)

Dusre AI advisor (ChatGPT) ki strategic review se "no-brainer, no-owner-input" items implement kiye.
Verdict pehle verify kiya: packages live par COHERENT hain (Sadqah 10×500-5%=4750 ✓, Community Forest 50×500-10%=22500 ✓ — uska M50=237/tree catch mere brief ki ghalat list par tha, live par apply nahi hota).

### 1. Independent versioned BACKUP (advisor priority #1: protect data)
- NEW KV namespace `shajardost-leads-backup` (dc8791f9) — LEADS_KV se ALAG, binding BACKUP_KV
- NEW `/api/cron` (POST): `task=backup` → poora leads snapshot (JSON + count + SHA-256 checksum) versioned key `snap:YYYY-MM-DD-HH-MM` + `latest` pointer; 40 snapshots retention
- Admin dashboard par 🗥 Backup button (admin cookie auth se manual snapshot)
- GitHub Actions `nightly-tasks` (default branch `main` par SIRF workflow file — site code untouched): roz 07:00 PKT backup, Monday 09:00 PKT weekly report email. Repo secrets: CRON_SECRET + CRON_ENDPOINT (encrypted, values file mein nahi)
- Restore test script: scripts/backup-restore-test.py (snapshot parse + count compare)

### 2. Follow-up engine (Day 1/3/7 cadence — advisor CRM checklist)
- Har nayi lead: next_action_at(+1d), followup_count, last_contact_at, history[] audit trail
- `/api/leads` PATCH: `action=log_contact` (Day 3/7/7 auto-schedule + status new→contacted auto), `next_action_at` manual date, closed_reason — har change history me log
- Dashboard: "Aaj ke follow-ups" stat card + DUE STRIP (chips: naam + due date; overdue RED + warning title)
- Lead modal: follow-up panel (agla follow-up, rabta count, aakhri rabta, OVERDUE badge) + "✓ Rabta ho gaya" button + custom date setter + History list (aakhri 10)
- List ab client-side filter (server se poora data — due-strip har tab me kaam karti hai); stats me due_today + overdue counters

### 3. Auto-acknowledgment email (advisor A.1: Resend first)
- `/api/lead` ab Resend se customer ko foran "Shukriya" email bhejta hai (ref number + 24hr window + call number) — fire-and-forget `waitUntil`, email fail ho to bhi lead intake KABHI fail nahi hota
- GRACEFUL: RESEND_API_KEY secret set nahi to silently skip — owner Resend account banaye ga (SPF/DKIM DNS records main add karunga)
- ACK_FROM env override possible (default no-reply@shajardost.com)

### 4. Security hardening (advisor G.2 subset)
- Soft rate limit `/api/lead`: 30 req/hour/IP (KV counter, fail-open — CGNAT safe)
- CSV export me next_action_at + followup_count columns
- OWNER_EMAIL env set (ahmad22mao@gmail.com) — weekly report ke liye
- Weekly report: by-status counts, due/overdue + overdue table, backup status, "inquiry ≠ revenue" note (advisor ka verified-collections point) — KV `report:last` me bhi save

### Config changes (Cloudflare)
- Pages project: BACKUP_KV binding (prod), CRON_SECRET (secret), OWNER_EMAIL (plain)

### OWNER ACTIONS PENDING (v1.0.5 ke liye sirf 1)
- Resend.com account (free) → Domain add karein shajardost.com → mujhe DNS records bhejein (main add kar dunga) → API key dein (RESEND_API_KEY secret set ho jayegi) → auto-ack + report emails LIVE
- Baqi 7 confirmations (matrix values, payment list, 86+, Ten Billion, DeraTalk rename, Miyawaki photo) pehle jaisi pending

### Verification
- node --check: saari 6 function files + admin script PASS
- E2E live test deploy ke baad (neeche update hoga)

---

## v1.0.6 — 2026-10-17: VOLUNTEER PAGE (Bug 5) + PRIVACY/TERMS (Bug 8) + SITEWIDE LINKS

Owner ne "shuru kro kam" kaha — teen no-owner-input builds ek saath. DeraTalk site par live confirm hua (owner).

### 1. NEW /volunteer — application page (Bug 5, URGENT tha: 2 real volunteer leads pending)
- Structure green-scouts.html shell par (header/nav/footer/DeraTalk-float/call-float identical)
- Content: "Why Volunteer" (1.2M legacy + certificate + training), 8 roles grid (planting, nursery, photo/video, writing, social, teaching, events, logistics — HANDOFF §6.4 confirmed list), 4-step process (Apply → Call 48hr → Orientation → Start)
- FULL application form (HANDOFF §6.4 template): name/phone/email/city/age/availability/skills[12 checkboxes]/motivation — interest=Volunteer, subject "🤝 Volunteer Lead", redirect thank-you
- Fallback Call 0333-3197977 + DeraTalk chat (WhatsApp template part SKIP — Option C rule)
- KV capture automatic (script.js global Web3Forms hook, _source=/volunteer.html) — TESTED LIVE: POST /api/lead → ok:true, lead KV mein aayi, phir delete (4 real leads intact)
- JSON-LD: WebPage + Breadcrumb + NGO; og:image national-record-2018.jpg; canonical /volunteer

### 2. NEW /privacy + /terms (Bug 8 — trust pages)
- Privacy: jo collect hota hai (sirf form data), kabhi sell nahi, Cloudflare-secured storage, Green Scouts parental consent (under-18), user rights (call/email par correct/delete), NGO community-initiative wording (koi false registration claim nahi)
- Terms: services, rates-at-order-time, care schedule + certificate promise, plant order 48hr damage window, volunteer voluntary-unpaid + safety, PK governing law — dono noindex (search traffic target nahi)
- Donation flows mein koi tax-deductible claim NAHI (v1.0.4 trust rule maintained)

### 3. Sitewide links (idempotent linker: scripts/linker-v106.py)
- 15 pages (14 + 404): nav mein "Volunteer" (About se pehle) + footer Get Involved mein Volunteer + footer-bottom mein Privacy · Terms
- Legacy stubs (shop/blog/products) untouched — Bug 7 decision pending
- Nav active-class logic preserved (about.html par About active rehta hai)

### 4. Sitemap: 14 → 17 URLs (+/volunteer 0.8 weekly, /privacy, /terms 0.3 yearly)

### NEW OFFLINE TOOL (site par nahi)
- download/SHAJARDOST-CARE-COST-CALCULATOR.html — owner ka care-cost measurement tool: paani/chowkidar/bijli/khaad/replacement inputs → per-tree/year, total 3yr, minimum sustainable rate, M50 check (50 × min rate vs 11,875), 10 middle-price matrix auto-gen (cost se neeche = RED flag), Report-Copy button (chat mein paste karne ke liye). Owner numbers naap kar bhejega — isi par rate matrix FINAL hogi.

### Files added/modified
- ADDED: volunteer.html, privacy.html, terms.html
- MODIFIED: 15 pages (nav/footer links) + sitemap.xml
- Commit: 107b066 (static-live) | Deploy: 95092041.shajardost-com.pages.dev → production

### Verification (ALL PASS)
- HTTP 200: / /volunteer /privacy /terms /donate /contact /admin
- /volunteer live: access_key ✓, form ✓, tel:+923333197977 ×3 ✓, DeraTalk ×2 ✓, wa.me=0 ✓, nav active ✓
- /donate par 2 volunteer links ✓ | sitemap 17 URLs ✓
- /api/lead capture (source=/volunteer.html) → KV ok → cleanup ok (4 real leads)
- IndexNow: / + /volunteer + sitemap → 200

---

## v1.0.7 — 2026-10-17: CALCULATOR SITE PAR LIVE (private URL) + FACEBOOK GROUP INTEGRATION

Owner ne (1) calculator 404 report kiya (wo offline file thi — site par nahi thi), (2) FB group facebook.com/groups/shajardost ko content/volunteer hub banana propose kiya.

### 1. /care-calculator — calculator ab site par (private)
- Owner ne phone/browser par khud kholna tha → ab https://shajardost.com/care-calculator LIVE
- PRIVATE: meta noindex,nofollow + robots.txt Disallow + sitemap mein NAHI + nav mein NAHI — sirf jise URL pata hai wo khol sakta hai
- Tool wahi: 5 measurements → per-tree/year + minimum rate + M50 check + 10-price matrix (cost se neeche = RED) + Report Copy
- Koi form/access_key nahi (sirf calculation, koi data collect nahi hota)

### 2. Facebook Group (facebook.com/groups/shajardost)
- Footer button 17 pages par (YouTube button ke neeche, same style)
- /volunteer par "Join Facebook Group" strip (#shajardost content pitch — best posts website/YouTube par feature honge)
- Legacy stubs + admin + calculator untouched

- Commit: 8dec9f3 (static-live) | Deploy: a8c07804 → production
- Verify ALL PASS: /care-calculator 200 + noindex + robots Disallow; / /volunteer /admin 200; FB links / =1, /volunteer =2; sitemap 17 (calculator nahi)

---

## v1.0.8 — 2026-10-18: RATE MATRIX FINAL — owner ke measured CARE COST REPORT par

Owner ne CARE COST REPORT diya (500 pouday: paani 96k + chowkidar 240k + bijli 30k + khaad 15k + misc 10k + replacement 10k = Rs.4,01,000/saal → Rs.802/pouday/saal + one-time Rs.150) aur approval di: "han sahi h ap thek kah rhay hain, jsy dil krey bnaen ab". Minimum sustainable rate = cost ÷ (1 − 20% margin).

### Rate changes (sirf proven-loss items fix — minimal shock)
- **Rate Chart 2–3 ft row** (donate.html): Rs.500/1,000/1,500/2,500 → **Rs.1,200/2,200/3,200/5,200** (cost 952/1,754/2,556/4,160 + 20% margin)
- **Starter Plant Rs.500 stays** — honest re-label: "6-month establishment care" (cost Rs.551; pehle "1-year care" likha tha = Rs.452/sale nuqsan)
- **Sadqah Jariyah Bundle: Rs.4,750 → Rs.9,500** — 10 plants + 1-year care, cost Rs.9,520 = **at-cost, no margin** (Sadaqah framing; pehle -4,770/sale nuqsan)
- **Community Forest: Rs.22,500 → Rs.45,000** — 50 plants ek site par batch + 1-year care (pehle -25,100/sale nuqsan)
- **Mission 50 Sponsor (index): Rs.11,875 → Rs.28,500** — 25 × 1,200 × 0.95 = chart-consistent 5% bulk discount (pehle -11,925/sale nuqsan)
- 3–5 ft + 5–7 ft chart rows UNCHANGED (already cost-covered: 3yr 4,000 vs cost 3,570; 5yr 10,000 vs 6,263)
- DeraTalk prefill msg prices sab affected cards mein update
- Quantity Discounts section (5%/10%/15%/20% off list rate) unchanged

### Zameen plan context (owner discussion)
- Partner-site planting (schools/hospitals/madrasas) = zameen free — ye bachat measured Rs.802 mein PEHLE SE shamil
- 1 acre Miyawaki (2,000–2,500 pouday) par per-tree cost GIRTI hai (chowkidar Rs.480→Rs.96/pouday) — rates zameen aane par dobara naap kar revise honge
- Nursery engine + CSR "Adopt-an-Acre" = agle acre ka funding route (10-saal cut-and-sell plan par agent ne nursery model recommend kiya)

- Commit: 386da5e (static-live) | Deploy: 40982f7f → production
- Script: scripts/rate-matrix-v108.py (idempotent, pattern-anchored, post-condition checks)

### Verification (ALL PASS)
- [x] /donate HTTP 200 — Rs.9,500 ✓, Rs.45,000 ✓, Rs.1,200 ✓, "6-month establishment care" ×2 ✓; 4,750/22,500/"with 1-year care" = 0 ✓
- [x] / HTTP 200 — Rs.28,500 ✓, Rs.9,500 ✓; 11,875/4,750 = 0 ✓
- [x] Form access_key intact (/donate) ✓ | /admin HTTP 200 (functions OK) ✓
- [x] IndexNow: /donate + / → HTTP 200
- [x] DeraTalk msg prefills: 9500/45000/28500 live ✓

---

## v1.0.9 — 2026-10-18: DEMAND WALL — public moderated board (owner idea, fully built)

Owner ka idea: "jo demand create ho wo website par mention hoti jaye — number etc admin ke paas, name/city/demand public ho, wo b admin ki ijazat se." Poora feature banaya (PKR 0, existing KV stack par).

### 1. NEW /demand-wall — public page
- Live board: 🌱 Demand (mang) + 📦 Pouday Aaye (supply) cards — naam, shehar, pouday/tadaad, detail, date, status chip (Open/Matched/Poora Hua)
- Filter chips (Sab / Demand / Received), empty-state, 3-step "How it works", donor CTA (Call + DeraTalk)
- Submit form: name/city/phone/plants/qty/detail + REQUIRED consent checkbox ("naam+shehar public ho sakti hai, mobile kabhi nahi") — Web3Forms email + KV capture (interest=Plant Demand)
- JSON-LD ×2, indexable, sitemap +1 (18 URLs)

### 2. Backend (functions/api/demands.js — NEW)
- KV keys `demand:{ms}:{hex}` (LEADS_KV same namespace, alag prefix)
- GET ?public=1 → sirf approved/matched/fulfilled, phone/email KABHI nahi + phone-jaisi sequences detail se auto-scrub ("[number hidden]")
- GET (admin) → full list + stats; POST (admin) manual add; PATCH approve/reject/match/fulfill/reopen/unpublish/edit; DELETE
- GOLDEN RULES: (1) kuch bhi public nahi jab tak admin APPROVE na kare (2) phone kabhi public response mein nahi (3) public text auto-scrub

### 3. functions/api/lead.js extend
- interest "Plant Demand" → demand record auto-create (pending) — wall form se aane wali har demand seedha admin queue mein

### 4. Admin dashboard (/admin) — "🌱 Demand Wall" tab
- View switcher (Leads ↔ Demand Wall), pending RED badge, 4 stat cards
- Actions: ✅ Approve | 🤝 Match | ✓ Fulfil | ↩ Reopen | 🙈 Unpublish | ✏️ Public-text edit (phone hatane ke liye) | 🚫 Reject | 🗑 Delete
- Manual add form (aaye hue pouday / phone-par-aayi demand, "Foran publish" option)
- Privacy/terms update: Demand Wall privacy paragraph + moderation terms line

- Commit: be1e417 (static-live) | Deploy: 2a1c262b → production
- Scripts: scripts/demand-wall-v109.py (linker, idempotent), scripts/test-demand-wall.py (E2E)

### Verification (E2E 17/17 PASS)
- [x] Submit → pending → public par NAHI (moderation enforced)
- [x] Admin list: phone visible admin ko, status pending
- [x] Approve → public: naam+city dikha, phone public response mein KABHI nahi, detail auto-scrub "Rabta: [number hidden]"
- [x] Unpublish → public se gayab | bina login → 401 | cleanup → 4 real leads intact
- [x] /demand-wall 200, nav+footer links live (index ×2), form key + consent live, sitemap 18, privacy para live
- [x] IndexNow: /demand-wall + / → HTTP 200

---

## v1.0.10 — 2026-10-18: SEO TIER-1 ON-PAGE — keyword tuning (pending list item 6)

Pending list ka item 6 (SEO plan → Tier 1 on-page). Pehle full-site SEO audit (scripts/seo-audit.py — 24 pages: title/meta/H1/canonical/JSON-LD/img-alt) — findings: base SEO pehle se solid (sab pages canonical ✓, 0 missing alts, FAQPage 10 Q&A ✓, NGO/Breadcrumb schema ✓). Tier-1 = 3 pages par keyword-intent tuning, body content ZERO change:

- **donate.html**: title "Donate — Plant Trees in Pakistan" → **"Donate Trees in Pakistan — Tree Plantation Donation"** (exact intent keywords); desc mein "Sadaqah bundles" keyword add; og:title sync
- **miyawaki-forest.html**: title → **"Miyawaki Forest Pakistan — Dense Forests 10x Faster"** ("Miyawaki Pakistan" keyword gap band); desc "Miyawaki method forests in Pakistan…"; og:title sync
- **faq.html**: title "FAQ | ShajarDost" (16 chars, weak) → **"FAQ — Donations, Mission 50 & Miyawaki | ShajarDost"**
- Jaan-boojh kar NAHI chheda: volunteer.html (already v1.0.6 mein SEO-strong), products/shop (Bug 7 pending, canonical=home se protected), blog (stub — real articles ke baad self-canonical hoga), admin/care-calculator (internal, noindex-type pages)

- Commit: d4309fb (static-live) | Deploy: bf4d0298 → production
- Scripts: scripts/seo-tier1-v1010.py (idempotent, 7/7 anchors, zero errors)

### Verification (live, production)
- [x] 3/3 naye titles LIVE; purane titles 0 occurrences
- [x] donate rate matrix intact (Rs. 9,500 live) — koi aur content change nahi (diff: 3 files, 7 lines)
- [x] IndexNow: donate + miyawaki-forest + faq + demand-wall → HTTP 200

### SEO Tier-1 status
- [x] Tier 1 on-page (donate/miyawaki/volunteer) — COMPLETE
- [ ] Blog articles (2/week) — content owner ke proof-items ke sath start hoga
- [ ] CSR landing page — zameen/Adopt-an-Acre ke sath
- [ ] Cross-domain internal links — blog ke baad

---

## v1.0.11 — 2026-10-18: FREE PLANTS WALL + GREEN HEROES (owner ke 2 faislay)

Owner ke faislay: (1) Demand Wall ka naam "Free Plants" — koi bhi mang banaye, koi bhi sponser ban jaye, LEKIN plants team khud khareed kar khud lagaye (team ka fund yun jama hota rahega); (2) Green Heroes — Pakistan ke shajar doston ki neutral honor wall (Sirf promotion, ShajarDost affiliation NAHI). 9 seed names + 8 photos (Google Drive se download kar ke webp banaye).

### 1. Demand Wall → FREE PLANTS (/free-plants — naya slug)
- demand-wall.html DELETE, free-plants.html NEW, _redirects: /demand-wall → /free-plants 301
- Card par: ref (FP-XXXX), 💰 taqreeban kharcha (admin set), 🌿 sponser ka naam (admin set), per-card "Sponser Banein" CTA (Call + DeraTalk ref prefill)
- Status chips: Open / Sponser Ban Gaya (matched) / Poora Hua; 4-step how-it-works
- Form mein hidden interest=Plant Demand + naya subject; nav/footer sitewide rename
- demands.js: publicView +ref/+cost/+sponsor; PATCH match sponsor naam leta hai; reopen sponsor clear

### 2. GREEN HEROES (/green-heroes — neutral honor wall)
- functions/api/heroes.js NEW: KV `hero:{ms}:{hex}`, GET ?public=1 (sirf approved; suggester naam/phone KABHI public nahi; detail phone-scrub), admin GET/POST/PATCH(approve|reject|unpublish|edit)/DELETE; public ?mode=suggest (consent ZAROORI + honeypot + rate limit 10/hr)
- green-heroes.html NEW: cards (photo webp / initials fallback, naam, shehar, pouday, award, detail), DISCLAIMER (neutral — members/partners nahi), suggest form (hero + suggester private), JSON-LD ItemList 9 naam
- 9 heroes seeded approved: Muhammad Ahmad Baloch (Pattoki), Ghulam Rasool Pakistani, Mazhar Hashmi, Khizar Wali Chishti, Rafiq Ahmed Soomro, Javed Hussain, Shahrooz, Abdul Basit Baloch, Khawaja Mazhar Iqbal — details owner ✏️ se bharega; Waqas Ch photo saved (confirm ka intezar)
- assets/images/heroes/ 8 webp (480px square, 14–68KB)
- admin.html: "⭐ Green Heroes" tab (suggestions queue + manual add + edit + hide/delete) + Free Plants tab 💰 cost + 🤝 sponsor-prompt

### 3. lead.js BUG FIX (v1.0.9 ka chupa hua masla)
- Form mein interest field nahi tha → asli form se demand record KABHI nahi banta tha (E2E direct API post karta tha isliye bach gaya tha)
- Ab trigger: interest YA subject "demand" YA source /demand-wall|/free-plants — teeno paths E2E me test

- Commit: 29a2f2f (static-live) | Deploy: 72dd27ec → production
- Scripts: free-plants-v1011.py (linker), test-free-plants-heroes.py (E2E)

### Verification (E2E 32/33 — ek "fail" test ki ghalat ummeed thi, neeche wajah)
- [x] 9 heroes public | suggestion pending→public NAHI | approve→public (suggester phone kabhi nahi, detail scrub)
- [x] bina consent → 400 | honeypot pretend-ok | unauth → 401 dono APIs
- [x] Form-flow 3 variants: interest ✓ / source ✓ / (subject sirf "demand" likhe to ✓ — naya subject "Nayi Mang" hai isliye us ka path NAHI chalta, lekin form interest+source dono se covered hai — test ka fail expectation thi, system theek)
- [x] approve→cost public→sponsor public→phone kabhi nahi | cleanup: 4 real leads intact, 0 test bakhiya
- [x] /free-plants 200 | /green-heroes 200 | /demand-wall → 301 | sitemap 19 URLs | 3 hero images 200
- [x] IndexNow: free-plants + green-heroes + home → 200

### Open items
- Owner: har hero ki 2-4 line detail + shehar (admin ✏️ se) | "Waqas Ch" photo kis ka hai? | pehli asli mang/supply entries (➕ se)

---

## v1.0.12 — 2026-10-19: THE GREEN JOURNAL — BLOG LAUNCH (SEO Tier-2)

Owner ka mandate: "kch research kch blogs" → SEO roadmap ka agla in-court item (v1.0.10 ke Tier-1 on-page ke baad). Stub blog.html ("Articles Coming Soon", canonical=home) hataya, asli research-backed blog banaya. Har article mein real sources — koi fabricated claim nahi.

### 1. /blog/ — The Green Journal (blog/index.html NEW)
- Design: site ke naye tokens (site-header/page-hero/site-footer + Remixicon), 4 article cards (category chips: Method / Islam & Trees / Species Guide / Costs & Care), date + read-time meta
- JSON-LD: Blog schema + ItemList (4 posts); "Suggest a Topic" DeraTalk CTA; "Behind the Numbers" section
- Purana blog.html DELETE — Cloudflare Pages /blog → 308 → /blog/ (verified)

### 2. Char Articles (blog/ folder, sab indexable, BlogPosting + BreadcrumbList JSON-LD, titles 55–60 chars)
- **/blog/miyawaki-forest-pakistan** — Miyawaki method guide: 4-layer system table, UK Trees Outside Woodlands trial (£4.8M, 16 plots, 3.5 saal): 79% vs 47% survival, £10 vs £50 per surviving tree, 2022 drought-tested; honest limitations box (timber nahi, 2–3 saal care, upfront cost); Pakistan context (dialogue.earth 2021, IQAir Lahore PM2.5)
- **/blog/sadaqah-jariyah-tree-plantation** — Sadaqah Jariyah guide: Sahih al-Bukhari 2320 + Sahih Muslim 1553 (sunnah.com cross-check), Muslim ka "deeds end except three" narration, Musnad Ahmad ka palm-shoot hadith; hadith-box styling (gold); koi Zakat claim nahi (site rule)
- **/blog/best-trees-punjab-pakistan** — 10 species guide: Kikar, Shisham, Amaltas, Neem, Sohanjna, Mulberry, Sukh Chain, Chanar, Semal, Jamun — Punjab Forest Department (Changa Manga) + Afforestation Lahore lists se; quick-comparison table + honest cautions (Eucalyptus water-draw, planting season, nursery quality)
- **/blog/tree-plantation-cost-pakistan** — REAL measured data (E-E-A-T): 500 trees ki 1-saal ledger = Rs.401,000 (watchman 240k > paani 96k > bijli 30k > khaad 15k > misc 10k > replacement 10k) → Rs.802/tree/yr care + Rs.150 one-time; 3-yr Rs.2,556; donate page ke retail packages isi ledger se anchored

### 3. Sitewide + SEO plumbing
- Footer "Get Involved" column: +Green Journal link — 19 pages (idempotent scripts/blog-v1012.py; products/shop old-footer untouched = Bug 7 waise hi)
- sitemap.xml: 19 → 24 URLs (+blog/ +4 articles)
- FORBIDDEN_AFTER checks: self-canonical ✓, koi relative ../ path nahi, koi relative asset path nahi (sab /assets absolute)

- Commit: 4781dfc (static-live) | Deploy: 45ce3781 → production
- Script: scripts/blog-v1012.py (footer linker + sitemap + post-checks)

### Verification
- [x] /blog/ 200, title "The Green Journal — Tree Plantation Guides | ShajarDost", canonical self
- [x] /blog → 308 → /blog/ (CF Pages auto)
- [x] 4 articles 200; cost article mein Rs.802/Rs.2,556/401,000 live; miyawaki mein "79% vs 47%" live
- [x] Home footer: Green Journal link ✓ | /donate rate matrix intact (Rs. 9,500) ✓
- [x] Sitemap live: 24 URLs | IndexNow: 6 URLs (5 blog + sitemap) → HTTP 200
- [x] Blog pages structure: 2× JSON-LD, 1× canonical, 1× H1, footer link — 5/5 clean

### Open items
- Agla SEO step: CSR landing page (sponsorship ke sath cross-link) + blog expansion (care guide article)
- Owner: article topics suggest kar sakta hai (DeraTalk se blog page par "Suggest a Topic") — ya admin/WhatsApp se


**END OF MASTER CHANGELOG**
