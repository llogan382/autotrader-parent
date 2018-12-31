var multi_upload_images = [];
function multi_upload_initialize_image(el) {
	var $ = jQuery;

	if (!$(el).hasClass('tfuse-option-multi-upload') || $.inArray($(el)[0], multi_upload_images) > -1) {
		return;
	}

	var container = $(el),
		button = container.find('.upload-button'),
		thumbsContainer = container.find('.thumbs-container'),
		input = container.find('.multi-upload-input'),
		templates = container.find('.templates');
	var frame,
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
		disableSortable = function () {
			thumbsContainer.sortable('disable');
		},
		enableSortable = function () {
			thumbsContainer.sortable('enable');
		},
		createFrame = function () {
			frame = wp.media({
				library: {
					type: 'image'
				},
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
						thumbnail: thumbnail,
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
			multi_upload_images.push(container[0]);
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
			input.on('change', function () {
				thumbsContainer.children('.thumb:not(.no-image)').remove();
				var attachments = getValues();
				for (var i = 0; i < attachments.length; i++) {
					var element = templates.find('.thumb').clone(),
						thumbnail = attachments[i].thumbnail;
					if (thumbnail == undefined) {
						thumbnail = attachments[i].url
					}
					element.find('img')
						.attr('src', thumbnail)
						.attr('alt', attachments[i].alt);
					element.attr('data-id', attachments[i].id);

					element.find('.clear-uploads-thumb').on('click', function (e) {
						e.preventDefault();
						removeByOrder($(this).closest('.thumb').index() - 1);
					});

					element.appendTo(thumbsContainer);
				}
				addSortable();
			});

			var fix_update = false;
			input.on('change', function () {
				if (fix_update == true) {
					fix_update = false;
					return;
				}
				var xhr = false,
					items = [],
					attachemnts = getValues();

				for (var i = 0; i < attachemnts.length; i++) {
					var item = {
						id: null,
						url: null,
						fields : []
					};
					if (!attachemnts[i].hasOwnProperty('id')
						||
						isNaN(parseInt(attachemnts[i].id))) {
						item.fields.push('id');
					} else {
						item.id = attachemnts[i].id;
					}

					if (!attachemnts[i].hasOwnProperty('url')
						||
						attachemnts[i].url.length == 0) {
						item.fields.push('url');
					} else {
						item.url = attachemnts[i].url;
					}

					if (!attachemnts[i].hasOwnProperty('thumbnail')
						||
						attachemnts[i].thumbnail.length == 0) {
						item.fields.push('thumbnail');
					}

					if (item.fields.length > 0) {
						items.push(item);
					}
				}

				if (items.length == 0) {
					return;
				}

				disableSortable();

				xhr = true;
				$.post(
					tf_script.ajaxurl,
					{
						'action': 'get_attachments_info',
						'items':   items
					},
					function(response){
						if (! Array.isArray(response)) {
							return;
						}

						var attachments = getValues();

						for (var i = 0; i < response.length; i++) {
							for (var j = 0; j < attachments.length; j++) {
								if (response[i].id != attachments[j].id
									&&
									response[i].url != attachments[j].url) {
									continue;
								}

								if (response[i].hasOwnProperty('id')
									&&
									! isNaN(parseInt(response[i].id))) {
									attachments[j].id = response[i].id;
								}

								if (response[i].hasOwnProperty('url')
									&&
									! response[i].url.length == 0) {
									attachments[j].url = response[i].url;
								}

								if (response[i].hasOwnProperty('thumbnail')
									&&
									! response[i].thumbnail.length == 0) {
									attachments[j].thumbnail = response[i].thumbnail;
								}
							}
						}

						fix_update = true;
						update(attachments);
						enableSortable();

						xhr = false;
					}
				);
			})
		};

	init();
}
jQuery(document).ready(function ($) {
	$('.tfuse-option-multi-upload.image').each(function () {
		multi_upload_initialize_image($(this));
	});

	$(document).on('div_table:cloned', function (e) {
		multi_upload_initialize_image($(e.element).find('.tfuse-option-multi-upload.image'));
	});

	$(document).on('tf_optionize', function (e) {
		multi_upload_initialize_image($(document).find('.tfuse-option-multi-upload.image'));
	});
});