/**
 * @file
 * Code for humbergur menu.
 */

var jsonObject;

(function($, Drupal, drupalSettings) {
  
  Drupal.behaviors.himalaya = {
    attach: function(context, settings) {
      $(".meanmenu-reveal").click(function() {
        var X = $(this).attr('id');
          if (X == 1) {
            $(".submenu").hide();
            $(this).attr('id', '0');
            $(this).removeClass('mean-close');
          }
          else {
            $(".submenu").show();
            $(this).attr('id', '1');
            $(this).addClass('mean-close');
          }
      });
      $(".submenu").mouseup(function() {
        return false
      });
      $(".meanmenu-reveal").mouseup(function() {
        return false
      });
      $(document).mouseup(function() {
        $(".submenu").hide();
        $(".meanmenu-reveal").attr('id', '');
        $('.meanmenu-reveal').removeClass('mean-close');
      });
    }
  };
  
  $(document).on('click', '#letsStartBtn', function() {
    $.ajax({
      url: "ajax/calculator-food-items",
      type:'POST',
      dataType: "json",
      data: {},
      success: function(response) {
        jsonObject = response.data;
        console.log(jsonObject);
        /*$('#breakfast .foodItemsSec').find('select').html(response.data);
        $('#lunch .foodItemsSec').find('select').html(response.data);
        $('#Snacks .foodItemsSec').find('select').html(response.data);
        $('#Dinner .foodItemsSec').find('select').html(response.data);*/
      }
    });
  });
  
  if($('body').hasClass('path-frontpage')) {
    $.ajax({
      url: "ajax/get-token",
      type:'POST',
      dataType: "json",
      data: {},
      success: function(response) {
        if(response.token != '') {
          $('.token').val(response.token);
        }
      }
    });
  }
  
  if($('body').hasClass('path-search')) {
    if($('.pagination li').length <= 2) {
      $('.pagination').remove();
    }
  }
  
  $(document).on('keyup', '#bkfastP, #lunchP, #snacksP, #dinnerP', function() {
    var item = $(this).val().toLowerCase();
    var htmList = '';
    if($(this).val().length >=2 ) {
      for(var x in jsonObject) {
        var name = jsonObject[x].name.toLowerCase();
        if(name.indexOf(item) == 0) {
          htmList += '<li data-protein="'+ jsonObject[x].protein +'" data-portion="'+ jsonObject[x].portion +'" data-value="'+ jsonObject[x].name +'"><a class="itemFood" href="javascript:;">'+ jsonObject[x].name +'</a></li>';
        }
      }
    }
    $(this).closest('.fieldBox').find('.itemListContainer ul').html('').append(htmList);
  });
  
  /*$(document).on('click', '.itemFood', function() {
    
  });*/
  
  
  
})(jQuery, Drupal, drupalSettings);
