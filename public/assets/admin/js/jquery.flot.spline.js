
!function(i){"use strict";function e(i,e,s,t,n,l,o){var a,r,c,p,h,u,f,d,x=Math.pow,v=Math.sqrt;return a=v(x(s-i,2)+x(t-e,2)),r=v(x(n-s,2)+x(l-t,2)),c=o*a/(a+r),p=o-c,h=s+c*(i-n),u=t+c*(e-l),f=s-p*(i-n),d=t-p*(e-l),[h,u,f,d]}function s(e,s,t,n,l){var o=i.color.parse(l);o.a="number"==typeof n?n:.3,o.normalize(),o=o.toString(),s.beginPath(),s.moveTo(e[0][0],e[0][1]);for(var a=e.length,r=0;r<a;r++)s[e[r][3]].apply(s,e[r][2]);s.stroke(),s.lineWidth=0,s.lineTo(e[a-1][0],t),s.lineTo(e[0][0],t),s.closePath(),!1!==n&&(s.fillStyle=o,s.fill())}function t(i,e,s,t){(void 0===e||"bezier"!==e&&"quadratic"!==e)&&(e="quadratic"),e+="CurveTo",0==l.length?l.push([s[0],s[1],t.concat(s.slice(2)),e]):"quadraticCurveTo"==e&&2==s.length?(t=t.slice(0,2).concat(s),l.push([s[0],s[1],t,e])):l.push([s[2],s[3],t.concat(s.slice(2)),e])}function n(n,o,a){if(!0===a.splines.show){var r,c,p,h=[],u=a.splines.tension||.5,f=a.datapoints.points,d=a.datapoints.pointsize,x=n.getPlotOffset(),v=f.length,g=[];if(l=[],v/d<4)i.extend(a.lines,a.splines);else{for(r=0;r<v;r+=d)c=f[r],p=f[r+1],null==c||c<a.xaxis.min||c>a.xaxis.max||p<a.yaxis.min||p>a.yaxis.max||g.push(a.xaxis.p2c(c)+x.left,a.yaxis.p2c(p)+x.top);for(v=g.length,r=0;r<v-2;r+=2)h=h.concat(e.apply(this,g.slice(r,r+6).concat([u])));for(o.save(),o.strokeStyle=a.color,o.lineWidth=a.splines.lineWidth,t(o,"quadratic",g.slice(0,4),h.slice(0,2)),r=2;r<v-3;r+=2)t(o,"bezier",g.slice(r,r+4),h.slice(2*r-2,2*r+2));t(o,"quadratic",g.slice(v-2,v),[h[2*v-10],h[2*v-9],g[v-4],g[v-3]]),s(l,o,n.height()+10,a.splines.fill,a.color),o.restore()}}}var l=[];i.plot.plugins.push({init:function(i){i.hooks.drawSeries.push(n)},options:{series:{splines:{show:!1,lineWidth:2,tension:.5,fill:!1}}},name:"spline",version:"0.8.2"})}(jQuery);