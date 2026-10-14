/* ShajarDost.com - Main JS */
(function(){
'use strict';
const WA='923360000597';
const SITE_URL='https://shajardost.com';

// Nav toggle
const navToggle=document.getElementById('navToggle'),navMenu=document.getElementById('navMenu');
if(navToggle){navToggle.addEventListener('click',()=>{navToggle.classList.toggle('active');navMenu.classList.toggle('open');});}

// Header scroll + back-top
const header=document.getElementById('siteHeader'),backTop=document.getElementById('backTop');
window.addEventListener('scroll',()=>{
  if(window.scrollY>20){if(header)header.classList.add('scrolled');if(backTop)backTop.classList.add('show');}
  else{if(header)header.classList.remove('scrolled');if(backTop)backTop.classList.remove('show');}
},{passive:true});
if(backTop){backTop.addEventListener('click',()=>window.scrollTo({top:0,behavior:'smooth'}));}

// WhatsApp float
const waFloat=document.getElementById('waFloat');
if(waFloat){waFloat.addEventListener('click',()=>{window.open('https://wa.me/'+WA+'?text='+encodeURIComponent('Hello ShajarDost! I want to know more about your programs.'),'_blank');});}

// SW
if('serviceWorker' in navigator){window.addEventListener('load',()=>navigator.serviceWorker.register('sw.js').catch(()=>{}));}
})();
