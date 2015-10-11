jQuery(function($) {
    $('.js-hide-button').mouseover(function() {
        $('.js-hide').show();
        $('.js-show').hide();
    });
    $('.js-hide-button').mouseout(function() {
        $('.js-hide').hide();
        $('.js-show').show();
    });
});