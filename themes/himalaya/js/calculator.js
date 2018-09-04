 var name;
 var age;
 var gender;
 var height;
 var maleHeight;
 var femaleHeight;
 var weight;
 var bmi;
 var diabetic;
 var pregnant = 0;
 var breakfastProtein = 0;
 var lunchProtein = 0;
 var snacksProtein = 0;
 var dinnerProtein = 0;
 var totalProtein = 0;
 var BMICalc = 0;
 var IdealBodyWeight = 0;
 var dailyProteinReq = 0;
 var dailyReq;
 


 
function validateEmail(sEmail) {
    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    if (filter.test(sEmail)) {
        return true;
    }
    else {
        return false;
    }
} 
 
 
(function($, Drupal) {
    
  var pattern = /^\d+$/;  // numeric values only
  $(".numericOnly").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
  });
    
    
    $(".calcSteps .foodItemsSec .lists .makeItclone").find(".genderRadioSec").hide();
    var dataPortion;
    $(document).on("click",".calcSteps .foodItemsSec .lists .makeItclone .itemListContainer ul li", function(){
        dataPortion = $(this).data("portion").replace(/[- )(]/g,'');
        $(this).closest('.fieldBox').find('input').attr("data-protein", $(this).data("protein"));
        
        $(this).closest('.makeItclone').find('.'+dataPortion).show().siblings(".genderRadioSec").hide();
        
        var selFoodItem = $(this).children('a').text();
        
        $(this).closest('.makeItclone').find('input').val(selFoodItem);
        $('.itemListContainer ul').html('');
    })
    
  $(document).on('click', '#letsGetBtn', function(){
    $('#letsGet .error').text('').css('opacity', '0');
    error = false;
      
    name = $('#name').val();
    // Name
    if(name == '') {
      $('#name').parent().find('.error').text('Please enter your name').css('opacity', '1');
      error = true;
    } else {
        var characterReg = /^\s*[a-zA-Z,.\'\s]+\s*$/;
        if(!characterReg.test(name)) {
            $('#name').parent().find('.error').text('Invalid name').css('opacity', '1');
            error = true;
        }
    }
    
    // Age
      if($('#age').val() == '') {
        $('#age').parent().find(".error").text('Please enter Age').css('opacity', '1');
        error = true;
      } else if($('#age').val() < 2 || $('#age').val() > 100) {
        $('#age').parent().find(".error").text('Please enter value between 2 and 100').css('opacity', '1');
        error = true;
      } else {
        age = $('#age').val();
      }
      

    // Gender
    if($('#letsGet .genderRadioSec li').eq(0).hasClass('toolArrrow') || $('#letsGet .genderRadioSec li').eq(1).hasClass('toolArrrow')) {
      
      if($('#letsGet .genderRadioSec li').eq(0).hasClass('toolArrrow')) {
        gender = 'male';


        if($('#diabetic').is(':checked')) {
          diabetic = '1';
        } else {
          diabetic = '0';
        }
      }
      if($('#letsGet .genderRadioSec li').eq(1).hasClass('toolArrrow')) {
        gender = 'female';
        
        if($('#diabetic2').is(':checked')) {
          diabetic = '1';
        } else {
          diabetic = '0';
        }
        if($('#pregnant').is(':checked')) {
          pregnant = '1';
        } else {
          pregnant = '0';
        }
      }
    } else {
      error = true;
      $('#letsGet .genderRadioSec').find('.error').text('Please select your gender').css('opacity', '1');
    }
    // Height
    if(pattern.test($('#height').val())) {
      height = $('#height').val();
      if(gender == 'male' && parseInt(height) < 100 ) {
        error = true;
        $('#height').parent().find(".error").text('Height should be above 100cms').css('opacity', '1');
      } 
      if(gender == 'female' && parseInt(height) < 105 ) {
        error = true;
        $('#height').parent().find(".error").text('Height should be above 105cms').css('opacity', '1');
      } 
    } else {
      error = true;
      if($('#height').val() == '') {
        $('#height').parent().find(".error").text('Please enter height').css('opacity', '1');
      } else {
        $('#height').parent().find(".error").text('Invalid value for height').css('opacity', '1');
      }
    }
    
    if(gender == 'male') {
        dailyReq = parseInt(height) - parseInt(100);
    } else {
        dailyReq = parseInt(height) - parseInt(105);
    }

    // Weight
    if(pattern.test($('#weight').val())) {
      weight = $('#weight').val();
    } else {
      error = true;
      if($('#weight').val() == '') {
        $('#weight').parent().find(".error").text('Please enter weight').css('opacity', '1');
      } else {
        $('#weight').parent().find(".error").text('Invalid value for weight').css('opacity', '1');
      }
    }
      
    var heightFeet = height/100;
    
    BMICalc = weight/Math.pow(heightFeet, 2)
    IdealBodyWeight = weight - BMICalc;
    dailyProteinReq = IdealBodyWeight * 0.0008;
    //console.log(heightFeet+ ', Bmi '+ BMICalc+ ', IdealBodyWeight '+IdealBodyWeight+', dailyProteinReq ' + dailyProteinReq)  
      
    if(error) {
      return false;
    } else {
      // goto next page
      $('.userNameSec h3').text('Hi, '+ name);
      //$("#letsStart").hide();
      $(".calcSteps").hide();
      $("#breakfast").show();
      var scrollHere = $("#breakfast").offset().top-$("header").outerHeight();
      $("html, body").animate({
        scrollTop:scrollHere+"px"
      },200);
    }
      
      

  });
    

    $(".calcSteps .foodItemsSec .lists .makeItclone").find(".genderRadioSec").hide();
    $(document).on("change",".calcSteps .foodItemsSec .lists .makeItclone .fieldBox select", function(){
        if($(this).find("option:selected").attr('data-portion')){
            var dataPortion = $(this).find("option:selected").data("portion").replace(/[- )(]/g,'');
        }
        $(this).closest('.makeItclone').find('.'+dataPortion).show().siblings(".genderRadioSec").hide();
        $(this).closest('.makeItclone').find('.genderRadioSec').each(function(){
            $(this).find("select").find('option:eq(0)').prop('selected', true);
            var thisSelect = $(this).find("select").find('option:eq(0)').text();
            $(this).find('.selectBox').removeClass('focused');
            $(this).find('.selectedValue').text(thisSelect);
        })
        if(!dataPortion){
            $(this).closest('.makeItclone').find('.genderRadioSec').each(function(){
                $(this).find("select").find('option:eq(0)').prop('selected', true);
                var thisSelect = $(this).find("select").find('option:eq(0)').text();
                $(this).find('.selectBox').removeClass('focused');
                $(this).find('.selectedValue').text(thisSelect);
            })
            $(this).closest('.makeItclone').find('.genderRadioSec').hide();
        }
    });    
    
    $(document).on('click', '#lunch .backBtn', function(){
        breakfastProtein = 0;
    })
    $(document).on('click', '#breakfastBtn', function(){
        $('#breakfast .error').text('').css('opacity', '0');
        error = false;
        $(this).parent().find(".makeItclone .fieldBox .inputBox").each(function(){
            if(!$(this).hasClass("focused")){
              $(this).find("input").next('.error').text('Please select option').css('opacity', '1');
              error = true;
            }
            if(!$(this).parent().next(".itemTypeSec").find(".selectBox").hasClass("focused")){
                $(this).parent().next(".itemTypeSec").find(".selectBox select").next('.error').text('Please select option').css('opacity', '1');
                error = true;
            }
        })
                
        if(error) {
          return false;
        } else {
            $(this).parent().find(".makeItclone").each(function(){
                var itemProtine = parseFloat($(this).find(".fieldBox input").data("protein"));
                var itemCount = parseFloat($(this).find(".itemTypeSec .selectBox.focused select").children("option:selected").data("count"));
                
                breakfastProtein += itemProtine * itemCount;
                console.log(itemProtine, itemCount, breakfastProtein);
            })
            // goto next page
            $(".calcSteps").hide();
            $("#lunch").show();
            var scrollHere = $("#lunch").offset().top-$("header").outerHeight();
            $("html, body").animate({
                scrollTop:scrollHere+"px"
            },200);
        }
        console.log('t ' + breakfastProtein);
    });
    $(document).on('click', '#Snacks .backBtn', function(){
        lunchProtein = 0;
    })
    $(document).on('click', '#lunchBtn', function(){
        $('#lunch .error').text('').css('opacity', '0');
        error = false;
        $(this).parent().find(".makeItclone .fieldBox .inputBox").each(function(){
            if(!$(this).hasClass("focused")){
              $(this).find("input").next('.error').text('Please select option').css('opacity', '1');
              error = true;
            }
            if(!$(this).parent().next(".itemTypeSec").find(".selectBox").hasClass("focused")){
                $(this).parent().next(".itemTypeSec").find(".selectBox select").next('.error').text('Please select option').css('opacity', '1');
                error = true;
            }
        })
        
        if(error) {
          return false;
        } else {
            $(this).parent().find(".makeItclone").each(function(){
                var itemProtine = parseFloat($(this).find(".fieldBox input").data("protein"));
                var itemCount = parseFloat($(this).find(".itemTypeSec .selectBox.focused select").children("option:selected").data("count"));
                console.log(itemProtine * itemCount)
                lunchProtein += itemProtine * itemCount
            })
            
            // goto next page
            $(".calcSteps").hide();
            $("#Snacks").show();
            var scrollHere = $("#lunch").offset().top-$("header").outerHeight();
            $("html, body").animate({
                scrollTop:scrollHere+"px"
            },200);
        }
        console.log('t ' + breakfastProtein + ' lunchProtein ' + lunchProtein);
  });
  
  $(document).on('click', '#Dinner .backBtn', function(){
        snacksProtein = 0;
  })
  $(document).on('click', '#SnacksBtn', function(){
        $('#Snacks .error').text('').css('opacity', '0');
        error = false;
        $(this).parent().find(".makeItclone .fieldBox .inputBox").each(function(){
            if(!$(this).hasClass("focused")){
              $(this).find("input").next('.error').text('Please select option').css('opacity', '1');
              error = true;
            }
            if(!$(this).parent().next(".itemTypeSec").find(".selectBox").hasClass("focused")){
                $(this).parent().next(".itemTypeSec").find(".selectBox select").next('.error').text('Please select option').css('opacity', '1');
                error = true;
            }
        })
        
        if(error) {
          return false;
        } else {
            $(this).parent().find(".makeItclone").each(function(){
                var itemProtine = $(this).find(".fieldBox input").data("protein");
                var itemCount = $(this).find(".itemTypeSec .selectBox.focused select").children("option:selected").data("count");
                console.log(itemProtine * itemCount)
                snacksProtein += itemProtine * itemCount
            })
            
            // goto next page
            $(".calcSteps").hide();
            $("#Dinner").show();
            var scrollHere = $("#Snacks").offset().top-$("header").outerHeight();
            $("html, body").animate({
                scrollTop:scrollHere+"px"
            },200);
        }
        console.log(`breakfastProtein ${breakfastProtein} lunchProtein ${lunchProtein} snacksProtein ${snacksProtein}`);
  });
    
  $(document).on('click', '#dinnerBtn', function(){
        $('#Dinner .error').text('').css('opacity', '0');
        error = false;
        $(this).parent().find(".makeItclone .fieldBox .inputBox").each(function(){
            if(!$(this).hasClass("focused")){
              $(this).find("input").next('.error').text('Please select option').css('opacity', '1');
              error = true;
            }
            if(!$(this).parent().next(".itemTypeSec").find(".selectBox").hasClass("focused")){
                $(this).parent().next(".itemTypeSec").find(".selectBox select").next('.error').text('Please select option').css('opacity', '1');
                error = true;
            }
        })
        
        if(error) {
          return false;
        } else {
            $(this).parent().find(".makeItclone").each(function(){
                var itemProtine = $(this).find(".fieldBox input").data("protein");
                var itemCount = $(this).find(".itemTypeSec .selectBox.focused select").children("option:selected").data("count");
                console.log(itemProtine * itemCount)
                dinnerProtein += itemProtine * itemCount
            })
            
            // goto next page
            $(".calcSteps").hide();
            $("#processingResults").show();
            var scrollHere = $("#processingResults").offset().top-$("header").outerHeight();
            $("html, body").animate({
                scrollTop:scrollHere+"px"
            },200);
        }
      
        totalProtein = breakfastProtein + lunchProtein + snacksProtein + dinnerProtein;
      
        console.log(`breakfastProtein ${breakfastProtein}, lunchProtein ${lunchProtein}, snacksProtein ${snacksProtein}, dinnerProtein ${dinnerProtein}, totalProtein ${totalProtein}`);
  });
  $(document).on('click', '#procRsltsBtn', function() {
	  $('#processingResults .error').text('').css('opacity', '0');
      error = false;
	  
	  var  mobile = $('#processingResults #mobile').val();
	  var  email = $('#processingResults #Email').val();
		if(mobile == '') {
		  $('#processingResults #mobile').parent().find('.error').text('Please enter your Mobile No.').css('opacity', '1');
		  error = true;
		}else if(mobile.length < 7) {
        	$('#processingResults #mobile').parent().find('.error').text('Please enter Vaild Mobile No.').css('opacity', '1');
		    error = true;
		}else{
			$('#processingResults #mobile').parent().find('.error').text('').css('opacity', '0');
			error = false;
		}
		if(email == ''){
			$('#processingResults #Email').parent().find('.error').text('Please enter your Email id').css('opacity', '1');
		  	error = true;
		}
		if (validateEmail(email)) {
            console.log('Email is valid');
        }
        else {
			$('#processingResults #Email').parent().find('.error').text('Invalid Email Address').css('opacity', '1');
			error = true;
        }

    
	  if(parseInt($('#mobile').val()) < 1) {
        $('#mobile').parent().find('.error').text('Invalid Mobile No.').css('opacity', '1');
        error = true;
      }
     //error = false;
      if(error) {
          return false;
        } else {
                      
            // goto next page
            $(".calcSteps").hide();
            $("#score").show();
            var scrollHere = $("#score").offset().top-$("header").outerHeight();
            $("html, body").animate({
                scrollTop:scrollHere+"px"
            },200);
			
			$.ajax({
		  url: "ajax/get-calci-result",
		  type:'POST',
		  dataType: "json",
		  data: {
			 'name' : name,
			 'age' : age,
			 'gender': gender,
			 'height': height,
			 'weight': weight,
             'mobile': mobile,
             'email' : email,
			 'diabetic': diabetic,
			 'pregnant': pregnant,
			 'breakfast': breakfastProtein,
			 'lunch':lunchProtein,
			 'snacks' :snacksProtein,
			 'dinner' :dinnerProtein,
			 'total' :totalProtein,
			 'bmi' :BMICalc,
			 'IdealBodyWeight' :IdealBodyWeight,
			 //'dailyProteinReq' :dailyProteinReq * 1000
             'dailyProteinReq' : dailyReq
		},
		  success: function(response) {
			console.log(response);
			var temp = response.data.protein_intake.split('%');
			$('.dailyRecPrtn').find('h3').text(response.data.daily_requirement);
			$('.PrtnDygram').find('h6').text(response.data.protein_less);
			$('.PrtnDygram').find('h5').find('strong').text(response.data.protein_intake);
			$('.PrtnDygram').find('h5').find('span').text(response.data.daily_intake);
			$('.inadequateSec').find('.subTitle').text(response.data.heading).after('<p>' + response.data.message + '</p>');
			$('.inadequateSec').find('.subTitle').append(response.data.images);
			var gauge3 = Gauge(
			document.getElementById("gauge3"),
				{
				  max: 100,
				  min:0,
				  //value:75
				}
			);
			(function loop() {
				var value3 = temp[0];
				gauge3.setValueAnimated(value3, 4);
				window.setTimeout(loop, 100);
			})();
		  }
		});
        }
  })
  
})(jQuery, Drupal);
