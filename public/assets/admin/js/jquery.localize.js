
!function(n){var t;t=function(n){return(n=n.replace(/_/,"-").toLowerCase()).length>3&&(n=n.substring(0,3)+n.substring(3).toUpperCase()),n},n.defaultLanguage=t(navigator.languages&&navigator.languages.length>0?navigator.languages[0]:navigator.language||navigator.userLanguage),n.localize=function(e,a){var r,l,u,i,o,c,s,f,g,p,v,h,d,b,x,j,m,z;return null==a&&(a={}),z=this,i={},u=a.fileExtension||"json",l=n.Deferred(),s=function(n,t,e){var r;switch(null==e&&(e=1),e){case 1:return i={},a.loadBase?(r=n+"."+u,o(r,n,t,e)):s(n,t,2);case 2:return r=n+"-"+t.split("-")[0]+"."+u,o(r,n,t,e);case 3:return r=n+"-"+t.split("-").slice(0,2).join("-")+"."+u,o(r,n,t,e);default:return l.resolve()}},o=function(t,e,r,l){var u,o,c;return null!=a.pathPrefix&&(t=a.pathPrefix+"/"+t),c=function(t){return n.extend(i,t),d(i),s(e,r,l+1)},o=function(){return 2===l&&r.indexOf("-")>-1?s(e,r,l+1):a.fallback&&a.fallback!==r?s(e,a.fallback):void 0},u={url:t,dataType:"json",async:!0,timeout:null!=a.timeout?a.timeout:500,success:c,error:o},"file:"===window.location.protocol&&(u.error=function(t){return c(n.parseJSON(t.responseText))}),n.ajax(u)},d=function(n){return null!=a.callback?a.callback(n,r):r(n)},r=function(t){return n.localize.data[e]=t,z.each(function(){var e,a,r;if(e=n(this),(a=e.data("localize"))||(a=e.attr("rel").match(/localize\[(.*?)\]/)[1]),null!=(r=m(a,t)))return f(e,a,r)})},f=function(t,e,a){if(t.is("input")?v(t,e,a):t.is("textarea")?v(t,e,a):t.is("img")?p(t,e,a):t.is("optgroup")?h(t,e,a):n.isPlainObject(a)||t.html(a),n.isPlainObject(a))return g(t,a)},v=function(t,e,a){var r;return r=n.isPlainObject(a)?a.value:a,t.is("[placeholder]")?t.attr("placeholder",r):t.val(r)},g=function(n,t){return x(n,"title",t),x(n,"href",t),j(n,"text",t)},h=function(n,t,e){return n.attr("label",e)},p=function(n,t,e){return x(n,"alt",e),x(n,"src",e)},m=function(n,t){var e,a,r,l;for(a=t,r=0,l=(e=n.split(/\./)).length;r<l;r++)n=e[r],a=null!=a?a[n]:null;return a},x=function(n,t,e){if(null!=(e=m(t,e)))return n.attr(t,e)},j=function(n,t,e){if(null!=(e=m(t,e)))return n.text(e)},b=function(n){var t;return"string"==typeof n?"^"+n+"$":null!=n.length?function(){var e,a,r;for(r=[],e=0,a=n.length;e<a;e++)t=n[e],r.push(b(t));return r}().join("|"):n},c=t(a.language?a.language:n.defaultLanguage),a.skipLanguage&&c.match(b(a.skipLanguage))?l.resolve():s(e,c,1),z.localizePromise=l,z},n.fn.localize=n.localize,n.localize.data={}}(jQuery);