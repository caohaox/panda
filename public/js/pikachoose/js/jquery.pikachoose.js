(function(a){var b={autoPlay:true,speed:5e3,text:{play:"",stop:"",previous:"Previous",next:"Next",loading:"Loading"},transition:[1],showCaption:true,IESafe:false,showTooltips:false,carousel:false,carouselVertical:false,animationFinished:null,buildFinished:null,bindsFinished:null,startOn:0,thumbOpacity:.4,hoverPause:false,animationSpeed:600,fadeThumbsIn:false,carouselOptions:{},thumbChangeEvent:"click.pikachoose",stopOnClick:false,minW:271,minH:271};a.fn.PikaChoose=function(b){return this.each(function(){a(this).data("pikachoose",new c(this,b))})};a.PikaChoose=function(c,d){this.options=a.extend({},b,d||{});this.list=null;this.image=null;this.anchor=null;this.caption=null;this.imgNav=null;this.imgPlay=null;this.imgPrev=null;this.imgNext=null;this.textNext=null;this.textPrev=null;this.previous=null;this.next=null;this.aniWrap=null;this.aniDiv=null;this.aniImg=null;this.thumbs=null;this.transition=null;this.active=null;this.tooltip=null;this.animating=false;this.stillOut=null;this.counter=null;this.timeOut=null;this.pikaStage=null;if(typeof this.options.data!="undefined"){c=a("<ul></ul>").addClass("jcarousel-skin-pika").appendTo(c);a.each(this.options.data,function(){if(typeof this.link!="undefined"){var b=a("<li><a href='"+this.link+"'><img></a></li>").appendTo(c);if(typeof this.title!="undefined"){b.find("a").attr("title",this.title)}}else{var b=a("<li><img></li>").appendTo(c)}if(typeof this.caption!="undefined"){b.append("<span>"+this.caption+"</span>")}if(typeof this.thumbnail!="undefined"){b.find("img").attr("ref",this.image);b.find("img").attr("src",this.thumbnail)}else{b.find("img").attr("src",this.image)}})}if(c instanceof jQuery||c.nodeName.toUpperCase()=="UL"||c.nodeName.toUpperCase()=="OL"){this.list=a(c);this.build();this.bindEvents()}else{return}var e=0;var f=0;for(var g=0;g<25;g++){var h='<div col="'+e+'" row="'+f+'"></div>';this.aniDiv.append(h);e++;if(e==5){f++;e=0}}};var c=a.PikaChoose;c.fn=c.prototype={pikachoose:"4.4.8"};a.fn.pikachoose=a.fn.PikaChoose;c.fn.extend=c.extend=a.extend;c.fn.extend({build:function(){this.step=0;if(this.options.pikaStage){this.wrap=this.options.pikaStage;this.wrap.addClass("pika-stage")}else{this.wrap=a("<div class='pika-stage'></div>").insertBefore(this.list)}this.image=a("<img>").appendTo(this.wrap);this.imgNav=a("<div class='pika-imgnav'></div>").insertAfter(this.image);this.imgPlay=a("<a></a>").appendTo(this.imgNav);this.counter=a("<span class='pika-counter'></span>").appendTo(this.imgNav);if(this.options.autoPlay){this.imgPlay.addClass("pause")}else{this.imgPlay.addClass("play")}this.imgPrev=a("<a class='previous'></a>").insertAfter(this.imgPlay);this.imgNext=a("<a class='next'></a>").insertAfter(this.imgPrev);this.caption=a("<div class='caption'></div>").insertAfter(this.imgNav).hide();this.tooltip=a("<div class='pika-tooltip'></div>").insertAfter(this.list).hide();this.aniWrap=a("<div class='pika-aniwrap'></div>").insertAfter(this.caption);this.aniImg=a("<img>").appendTo(this.aniWrap).hide();this.aniDiv=a("<div class='pika-ani'></div>").appendTo(this.aniWrap);this.textNav=a("<div class='pika-textnav'></div>").insertAfter(this.aniWrap);this.textPrev=a("<a class='previous'>"+this.options.text.previous+"</a>").appendTo(this.textNav);this.textNext=a("<a class='next'>"+this.options.text.next+"</a>").appendTo(this.textNav);this.list.addClass("pika-thumbs");this.thumbs=this.list.find("img");this.loader=a("<div class='pika-loader'></div>").appendTo(this.wrap).hide().html(this.options.text.loading);this.active=this.thumbs.eq(this.options.startOn);this.finishAnimating({index:this.options.startOn,source:this.active.attr("ref")||this.active.attr("src"),caption:this.active.parents("li:first").find("span:first").html(),clickThrough:this.active.parent().attr("href")||"",clickThroughTarget:this.active.parent().attr("target")||"",clickThroughTitle:this.active.parent().attr("title")||""});this.aniDiv.css({position:"relative"});var b=this;this.updateThumbs();if(this.options.fadeThumbsIn){this.list.fadeIn()}if(this.options.carousel){var c={vertical:this.options.carouselVertical,initCallback:function(a){jQuery(a.list).find("img").click(function(c,d){if(typeof d!=="undefined"&&d.how=="auto"){if(b.options.autoPlay==false){return false}}var e=parseInt(jQuery(this).parents(".jcarousel-item").attr("jcarouselindex"));var f=jQuery(this).parents("ul").find("li:last").attr("jcarouselindex")==e-1?true:false;if(!f){e=e-2<=0?0:e-2}e++;a.scroll(e)})}};var d=a.extend({},c,this.options.carouselOptions||{});this.list.jcarousel(d)}if(typeof this.options.buildFinished=="function"){this.options.buildFinished(this)}},createThumb:function(b){var c=b;var d=this;this.thumbs=this.list.find("img");if(typeof a.data(b[0],"source")!=="undefined"){return}b.parents("li:first").wrapInner("<div class='clip' />");c.hide();a.data(b[0],"clickThrough",c.parent("a").attr("href")||"");a.data(b[0],"clickThroughTarget",c.parent("a").attr("target")||"");a.data(b[0],"clickThroughTitle",c.parent("a").attr("title")||"");if(c.parent("a").length>0){c.unwrap()}a.data(b[0],"caption",c.next("span").html()||"");c.next("span").remove();a.data(b[0],"source",c.attr("ref")||c.attr("src"));a.data(b[0],"imageTitle",c.attr("title")||"");a.data(b[0],"imageAlt",c.attr("alt")||"");a.data(b[0],"index",this.thumbs.index(b));a.data(b[0],"order",c.closest("ul").find("li").index(c.parents("li")));var e=a.data(b[0]);a("<img />").bind("load",{data:e},function(){if(typeof d.options.buildThumbStart=="function"){d.options.buildThumbStart(d)}var b=a(this);var f=this.width;var g=this.height;if(f===0){f=b.attr("width")}if(g===0){g=b.attr("height")}var h=parseInt(c.parents(".clip").css("height").slice(0,-2));var i=parseInt(c.parents(".clip").css("width").slice(0,-2));if(i==0){i=c.parents("li:first").outerWidth()}if(h==0){h=c.parents("li:first").outerHeight()}var j=i/f;var k=h/g;var l;if(j<k){c.css({height:"100%"})}else{c.css({width:"100%"})}c.hover(function(b){clearTimeout(d.stillOut);a(this).stop(true,true).fadeTo(250,1);if(!d.options.showTooltips){return}d.tooltip.show().stop(true,true).html(e.caption).animate({top:a(this).parent().position().top,left:a(this).parent().position().left,opacity:1},"fast")},function(b){if(!a(this).hasClass("active")){a(this).stop(true,true).fadeTo(250,d.options.thumbOpacity);d.stillOut=setTimeout(d.hideTooltip,700)}});if(e.order==d.options.startOn){c.fadeTo(250,1);c.addClass("active");c.parents("li").eq(0).addClass("active")}else{c.fadeTo(250,d.options.thumbOpacity)}if(typeof d.options.buildThumbFinish=="function"){d.options.buildThumbFinish(d)}}).attr("src",c.attr("src"))},bindEvents:function(){this.thumbs.bind(this.options.thumbChangeEvent,{self:this},this.imgClick);this.imgNext.bind("click.pikachoose",{self:this},this.nextClick);this.textNext.bind("click.pikachoose",{self:this},this.nextClick);this.imgPrev.bind("click.pikachoose",{self:this},this.prevClick);this.textPrev.bind("click.pikachoose",{self:this},this.prevClick);this.imgPlay.unbind("click.pikachoose").bind("click.pikachoose",{self:this},this.playClick);this.wrap.unbind("mouseenter.pikachoose").bind("mouseenter.pikachoose",{self:this},function(a){a.data.self.imgNav.stop(true,true).fadeTo("slow",1);if(a.data.self.options.hoverPause==true){clearTimeout(a.data.self.timeOut)}});this.wrap.unbind("mouseleave.pikachoose").bind("mouseleave.pikachoose",{self:this},function(a){a.data.self.imgNav.stop(true,true).fadeTo("slow",0);if(a.data.self.options.autoPlay==true&&a.data.self.options.hoverPause){a.data.self.timeOut=setTimeout(function(a){return function(){a.nextClick()}}(a.data.self),a.data.self.options.speed)}});this.tooltip.unbind("mouseenter.pikachoose").bind("mouseenter.pikachoose",{self:this},function(a){clearTimeout(a.data.self.stillOut)});this.tooltip.unbind("mouseleave.pikachoose").bind("mouseleave.pikachoose",{self:this},function(a){a.data.self.stillOut=setTimeout(a.data.self.hideTooltip,700)});if(typeof this.options.bindsFinished=="function"){this.options.bindsFinished(this)}},hideTooltip:function(b){a(".pika-tooltip").animate({opacity:.01})},imgClick:function(b,c){var d=b.data.self;var e=a.data(this);if(d.animating){return}if(typeof c=="undefined"||c.how!="auto"){if(d.options.autoPlay&&d.options.stopOnClick){d.imgPlay.trigger("click")}else{clearTimeout(d.timeOut)}}else{if(d.options.autoPlay==false){return false}}if(a(this).attr("src")!==a.data(this).source){d.loader.fadeIn("fast")}d.caption.fadeOut("slow");d.animating=true;d.active.fadeTo(300,d.options.thumbOpacity).removeClass("active");d.active.parents(".active").eq(0).removeClass("active");d.active=a(this);d.active.addClass("active").fadeTo(200,1);d.active.parents("li").eq(0).addClass("active");a("<img />").bind("load",{self:d,data:e},function(){d.loader.fadeOut("fast");d.aniDiv.css({height:d.image.height(),width:d.image.width()}).show();d.aniDiv.children("div").css({width:"20%",height:"20%","float":"left"});var b=0;if(d.options.transition[0]==-1){b=Math.floor(Math.random()*7)+1}else{b=d.options.transition[d.step];d.step++;if(d.step>=d.options.transition.length){d.step=0}}if(d.options.IESafe&&a.browser.msie){b=1}d.doAnimation(b,e)}).attr("src",a.data(this).source)},doAnimation:function(b,c){this.aniWrap.css({position:"absolute",top:this.wrap.css("padding-top"),left:this.wrap.css("padding-left"),width:this.wrap.width()});var d=this;d.image.stop(true,false);d.caption.stop().fadeOut();var e=d.aniDiv.children("div").eq(0).width();var f=d.aniDiv.children("div").eq(0).height();var g=new Image;a(g).attr("src",c.source);if(g.height!=d.image.height()||g.width!=d.image.width()){if(b!=0&&b!=1&&b!=7){}}d.aniDiv.css({height:d.image.height(),width:d.image.width()});d.aniDiv.children().each(function(){var b=a(this);var d=Math.floor(b.parent().width()/5)*b.attr("col");var e=Math.floor(b.parent().height()/5)*b.attr("row");b.css({background:"url("+c.source+") -"+d+"px -"+e+"px","background-size":b.parent().width()+"px "+b.parent().height()+"px",width:"0px",height:"0px",position:"absolute",top:e+"px",left:d+"px","float":"none"})});d.aniDiv.hide();d.aniImg.hide();switch(b){case 0:d.image.stop(true,true).fadeOut(d.options.animationSpeed,function(){d.image.attr("src",c.source).fadeIn(d.options.animationSpeed,function(){d.finishAnimating(c)})});break;case 1:d.aniDiv.hide();d.aniImg.hide().attr("src",c.source);w=g.width;h=g.height;if(w>h*d.options.minW/d.options.minH){d.aniImg.height(d.options.minH+"px");newW=d.options.minH*w/h;d.aniImg.width(newW+"px");d.aniImg.css("left",-1*((newW-d.options.minW)/2)+"px")}else if(h>w*d.options.minH/d.options.minW){d.aniImg.width(d.options.minW);newH=d.options.minW*h/w;d.aniImg.height(newH+"px");d.aniImg.css("left",0)}else{d.aniImg.width(d.options.minW);d.aniImg.css("left",0)}a.when(d.image.fadeOut(d.options.animationSpeed),d.aniImg.eq(0).fadeIn(d.options.animationSpeed)).done(function(){d.finishAnimating(c)});break;case 2:d.aniDiv.show().children().hide().each(function(b){var g=b*30;a(this).css({opacity:.1}).show().delay(g).animate({opacity:1,width:e,height:f},200,"linear",function(){if(d.aniDiv.find("div").index(this)==24){d.finishAnimating(c)}})});break;case 3:d.aniDiv.show().children("div:lt(5)").hide().each(function(b){var f=a(this).attr("col")*100;a(this).css({opacity:.1,width:e}).show().delay(f).animate({opacity:1,height:d.image.height()},d.options.animationSpeed,"linear",function(){if(d.aniDiv.find(" div").index(this)==4){d.finishAnimating(c)}})});break;case 4:d.aniDiv.show().children().hide().each(function(b){if(b>4){return}var g=a(this).attr("col")*30;var h=d.gapper(a(this),f,e);var i=d.options.animationSpeed*.7;a(this).css({opacity:.1,height:"100%"}).show().delay(g).animate({opacity:1,width:h.width},i,"linear",function(){if(d.aniDiv.find(" div").index(this)==4){d.finishAnimating(c)}})});break;case 5:d.aniDiv.show().children().show().each(function(b){var g=b*Math.floor(Math.random()*5)*7;var h=d.gapper(a(this),f,e);if(a(".animation div").index(this)==24){g=700}a(this).css({height:h.height,width:h.width,opacity:.01}).delay(g).animate({opacity:1},d.options.animationSpeed,function(){if(d.aniDiv.find(" div").index(this)==24){d.finishAnimating(c)}})});break;case 6:d.aniDiv.height(d.image.height()).hide().css({background:"url("+c.source+") top left no-repeat"});d.aniDiv.children("div").hide();d.aniDiv.css({width:0}).show().animate({width:d.image.width()},d.options.animationSpeed,function(){d.finishAnimating(c);d.aniDiv.css({background:"transparent"})});break;case 7:d.wrap.css({overflow:"hidden"});d.aniImg.height(d.image.height()).hide().attr("src",c.source);d.aniDiv.hide();d.image.css({position:"relative"}).animate({left:"-"+d.wrap.outerWidth()+"px"});d.aniImg.show();d.aniWrap.css({left:d.wrap.outerWidth()}).show().animate({left:"0px"},d.options.animationSpeed,function(){d.finishAnimating(c)});break}},finishAnimating:function(b){this.animating=false;this.image.attr("src",b.source);this.image.attr("alt",b.imageAlt);this.image.attr("title",b.imageTitle);this.image.css({left:"0"});this.image.show();var c=this;a("<img />").bind("load",function(){c.aniImg.fadeOut("fast");c.aniDiv.fadeOut("fast")}).attr("src",b.source);var d=new Image;a(d).attr("src",b.source);w=d.width;h=d.height;if(w>h*c.options.minW/c.options.minH){this.image.height(c.options.minH+"px");newW=c.options.minH*w/h;this.image.width(newW+"px");this.image.css("left",-1*((newW-c.options.minW)/2)+"px")}else if(h>w*c.options.minH/c.options.minW){this.image.width(c.options.minW);newH=c.options.minW*h/w;this.image.height(newH+"px");this.image.css("left",0)}else{this.image.width(c.options.minW);this.image.css("left",0)}var e=b.index+1;var f=this.thumbs.length;this.counter.html(e+"/"+f);if(b.clickThrough!=""){if(this.anchor==null){this.anchor=this.image.wrap("<a>").parent()}this.anchor.attr("href",b.clickThrough);this.anchor.attr("title",b.clickThroughTitle);this.anchor.attr("target",b.clickThroughTarget)}else{if(this.image.parent("a").length>0){this.image.unwrap()}this.anchor=null}if(this.options.showCaption&&b.caption!=""&&b.caption!=null){this.caption.html(b.caption).fadeTo("slow",1)}if(this.options.autoPlay==true){var c=this;this.timeOut=setTimeout(function(a){return function(){a.nextClick()}}(this),this.options.speed,this.timeOut)}if(typeof this.options.animationFinished=="function"){this.options.animationFinished(this)}},gapper:function(a,b,c){var d;if(a.attr("row")==4){d=this.aniDiv.height()-b*5+b;b=d}if(a.attr("col")==4){d=this.aniDiv.width()-c*5+c;c=d}return{height:b,width:c}},nextClick:function(a){var b="natural";try{var c=a.data.self;if(typeof a.data.self.options.next=="function"){a.data.self.options.next(this)}}catch(d){var c=this;b="auto"}var e=c.active.parents("li:first").next().find("img");if(e.length==0){e=c.list.find("img").eq(0)}e.trigger("click",{how:b})},prevClick:function(a){if(typeof a.data.self.options.previous=="function"){a.data.self.options.previous(this)}var b=a.data.self;var c=b.active.parents("li:first").prev().find("img");if(c.length==0){c=b.list.find("img:last")}c.trigger("click")},playClick:function(a){var b=a.data.self;b.options.autoPlay=!b.options.autoPlay;b.imgPlay.toggleClass("play").toggleClass("pause");if(b.options.autoPlay){b.nextClick()}},Next:function(){var a={data:{self:this}};this.nextClick(a)},Prev:function(){var a={data:{self:this}};this.prevClick(a)},Play:function(){if(this.options.autoPlay){return}var a={data:{self:this}};this.playClick(a)},Pause:function(){if(!this.options.autoPlay){return}var a={data:{self:this}};this.playClick(a)},updateThumbs:function(){var b=this;this.thumbs=this.list.find("img");this.thumbs.each(function(){b.createThumb(a(this),b)})}})})(jQuery)