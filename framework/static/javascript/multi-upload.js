var multi_upload_items = [];
function multi_upload_initialize(el) {
	var $ = jQuery;

	if (!$(el).hasClass('tfuse-option-multi-upload') || $.inArray($(el)[0], multi_upload_items) > -1) {
		return;
	}

	var container = $(el),
		button = container.find('.upload-button'),
		thumbsContainer = container.find('.thumbs-container'),
		input = container.find('.multi-upload-input'),
		type = input.data('type'),
		templates = container.find('.templates'),
		frame,
		remove = function (ids) {
			if (!Array.isArray(ids)) {
				if (isNaN(parseInt(ids))) {
					return;
				}

				ids = [ids];
			}

			var newAttachments = [],
				attachments = getValues();

			for (var i = 0; i < attachments.length; i++) {
				if ($.inArray(attachments[i].id, ids) === -1) {
					newAttachments.push(attachments[i])
				}
			}

			container.trigger({
				type: 'removed',
				ids: ids
			});
			update(newAttachments);
		},
		removeByOrder = function (index) {
			index = parseInt(index);
			var values = getValues();
			if (isNaN(index) || values.length <= index) {
				return;
			}
			values.splice(index, 1);
			update(values);
		},
		add = function (attachments) {
			var values = getValues().concat(attachments);
			container.trigger({
				type: 'added',
				attachments: attachments
			});
			update(values);
		},
		update = function (attachments) {
			input.attr('value', JSON.stringify(attachments));
			container.trigger({
				type: 'updated',
				attachments: getValues()
			});
			input.trigger('change');
		},
		getValues = function () {
			try {
				return JSON.parse(input.val());
			} catch (e) {
				return [];
			}
		},
		addSortable = function () {
			thumbsContainer.sortable({
				cancel: '.no-image',
				distance: 1,
				update: function () {
					var newItems = [],
						items = getValues();
					thumbsContainer.find('.thumb:not(.no-image)').each(function () {
						for (var i = 0; i < items.length; i++) {
							if ($(this).data('id') == items[i].id) {
								newItems.push(items[i]);
								items.splice(i, 1);
								break;
							}
						}
					});

					update(newItems);
				}
			});
		},
		createFrame = function () {
			frame = wp.media({
				multiple: true
			});

			frame.on('select', function () {
				var attachments = [];

				frame.state().get('selection').each(function (attachment) {
					var thumbnail;
					if (attachment.get('sizes')) {
						thumbnail = attachment.get('sizes').thumbnail
							? attachment.get('sizes').thumbnail.url
							: attachment.get('sizes').full.url;
					} else {
						thumbnail = attachment.get('url');
					}

					attachments.push({
						alt: attachment.get('alt'),
						desc: attachment.get('description'),
						title: attachment.get('title'),
						caption: attachment.get('caption'),
						id: attachment.id,
						url: attachment.get('url')
					});
				});

				if (attachments.length > 0) {
					add(attachments)
				}
			})
		},
		init = function () {
			multi_upload_items.push(container[0]);
			thumbsContainer.find('.thumb:not(.no-image)').each(function () {
				$(this).find('.clear-uploads-thumb').on('click', function (e) {
					e.preventDefault();
					removeByOrder($(this).closest('.thumb').index() - 1);
				});
			});

			addSortable();
			button.on('click', function (e) {
				e.preventDefault();

				if (!frame) {
					createFrame();
				}

				frame.open();
			});
			container.on('change', function () {
				thumbsContainer.children('.thumb:not(.no-image)').remove();
				var attachments = getValues();
				for (var i = 0; i < attachments.length; i++) {
					var element = templates.find('.thumb').clone();
					element.attr('data-id', attachments[i].id);

					element.find('.item')
						.text(attachments[i].title)
						.attr('title', attachments[i].title);

					element.find('.clear-uploads-thumb').on('click', function (e) {
						e.preventDefault();
						removeByOrder($(this).closest('.thumb').index() - 1);
					});

					element.appendTo(thumbsContainer);
				}
				addSortable();
			});
		};

	init();
}
jQuery(document).ready(function ($) {
	$('.tfuse-option-multi-upload.any').each(function () {
		multi_upload_initialize($(this));
	});

	$(document).on('div_table:cloned', function (e) {
		multi_upload_initialize($(e.element).find('.tfuse-option-multi-upload.any'));
	});

	$(document).on('tf_optionize', function (e) {
		multi_upload_initialize($(document).find('.tfuse-option-multi-upload.any'));
	});
});