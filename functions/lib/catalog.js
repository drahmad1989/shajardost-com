/* ShajarDost — functions/lib/catalog.js  (v1.0.25)
 * SINGLE SOURCE OF TRUTH: ShajarDost ke projects ki list + volunteer 12-task menu.
 * Consumed by /api/lead auto-ack email — har form filler ko foran milta hai:
 *   (1) ShajarDost ke projects ki list (1-line each, site ke apne alfaz mein),
 *   (2) us ke interest ka "agla qadam" block,
 *   (3) volunteer ho to EK tay-shuda pehla task (kya / kab tak / saboot).
 * Content sources: site pages ke meta descriptions + Volunteer Playbook Section 3
 * (12-task menu, ChatGPT-approved). Owner jab playbook badle to yahan badle —
 * email khud update ho jayega.
 * SAFETY: ye catalog 100% static text hai — koi user input is file mein enter
 * nahi hota, is liye email HTML mein injection ka koi raasta nahi.
 */

export const PROJECTS = [
  { name: 'Mission Fifty', url: '/mission-fifty', line: 'Zindagi mein 50 ped lagayein, unki care karein aur video se document karein' },
  { name: 'Green Scouts', url: '/green-scouts', line: 'School students (8-18 saal) ko native trees aur plantation ki training' },
  { name: 'Mini Nursery', url: '/mini-nursery', line: '5x5 feet ke chhat ya balcony par 100 saplings ka nursery' },
  { name: 'Miyawaki Forest', url: '/miyawaki-forest', line: 'Miyawaki tareeqa — 10x tez growth, 30x dense, 3 saal mein mature' },
  { name: 'Free Plants Wall', url: '/free-plants', line: 'Schools, kisan aur communities ke liye free plants — request post karein ya sponsor karein' },
  { name: 'Volunteer Program', url: '/volunteer', line: 'Chhote verified tasks se shuru — on-site, remote ya apne zile mein' },
  { name: 'Green Heroes', url: '/green-heroes', line: 'Darakhton ke liye apni zindagi ka hissa qurban karne walon ka izzat-nama' }
];

/* 12-task menu — Volunteer Playbook Section 3 (titles wahi, faida column ke sath).
 * what  = volunteer ko kya karna hai
 * proof = saboot kaise dena hai (photo / list / report / reply)
 * where = on-site (Kasur/Pattoki/Phool Nagar ke qareeb) | remote | own-district
 * hours = andazan waqt (playbook ke mutabiq — guarantee nahi)
 */
export const VOL_TASKS = {
  T1:  { id: 'T1',  title: 'Saplings ki care check',        where: 'on-site',      hours: '1-2 ghantay',  what: 'Muqarrar area ke paudon ko check karein — pani ki zaroorat, damage ya sukhne ke nishan report karein', proof: 'photos + chhoti report' },
  T2:  { id: 'T2',  title: 'Nursery inventory',             where: 'on-site',      hours: '1-2 ghantay',  what: 'Paudon ki ginti, species aur unki condition record karein', proof: 'list ya photos' },
  T3:  { id: 'T3',  title: 'Plantation documentation',      where: 'on-site',      hours: '1-3 ghantay',  what: 'Plantation ki pehle/baad ki photos, date aur site record karein (public sharing se pehle permission zaroori)', proof: 'photos + site note' },
  T4:  { id: 'T4',  title: 'Plantation-day support',        where: 'on-site',      hours: '2-4 ghantay',  what: 'Planting day par tools arrange karna, attendance record karna aur planting team ka support', proof: 'photos + attendance' },
  T5:  { id: 'T5',  title: 'Website/blog proofreading',     where: 'remote',       hours: '30-60 minute', what: 'shajardost.com ke pages parh kar spelling ya maloomat ki ghaltiyan note karein', proof: 'ghaltiyon ki list (is email par reply)' },
  T6:  { id: 'T6',  title: 'Social media post design',      where: 'remote',       hours: '45-90 minute', what: 'Ped lagane ya care par ek awareness post design karein', proof: 'draft image ya link' },
  T7:  { id: 'T7',  title: 'Short video editing',           where: 'remote',       hours: '1-2 ghantay',  what: 'Plantation ki videos ko chhota aur saaf edit karein', proof: 'video file ya link' },
  T8:  { id: 'T8',  title: 'Verified local tree research',  where: 'remote',       hours: '45-60 minute', what: 'Punjab ke kisi native ped par chhoti research (care tips, faide) likhein', proof: 'chhoti report + sources' },
  T9:  { id: 'T9',  title: 'Local plantation opportunity',  where: 'own-district', hours: '30-60 minute', what: 'Apne ilaqe mein ped lagane ki mumkin jagah (school, park, masjid) identify karein', proof: 'naam + location list' },
  T10: { id: 'T10', title: 'School/community contact list', where: 'own-district', hours: '30-60 minute', what: 'Apne ilaqe ke schools aur community contacts ki list banayein jo plantation mein interested ho sakte hain', proof: 'contact list' },
  T11: { id: 'T11', title: 'Existing trees photo survey',   where: 'own-district', hours: '1-2 ghantay',  what: 'Apne ilaqe ke purane darakhton ki photos aur locations ka survey karein', proof: 'photos + locations' },
  T12: { id: 'T12', title: 'Small awareness activity',      where: 'own-district', hours: '1-3 ghantay',  what: 'Class, family ya mohalle ke sath chhoti awareness activity karein', proof: 'photos + chhota note' }
};

