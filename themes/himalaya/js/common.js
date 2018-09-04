(function ($, window, document, undefined) {
    $.fn.addPlaceholder = function (options) {
        var settings = $.extend({
            events: true,
            IE: true
        }, options);


        function phBehaviour(_this,isShowLable,selfEvent){
            if(isShowLable == "true"){
                if(selfEvent == false){
                    $(_this).prev(".placeholder").addClass("active");
                    $(_this).parents(".inputBox").addClass("focused");
                    $(_this).siblings(".cc").fadeIn();
                }else{
                     $(_this).prev(".placeholder").removeClass("active");
                    $(_this).parents(".inputBox").removeClass("focused");
                     $(_this).siblings(".cc").hide();
                }
            }else{
                if(selfEvent == false){
                    $(_this).prev(".placeholder").hide();
                }else{
                    $(_this).prev(".placeholder").show();
                }
            }
        }

        function placeHolderFontSize(_this){
        	_this.prev(".placeholder").css("font-size",(parseInt(_this.css("font-size"))/10)+"rem");
        }

         

        return this.each(function () {
            var a = $(this).attr("placeholder");
            var showlabel = $(this).attr("data-label");

            $(this).attr("placeholder", "");
            //alert($(this).val());
            if($(this).val()==""){
                $(this).before("<div class='placeholder'>" + a + "</div>")
                if($(this).val()!=""){
                    phBehaviour(this,showlabel,true)                    
                }
            }else{
                $(this).before("<div class='placeholder'>" + a + "</div>");

                if(showlabel == "true"){
                    $(this).prev(".placeholder").addClass("active");
                    $(this).parents(".inputBox").addClass("focused");
                	$(this).siblings(".cc").show();
                }else{
                	$(this).prev(".placeholder").hide();
                }
            }

            $(this).focus(function (e) {
                if ($(this).val() == "") {
                    phBehaviour(this,showlabel,false)  
                } else {
                    phBehaviour(this,showlabel,false)  
                }
            });

            $(this).blur(function (e) {
            	// console.log($(this))
                if ($(this).val() == "") {
                  /*IF its is a number field it does accept alphabets but 
                  inavalidate it as wrong characters while keeping value as blank. 
                  try commenting this line & inputing alphabets on "number" type field*/
                  $(this).val("");  
                   phBehaviour(this,showlabel,true);
                }
                else {
                   phBehaviour(this,showlabel,false);
                }
            });

            

        });
    }

})(jQuery);

	var maxHeight=0;
	var arrayHeight = [];
	
