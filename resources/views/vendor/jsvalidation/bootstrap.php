validateInit.push(function () {
	$("<?php echo $validator['selector']; ?>").validate({
		errorElement: 'span',
		errorClass: 'help-block error-help-block',
		errorPlacement: function(error, element) {
			if (element.parent('.input-group').length ||
				element.prop('type') === 'checkbox' || element.prop('type') === 'radio') {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		},
		highlight: function(element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		<?php if (isset($validator['ignore']) && is_string($validator['ignore'])): ?>
		ignore: "<?php echo $validator['ignore']; ?>",
		<?php endif; ?>
		success: function(element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		focusInvalid: false,
		<?php if (Config::get('jsvalidation.focus_on_error')): ?>
		invalidHandler: function(form, validator) {
			if (!validator.numberOfInvalids()) {
				return;
			}
			$('html, body').animate({
				scrollTop: $(validator.errorList[0].element).offset().top
			}, <?php echo Config::get('jsvalidation.duration_animate') ?>);
			$(validator.errorList[0].element).focus();
		},
		<?php endif; ?>
		rules: <?php echo json_encode($validator['rules']); ?>
	});
});
