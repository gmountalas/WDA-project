$(function() {
    $( "#slider" ).slider({
      range:true,
      animate:"fast",
      min: 0,
      max: 5000,
      step: 50,
      values: [ 0, 5000 ],
      slide: function( event, ui ) {
          $(".min-price").html(ui.values[ 0 ] + " €");
          $(".max-price").html(ui.values[ 1 ] + " €");
          $("#min_price").val(ui.values[ 0 ]);
          $("#max_price").val(ui.values[ 1 ]);
      }
    });
  
    $(".min_price_label").val($( "#slider" ).slider( "values", 0 ) + " €");
    $(".max_price").val($( "#slider" ).slider( "values", 1 ) + " €");
});
$(document).ready(function(){
  $('.ui-slider-handle').append('<div class="inner"></div>');
});