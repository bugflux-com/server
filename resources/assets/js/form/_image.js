$(function() {

	$('.form-image input').change(function(e) {
		var input = $(this);
		var field = $(input).parent('.form-image');

		field.find('.form-image-default-text').show();
		field.find('.form-image-preview').hide();

		if(input[0].files && input[0].files[0]) {
			var fileName = input[0].files[0].name;

			if(['image/jpeg', 'image/jpg', 'image/png'].indexOf(input[0].files[0].type) >= 0) {
				var reader = new FileReader();

				reader.onload = function(e) {
					field.find('.form-image-default-text').hide();
					field.find('.form-image-preview img').attr('src', e.target.result);
					field.find('.form-image-preview').show();
				}

				reader.readAsDataURL(input[0].files[0]);
			}
		}
	});

	$('.form-image input').each(function(i, elem) {
		var val = $(elem).attr('value');
		var field = $(elem).parent('.form-image');
		
		if(val.length > 0) {
			field.find('.form-image-default-text').hide();
			field.find('.form-image-preview img').attr('src', val);
			field.find('.form-image-preview').show();
		}
	});

})