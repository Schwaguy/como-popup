/*!
 * jQuery Cookie Plugin v1.4.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2006, 2014 Klaus Hartl
 * Released under the MIT license
 */
!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):"object"==typeof exports?module.exports=e(require("jquery")):e(jQuery)}(function(e){var i=/\+/g;function n(e){return t.raw?e:encodeURIComponent(e)}function r(e){return t.raw?e:decodeURIComponent(e)}function o(n,r){var o=t.raw?n:function e(n){0===n.indexOf('"')&&(n=n.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\"));try{return n=decodeURIComponent(n.replace(i," ")),t.json?JSON.parse(n):n}catch(r){}}(n);return e.isFunction(r)?r(o):o}var t=e.cookie=function(i,c,s){if(arguments.length>1&&!e.isFunction(c)){if("number"==typeof(s=e.extend({},t.defaults,s)).expires){var u,a=s.expires,f=s.expires=new Date;f.setMilliseconds(f.getMilliseconds()+864e5*a)}return document.cookie=[n(i),"=",(u=c,n(t.json?JSON.stringify(u):String(u))),s.expires?"; expires="+s.expires.toUTCString():"",s.path?"; path="+s.path:"",s.domain?"; domain="+s.domain:"",s.secure?"; secure":""].join("")}for(var p=i?void 0:{},l=document.cookie?document.cookie.split("; "):[],d=0,x=l.length;d<x;d++){var v=l[d].split("="),g=r(v.shift()),k=v.join("=");if(i===g){p=o(k,c);break}i||void 0===(k=o(k))||(p[g]=k)}return p};t.defaults={},e.removeCookie=function(i,n){return e.cookie(i,"",e.extend({},n,{expires:-1})),!e.cookie(i)}});

// Como Modal
function autoModal(o){"use strict";jQuery("#"+o).modal("show")}