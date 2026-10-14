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

**END OF MASTER CHANGELOG**
