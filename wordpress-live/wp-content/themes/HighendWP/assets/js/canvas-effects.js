!function(){for(var n=0,e=["ms","moz","webkit","o"],i=0;i<e.length&&!window.requestAnimationFrame;++i)window.requestAnimationFrame=window[e[i]+"RequestAnimationFrame"],window.cancelAnimationFrame=window[e[i]+"CancelAnimationFrame"]||window[e[i]+"CancelRequestAnimationFrame"];window.requestAnimationFrame||(window.requestAnimationFrame=function(e){var i=(new Date).getTime(),t=Math.max(0,16-(i-n)),o=window.setTimeout(function(){e(i+t)},t);return n=i+t,o}),window.cancelAnimationFrame||(window.cancelAnimationFrame=function(n){clearTimeout(n)})}(),function(){function n(){s=document.getElementById("slider-section"),jQuery("#slider-section").hasClass("fullscreen-image")?(r=window.innerWidth,d=window.innerHeight):(r=s.offsetWidth,d=s.offsetHeight),l={x:0,y:d},c=document.getElementById("hb-canvas-effect"),c.width=r,c.height=d,w=c.getContext("2d"),m=[];for(var n=0;.3*r>n;n++){var e=new a;m.push(e)}o()}function e(){window.addEventListener("scroll",i),window.addEventListener("resize",t)}function i(){h=document.body.scrollTop>d?!1:!0}function t(){jQuery("#slider-section").hasClass("fullscreen-image")?(r=window.innerWidth,d=window.innerHeight):(r=s.offsetWidth,d=s.offsetHeight),c.width=r,c.height=d}function o(){if(h){w.clearRect(0,0,r,d);for(var n in m)m[n].draw()}requestAnimationFrame(o)}function a(){function n(){e.pos.x=Math.random()*r,e.pos.y=d+100*Math.random(),e.alpha=.1+.2*Math.random(),e.scale=.1+.5*Math.random(),e.velocity=Math.random()}var e=this;!function(){e.pos={},n()}(),this.draw=function(){e.alpha<=0&&n(),e.pos.y-=e.velocity,e.alpha-=5e-4,w.beginPath(),w.arc(e.pos.x,e.pos.y,10*e.scale,0,2*Math.PI,!1),w.fillStyle="rgba(255,255,255,"+e.alpha+")",w.fill()}}var r,d,s,c,w,m,l,h=!0;n(),e()}();