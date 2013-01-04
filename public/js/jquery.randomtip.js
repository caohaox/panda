(function(a) {
    var b = {
        points: [],
        pause: 5e3,
        speed: 300,
        visibleTips: 4
    };
    a.fn.randomtip = function(c) {  // for avartars
        var d=a.extend({
		},b,c||{
		});
		d.area=this;
		if(jQuery.browser.msie) {
			var e=parseInt(jQuery.browser.version);
			if(e<=8) {
				d.speed=0
			}
		}var f=function () {
			this.getRan=function (a) {
				var b=Math.floor(Math.random()*a)+1;
				return b
			};
			this.show=function () {
				var a=this.getRan(d.area.children("div.nonActive").length)-1;
				pointDiv=d.area.children("div.nonActive:eq("+a+")");
				var b=this.getRan(d.area.children("a").length)-1;
				tipDiv=d.area.children("a:eq("+a+")");
				var c=this.getRan(d.area.children("div.active").length)-1;
				activePointDiv=d.area.children("div.active:eq("+c+")");
				activePointDiv.fadeOut(d.speed,function () {
					d.area.prepend(activePointDiv.html());
					jQuery(this).empty()
				}).removeClass("active").addClass("nonActive");
				tipDiv.appendTo(pointDiv).removeClass("nonActive");
				pointDiv.removeClass("nonActive").addClass("active").fadeIn(d.speed)
			}
		};
		var g=new f;
		var h=d.points.length;
		var j="";
		for(i=0;i<h;i++) {
			j+='<div class="nonActive" style="top:'+d.points[i][1]+"px; left:"+d.points[i][0]+'px;"></div>'
		}d.area.html(d.area.html()+j);
		d.area.find("div").click(function () {
			var a=new PopUpMethods;
			a.popUpOpen(jQuery("."+(jQuery(this).parents("div#mapChina").length!=0?"popUpWelcomeW":"popUpWelcomeG")))
		});
		var k=d.area.children("div.nonActive").length;
		for(i=0;i<d.visibleTips;i++) {
			var l=g.getRan(k-i)-1;
			pointDiv=d.area.children("div.nonActive:eq("+l+")");
			d.area.children("a:eq("+(g.getRan(d.area.children("a").length)-1)+")").appendTo(pointDiv).removeClass("nonActive");
			pointDiv.removeClass("nonActive").addClass("active").fadeIn(d.speed)
		}var m=function () {
			g.show()
		};
		var n=d.area.parent().attr("id")=="mapChina"?setTimeout(function () {
			setInterval(m,d.pause)
		},d.pause/2):setInterval(m,d.pause)
    }
	
	a.fn.randomtip1 = function(c) {	// for city
        var d = a.extend({},
        b, c || {});
        d.area = this;
        if (jQuery.browser.msie) {
            var e = parseInt(jQuery.browser.version);
            if (e <= 8) {
                d.speed = 0
            }
        }
        var f = function() {
            this.getRan = function(a) {
                var b = Math.floor(Math.random() * a) + 1;
                return b
            };
			
            this.show = function() {
                var a = this.getRan(d.area.children("div.nonActive").length) - 1;
                pointDiv = d.area.children("div.nonActive:eq(" + a + ")");
                var b = this.getRan(d.area.children("a").length) - 1;
                tipDiv = d.area.children("a:eq(" + a + ")");
                var c = this.getRan(d.area.children("div.active").length) - 1;
                activePointDiv = d.area.children("div.active:eq(" + c + ")");
                activePointDiv.fadeOut(d.speed,
                function() {
                    d.area.prepend(activePointDiv.html());
                    jQuery(this).empty()
                }).removeClass("active").addClass("nonActive");
                tipDiv.appendTo(pointDiv).removeClass("nonActive");
                pointDiv.removeClass("nonActive").addClass("active").fadeIn(d.speed)
				//console.log(pointDiv.html());
            }
			
        };
		
		var point_city = [ [ 'Chengdu', 411, 410 ] , [ 'Xian', 460, 355] , [ 'Guangzhou', 473, 487 ] , [ 'Beijing', 540, 342 ] , [ 'Shanghai', 575, 409 ] ,
						   [ 'Nanjing', 550, 400 ] , [ 'Hangzhou', 562, 424 ] , [ 'Sanya', 467, 534 ] , [ 'Datong', 500, 339 ] , [ 'Kunming', 425, 465 ] ,
						   [ 'Chongqing', 437, 422 ], [ 'Harbin', 592, 282 ], [ 'Hongkong', 522, 488 ], [ 'Tianjin', 565, 346 ], [ 'Urumqi',292, 278 ],
						   [ 'volgograd', 98, 170 ], [ 'moscow', 75,110 ], [ 'novgorod', 27, 100 ], [ 'spb', 37, 80], [ 'arkhangelsk', 90, 62 ],
						   [ 'kazan', 150, 133 ], [ 'ufa',180, 151 ], [ 'yekaterinburg',205, 141 ], [ 'omsk', 268, 165], [ 'novosibirsk', 310, 171 ], 
						   [ 'krasnoyarsk', 370, 167 ],[ 'chita', 490, 210 ], [ 'khabarovsk', 630, 235 ], [ 'vladivostok', 632, 293 ], [ 'yakutsk', 580, 130 ]];
		
		function get_city(cities, left, right){
			var city
			$.each(cities, function(v){
				if(cities[v][1]==left && cities[v][2]==right){
					city = cities[v][0]; 
					return city;
				};
			});
			return city;
		}
		
        var g = new f;
        var h = d.points.length;
        var j = "";
        for (i = 0; i < h; i++) {
			var city = get_city(point_city, d.points[i][0], d.points[i][1])
			if(jQuery(this).parents("div#mapRussia").length == 1){
				j += '<div id=russia_active_'+i+' class="nonActive" style="top:' + d.points[i][1] + "px; left:" + d.points[i][0] + 'px;"><a><img src="img/' + city + '-81x81.jpg"/></a></div>'
				j += '<span id=russia_dot_'+i+' class="dot" style="top:' + (d.points[i][1]+100) + "px; left:" + (d.points[i][0]+42.5) + 'px;"></span>'
			}
			if(jQuery(this).parents("div#mapChina").length == 1){
				j += '<div id=china_active_'+i+' class="nonActive" style="top:' + d.points[i][1] + "px; left:" + d.points[i][0] + 'px;"><a><img src="img/' + city + '-81x81.jpg"/></a></div>'
				j += '<span id=china_dot_'+i+' class="dot" style="top:' + (d.points[i][1]+100) + "px; left:" + (d.points[i][0]+42.5) + 'px;"></span>'
			}
		//	console.log('<div class="nonActive" style="top:' + d.points[i][1] + "px; left:" + d.points[i][0] + 'px;"></div>');
        }
        d.area.html(d.area.html() + j);
        d.area.find("div").click(function() {
			//console.log($(this).parent().attr("class"))
			$(this).fadeOut(d.speed)
            var a = new PopUpMethods;
            a.popUpOpen(jQuery("." + (jQuery(this).parents("div#mapChina").length != 0 ? "popUpChinaCity" : "popUpRussiaCity")));

			var id = this.id;
			var id_arr = id.split("_");
			//console.log("click id:"+id_arr[2]);
			
			if(jQuery(this).parents("div#mapChina").length != 0){
				$("#contentHolderUnit_"+id_arr[2],$(".popUpChinaCity")).trigger('click');
			}else{
				$("#contentHolderUnit_"+id_arr[2],$(".popUpRussiaCity")).trigger("click");
			}
			
        });
		d.area.find("div").mouseout(function() {
			var id = this.id;
			var id_arr = id.split("_");
			$('#active_'+id_arr[1]).fadeOut(d.speed,
                function() {
                    d.area.prepend($('#active_'+id_arr[1]).html());
                    jQuery(this).empty()
                }).removeClass("active").addClass("nonActive");
        });
		d.area.find("span").click(function() {
            var id = this.id;
			var id_arr = id.split("_");
			if(jQuery(this).parents("div#mapRussia").length == 1){
				if($('#russia_active_'+id_arr[2]).attr("class")=="nonActive"){
					$('#russia_active_'+id_arr[2]).removeClass("nonActive").addClass("active").fadeIn(d.speed)
				}else{
					$('#russia_active_'+id_arr[2]).removeClass("active").addClass("nonActive").fadeOut(d.speed)
				}
			}
			if(jQuery(this).parents("div#mapChina").length == 1){
				if($('#china_active_'+id_arr[2]).attr("class")=="nonActive"){
					$('#china_active_'+id_arr[2]).removeClass("nonActive").addClass("active").fadeIn(d.speed)
				}else{
					$('#china_active_'+id_arr[2]).removeClass("active").addClass("nonActive").fadeOut(d.speed)
				}
			}
        });
		d.area.find("span").mouseover(function() {
			var id = this.id;
			var id_arr = id.split("_");
        });
		d.area.find("span").mouseout(function() {
			
        });
    }
})(jQuery)