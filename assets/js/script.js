/* ShajarDost.com - Main JS */
(function(){
'use strict';
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

// Lead backup capture: har Web3Forms form submit par lead Cloudflare KV mein bhi save
// hoti hai (fire-and-forget) — agar email fail ho jaye to lead phir bhi zaya nahi hogi.
// Native form submission (email + redirect) bilkul normal chalti rehti hai.
document.addEventListener('submit',function(e){
  var f=e.target;
  if(!f||!f.action||f.action.indexOf('api.web3forms.com')===-1)return;
  try{
    var fd=new FormData(f);
    fd.append('_source',location.pathname);
    fetch('/api/lead',{method:'POST',body:fd,keepalive:true}).catch(function(){});
  }catch(err){}
},true);

// SW
if('serviceWorker' in navigator){window.addEventListener('load',()=>navigator.serviceWorker.register('sw.js').catch(()=>{}));}
})();