(function ($, Drupal) {
  var left_p, top_p, winW, winH;
	function boxPosition(selector){
		winW = $(window).width();
		winH = $(window).height();
		left_p = (winW - $(selector).width())/2;
		top_p = (winH - $(selector).height())/2;
		//console.log(left_p);
		//console.log(top_p);
		if(top_p>30){
			$(selector).css({'left':left_p, 'top': top_p});
		}
		else {
			$(selector).css({'left':left_p, 'top': 30});
		}
			
	}
    
  function windowORdevice() {
      if (winW > 1024) {
          $("html").addClass("desktop").removeClass("device");
      } else {
          /*Device*/
          $("html").addClass("device").removeClass("desktop");
      }
  }
  
  function customSelect() {
      $("select").each(function () {
  
          if ($(this).prop('selectedIndex') != "0") {
              $(this).parents('.selectBox').addClass('focused');
          }
  
          $(this).siblings(".selectedValue").text($(this).find("option:selected").text())
      });
  
      $(document).on('change', 'select', function (e) {
          var _this = $(this).parents('.selectBox').find('.selectedValue');
          $(this).siblings(".selectedValue").text($(this).find("option:selected").text());
  
          if ($(this).prop('selectedIndex') != "0") {
  
              $(this).parents('.selectBox').addClass('focused');
          } else {
  
              $(this).parents('.selectBox').removeClass('focused');
          }
      });
  }
  
  
  function svgEditorForIE(_thisParam) {
      var _this = _thisParam;
  
      if (_this.find("svg").length) {
          _this.find("svg").each(function() {
              $(this).removeAttr("style");
              var thisWidth = parseInt($(this).attr("width"));
              var thisHeight = parseInt($(this).attr("height"));
              var viewBox = this.getAttribute('viewBox');
              viewBox = viewBox.split(" ");
              var requiredWidth = parseInt($(this).width());
              var requiredHeight = ((requiredWidth * thisHeight) / thisWidth);
              /*_this.find("svg").attr({
                  "viewBox": viewBox[0] + " " + viewBox[1] + " " + requiredWidth + " " + requiredHeight
              });*/
              _this.find("svg").css({
                  "width": requiredWidth + "px",
                  "height": requiredHeight + "px"
              });
          });
  
  
      }
  }
  
  var svgloader = function(target) {
     
  
      //console.log($(this).attr('data-svg'));
      $(target).each(function(index){
          var svgPath = $(this).attr("data-svg");
          var _this = this;
          if ($(this).find("svg").length == 0) { 
              var l = Snap.load(svgPath, onSVGLoadedCallBack);
  
              function onSVGLoadedCallBack(data) {
                  var svgParent = Snap(_this);
                  svgParent.append(data);
                  var svg = svgParent.select("svg");
                  var imgPath = $(_this).find("img").attr("src");
                  //alert(imgPath)
                  if(imgPath != undefined){
  
                      svg.select("image").attr("xlink:href",imgPath);
                  }
                  
                  if (isIE == true) {
                      svgEditorForIE($(_this))
                  }
  
              }
          }
      });
      
  
  }
  
  function customUpload(browseButtonSelector){
      var selector = browseButtonSelector;
      if($(selector).length){
          var text = $(selector).find("p");
          $(document).on("change", selector+" input[type=file]", function(){
              /*var str = $(this).val().split('\\');
              text.text(str[str.length-1]);*/
  
              $(selector).find(".buttonUp").text("Image Uploaded").addClass("green");
          });
      }
  }
  
  function readyCalls(){
      /*-------------------------------------------------------------------------------------*/
          // Loader - Start
      /*-------------------------------------------------------------------------------------*/
          loader = $(".loader span");
  
          if(loaded == false){
              loader.animate({width:lWidth+"%"},1000,function(){
                  toutLoader = setInterval(function(){
                      if(loaded == false && lWidth != 100){
                          if(lWidth == 95){
                              toutLoaderTime=2000;
                          }
                          if(lWidth <= 98){
                              loader.css("width",++lWidth+"%")
                          }
                      }else{
                          loader.css("width","100%");
                          clearTimeout(toutLoader);
                      }
                      
                  },toutLoaderTime);
              });
          }
  
      /*-------------------------------------------------------------------------------------*/
          // Loader - End 
      /*-------------------------------------------------------------------------------------*/
  
  
  
      /*-------------------------------------------------------------------------------------*/
          // Window Setup - Start
      /*-------------------------------------------------------------------------------------*/
  
          winW = $(window).width();
          winH = $(window).height();
          winL = window.location.href;
  
          if(ua.indexOf('MSIE') > 0 || ua.indexOf('Trident/') > 0 || ua.indexOf('Edge/') > 0){
              isIE = true;
          }
  
          isTouch = /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent);
          var isIOS =  /iPhone|iPad|iPod|Macintosh/i.test(navigator.userAgent);
  
          if(isTouch || $(window).width()<=1024){
              $("html").addClass("touchDevice")
          }else{
              $("html").addClass("nonTouch");
          }
  
          if(isIOS){
              $("html").addClass("iOS");
          }
  
          if (isIE == false) {
              $("html").addClass("otherBrowsers");
          }else{
              $("html").addClass("ieBrowser");
          }
  
          windowORdevice();
  
      /*-------------------------------------------------------------------------------------*/
          // Window Setup - End
      /*-------------------------------------------------------------------------------------*/
  
  
      /*-------------------------------------------------------------------------------------*/
          // For Form Elements - Start
      /*-------------------------------------------------------------------------------------*/
          $("input[type=text], input[type=password], input[type=number], textarea").addPlaceholder();
  
          customSelect();
          customUpload('.upload-btn');
  
         /*  $(document).on('focus', 'input:text, select', function(e) {
              $(this).parent().addClass("focused");
          });
  
          $(document).on('blur', 'input:text, select', function(e) {
              $(this).parent().removeClass("focused");
          }); */
      /*-------------------------------------------------------------------------------------*/
          // For Form Elements - End
      /*-------------------------------------------------------------------------------------*/  
  
      svgloader(".svgBox"); 
  
      $("nav .searchBtn").click(function(){
          $(".searchLayer").fadeIn();
          $(this).toggleClass("activeSearch");
          $(".searchLayer form").toggleClass("activeSearch");
      });
  
      $(".searchLayer .close").click(function(){
          $(".searchLayer").fadeOut();
          $("nav .searchBtn").toggleClass("activeSearch");
          $(".searchLayer form").toggleClass("activeSearch");
      });
  
      $(".mainNavigation").not(".productList").find("li > a").click(function(){
          $(this).siblings(".subNav").slideToggle();
      });
  
      //Hamburger Menu Event
      $(document).on('click', '.hamburger', function (e) {
          $(this).toggleClass("active");
          $(".mainNavs, .mainNavs .subNav").removeAttr("style");
          if($(this).hasClass("active")){
              $(".mainNavs").animate({"left":0},500);
          }else{
              var boxWidth = -1*parseInt($(".mainNavs").css("max-width"))+"px";
              $(".mainNavs").animate({"left":boxWidth},500);
          }
      });
  
  }
  
  function circleAnimator(_this){
      $(_this).each(function(){
          var s = Snap(this);
          var svg = s.select("svg");
  
          if(svg != undefined){
              var svgGroup = svg.selectAll("#circles_1 > g");
              //console.log(svgGroup)
              svgGroup.forEach(function(elem, index) {
                  var eBBox = elem.getBBox();
                  //console.log(eBBox.cx+" "+eBBox.cy)
                  var posNeg = index%2 != 1 ? 1 : -1;
                  //var rot = (Math.floor(Math.random() * 200) + 100);  
  
                 /* elem.animate({ 
                      transform: 'r' + 360  * posNeg  + ',' + eBBox.cx + ',' + eBBox.cy
                  },(Math.floor(Math.random() * 2000) + 1500), mina.easeinout)*/
  
                 Snap.animate(0, 1, function(val) {
                      elem.attr({
                          transform: 'r' + 360  * posNeg * val  + ',' + eBBox.cx + ',' + eBBox.cy
                      })
                  }, (Math.floor(Math.random() * 2000) + 1500), mina.easeinout); //if var rot is used change to 1000 from 1500;
              });
          }
      });
  }
  
  var dataSkroller = function() {
      if ($("[data-scroll='active']").length) {
          //var offTop = $("[data-scroll='active']").eq(0).offset().top;
          var offTop = $("[data-scroll='active']").offset().top;
          var windowScrollTop = $(window).scrollTop();
          //console.log(offTop-windowScrollTop + $("[data-scroll='active']").outerHeight())
          if ((offTop - windowScrollTop >= 0 && offTop - windowScrollTop < $(window).height() / 1.7) || windowScrollTop == 0) {
              $("[data-scroll='active']").each(function() {
                  $(this).attr("data-scroll", "completed");
                  $("[data-scroll='']").eq(0).attr("data-scroll", "active");
                  //svgBoxer(this);
                  circleAnimator(this);
              });
          }
      }
  }
  
  var ua = window.navigator.userAgent;
  var isIE = false;
  var winW,winH,winL,isTouch,cScroll;
  var loader;
  var lWidth = 60;
  var toutLoader;
  var toutLoaderTime=200;
  var loaded = false;
  var currentSlide = 0;
  
  var homeWave = 0;
  
  function imageSrcChanger(_this){
  
      var items = _this.find("img");
  
      items.each(function(){
          if(winW < 768){
              $(this).attr("src",$(this).data('banner-mobile'));
          }
          else{
              $(this).attr("src",$(this).data('banner'));
          }
      });
  }
  
  function bannerWaveResponsive(){
      if($(".bannerWave").length){
          var baseVal = 1920;
          var waveBaseHeight = 175;
          var newWaveHeight = Math.floor((winW*waveBaseHeight)/baseVal);
          newWaveHeight = newWaveHeight+20 > waveBaseHeight ? waveBaseHeight : newWaveHeight+20;
          $(".bannerWave").height(newWaveHeight+"px");
          $(".bannerWave svg").width(winW*3);
          $(".bannerWave").css({"transform":"translateX(-"+(winW*currentSlide)+"px)"});
          
      }
  }
  
  function bannerWave(banLength){
      if(banLength > 3){
          var loopCounter = Math.ceil(banLength/3);
          //alert(loopCounter)
          for(var i=1; i < loopCounter; i++){
             $(".bannerWave svg").eq(0).clone().appendTo($(".bannerWave")); 
          }
      }
  }
  
  function tab(){
      $(".tabSec").each(function(){
          $(this).prepend("<div class='navTabs'><ul></ul><span class='slider'></span></div>");
          var titleLength = $(this).children(".tabContent").find(".accTitle").length;
          for(var i = 0; i < titleLength; i++){
              $(this).children(".tabContent").find(".accTitle").text();
              $(this).find(".navTabs ul").append("<li><a href='javascript:;'>"+$(".tabSec").children(".tabContent").find(".accTitle").eq(i).text()+"</a></li>")
          }
      })
      
      $(".accTitle").on("click", function(event){
          var myThis = $(this);
          if(!myThis.parent().find(".acctDiv").is(":animated")){
              /*myThis.addClass("open").parent().siblings().find('.accTitle').removeClass("open");
              myThis.next().slideDown(800, function(){
                  if(myThis.is(":visible")){
                      var scrollHere = myThis.offset().top-$("header").outerHeight();
                      $("html, body").animate({
                          scrollTop:scrollHere+"px"
                      },300);
                  }
              }).parent().siblings().find(".acctDiv").slideUp();
              $(".navTabs ul li").eq(myThis.parent().index()).addClass("active").siblings().removeClass("active");*/
              myThis.parent().siblings().find(".acctDiv").hide();
              if(myThis.is(":visible")){
                  var scrollHere = myThis.offset().top-$("header").outerHeight() - 10;
              }
              $(window).scrollTop(scrollHere);
              myThis.addClass("open").parent().siblings().find('.accTitle').removeClass("open");
              myThis.next().slideDown();
              
              
              /*myThis.addClass("open").parent().siblings().find('.accTitle').removeClass("open");
              myThis.next().slideDown(800, function(){
                  if(myThis.is(":visible")){
                      var scrollHere = myThis.offset().top-$("header").outerHeight();
                      $("html, body").animate({
                          scrollTop:scrollHere+"px"
                      },300);
                  }
              })*/
              $(".navTabs ul li").eq(myThis.parent().index()).addClass("active").siblings().removeClass("active");
          }
      });
      
      
      $(document).on("click", ".navTabs ul li", function(){
          var positionLeft = $(this).find("a").position().left;
          var outerWidth = $(this).find("a").outerWidth();
          $(".slider").css({'width': outerWidth, 'left':positionLeft})
      })
      $(".navTabs ul li").eq(0).click();
      
      
      $(".tabContent h3.accTitle").eq(0).click();
  
      $(document).on("click", ".navTabs li", function(event){
          $(this).closest(".tabSec").find(".accTitle").eq($(this).index()).trigger("click");
      });
  }
  
  function PopUp(hideOrshow) {
		if (hideOrshow == 'hide') {
			//$("#lightboxWrap, #overlay").fadeOut();
			$("#lightboxWrap").removeClass('show');
			$("#lightboxWrap, #overlay").fadeOut();
		}
		else if (localStorage.getItem("popupWasShown") == null) {
			localStorage.setItem("popupWasShown", 1);
			$("#overlay, #lightboxWrap").fadeIn();
			$("#lightboxWrap").addClass('show');
			boxPosition("#lightboxWrap");
		}
	}
  
  
  $(function(){
      //functions that setups everything    
      readyCalls();
      $("#lightboxWrap").hide();
      
      tab();
      //Owl Carousel Example
      bannerWaveResponsive();
      var bannerCounter = $('.bannerCarousel .item').length;
      bannerWave(bannerCounter);
      imageSrcChanger($(".bannerCarousel"));
      imageSrcChanger($(".bannerSection .tempBanner"));
  
      var bannerCarousel = $('.bannerCarousel');
      bannerCarousel.owlCarousel({
          items:1,
          pullDrag:false,
          nav:true,
          video:true,
          navText:["",""]
      });
  
      bannerCarousel.on("play.owl.video", function(){
          $(".bannerWave").addClass("playingVideo");
      });
  
      bannerCarousel.on("stop.owl.video", function(){
          $(".bannerWave").removeClass("playingVideo");
      });   
  
      homeWave = (5760-winW)/bannerCounter;
      var updown = -1;
      var lastBannerSlide = 0;
      $('.bannerCarousel').on("translated.owl.carousel", function(event){
          currentSlide = event.page.index;
          console.log(currentSlide);
          if(currentSlide == lastBannerSlide){
              return false;
          }
  
          var colors = [];
          colors = $('.bannerCarousel').find(".owl-item.active").children().data("colors").split(",");
          //$(".bannerWave").css({"transform":"translateX(-"+currentSlide*homeWave+"px)"});
          $(".bannerWave").css({"transform":"translateX(-"+(winW*currentSlide)+"px)"});
          
          $(".topFill").css({
              "-webkit-fill":colors[0],
              "fill":colors[0]
          });
          $(".bottomFill").css({
              "-webkit-fill":colors[1],
              "fill":colors[1],
  
              /*"-webkit-transform":"translateY("+Math.floor((Math.random() * 10) + 1)*updown+"px)",
              "transform":"translateY("+Math.floor((Math.random() * 10) + 1)*updown+"px)"*/
          });
  
           $(".whiteFill").css({
  
              /*"-webkit-transform":"translateY("+Math.floor((Math.random() * 10) + 1)*updown+"px)",
              "transform":"translateY("+Math.floor((Math.random() * 10) + 1)*updown+"px)"*/
  
           });
          updown = updown == -1 ? 1 : -1;
          lastBannerSlide = currentSlide;
      });
  
  
      $(".mouseIcon").click(function(){
          var scrollHere = $(this).closest("section").next().offset().top-$("header").outerHeight();
          $("html, body").animate({
              scrollTop:scrollHere+"px"
          },800);
      })
  
  
      var lastIndex = 0;
      var fTout;
      var bTout;
      var animOver = false;
  
      $('.productList .subNav ul li').mouseenter(function(event){
           animOver = false;
          event.stopPropagation();
          var cIndex = $(this).index();
          $(".productView .productItem").eq(cIndex).addClass("show").siblings(".show").removeClass("show");
      });
  
      $('.productList .subNav').mouseleave(function(event){
          $(".productView .productItem").eq(0).addClass("show").siblings(".show").removeClass("show");
      });
  
      $(".blogCarousel").owlCarousel({
          items:1,
          pullDrag:false
      });
  
     /* $('.my-flipster').flipster({
          style: 'carousel', 
          start: 0,
          buttons: true,
          spacing: 5
  
      });*/
  
       var itemLength = $(".bannerCarousel").length ? 3 : 1;
       var navAttows = $(".bannerCarousel").length ? true : false;
       var widthAuto = $(".bannerCarousel").length ? true : false;
       $('.ourProducts .productCarousel').owlCarousel({
          items:3,
          center:true,
          loop:false,
          pullDrag:false,
          smartSpeed: 800,
          nav:navAttows,
          navText:["",""],
          dots:true,
          autoWidth:widthAuto,
          responsive:{
              0:{
                   items:1,
                   autoWidth:false
              },
              640:{
                   items:1,
				   autoWidth:false
              },
			  1024:{
				  items:3,
                  autoWidth:widthAuto,
              }
          }
      });
       $('#productCarousel2.productCarousel').owlCarousel({
          items:1,
          center:true,
          loop:false,
          pullDrag:false,
          smartSpeed: 800,
          //nav:navAttows,
          navText:["",""],
          dots:true,
          autoWidth:false,
          responsive:{
              0:{
                   items:1,
                   autoWidth:false
              },
              640:{
                   items:1,
				   autoWidth:false
              },
			  1024:{
				  items:1,
                  autoWidth:false,
              }
          }
      });
      
      $('.wordOfRecipesTemplate .contentCarousel').owlCarousel({
          items:1,
          center:true,
          loop:true,
          pullDrag:false,
          smartSpeed: 800,
          margin:5,
          nav:false,
          navText:["",""],
          dots:true,
          //autoWidth:true,
          responsive:{
              0:{
                   items:1,
                   autoWidth:false
              },
              640:{
                   items:1
              }
          }
      });
      
      $('.availFlavCarousel').owlCarousel({
          items:5,
          //center:true,
          loop:false,
          pullDrag:false,
          smartSpeed: 800,
          //margin:5,
          nav:true,
          //navText:["",""],
          dots:false,
          //autoWidth:true,
          responsive:{
              0:{
                   items:2,
              },
              480:{
                   items:3,
              },
              640:{
                   items:4,
              },
              768:{
                   items:3,
              },
              1024:{
                   items:5,
              }
          }
      });
      
      $('.availFlavCarousel .item').click(function(){
          var index = $(this).parent().index();
          $('.productCarousel').trigger('to.owl.carousel', [index, 300]);
      })
      
      $('.availFlavBigCarous').owlCarousel({
          items:1,
          //center:true,
          loop:false,
          pullDrag:false,
          mouseDrag: false,
          touchDrag: false,
          smartSpeed:800,
          margin:5,
          nav:false,
          //navText:["",""],
          dots:true,
          //autoWidth:true,
          responsive:{
              0:{
                   items:1,
              },
              640:{
                   items:1,
              }
          }
      });
      
      $('.ourBrandCarousel').owlCarousel({
          items:3,
          //center:true,
          loop:false,
          pullDrag:false,
          smartSpeed: 800,
          margin:48,
          nav:false,
          //navText:["",""],
          dots:true,
          //autoWidth:true,
          responsive:{
              0:{
                  items:2,
                  margin:30,
              },
              640:{
                   items:2,
              },
              1024:{
                   items:3,
              }
          }
      });
      $('.featuredRecipesBttm .chilCarsl').owlCarousel({
          items:3,
          //center:true,
          loop:false,
          pullDrag:false,
          smartSpeed: 800,
          margin:48,
          nav:false,
          //navText:["",""],
          dots:true,
          //autoWidth:true,
          responsive:{
              0:{
                  items:2,
                  margin:30,
              },
              640:{
                   items:2,
              },
              1024:{
                   items:3,
              }
          }
      });
      
    setTimeout(function() {
    $('.videoWrap').owlCarousel({
        items: 1,
        center: true,
        loop: false,
        pullDrag: false,
        smartSpeed: 800,
        nav: false,
        dots: true,
        margin: 0
    });
    }, 300);
    
    
    

    $('.videoWrap').on("translated.owl.carousel", function (event) {
        var videocurrentSlide = event.page.index;
        var videoDesc = $(this).find('.item').eq(videocurrentSlide).data('desc');
        //alert(videoDesc);
        $('.videodesc p').text(videoDesc);
    });
    $('.videodesc p').text($('.videoWrap').find('.item').eq(0).data('desc'));
      
      $(document).on("click", ".availFlavCarousel .item", function(){
          $(this).addClass("active").parent().siblings().find(".item").removeClass("active");
      })
      $(".availFlavCarousel .item").parent().eq(0).find(".item").click();
      
      $(".didKnowCarousel .imgBox").eq(0).addClass("active");
      $('.wordOfRecipesTemplate .contentCarousel').on("translated.owl.carousel", function(event){
          currentSlide = event.page.index;
          $(".didKnowCarousel .imgBox").eq(currentSlide).addClass("active").siblings().removeClass("active");
      });
  
  
      var productName = $(".productNameLink h2");
      var productLink = $(".productNameLink .btn");
      var productBuyBtn = $(".buyBtnOnBanner a");
  
      $(document).on('click', '.productCarousel .owl-item', function(event){
          var tIndex = $(this).index();
          var cIndex = $(".productCarousel .owl-item.center").index();
         if(tIndex > cIndex){
              $('.productCarousel').trigger('next.owl.carousel',800);
         }else if(tIndex < cIndex){
              $('.productCarousel').trigger('prev.owl.carousel',800);
         }
         
      })  
      
       $(".productCarousel").on('translated.owl.carousel', function(event){
          var _this = $(".productCarousel .owl-item.center");
          productName.text(_this.children().data("productname")).css("color",_this.children().data("colortype"));
          productLink.attr("href",_this.children().data("productlink")).css("background-color",_this.children().data("colortype"));
          $(".buyBtnOnBanner a").attr("href",_this.children().data("productlink"))//.css("background-color",_this.children().data("colortype"));
          $(".buyBtnOnBanner p").text(_this.children().data("productname"))
      }).trigger("translated.owl.carousel");
  
  
      
  
      //LightBox - START
      /*$(document).on('click', '.viewLBox', function (e) {
          cScroll = $(window).scrollTop();
          $("body").css("top","-"+cScroll+"px");
          $("body").addClass("lBoxOpen");
          //console.log($(this).data("video"))
          var dataVideo = $(this).data("video");
          if(dataVideo != undefined){
              $(".lBoxMaster.videoLBox iframe").attr("src",dataVideo+"?autoplay=1");
              $(".lBoxMaster.videoLBox").fadeIn(300);
          }else{
              $(".lBoxMaster.contentLBox .container").html("");
              $(this).next(".hiddenContent").clone().appendTo($(".lBoxMaster .lBox .container"));
              $(".lBoxMaster.contentLBox").fadeIn(300);
          }
      });*/
  
      $(".greenText").addClass("show");
  
      $(document).on('click', '.viewLBox, [data-svg-video] .redButton', function (e) {
          cScroll = $(window).scrollTop();
          $("body").css("top","-"+cScroll+"px");
          $("body").addClass("lBoxOpen");
          var _this = $(this);
          if($(this).hasClass("redButton")){
              var dataVideo = _this.closest("[data-svg-video]").data("svg-video");
          }else{
              var dataVideo = _this.data("video");
          }
          
          var lightBox =  $(".lBoxMaster");
  
          if(dataVideo != undefined){
              lightBox.find(".iframe").show();
              lightBox.find("iframe").attr("src",dataVideo+"?autoplay=1&amp;rel=0&amp;fs=0&amp;showinfo=0").show();
              lightBox.find(".lContent").html("");
          }else{
              //lightBox.find("iframe").hide();
              lightBox.find(".iframe").hide();
              lightBox.find(".lContent").html(""); 
              _this.next(".hiddenContent").clone().appendTo($(".lBoxMaster .lContent"));
          }
  
          $(".lBoxMaster").fadeIn(300);
      });
  
      /*$(document).on('click', 'svg .redButton', function (e) {
          var triggerOn = $(this).closest("[data-video]");
          $(".viewLBox").trigger("click",triggerOn);
      });*/
  
      $(document).on('click', '.lBox .close', function (e) {
          var parentLBox = $(this).closest(".lBoxMaster");
          parentLBox.fadeOut();
          parentLBox.find("iframe").attr("src","");
          $("body").removeAttr("style").removeClass("lBoxOpen");
          $(window).scrollTop(cScroll);
      });
  
      $(document).on('click', function (e) {
          e.stopPropagation();
          if($(e.target).closest(".lBox").length == 0 && $(e.target).hasClass("lBoxMaster")){
              $('.lBox:visible .close').click();
          }
      });
      //LightBox - End
      
      if($(window).width() < 1024){
          $(document).on("click", ".share span", function(){
              $(this).parent().toggleClass("iconsShow");
          })
      }
  
      $(document).on('click','.faq h2', function(){
          var _this =  $(this).closest('.faq');
          _this.toggleClass("active");
          _this.find(".ans").slideToggle(800,function(){
              $('html,body').animate({scrollTop: (_this.offset().top - $("header").height() - 30) + "px"},300);
          });
      });
  
      
      var blogCarousel = $('.blogSlider');
      if(blogCarousel.length){
          blogCarousel.owlCarousel({
              items:1,
              pullDrag:false,
              nav:false
          });
  
           blogCarousel.on("translated.owl.carousel", function(event){
              circleAnimator(".blogSlider .owl-item.active .svgBox");
           });
      }
      $("#sharePopupBtn").click(function(){
        $(".sharePopup").show();
        var scrollHere = $(".sharePopup").offset().top-$("header").outerHeight() - 5;
        $("html, body").animate({
            scrollTop:scrollHere+"px"
        },200);
    })
    $(".sharePopup .close").click(function(){
        $(".sharePopup").hide();
    })
    $("#letsGet .genderRadioSec ul li").click(function(){
        $(this).addClass("toolArrrow").siblings().removeClass();
        $(".genderRadioSec .toolSec").eq($(this).index()).slideDown().siblings(".toolSec").slideUp();
    })
    $(".genderRadioSec .toolSec").find('.closeBtn').click(function(){
        $(this).parent().slideUp();
        $("#letsGet .genderRadioSec ul li").eq($(this).parent().index() - 2).addClass('toolArrrowRemove').siblings().removeClass('toolArrrowRemove');
        console.log($(this).parent().index() - 2);
    })
    
	var cloneLi;
    $("#letsStartBtn").click(function(){
        $("#letsStart").hide();
        $("#letsGet").show();
        var scrollHere = $("#letsGet").offset().top-$("header").outerHeight();
        $("html, body").animate({
            scrollTop:scrollHere+"px"
        },200);
		cloneLi = $("#breakfast").find(".makeItclone").html();
		//alert(cloneLi);
    });
      
      
    /*$(".calcSteps .btn.red").click(function(){
        var scrollHere = $(".calcSteps").offset().top-$("header").outerHeight();
        $("html, body").animate({
            scrollTop:scrollHere+"px"
        },200);
    });*/
      
    $("#procRsltsBtn").click(function(){
        /*$("#letsStart").hide();
        $("#letsGet").hide();
        $("#breakfast").hide();
        $("#lunch").hide();
        $("#Snacks").hide();
        $("#Dinner").hide();
        $("#processingResults").hide();
        $("#score").show();*/
        
        /*var gauge3 = Gauge(
        document.getElementById("gauge3"),
            {
              max: 100,
              min:0,
              //value:75
            }
        );
        (function loop() {
            var value3 = 75;
            gauge3.setValueAnimated(value3, 4);
            window.setTimeout(loop, 100);
        })();*/
    });
    $("#breakfast").find(".backBtn").click(function(){
        $("#breakfast").hide();
        $("#letsGet").show();
    })
    $("#lunch").find(".backBtn").click(function(){
        $("#lunch").hide();
        $("#breakfast").show();
    });
    $("#Snacks").find(".backBtn").click(function(){
        $("#Snacks").hide();
        $("#lunch").show();
    })
    $("#Dinner").find(".backBtn").click(function(){
        $("#Dinner").hide();
        $("#Snacks").show();
    })
    if($(window).width() < 768){
        var h1Text = $(".calcTemplate #letsStart").find("h1").text();
        $(".calcTemplate #letsStart").find("h1").remove();
        $(".calcTemplate #letsStart").find(".column").first().prepend("<h1 class='redText'>"+h1Text+"</h1>")
    }
      
      
      var incr = 0;
      $(document).on("click", ".addMoreBtn", function(){
          incr++;
		  
          //if($(this).parent().find(".makeItclone").length < 3){
              //var cloneLi = $(this).parent().find(".makeItclone").first().html();
              $(this).parent().children("ul").append('<li class="makeItclone countLi'+incr+'">'+cloneLi+'</li>');
          //}
          /*if($(this).parent().find(".makeItclone").length == 3){
              $(this).addClass("disable");
          }*/
          
		  $(".calcSteps .foodItemsSec .lists .countLi"+incr+"").each(function(){
              /*$(this).find('.inputBox').removeClass('focused');
              $(this).find('.placeholder').remove();*/
              $(this).find('.inputBox').removeClass('focused');
              $(this).find('.placeholder').remove();
              
                $(this).find('input').addPlaceholder();
                $(this).find('.placeholder').text('Food Items');
             
          });
		  
          var newIndex = $(this).parent().find(".makeItclone").length;
          $(".calcSteps .foodItemsSec .lists .countLi"+incr+"").find(".genderRadioSec").hide();     
                    
          var liList = $(this).parent().find(".countLi"+incr+"").children(".genderRadioSec").find("li");
          $(".countLi"+incr+"").find(".error").text('').css('opacity', '0');
          liList.find("input[type='radio']").prop('name', 'item['+incr+']');
          liList.children("input[type='radio']").prop('id', 'item['+incr+']');
          liList.children("input[type='radio']").next("label").prop('for', 'item['+incr+']');
          
          liList.last().children("input[type='radio']").prop('id', 'itemTwo['+incr+']');
          liList.last().children("input[type='radio']").next("label").prop('for', 'itemTwo['+incr+']');
          //$('input').addPlaceholder();
      });
	  
      $(document).on("click", ".removeLi", function(){
          $(this).parent().remove();
          $(".addMoreBtn").removeClass("disable")
      });
	  
       var len = $(".productCarousel.owl3D").find(".owl-dot").length;
        $( "#slider" ).slider({
            value:0,
            min: 0,
            max: 1 * (len-1),
            step: 1,
            slide: function( event, ui ) {
                $( "#amount" ).text( "$" + ui.value );
                $('.productCarousel .owl-controls').find('.owl-dot').eq(ui.value).trigger('click');
            }
        });
        $( "#amount" ).text( "$" + $( "#slider" ).slider( "value" ) );

      $('.responsiveTable').stacktable();
      //equalheight();
      
        boxPosition("#lightboxWrap");
    /*Lightbox*/
	$("#lightboxWrap .closeBtn, #lightboxWrap .proceedBtn").on("click", function(){
		/* $("#lightboxWrap").removeClass('show');
		$("#lightboxWrap, #overlay").fadeOut(); */
		PopUp('hide');
	});
	$('.dailyReqFor .tabLinkSec ul li').click(function(){
		$(this).addClass('active').siblings().removeClass('active');
		$('.dailyReqFor .tab-contents .content').eq($(this).index()).show().siblings().hide();
	}).eq(0).click()
  });
    

    function equalheight()
    {
		arrayHeight = [];
        $(".equalheight, .blogArea .blogBox, .media-master .media-wrap li").removeAttr("style");
        maxHeight=0;	
        $(".equalheight, .blogArea .blogBox, .media-master .media-wrap li").each(function() {      
          /* if(maxheight<($(this).innerHeight()))
           {
             maxheight=$(this).innerHeight();	
           }*/
		   arrayHeight.push($(this).innerHeight());
			//console.log(arrayHeight);
			maxHeight = Math.max.apply(Math,arrayHeight);
			//console.log(maxHeight);
        });
        $(".equalheight, .blogArea .blogBox, .media-master .media-wrap li").css("min-height",maxHeight + 1);
        return false;
    }
  

  $(window).on("load", function(){
      setTimeout(function(){$(window).scrollTop(0);},0);
      loaded = true;
      //$(".loader span").stop().clearQueue().css("width","100%").closest(".loader").delay(1000).fadeOut(800);
      $(".loader").delay(1000).fadeOut(800);

      if($("#page-wrapper").hasClass("blogTemplate")){
           var pageURL = $(location).attr("href");
           var list_split = pageURL.split('?page=')[1];
           var scrollHere = ($(".media-master h1, .section.blogMaster.twoColumn").offset().top-$("header").outerHeight())-12;
           //console.log(scrollHere);

           if(pageURL.indexOf('?page') > -1){
               $("html, body").animate({
                   scrollTop:scrollHere+"px"
               },300);
           }
      }
	  setTimeout(function(){
	  	equalheight();
	  }, 2000);
	  
	  PopUp('show');
      /*$("#lightboxWrap").addClass('show');
	  boxPosition("#lightboxWrap");*/
  });
  
  $(window).resize(function() {
      if (this.resizeTO) clearTimeout(this.resizeTO);
      this.resizeTO = setTimeout(function() {
          $(this).trigger('resizeEnd');
      }, 300);
  });
  
  $(window).bind('resizeEnd', function() {
      winW = $(window).width();
      winH = $(window).height();
      windowORdevice();
      bannerWaveResponsive();
      if(winW > 1254){
          $(".hamburger.active").click();
          $(".mainNavs").removeAttr("style");
      }
      
      if($(".bannerCarousel").length){
          imageSrcChanger($(".bannerCarousel"));
          $(".bannerCarousel").trigger("refresh.owl.carousel");
      }
    
      if ($(".bannerSection").length) {
            imageSrcChanger($(".bannerSection .tempBanner"));
        }
      /*$(".bannerSection").fadeIn();*/
      boxPosition("#lightboxWrap");

	  	equalheight();
      
  });
  
  
  $(window).on("scroll",function(){
      //Do Something On each Scroll
      dataSkroller();
  
  
      if (this.scrollTO) clearTimeout(this.scrollTO);
      this.scrollTO = setTimeout(function() {
          $(this).trigger('scrollEnd');
      }, 300);
  })
  
  
  $(window).bind('scrollEnd', function() {
      //Do Something After Scroll has stop.
  });
  
    var isLoadMoreClk = false;  
  $(document).on('click', '.loadMore', function() {
    if(!isLoadMoreClk) {
      isLoadMoreClk = true;
      var loadNo = $(this).attr('rel');
      var total = $(this).attr('id');
      $.ajax({
        url: "//ajax/recipes-load-more",
        type:'POST',
        dataType: "json",
        data: {'loadNo': loadNo},
        success: function(response) {
          if(response.res != '') {
            $('.loadMoreSec').before(response.res);
            svgloader(".svgBox");
          }
          if(response.lm == '1') {
            $('.loadMore').attr('rel', response.up);
            $('.cntRcp').text(response.up +' of '+ total);
          } else {
            $('.loadMoreSec').remove();
          }
        
          isLoadMoreClk = false;
        }
      });
    }
  });
  
  $(document).on('click', '.loadMoreTest', function() {
    if(!isLoadMoreClk) {
      isLoadMoreClk = true;
      var loadNo = $(this).attr('rel');
      var total = $(this).attr('id');
      $.ajax({
        url: "//ajax/testimonial-load-more",
        type:'POST',
        dataType: "json",
        data: {'loadNo': loadNo},
        success: function(response) {
          if(response.res != '') {
            $('.loadMoreSec').before(response.res);
            svgloader(".svgBox"); 
          }
          if(response.lm == '1') {
            $('.loadMoreTest').attr('rel', response.up);
            $('.cntRcp').text(response.up +' of '+ total);
          } else {
            $('.loadMoreSec').remove();
          }
        
          isLoadMoreClk = false;
        }
      });
    }
  });
  $(document).on('click', '.askSbmt', function() {
    $('.error').remove();
    var name = $('#name').val();
    var mail = $('#email').val();
    var qry = $('#query').val();
    var token = $('.token').val();
    $.ajax({
      url: "ajax/ask-the-experts",
      type:'POST',
      dataType: "json",
      data: {
        'name':name,
        'mail':mail,
        'query': qry,
        'token':token
      },
      success: function(response) {
        $('.token').val(response.tkn);
        if(response.error ==  '1') {
          if(response.errArr == '0') {
            //$('#askExpert .fieldBox').eq(0).before('<span class="error" style="opacity:1;position:relative;">'+ response.msg +'</span>')
          } else {
            for(var x in response.errArr) {
              var temp = response.errArr[x].split('-');
              var errId = temp[0];
              var errMsg = temp[1];
              $("#"+errId).after('<span class="error" style="opacity:1;">'+ errMsg +'</span>');
            }
          }
                
        } else {
          $('#askExpert').find('.formBox').addClass('dnone');
          $('#askExpert').append(response.msg);
          setTimeout(function(){
            $('.mssage').remove();
            $('#name').val('');
            $('#name').closest('.fieldBox').find('.inputBox').removeClass('focused');
            $('#name').closest('.fieldBox').find('.placeholder').removeClass('active');
            $('#email').val('');
            $('#email').closest('.fieldBox').find('.inputBox').removeClass('focused');
            $('#email').closest('.fieldBox').find('.placeholder').removeClass('active');
            $('#query').val('');
            $('#query').closest('.fieldBox').find('.inputBox').removeClass('focused');
            $('#query').closest('.fieldBox').find('.placeholder').removeClass('active');
            $('#askExpert').find('.formBox').removeClass('dnone');
          }, 3000);     
        }
      }
    });
  });
    
    $('.srchPager li a').click(function() {
        var page = $(this).text();
        console.log(page);
        var showPage = 'page'+page;
        $('.srchPager li').each(function() {
            console.log($(this).find('a').text());
            if($(this).find('a').text() == page) {
                $(this).find('a').prop('title', 'Current page');
            }  else {
                $(this).find('a').prop('title', '');
            }
        });
        $('.srchResBox').each(function() {
            if($(this).attr('rel') == showPage) {
                $(this).removeClass('dnone');
            } else {
                $(this).addClass('dnone');
            }
        });
        $(window).scrollTop(0);
    });
    
    
    $("#contact").keypress(function (e) {
         //if the letter is not digit then display error and don't type anything
         if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            $(".mobErr").html("Numeric value only").css('opacity', '1');
            setTimeout(function() {
                $(".mobErr").css('opacity', '0');
            },2000);
            return false;
        }
    });
    
    $("#mobile").keypress(function (e) {
         //if the letter is not digit then display error and don't type anything
         if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            $("#mobErr").html("Numeric value only").css('opacity', '1');
            setTimeout(function() {
                $("#mobErr").css('opacity', '0');
            },2000);
            return false;
        }
    });

    $("#ageofkid").keypress(function (e) {
         //if the letter is not digit then display error and don't type anything
         if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
            //display error message
            $("#ageErr").html("Numeric value only").css('opacity', '1');
            setTimeout(function() {
                $("#ageErr").css('opacity', '0');
            },2000);
            //fadeOut(2000);
            return false;
        }
    });
    
    


    
        $('#example').paginate();
        
            $('.srchResBox page').click(function() {
                if($(this).hasClass('page-1')) {
                    $('.page-prev').hide();
                }
            });
            
   $(document).on("click", ".fb", function () {
        var curUrl = window.location.href;
        window.open("https://www.facebook.com/sharer/sharer.php?u="+ curUrl, "pop", "width=600, height=400, scrollbars=no");
        return false;
    });
   
    $(document).on("click", ".twitter", function () {
        var url = window.location.href;
        if(url.indexOf('recipes') > -1){
            var text = "#ChillWithProtein Recipes";   
        }
        if(url.indexOf('the-world-of-proteins') > -1){
            var text = "Proteins are called the building blocks of life. Like Carbohydrates and Fat, Protein is macronutrient, important for our growth as well as to provide the body with energy.";   
        }
        if(url.indexOf('types-of-protein') > -1){
            var text = "Fuel your body with the requisite amount of protein daily.";   
        }
        if(url.indexOf('myths-and-facts') > -1){
            var text = "Here, we hope to clarify those so that proteins are no longer misunderstood and you understand that �something� missing in your diet.";   
        }
        if(url.indexOf('protinex-original') > -1){
            var text = "Protein is one of the most important everyday nutrients for all of us. However due to a busy lifestyle, it gets difficult to meet our daily protein needs. Protinex is the perfect partner for you in the race of life";   
        }
        if(url.indexOf('protinex-junior') > -1){
            var text = "Protinex Junior is formulated to fix what's missing in your child�s diet. It serves as a crucial supplement for the nutritional requirement for the child�s all-round growth.";   
        }
        if(url.indexOf('protinex-grow') > -1){
            var text = "Preteens and teens between 8-15 years experience a 2nd (MAKE THIS SUPERSCRIPT) growth spurt. They experience a rapid increase in height, weight and overall growth. ";   
        }
        if(url.indexOf('protinex-diabetes-care') > -1){
            var text = "Protinex Diabetes Care is a protein supplement made to serve that something missing in the nutrition needed to manage blood sugar levels. It also helps in managing weight, cholesterol and satisfies the special nutritional needs of the body.";   
        }
        if(url.indexOf('protinex-mama') > -1){
            var text = "Mama Protinex is a protein-rich supplement essential for the physical and brain development of the foetus. It satisfies that 'something' missing in your diet to ensure the optimum health and development of your child";   
        }
        if(url.indexOf('protinex-bytes') > -1){
            var text = "Protein in every bite! Protinex Bytes provides High Protein and 26 essential Nutrients to help fulfil your daily nutritional needs in a convenient manner.";   
        }
        window.open('http://twitter.com/share?url='+encodeURIComponent(url)+'&text='+encodeURIComponent(text), '', 'left=0,top=0,width=550,height=450,personalbar=0,toolbar=0,scrollbars=0,resizable=0');
    });
    
    
})(jQuery, Drupal);





