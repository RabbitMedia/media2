/*
* FitVids 1.0
*
* Copyright 2011, Chris Coyier - http://css-tricks.com + Dave Rupert - http://daverupert.com
* Credit to Thierry Koblentz - http://www.alistapart.com/articles/creating-intrinsic-ratios-for-video/
* Released under the WTFPL license - http://sam.zoy.org/wtfpl/
*
* Date: Thu Sept 01 18:00:00 2011 -0500
*/
(function(a){a.fn.fitVids=function(e){var b={customSelector:null};var c=document.createElement("div"),d=document.getElementsByTagName("base")[0]||document.getElementsByTagName("script")[0];c.className="fit-vids-style";c.innerHTML="­<style> .fluid-width-video-wrapper {width:100%;position:relative;padding:0;} .fluid-width-video-wrapper iframe, .fluid-width-video-wrapper object, .fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}</style>";d.parentNode.insertBefore(c,d);if(e){a.extend(b,e)}return this.each(function(){var g=["iframe[src*='player.vimeo.com']","iframe[src*='www.youtube.com']","iframe[src*='www.kickstarter.com']","object","embed"];if(b.customSelector){g.push(b.customSelector)}var f=a(this).find(g.join(","));f.each(function(){var h=a(this);if(this.tagName.toLowerCase()==="embed"&&h.parent("object").length||h.parent(".fluid-width-video-wrapper").length){return}var l=(this.tagName.toLowerCase()==="object"||(h.attr("height")&&!isNaN(parseInt(h.attr("height"),10))))?parseInt(h.attr("height"),10):h.height(),j=!isNaN(parseInt(h.attr("width"),10))?parseInt(h.attr("width"),10):h.width(),i=l/j;if(!h.attr("id")){var k="fitvid"+Math.floor(Math.random()*999999);h.attr("id",k)}h.wrap('<div class="fluid-width-video-wrapper"></div>').parent(".fluid-width-video-wrapper").css("padding-top",(i*100)+"%");h.removeAttr("height").removeAttr("width")})})}})(jQuery);