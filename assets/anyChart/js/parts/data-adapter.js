if(!_.data_adapter){_.data_adapter=1;(function($){var jT=function(a){try{return a.b?a.b.responseText:""}catch(b){return""}},lT=function(a,b,c,d,e,f,h,k,l){var m=kT("fromXml",c,l);m&&(b=(0,$.qa)(lT.b,void 0,b,c,l,m),$.LS(a,b,d,e,f,h,k))},kT=function(a,b,c){var d=$.Sj.anychart;if(!d)return b&&b.call(c,500,"AnyChart in not present on the page."),null;d=d[a];return d?d:(b&&b.call(c,500,$.$b("anychart.%s is not available.",a)),null)},jga=function(a){return[$.US(a)||null]},kga=function(a){try{var b=a.b?a.b.responseXML:null}catch(c){b=null}return[$.Sj.anychart.utils.xml2json(b).data]},
lga=function(a){a=$.US(a);for(var b=a.feed.entry,c={title:a.feed.title.$t,rows:[]},d=0,e=b.length;d<e;d++){var f=b[d],h=f.gs$cell.$t,k=f.gs$cell.col-1;f=f.gs$cell.row-1;c.rows[f]||(c.rows[f]=[]);c.rows[f][k]=h}c.header=c.rows.shift();return[c,a]},mga=function(a){return[jT(a)]},mT=function(a,b,c,d,e){e=e.target;if($.RS(e)){try{var f=a(e)}catch(h){c&&c.call(d,500,h)}b.apply(d,f)}else c&&c.call(d,e.g,$.VS(e))};$.Sj.anychart.exports||$.Pj(4,null,["Exporting"]);
lT.b=function(a,b,c,d,e){e=e.target;if($.RS(e)){try{var f=d(jT(e))}catch(h){b&&b.call(c,500,h)}a?$.z(a)?(f.container(a),f.draw()):$.E(a)&&a.call(c,f):f.container()&&f.draw()}else b&&b.call(c,e.g,$.VS(e))};$.G("anychart.fromXmlFile",lT);$.G("anychart.fromJsonFile",function(a,b,c,d,e,f,h,k,l){var m=kT("fromJson",c,l);m&&(b=(0,$.qa)(lT.b,void 0,b,c,l,m),$.LS(a,b,d,e,f,h,k))});
$.G("anychart.data.parseHtmlTable",function(a,b,c,d,e,f){var h=window.document.querySelector(a||"table");a=null;var k;if(h){d=d||"tr:first-child th";c=c||"td, th";b=b||"tr";a={};(e=h.querySelector(e||"caption"))&&(k=f?f.call(void 0,e):e.innerText);k&&(a.title=k);var l=h.querySelectorAll(d),m=[];e=null;d=0;for(k=l.length;d<k;d++){var p=l[d];p&&!e&&(e=$.we(p));m.push(f?f.call(void 0,p):p.innerText)}m.length&&(a.header=m);if((b=h.querySelectorAll(b))&&b.length){h=[];d=0;for(k=b.length;d<k;d++)if(m=b[d],
m!=e){l=[];if((m=m.querySelectorAll(c))&&m.length){p=0;for(var q=m.length;p<q;p++){var r=m[p];f?l.push(f.call(void 0,r)):l.push(r.innerText)}}l.length&&h.push(l)}a.rows=h}}return a});$.G("anychart.data.loadJsonFile",function(a,b,c,d,e,f,h,k,l){b=(0,$.qa)(mT,void 0,jga,b,c,l);$.LS(a,b,d,e,f,h,k)});$.G("anychart.data.loadXmlFile",function(a,b,c,d,e,f,h,k,l){b=(0,$.qa)(mT,void 0,kga,b,c,l);$.LS(a,b,d,e,f,h,k)});
$.G("anychart.data.loadCsvFile",function(a,b,c,d,e,f,h,k,l){b=(0,$.qa)(mT,void 0,mga,b,c,l);$.LS(a,b,d,e,f,h,k)});
$.G("anychart.data.loadGoogleSpreadsheet",function(a,b,c,d,e){b=(0,$.qa)(mT,void 0,lga,b,c,e);$.z(a)?(c=a,a="od6"):(c=a.key,a=$.n(a.sheet)?a.sheet:"od6");a=new $.hS("https://spreadsheets.google.com/feeds/cells/"+c+"/"+a+"/public/values");a.f.set("alt","json");c=Math.floor(2147483648*Math.random()).toString(36)+Math.abs(Math.floor(2147483648*Math.random())^(0,$.rm)()).toString(36);a.f.set("zx",c);$.LS(a.toString(),b,"GET",null,null,d)});}).call(this,$)}