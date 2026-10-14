/**
 * Universal WhatsApp Referrer Tracker v3
 * Appends site name + page URL to ALL WhatsApp links + float button
 */
(function(){
'use strict';

function getSiteName() {
  const host = window.location.hostname.replace('www.', '');
  const map = {
    'birdnest.pk':'BirdNest.pk','pattoki.pk':'Pattoki.Pk','podayonline.com':'PodayOnline',
    'gardeningtools.pk':'GardeningTools.pk','seedballs.pk':'SeedBalls.pk',
    'shajardost.com':'ShajarDost','digitalkasur.com':'DigitalKasur',
    'tarteelstudio.org':'TarteelStudio','startnest.pk':'StartNest',
    'tiktokcoins.pk':'TikTokCoins','tiktokshop.pk':'TikTokShop',
    'eshalcare.com':'EshalCare','kasurizaiqa.com':'KasuriZaiqa',
    'phoolnagar.pk':'PhoolNagar','alansaarschools.com':'AlAnsaar',
  };
  return map[host] || host;
}

function getWA() {
  if (window.__WA__) return window.__WA__;
  const link = document.querySelector('a[href*="wa.me/"]');
  if (link) {
    const m = link.href.match(/wa\.me\/(\d+)/);
    if (m) return m[1];
  }
  const host = window.location.hostname.replace('www.', '');
  const defaults = {
    'birdnest.pk':'923360000597','pattoki.pk':'923360000597',
    'podayonline.com':'923360000597','gardeningtools.pk':'923360000597',
    'seedballs.pk':'923360000597','shajardost.com':'923360000597',
    'digitalkasur.com':'923713097977','tarteelstudio.org':'923713097977',
  };
  return defaults[host] || '';
}

function getPage() {
  const p = window.location.pathname;
  if (p === '/' || p === '') return 'Homepage';
  return p.split('/').pop().replace('.html','').replace(/-/g,' ') || 'Homepage';
}

function process() {
  const site = getSiteName();
  const page = getPage();
  const wa = getWA();
  const url = window.location.href.split('?')[0];
  if (!wa) return;
  
  // Process all wa.me links
  document.querySelectorAll('a[href*="wa.me/"]').forEach(link => {
    if (link.hasAttribute('data-wa-fixed')) return;
    let href = link.getAttribute('href');
    if (!href) return;
    
    // Ensure the number is in the URL
    if (!href.match(/wa\.me\/\d+/)) {
      href = href.replace('wa.me/?', `wa.me/${wa}?`);
      href = href.replace('wa.me?', `wa.me/${wa}?`);
    }
    
    // Add tracking to the text parameter
    let text = '';
    try {
      const u = new URL(href);
      text = u.searchParams.get('text') || '';
      if (!text.includes(site)) {
        text = text + '%0A%0A[' + encodeURIComponent(site + ' → ' + page) + ']';
        text = text + '%20' + encodeURIComponent(url);
        u.searchParams.set('text', text);
        href = u.toString();
      }
    } catch(e) {}
    
    link.setAttribute('href', href);
    link.setAttribute('data-wa-fixed', 'true');
  });
  
  // Fix float button — override its click handler
  const float = document.getElementById('waFloat');
  if (float && !float.hasAttribute('data-wa-fixed')) {
    float.setAttribute('data-wa-fixed', 'true');
    // Remove all existing listeners by replacing with clone
    const clone = float.cloneNode(true);
    float.parentNode.replaceChild(clone, float);
    clone.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      const msg = encodeURIComponent('Hello ' + site + '! I visited: ' + page + ' | ' + url);
      window.open('https://wa.me/' + wa + '?text=' + msg, '_blank');
    });
    clone.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        clone.click();
      }
    });
  }
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', process);
} else {
  process();
}
setTimeout(process, 1000);
setTimeout(process, 3000);
})();
