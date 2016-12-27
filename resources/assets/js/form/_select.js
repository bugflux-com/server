$(function() {

	$('.form-select select').change(function(e) {
		var value = $(this).find('option:selected').text();
		$(this).next('label').find('.form-select-value').text(value)

		if($(this).is('[submit-on-change]')) {
			$(this).closest('form').submit();
		}
	});

	$('.form-select select').each(function(i, elem) {
		var value = $(this).find('option:selected').text();
		$(this).next('label').find('.form-select-value').text(value);
	});

})