/* volunteer.html "Preferred work" select → pehla task (playbook mapping).
 * Rule (Section 3): ek waqt mein sirf EK task. Default T5 — sab se aasan
 * remote starter (na jaga chahiye, na equipment). On-site tasks ko email
 * mein location-condition ke sath bheja jata hai; door hone par remote
 * alternative offer hota hai (Section 4 no-project policy). */
const ROLE_MAP = {
  'tree planting (field)': 'T4',
  'nursery care': 'T2',
  'photography / video': 'T7',
  'content writing': 'T5',
  'social media': 'T6',
  'teaching (green scouts)': 'T10',
  'event management': 'T4',
  'research / online tasks': 'T8',
  'local outreach in my area': 'T10',
  'anywhere needed': 'T5'
};

export function firstTaskFor(role) {
  /* internal whitespace bhi normalize (browser/form double-space safe) */
  const key = String(role == null ? '' : role).trim().toLowerCase().replace(/\s+/g, ' ');
  const id = ROLE_MAP[key] || 'T5';
  return VOL_TASKS[id];
}

/* interest/source → email block. extra = { role } (volunteer path ke liye).
 * Returns null (koi block nahi) ya { h, items[], task? } — sab STATIC strings;
 * items mein <b> tags allowed (catalog file khud hi in ko control karti hai). */
export function interestBlock(lead, extra) {
  const interest = String((lead && lead.interest) || '');
  const source = String((lead && lead.source) || '');
  const has = (re) => re.test(interest) || re.test(source);

  if (has(/volunteer/i)) {
    return {
      h: 'Aap ka pehla task',
      task: firstTaskFor(extra && extra.role),
      items: [
        'Paise ya donations collect karna sirf team ke authorized logon ka kaam hai — volunteer ke tor par kabhi nahi.',
        'Website ka admin password kisi volunteer ko nahi diya jata — kaam photos, lists aur reports ke zariye hota hai.'
      ]
    };
  }
  if (has(/mission ?50/i)) {
    return {
      h: 'Aap ki Mission Fifty progress',
      items: [
        'Team aap ki entry review kar ke verified count karegi — planting count aur surviving trees alag ginay jate hain.',
        'Har ped ki video <b>#ShajarDost</b> <b>#MissionFifty</b> ke sath share karein.',
        '50 verified plants mukammal hon to Mission Fifty certificate milta hai.'
      ]
    };
  }
  if (has(/plant demand/i) || has(/free.plants/i)) {
    return {
      h: 'Aap ki plant request ka agla qadam',
      items: [
        'Admin review ke baad aap ki request wall par approve hogi — aap ka phone/email website par kabhi nahi dikhta.',
        'Sponsor match hote hi team ped kharid kar lagane ka intezam karti hai.'
      ]
    };
  }
  if (has(/green scouts/i)) {
    return {
      h: 'Green Scouts ka agla qadam',
      items: [
        'Green Scouts 8 se 18 saal ke students ke liye training program hai.',
        '18 saal se kam umar wale students ki application parent/guardian ya school teacher ke zariye hoti hai.',
        'School ke liye training session ki planning call par hogi (24 ghantay ke andar).'
      ]
    };
  }
  if (has(/donate/i)) {
    return {
      h: 'Aap ki donation',
      items: [
        'IBAN aur details website ke Donate page par mojood hain — koi bhi baat call par confirm kar lein.',
        'Call par aap ko bataya jayega ke kis jagah aur kis qism ke ped lagenge.'
      ]
    };
  }
  if (has(/sponsorship|csr/i)) {
    return {
      h: 'Corporate sponsorship',
      items: [
        'Plantation drive, park ya Miyawaki forest — company ke liye options CSR page par hain.',
        'Proposal aur rates call par discuss hongi (24 ghantay ke andar).'
      ]
    };
  }
  if (has(/hero suggestion/i)) {
    return {
      h: 'Aap ki Green Hero suggestion',
      items: ['Har entry admin-verified hoti hai — verification ke baad website par publish hogi.']
    };
  }
  if (has(/miyawaki/i)) {
    return {
      h: 'Miyawaki Forest',
      items: [
        'Miyawaki method: 10x tez growth, 30x denser — 3 saal mein mature native jungle.',
        'Aap ki site ke liye planning call par hogi (24 ghantay ke andar).'
      ]
    };
  }
  if (has(/mini nursery/i)) {
    return {
      h: 'Mini Nursery',
      items: [
        '5x5 feet ke chhat ya balcony par 100 saplings tak mumkin hain.',
        'Setup guide aur saplings ki details call par share hongi (24 ghantay ke andar).'
      ]
    };
  }
  return null;
}
