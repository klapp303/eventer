jQuery(function($) {
    $('.js-hide-button').click(function() {
        $('.js-hide').show();
        $('.js-show').hide();
    });
    $('.js-show-button').click(function() {
        $('.js-hide').hide();
        $('.js-show').show();
    });
});