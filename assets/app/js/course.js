// on ready stuff (will probably change)
$(document).ready(function() {
  // pjax stuff
  $('.js-pjax').pjax('#jaxecute', {
  timeout: null, error: function(xhr, err){
    $('.error').text('Something went wrong: ' + err)
  }});
  $('#jaxecute')
    .bind('pjax:start', function() {
      $("#mainBlocker").css({cursor:"progress"});
      $('html, body').animate({ scrollTop: 0 }, 'fast');
    })
    .bind('pjax:end',   function() {
      $("#mainBlocker").css({cursor:"auto"});
    });
});