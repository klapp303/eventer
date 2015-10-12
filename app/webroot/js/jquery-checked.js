jQuery(function($) {
    $('.js-checkbox_time_start').change(function(){
	if ($(this).is(':checked')) {
		$('.js-input_time_start').attr('disabled','disabled');
	} else {
		$('.js-input_time_start').removeAttr('disabled').focus();
	}
    });
    $('.js-checkbox_entry_start').change(function(){
	if ($(this).is(':checked')) {
		$('.js-input_entry_start').attr('disabled','disabled');
	} else {
		$('.js-input_entry_start').removeAttr('disabled').focus();
	}
    });
    $('.js-checkbox_entry_end').change(function(){
	if ($(this).is(':checked')) {
		$('.js-input_entry_end').attr('disabled','disabled');
	} else {
		$('.js-input_entry_end').removeAttr('disabled').focus();
	}
    });
    $('.js-checkbox_announcement_date').change(function(){
	if ($(this).is(':checked')) {
		$('.js-input_announcement_date').attr('disabled','disabled');
	} else {
		$('.js-input_announcement_date').removeAttr('disabled').focus();
	}
    });
    $('.js-checkbox_payment_end').change(function(){
	if ($(this).is(':checked')) {
		$('.js-input_payment_end').attr('disabled','disabled');
	} else {
		$('.js-input_payment_end').removeAttr('disabled').focus();
	}
    });
});