/*! jQuery UI - v1.12.1 - 2017-03-31
* http://jqueryui.com
* Copyright jQuery Foundation and other contributors; Licensed  */
!function(a){"function"==typeof define&&define.amd?define(["jquery","../version","../effect"],a):a(jQuery)}(function(a){return a.effects.define("size",function(b,c){var d,e,f,g=a(this),h=["fontSize"],i=["borderTopWidth","borderBottomWidth","paddingTop","paddingBottom"],j=["borderLeftWidth","borderRightWidth","paddingLeft","paddingRight"],k=b.mode,l="effect"!==k,m=b.scale||"both",n=b.origin||["middle","center"],o=g.css("position"),p=g.position(),q=a.effects.scaledDimensions(g),r=b.from||q,s=b.to||a.effects.scaledDimensions(g,0);a.effects.createPlaceholder(g),"show"===k&&(f=r,r=s,s=f),e={from:{y:r.height/q.height,x:r.width/q.width},to:{y:s.height/q.height,x:s.width/q.width}},"box"!==m&&"both"!==m||(e.from.y!==e.to.y&&(r=a.effects.setTransition(g,i,e.from.y,r),s=a.effects.setTransition(g,i,e.to.y,s)),e.from.x!==e.to.x&&(r=a.effects.setTransition(g,j,e.from.x,r),s=a.effects.setTransition(g,j,e.to.x,s))),"content"!==m&&"both"!==m||e.from.y!==e.to.y&&(r=a.effects.setTransition(g,h,e.from.y,r),s=a.effects.setTransition(g,h,e.to.y,s)),n&&(d=a.effects.getBaseline(n,q),r.top=(q.outerHeight-r.outerHeight)*d.y+p.top,r.left=(q.outerWidth-r.outerWidth)*d.x+p.left,s.top=(q.outerHeight-s.outerHeight)*d.y+p.top,s.left=(q.outerWidth-s.outerWidth)*d.x+p.left),g.css(r),"content"!==m&&"both"!==m||(i=i.concat(["marginTop","marginBottom"]).concat(h),j=j.concat(["marginLeft","marginRight"]),g.find("*[width]").each(function(){var c=a(this),d=a.effects.scaledDimensions(c),f={height:d.height*e.from.y,width:d.width*e.from.x,outerHeight:d.outerHeight*e.from.y,outerWidth:d.outerWidth*e.from.x},g={height:d.height*e.to.y,width:d.width*e.to.x,outerHeight:d.height*e.to.y,outerWidth:d.width*e.to.x};e.from.y!==e.to.y&&(f=a.effects.setTransition(c,i,e.from.y,f),g=a.effects.setTransition(c,i,e.to.y,g)),e.from.x!==e.to.x&&(f=a.effects.setTransition(c,j,e.from.x,f),g=a.effects.setTransition(c,j,e.to.x,g)),l&&a.effects.saveStyle(c),c.css(f),c.animate(g,b.duration,b.easing,function(){l&&a.effects.restoreStyle(c)})})),g.animate(s,{queue:!1,duration:b.duration,easing:b.easing,complete:function(){var b=g.offset();0===s.opacity&&g.css("opacity",r.opacity),l||(g.css("position","static"===o?"relative":o).offset(b),a.effects.saveStyle(g)),c()}})})});