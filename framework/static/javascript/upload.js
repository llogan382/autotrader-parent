var tfuse_upload_items = [];
function tfuse_upload_initialize(el) {
	var $ = jQuery;

	if (!$(el).hasClass('option-upload') || $.inArray($(el)[0], tfuse_upload_items) > -1) {
		return;
	}

	var container = $(el),
		button = container.find('.upload-button'),
		input = container.find('.upload-input'),
		thumbnail = container.find('.uploaded_thumb'),
		frame,
		update = function (url) {
			input.attr('value', url);
			input.trigger('change');
		},
		getValue = function () {
			return input.val();
		},
		createFrame = function () {
			frame = wp.media({
				multiple: false
			});

			frame.on('select', function () {
				frame.state().get('selection').each(function (attachment) {
					update(attachment.get('url'))
				});
			})
		},
		init = function () {
			tfuse_upload_items.push(container[0]);
			button.on('click', function (e) {
				e.preventDefault();

				if (!frame) {
					createFrame();
				}

				frame.open();
			});

			input.on('change', function () {
				thumbnail.find('img').attr('src', getValue())
			});
		};

	init();
}
jQuery(document).ready(function ($) {
	$('.option-upload').each(function () {
		tfuse_upload_initialize($(this));
	});

	$(document).on('div_table:cloned', function (e) {
		tfuse_upload_initialize($(e.element).find('.option-upload'));
	});

	$(document).on('tf_optionize', function (e) {
		tfuse_upload_initialize($(document).find('.option-upload'));
	});
});