(function ($, Drupal) {
    var invalidCounter = 0;
    function validateMe(_this){
        $(_this).closest(".fieldBox").removeClass("isValid");
            var thisField = $(_this).attr("name");
            if(thisField == 'Mobile') { thisField += ' Number' } 
            var validationInfo = $(_this).data("validation");
            var validationType = []; 
            if($(_this).is(":disabled"))
            {
                return false;
            }
    
            if(validationInfo.indexOf(",") != -1){
               validationType = validationInfo.split(",");
            }else{
               validationType.push(validationInfo);
            }
    
            var validAlpha =/^[a-zA-Z ]*$/;
            var validNumeric = /^[0-9]+$/;
            var validEmail = /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,15})$/;
            var validDate = /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/;
            var validAlphaNum = /^[a-zA-Z0-9 ]*$/;
    
            //GET Value
            if($(_this).is(":checkbox")){
                var inputVal =  $(_this).is(":checked") ? true : false;
            }else if(_this.nodeName == "SELECT"){
               if($(_this).find('option:selected').index() == 0){
                    var inputVal =  $(_this).val();
               }
            }else{
                var inputVal =  $(_this).val();
            } 
    
            
    
            for(var i=0; i < validationType.length; i++){
                if(validationType[i] =="required" &&  (inputVal=="" || inputVal== false || inputVal== "-1" )){ // for normal select dropdown 
    
                        if($(_this).closest(".checkBox").length && inputVal == false){
    
                            $(_this).closest(".checkBox").addClass("error-field"); 
    
                        }else if($(_this).closest(".selectBox").length && inputVal == "-1"){ // for normal select dropdown 
                             
                            $(_this).closest(".fieldBox").addClass("error-field");    
    
                        }
                        else{
                            $(_this).closest(".fieldBox").addClass("error-field");
    
                            if($(this).siblings(".rs").length){
                                $(this).siblings(".rs").addClass("error-text");
                            }  
                        }
                        
     
                        $(_this).siblings(".error").text(thisField+" is required");
                        if($(_this).closest(".selectBox").length==0){
                            $(_this).val("");
                        }
                    
                    invalidCounter++; 
                    return false;
                }
    
                if(inputVal != "" && validationType[i].indexOf("minlength") != -1){
                    var splitMe= validationType[i].split("minlength");
    
                    if(inputVal.length < parseInt(splitMe[1])){
                        $(_this).closest(".fieldBox").addClass("error-field");
                        $(_this).siblings(".error").text($(_this).attr("name")+" should not be lesser than "+splitMe[1]+" characters");
                        
                        invalidCounter++; 
                        return false;
                    }
                    
                }
    
                if(inputVal != "" && validationType[i].indexOf("maxlength") != -1){
    
                    var maxLen = parseInt($(_this).attr("maxlength"));
                    var typeOfField = validationType.indexOf("numbersOnly") != -1 ? "digits" : "letters";
    
                    if(inputVal.length != maxLen){
                        $(_this).closest(".fieldBox").addClass("error-field");
                        $(_this).siblings(".error").text(thisField+" should be exactly of "+maxLen+" "+typeOfField);
                        
                        invalidCounter++; 
                        return false;
    
                    }
                }
    
                if(inputVal != "" && validationType[i] == "alphaOnly" && validAlpha.test(inputVal) == false){
                    $(_this).closest(".fieldBox").addClass("error-field");
                    $(_this).siblings(".error").text(thisField+" can only have alphabets");
                    
                    invalidCounter++; 
                    return false; 
                }
    
                if(inputVal != "" && validationType[i] == "numbersOnly" && validNumeric.test(inputVal) == false && inputVal.indexOf(" ")==-1){
                    $(_this).closest(".fieldBox").addClass("error-field");
                    $(_this).siblings(".error").text(thisField+" can only have numbers");
                    
                    invalidCounter++; 
                    return false; 
                }
    
                if(inputVal != "" && validationType[i] == "email" && validEmail.test(inputVal) == false){
                    $(_this).closest(".fieldBox").addClass("error-field");
                    $(_this).siblings(".error").text("Please enter valid "+thisField);
    
                    invalidCounter++; 
                    return false;
                }
    
                if(inputVal != "" && validationType[i].indexOf("matchWith") != -1){
                    var toMatchFieldID = "#"+validationType[i].split("#")[1];
                    
                    if(inputVal != $(toMatchFieldID).val()){
                        $(_this).closest(".fieldBox").addClass("error-field");
                        $(_this).siblings(".error").text(thisField+" should match with "+$(toMatchFieldID).attr("name"));
    
                        invalidCounter++; 
                        return false;
                    }
                }
    
    
    
    
    
    
            }
    
            $(_this).closest(".error-field").removeClass("error-field");  
            $(_this).closest(".fieldBox").addClass("isValid"); 
    }
    
    
    $(function(){   
    
        var validateTout;
        $("[data-validation]").each(function(){
            if($(this).siblings(".error").length == 0){
                if($(this).attr("type")=="checkbox"){
                    $('<div class="error"></div>').insertAfter($(this).next("label"));
                }else{
                    $('<div class="error"></div>').insertAfter($(this));
                }
                
            }
        });
        
    
        $(document).on("keyup", "[data-validation]", function(e){
            var code = e.keyCode || e.which;
            if (code != 9) {
                var _this = this;
                clearTimeout(validateTout);
                validateTout = setTimeout(function(){
                    validateMe(_this);
                },1000);
            }
        });
    
        $(document).on("blur change", "[data-validation]", function(e){
            var _this = this;
            clearTimeout(validateTout);
            validateTout = setTimeout(function(){
                validateMe(_this);
            },0);
        });
    
    
        /*$("#askform, #testimonial-form").submit(function(event){
            event.preventDefault();
            invalidCounter = 0;
            var form = $(this);
            form.find(":input[data-validation]").each(function(){
                validateMe(this);
            }).promise().done(function(){
                if(invalidCounter == 0){
                    submitForm(form.attr("id"));
                }else{
                    invalidCounter = 0; 
                }      
            });
        });   */
    
    });
    
    function submitForm(formId){
    
        if(formId == "initForm"){
            $("#lboxOTP").show();
        }
        
        //Do anything on submit
    }
})(jQuery, Drupal);