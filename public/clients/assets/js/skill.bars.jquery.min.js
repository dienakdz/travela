/*!
 * https://github.com/umarwebdeveloper/jquery-css-skills-bar
 * Author: @umarwebdeveloper
 * Licensed under the MIT license
 */
 
!function(e){e.fn.skillBars=function(t){var a=e.extend({from:0,to:!1,speed:1e3,interval:100,decimals:0,onUpdate:null,onComplete:null,classes:{skillBarBar:".skillbar-bar",skillBarPercent:".skill-bar-percent"}},t);return this.each(function(){var t=e(this),n=0!=a.to?a.to:parseInt(t.attr("data-percent"));n>100&&(n=100);var l=a.from,r=Math.ceil(a.speed/a.interval),s=(n-l)/r,i=0,o=setInterval(function(){l+=s,i++,e(t).find(a.classes.skillBarPercent).text(l.toFixed(a.decimals)+"%"),"function"==typeof a.onUpdate&&a.onUpdate.call(t,l);i>=r&&(clearInterval(o),l=n,"function"==typeof a.onComplete&&a.onComplete.call(t,l))},a.interval);t.find(a.classes.skillBarBar).animate({width:parseInt(t.attr("data-percent"))+"%"},a.speed)})}}(jQuery);