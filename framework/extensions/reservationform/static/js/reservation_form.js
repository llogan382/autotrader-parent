var form_has_changed = false;
var theme_index = tf_script.TF_THEME_PREFIX;
jQuery(document).ready(function ($) {  sortStop();
    if ($('#tf_rf_gensett_form .inside .hidden').length > 0) {
        $('.hidden').each(function () {
            if(!$(this).is('#wp-auth-check-wrap')){
                $(this).closest('.option').addClass('hidden');
                $(this).removeClass('hidden');
            }
        });
        if ($('.divider').length) {
            $('.divider').each(function () {
                $(this).addClass('hidden');
            });
        }
    }
    $("#rf_form_elements").sortable({
            stop:sortStop
        }
    );
    $("#tf_rf_prev_button").click(function () {
        createFormPreview($(this).closest('form'));

    });

    $("body").on('mouseover', '.ui-selectmenu-status', function (event) {
        offset = $(this).offset();
        _leftRelative = offset.left;
        _topRelative = offset.top;
        _absoluteLeft = event.pageX;
        _absoluteTop = event.pageY;
        _id = $(this).closest('a').attr('id');
        _listElem = $('#' + _id.substring(0, _id.lastIndexOf('-')) + '-menu');
        _listTop = parseInt(_topRelative) + 30;
        _listLeft = parseInt(_leftRelative - 1);
        _listElem.css('top', _listTop + 'px');
        _listElem.css('left', _listLeft + 'px');
    });

    $("body").on('mouseover', '.sortable_options .tfuse-tax-form-field', function () {
        _options = $(this).closest('.sortable_options');
        if (_options.find('.tfuse-tax-form-field').length > 1) {
            _delete = $(this).find('.tf_cf_delete_option');
            _delete.css('display', 'inline');
        }
    }).on('mouseout', '.sortable_options .tfuse-tax-form-field', function () {
        _delete = $(this).find('.tf_cf_delete_option');
        _delete.css('display', 'none');
    });

    $("body").on('mouseover', '.tf_custom_reservationform_row', function () {
        _inside = $(this).closest('.inside');
        _delete = _inside.find('.reservationform_delete_input');
        if (_delete.length > 1) {
            _delete_inp = $(this).find('.reservationform_delete_input');
            _delete_inp.css('display', 'inline');
        }
    }).on('mouseout', '.tf_custom_reservationform_row', function () {
        _delete = $(this).find('.reservationform_delete_input');
        _delete.css('display', 'none');
    });

    _form_content_id =  jQuery('#tf_form_main_content_id').attr('data-form-content-id');
    $('#'+_form_content_id+' .tfuse_is_datepicker_flag').each(function(){
        if($(this).val() !='false'){
            $(this).closest('.tf_custom_reservationform_row').find('label[for^="tf_rf_input_required"]').css('visibility','hidden');
        }
    });
    $("#tf_rf_add_new").click(function () {
        _inside = $(this).closest('.inside');
        var _last_li  = _inside.find('.tf_custom_reservationform_row:last');
        /* find first default input for clone */
        var _first_li = _inside.find('.tf-rf-default-input:first').parents('.tf_custom_reservationform_row');
        count_el = _last_li.find('.rf_input_newline:last');
        if(_last_li.find('.tfuse_is_datepicker_flag:last').val() !='false'){
            $('#'+_form_content_id+' .tfuse_is_datepicker_flag').each(function(){
                if($(this).val() == 'false'){
                    _last_li =  $(this).closest('.tf_custom_reservationform_row');
                    return false;
                }
            });
        }
        count_id = count_el.attr('id');
        count = count_id.substring(count_id.lastIndexOf('_') + 1, count_id.length);
        count++;
        _ul = _last_li.closest('ul');
        if (_inside.find('.tf_custom_reservationform_row').length == 3) {
            _del = _inside.find('.tf_custom_reservationform_row .reservationform_delete_input_last');
            _del.removeAttr('class');
            _del.attr('class', 'reservationform_delete_input');
        }

        if ( _first_li.length >0 ) {
            /* for clone first item with custom class "tf-rf-default-input" */
            _html = "<li class='" + _first_li.attr('class') + "'>" + _first_li.html() + "</li>";
        }
        else {
            /* initial */
            _html = "<li class='" + _last_li.attr('class') + "'>" + _last_li.html() + "</li>";
        }
        _ul.append(_html);
        _last_li = _ul.find('.tf_custom_reservationform_row:last');
        prepare_fields(_last_li);
        _options_li = _last_li.find('.sortable_options:last li');
        if (_options_li.length > 1) {
            _options_li.each(function () {
                if (_last_li.find('.sortable_options:last li').length > 1) {
                    $(this).remove();
                }
                else {
                    _last_li.find('.sortable_options:last li input').val('');
                }
            });
        }
        _inp = _last_li.find('.sortable_options:last li input');
        _last_li.find('ul.sortable_options:last').removeAttr('style');
        _inp.attr('id', _inp.attr('id').substr(0, _inp.attr('id').indexOf('[') + 1) + count + '][]');
        _inp.attr('name', _inp.attr('name').substr(0, _inp.attr('name').indexOf('[') + 1) + count + '][]');
        _html_row = _inside.find('.tf_custom_reservationform_row:last');
        count_el = _html_row.find('.single_checkbox');
        count_el.each(function () {
            _id_var = $(this).attr('id');
            _id = _id_var.substring(0, _id_var.lastIndexOf('_') + 1);
            _label = $(this).next('label');
            _hidden = $(this).prev('.checkbox_default_hidden_value');
            _hidden.attr('name', _id + count);
            _hidden.attr('hiddenname', '');
            _label.attr('for', _id + count);
            $(this).attr('id', _id + count);
            $(this).attr('name', _id + count);
        });
        form_has_changed = true;
    });

    $('body').on('click', '.shortcode_code,#tfuse_form_name,.form_list_table code', function () {
        var r = document.createRange();
        var w = jQuery(this).get(0);
        r.selectNodeContents(w);
        var sel = window.getSelection();
        sel.removeAllRanges();
        sel.addRange(r);
    });
    $('body').on({
        blur:function () {
            manageShortcodes($(this));
        },
        keyup:function () {
            manageShortcodes($(this));
        }
    }, '.rf_input_label');
    $('.rf_input_label').keyup();
    $('body').on('change', '.'+theme_index+'_inp_select', function () {
        _selected = $(this).find(":selected");
        _has_options = _selected.val() == 'radio' || _selected.val() == 'select' || _selected.val() == 'multicheckbox';
        _can_be_required = _selected.val() == 'text' || _selected.val() == 'textarea' || _selected.val() == 'checkbox' || _selected.val() == 'email';
        _parent_tr = $(this).closest('.tf_custom_reservationform_row');
        if (!_can_be_required) {
            _req = _parent_tr.find('.rf_input_required:last');
            _req.attr('checked', false);
            _req.next('label').removeClass('on');
            _req.next('label').css('visibility', 'hidden');
        } else {
            _req = _parent_tr.find('.rf_input_required:last');
            _req.next('label').css('visibility', 'visible');
        }
        _options_ul = _parent_tr.find('.sortable_options');
        if (_has_options) {
            _parent_tr.find('.tf_rf_toggle_show:last').show();
            switch (_selected.val())
            {
                case 'radio':
                    _type = 'Radio';
                    break;
                case 'select':
                    _type = 'Select';
                    break;
                case 'multicheckbox':
                    _type = 'Checkbox';
                    break;
                default :
                    _type = 'Set'
            }
            _parent_tr.find('ul.sortable_options .tfuse-tax-form-field span').each(function () {
                $(this).html(_type + ' Option');
            });
            _options_ul.show();
        } else {
            _parent_tr.find('.tf_rf_toggle_show:last').hide();
            _options_ul.hide();
        }
        form_has_changed = true;
    });
    $('body').on('click', '.cf_add_option', function () {
        _ul_parent = $(this).closest('.sortable_options');
        _li = _ul_parent.find('li.form-field:last');
        _class = _li.attr('class');
        _li.after('<li class="' + _class + '" style="display:none" >' + _li.html() + '</li>');
        _added_li = _ul_parent.find('li.form-field:last');
        _input = _added_li.find('input');
        _input.val('');
        _added_li.show();
        form_has_changed = true;
    });
    $(".tf_checkbox_switch").on('click', function () {
        form_has_changed = true;
    });
    $('#tf_rf_form_setup').submit(function () {
        normalizeCheckboxes();
        $("#thickboxpreview").html('');
        _return = true;
        if(jQuery('#tf_rf_formname_input').val() == ''){
            jQuery('#tf_rf_formname_input').css('borderColor','red');
            _return=false;
        } else {jQuery('#tf_rf_formname_input').removeAttr('style');}
        if(jQuery('#tf_rf_res_title').val() == ''){
            jQuery('#tf_rf_res_title').css('borderColor','red');
            _return=false;
        } else {jQuery('#tf_rf_res_title').removeAttr('style');}
        if(!_return) return _return;
        _inputs = $(this).find('input[type!="hidden"]');
        _url_vars = getUrlVars();
        isset_id = 'id' in _url_vars;
        _id = (isset_id) ? _url_vars['id'] : -1;
        if (isset_id) {
            simulate_submit($(this), _id);
            _form_ok = false;
        } else {
            create_shortcodes_inputs(this);
        }
        $(window).unbind("beforeunload");

        return _form_ok;
    });
    $('body').on('submit', '#TB_ajaxContent #reservationForm', function () {
        return false;
    });
    $('body').on('click', '.show_more_less', function () {
        _options_list = $(this).closest('.tf_custom_reservationform_row').find('.sortable_options:first');
        if (_options_list.is(':visible')) {
            _options_list.hide();
            $(this).text('+Show more');
        } else {
            _options_list.show();
            $(this).text('-Show less');
        }
    });
    $('.tf_rf_mail_type').change(function () {
        _checked = $('.tf_rf_mail_type:checked').val();
        _parent = $(this).closest('.option');
        _parent.attr('id', 'checkpoint');
        _sibling_options = $('#checkpoint ~ .option, .divider');
        if (_checked == 'wpmail') {
            _sibling_options.each(function () {
                $(this).hide();
            });
            _parent.removeAttr('id');
        } else {
            _sibling_options.each(function () {
                $(this).show();
            });
            _parent.removeAttr('id');
        }
        form_has_changed = true;
    });
    $("body").on("click", ".reservationform_delete_input", function () {
        _form = $(this).closest('form');
        _tr = $(this).closest(".tf_custom_reservationform_row");
        _inside = _tr.parent();
        if (_inside.find('.reservationform_delete_input').length > 1) {
            _tr.attr('id', 'row_to_delete')
            _siblings = $('#row_to_delete ~ .tf_custom_reservationform_row');
            _siblings.each(function () {
                    _checkboxes = $(this).find('.single_checkbox');
                    _checkboxes.each(function () {
                        _checkboxId = $(this).attr('id');
                        _baseId = _checkboxId.substring(0, _checkboxId.lastIndexOf('_') + 1);
                        count = _checkboxId.substring(_checkboxId.lastIndexOf('_') + 1, _checkboxId.length);
                        count--;
                        $(this).attr('id', _baseId + count);
                        $(this).attr('name', _baseId + count);
                        $(this).next('label').attr('for', _baseId + count);
                    });
                }
            );
            _tr.remove();
            _li = _inside.find('.reservationform_delete_input');
            if (_li.length == 1) {
                _li.each(function () {
                    $(this).removeAttr('class');
                    $(this).attr('class', 'reservationform_delete_input_last');
                });
            }
        }
        form_has_changed = true;
    });
    $('body').on('click', '.tf_cf_delete_option', function () {
        _li = $(this).closest('li');
        _li_count = $(this).closest('ul').find('li').length;
        if (_li_count > 1) {
            _li.remove();
            form_has_changed = true;
        }
    });
    $('.tf_rf_mail_type, .tf_rf_secure_conn').change(function () {
        form_has_changed = true;
    });
    $('#tf_rf_smtp_port , #tf_rf_smtp_user , #tf_rf_smtp_pwd').keyup(function () {
        form_has_changed = true;
    });
    $('.tf_trash_reservation').click(function () {
        _form_id = $(this).attr('rel');
        _tr = $(this).closest('tr');
        if (confirm('Are you sure you want to delete this reservation?')) {
            $.ajax({
                url:ajaxurl,
                type:'POST',
                data:{action:'tfuse_ajax_reservationform', tf_action:'trash_reservations', resid:_form_id},
                success:function (data) {
                    _tr.remove();
                }
            });
        }
    });
    $('.tf_delete_reservation').click(function () {
        _form_id = $(this).attr('rel');
        _tr = $(this).closest('tr');
        if (confirm('Are you sure you want to delete this reservation?')) {
            $.ajax({
                url:ajaxurl,
                type:'POST',
                data:{action:'tfuse_ajax_reservationform', tf_action:'delete_reservations', resid:_form_id},
                success:function (data) {
                    _tr.remove();
                }
            });
        }
    });
    $('.delete_selected_reservations').click(function () {
        _checkboxes = $('.checkbox_delete_reservation:checked');
        _form_ids = [];
        _trs = [];
        if (_checkboxes.length > 0) {
            if (confirm('Are you sure you want to delete these reservations?')) {
                _checkboxes.each(function () {
                    _form_ids.push($(this).val());
                    _trs.push($(this).closest('tr'));

                });
                $.ajax({
                    url:ajaxurl,
                    type:'POST',
                    data:{action:'tfuse_ajax_reservationform', tf_action:'delete_reservations', resid:_form_ids},
                    success:function (data) {
                        for (i in _trs) {
                            $(_trs[i]).remove();
                        }
                    }
                });
            }
        } else {
            alert('Select at least one row!');
        }
    });
    $('.trash_selected_reservations').click(function () {
        _checkboxes = $('.checkbox_delete_reservation:checked');
        _form_ids = [];
        _trs = [];
        if (_checkboxes.length > 0) {
            if (confirm('Are you sure you want to delete these reservations?')) {
                _checkboxes.each(function () {
                    _form_ids.push($(this).val());
                    _trs.push($(this).closest('tr'));

                });
                $.ajax({
                    url:ajaxurl,
                    type:'POST',
                    data:{action:'tfuse_ajax_reservationform', tf_action:'trash_reservations', resid:_form_ids},
                    success:function (data) {
                        for (i in _trs) {
                            $(_trs[i]).remove();
                        }
                    }
                });
            }
        } else {
            alert('Select at least one row!');
        }
    });
    $('.delete_selected_forms').click(function () {
        var _checkboxes = $('.checkbox_delete_form:checked');
        var _form_ids = [];
        var _trs = [];
        if (_checkboxes.length > 0) {
            if (confirm('Are you sure you want to delete these forms and your reservations?')) {
                _checkboxes.each(function () {
                    _form_ids.push($(this).val());
                    _trs.push($(this).closest('tr'));

                });
                $.ajax({
                    url:ajaxurl,
                    type:'POST',
                    data:{action:'tfuse_ajax_reservationform', tf_action:'delete_form', formid:_form_ids},
                    success:function (data) {
                        for (i in _trs) {
                            $(_trs[i]).remove();
                        }
                    }
                });
            }
        } else {
            alert('Select at least one row!');
        }
    });
    $('.tf_delete_reservation_form').click(function () {
        _trs= $(this).closest('tr');
        if (confirm('Are you sure you want to delete this form?')) {
            $.ajax({
                url:ajaxurl,
                type:'POST',
                data:{action:'tfuse_ajax_reservationform', tf_action:'delete_form', formid:$(this).attr('rel')},
                success:function (data) {
                    $(_trs).remove();
                }
            });
        }

    });
    $('body').on('click', '.excludedate_ok', function (event) {
        repeat = ['Do not repeat','Every week','Every month','Every year'];
        event.preventDefault();
        date_from = $(this).parent().find('.tf_exclude_from_datepicker:first').val();
        date_to = $(this).parent().find('.tf_exclude_to_datepicker:first').val();
        if($.trim(date_from) !='' && $.trim(date_to) !='' )
            content = 'From '+date_from +' to '+ date_to + ' |';
        else {
            if($.trim(date_to) !='' )
                content = date_to + ' |';
            if($.trim(date_from) !='')
                content = date_from + ' |';
            else if ($.trim(date_from) =='' && $.trim(date_to) =='')
                content='';
        }
        date_repeat = $(this).parent().find('.tf_exclude_repeat_datepicker:first').val();
        if(content != ''){
            content += (date_repeat !=0)?' Repeat ':' ';
            content += repeat[date_repeat];
        }

        _input = $(this).closest('.tf_custom_reservationform_dates_row').find('.tf_rf_exclude_interval:first');
        _input.val(content);
        $(this).parent().removeClass('current');
        $(this).parent().fadeOut("fast");
    });
    $('#rf_exclude_new_interval').click(function () {
        _parent = $('#rf_excludedates_elements');
        _div = _parent.find('.form-field:last');
        _class = _div.attr('class');
        _parent.append('<li class="' + _class + '">' + _div.html() + '</li>');
        init_datepickers();
    });
    $('.popbox').popbox();
    $('#tf_rf_form_setup input[type=text],#tf_rf_form_setup textarea').on('keyup', function () {
        form_has_changed = true;
    });
    $('body').on('click', '.tf_exclude_from_datepicker', function () {
        date_orientation = 'from';
    });
    $('body').on('click', '.tf_exclude_to_datepicker', function () {
        date_orientation = 'to';
    });
    $('body').on('click', '.reservationform_delete_excludedatesinput', function () {
        if ($('#rf_excludedates_elements').find('.tf_custom_reservationform_dates_row').length > 1)
            $(this).closest('.tf_custom_reservationform_dates_row').remove();
    });

    $('body').on('mouseover', '.tf_custom_reservationform_dates_row', function () {
        if ($(this).parent().find('.tf_custom_reservationform_dates_row').length > 1)
            $(this).find('.reservationform_delete_excludedatesinput').css('display', 'block')
    }).on('mouseout', '.tf_custom_reservationform_dates_row', function () {
        $(this).find('.reservationform_delete_excludedatesinput').css('display', 'none')
    });

    $('body').on('keyup', '.rf_input_width', function () {
        if ($(this).val() < 0) {
            $(this).val(0);
        } else if ($(this).val() > 100) {
            $(this).val(100);
        } else if (isNaN($(this).val())) {
            $(this).val(0);
        }
    });

    $('#tf_rf_datepickers_count').change(function(){
        _flags =$('#rf_form_elements .tfuse_is_datepicker_flag');
        if($(this).val() == 1){

            _flags.each(function(){
                if($(this).val() == 'out')
                    $(this).closest('.tf_custom_reservationform_row').remove();
            });
        } else {
            $.post(ajaxurl, {
                action:'tfuse_ajax_reservationform',
                tf_action:'ajax_datepicker_row',
                number:_flags.length,
                _ajax_nonce:tf_script.tf_reservationform_save
            }, function (data) {
                $('#rf_form_elements').append(data.html);
                $('#rf_form_elements').find('.tf_custom_reservationform_row:last label[for^="tf_rf_input_required"]').css('visibility','hidden');
                $('.rf_input_label').keyup();
            }, 'json');
        }
    });
    $('#tf_rf_confirm_reservation').click(function(){
        send_admin_email(1);
    });
    $('#tf_rf_reject_reservation').click(function(){
        send_admin_email(-1);
    });
    $('#tf_rf_send_message_reservation').click(function(){
        send_admin_email(0);
    });
    $('.approve_selected_reservations').click(function(){
        send_admin_emails(1);
    });
    $('.reject_selected_reservations').click(function(){
        send_admin_emails(-1);
    });
    $('#new_form_reset ,#messages_reset ,#gen_options_reset').click(function () {
        has_changes = false;
        window.location.href = 'admin.php?page=tf_reservation_forms_list';
    });
    $(window).bind('beforeunload', function () {
        if (form_has_changed == true)
            return 'You are about to leave the page without saving the changes.'
    });
    $('#tf_rf_gensett_form').submit(function () {
        $(window).unbind("beforeunload");
    });
    $('#tf_rf_form_name_select').change(function () {

    });
});
function send_admin_emails(type){
    var $ = jQuery;
    _checkboxes = $('.checkbox_delete_reservation:checked');
    _form_ids = [];
    types = (type == -1) ? 'reject' : 'approve';
    if (_checkboxes.length > 0) {
        if (confirm('Are you sure you want to '+types+' these reservation(s)?\nAn email will be sent to the user containing the template you have set in the form Messages Settings.')) {
            _checkboxes.each(function () {
                _form_ids.push($(this).val());
            });
            $.ajax({
                url:ajaxurl,
                type:'POST',
                data:{action:'tfuse_ajax_reservationform',status:type, tf_action:'ajax_send_multiple_emails', resid:_form_ids,_ajax_nonce:tf_script.tf_reservationform_save},
                success:function (data) {
                    location.reload();
                }
            });
        }
    } else {
        alert('Select at least one row!');
    }
}
function send_admin_email(type){
    var $ = jQuery;
    $('#tfuse-popup-save .tfuse-save-save').html('Email Sent');
    $('#tfuse-popup-fail .tfuse-save-reset').html('Email Not Sent');
    $_get = getUrlVars();
    showLoading();
    $.post(ajaxurl, {
        action:'tfuse_ajax_reservationform',
        tf_action:'ajax_send_email',
        message:$('#tfuse_rf_email').val(),
        status : type,
        message:$('#'+theme_index+'_email_message').val(),
        post_id:$_get['id'],
        _ajax_nonce:tf_script.tf_reservationform_save
    }, function (data) {
        if (data.error == false) {
            showFinishedLoading();
        } else if (data.error == true) {
            showFailLoading();
        }
    }, 'json');
}
function manageShortcodes(_current_one) {
    var $ = jQuery;
    if ($.trim(removeNonAlphaNumeric($(_current_one).val())) == '') return;
    _container = _current_one.closest('#rf_form_elements');
    _parent = _current_one.closest('.tf_custom_reservationform_row');
    _code = _parent.find('.tfuse_selectable_code:last');
    _value = _current_one.val();
    _clean_value = removeNonAlphaNumeric(_value);
    _code.html('[' + _clean_value.toLowerCase() + ']');
    _labels = _container.find('.rf_input_label');
    _repeat_count = 0;
    _labels.each(function () {
        if (removeNonAlphaNumeric($(this).val().toLowerCase()) == _clean_value.toLowerCase()) {
            _repeat_count++;
        }
    });
    if (_repeat_count > 1) {
        _sufix_to_add = 0;
        _labels.each(function () {
            _shortcode_tag = $(this).closest('.tf_custom_reservationform_row').find('.tfuse_selectable_code:last');
            if ((removeNonAlphaNumeric($(this).val().toLowerCase()) == _clean_value.toLowerCase())) {
                _sufix_to_add++;
                _shortcode_tag.html('[' + removeNonAlphaNumeric($(this).val().toLowerCase()) + '_' + _sufix_to_add + ']');
            }
        });

    }
}
function removeNonAlphaNumeric(_val) {
    var $ = jQuery;
    _val = $.trim(_val);
    _val = _val.replace(/ /g, ".");
    _val = tf_rf_latinize(_val);
    _val = _val.replace(/[^a-zA-Z0-9]+/g, "");
    _val = _val.replace(/\.+/g, "_");
    if (_val.charAt(0) == '_')
        _val = _val.substring(1, _val.length());
    _first_letters = /^[a-zA-Z].*/;
    if (!_first_letters.test(_val)) {
        _numbers = parseFloat(_val);
        _val = _val.replace(_numbers, '');
    }
    return _val;
}
function createFormPreview(_form) {
    var $ = jQuery;
    $.ajax({
        url:ajaxurl,
        type:'POST',
        data:{tf_form_:generatePostData(_form), tf_action:'form_preview', action:'tfuse_ajax_reservationform'},
        async:false,
        success:function (response, textStatus, XMLHttpRequest) {
            _container = $("#thickboxpreview");
            if (typeof _container != undefined) {
                _container.remove();
                $('.tf_meta_tabs').append('<div id="thickboxpreview" style="display:none">' + response + '</div>');
                tb_show('Form Preview', '#TB_inline?height=400&width=640&inlineId=thickboxpreview', null);
                $('.tfuse_rf_post_datepicker_in,.tfuse_rf_post_datepicker_out').datepicker();
                curr_height = $('#TB_ajaxContent').height();
                $('#TB_ajaxContent').height(curr_height + 80).lionbars();
                $(document).trigger('reservationform_preview');
                $('.radiolabel').click(function () {
                    $(this).closest('.multicheckbox').find('input[type="radio"]').click();
                });
            }


        },
        error:function () {
        }
    });
}
function prepare_fields(ob) {
    var $ = jQuery;
    inps = ob.find("input[id*=tf_rf_input]");
    inps.each(function () {
        if (!$(this).hasClass('single_checkbox')) {
            $(this).val('');
        }
    });
    _select = ob.find("select[id*=tf_rf_select]");
    _required = ob.find('.rf_input_required:last');
    _required.removeAttr('checked');
    _required.next('label').css('visibility', 'visible').removeClass('on');
    _newline = ob.find('.cf_input_newline:last');
    _newline.removeAttr('checked');
    _newline.next('label').removeClass('on');
    ob.find('.tf_rf_toggle_show').hide();
    ob.find('.shortcode_code').html('[]');
    ob.find('.tf_rf_toggle_show').html('<span class="show_more_less">-Show less</span>');
    _select.find('option:checked').removeAttr('selected');
    _select.find('option:first').attr('selected', 'selected');
    ob.find('input[id*=tf_rf_input_width]').val('50');
    _check = ob.find('.single_checkbox:checked');
    _check.each(function () {
        if ($(this).attr('checked'))
            $(this).removeAttr('checked');
    });
    _options = ob.find('.sortable_options');
    if (_options.length > 1) {
        _options.each(function () {
            _length = ob.find('.sortable_options');
            if (_length > 1) {
                $(this).remove();
            }
        });
        _option = ob.find('.sortable_options:last').find('input').val('');
    }
}
function sortStop() {
    var $ = jQuery;
    elements = $("#rf_form_elements .tf_custom_reservationform_row ");
    i = 0;
    elements.each(function () {
        inp_req = $(this).find('*[id*=tf_rf_input_required]:last');
        label_req = inp_req.next('label');
        inp_newline = $(this).find('*[id*=tf_rf_input_newline]:last');
        label_newline = inp_newline.next('label');
        new_required_attr = inp_req.attr('id').substr(0, inp_req.attr('id').lastIndexOf('_') + 1) + i;
        new_newline_attr = inp_newline.attr('id').substr(0, inp_newline.attr('id').lastIndexOf('_') + 1) + i;
        inp_req.attr('id', new_required_attr);
        inp_req.attr('name', new_required_attr);
        label_req.attr('for', new_required_attr);
        inp_newline.attr('id', new_newline_attr);
        inp_newline.attr('name', new_newline_attr);
        label_newline.attr('for', new_newline_attr);
        $(this).find('.sortable_options input').each(function () {
            $(this).attr('id', $(this).attr('id').substr(0, $(this).attr('id').indexOf('[') + 1) + i + '][]');
            $(this).attr('name', $(this).attr('name').substr(0, $(this).attr('name').indexOf('[') + 1) + i + '][]');
        });

        i++;
    });
}
function normalizeCheckboxes(){
    jQuery('.single_checkbox').each(function(){
        _default = jQuery(this).parent().find('.checkbox_default_hidden_value');
        if (_default.attr('name') !== undefined) {
            jQuery(this).attr('hiddenname', jQuery(this).attr('name'));
            jQuery(this).removeAttr('name');
        }
    });

}
function getUrlVars() {
    urlParams = {};
    var e,
        a = /\+/g,
        r = /([^&=]+)=?([^&]*)/g,
        d = function (s) {
            return decodeURIComponent(s.replace(a, " "));
        },
        q = window.location.search.substring(1);
    while (e = r.exec(q))
        urlParams[d(e[1])] = d(e[2]);
    return urlParams;
}
function simulate_submit(_form, _form_id) {
    var $ = jQuery;
    showLoading();
    pData = generatePostData(_form);
    $.post(ajaxurl, {
        action:'tfuse_ajax_reservationform',
        tf_action:'save_form',
        post_data:pData,
        get_id:_form_id,
        _ajax_nonce:tf_script.tf_reservationform_save
    }, function (data) {
        if (data.saved == 1) {
            showFinishedLoading();
            form_has_changed = false;
        } else if (data.saved == -1) {
            showFailLoading();
        }
    }, 'json');
}
function generatePostData(form_object) {
    create_shortcodes_inputs(form_object);
    return form_object.serialize();
}
function create_shortcodes_inputs(ob){
    $_inp = jQuery(ob).find('.tf_rf_shortcode_inp');
    $_inp.remove();
    _shortcodes = jQuery(ob).find('.shortcode_code');
    _shortcodes.each(function () {
        _val = jQuery(this).html();
        jQuery(this).append("<input type='text' class='hidden tf_rf_shortcode_inp' name='tf_rf_input_shortcode[]' value='" + _val.substring(1, _val.length - 1) + "'/>");
    });
}

function tf_rf_latinize(str) {
    return str.replace(/[^A-Za-z0-9]/g, function (x) {
        return tf_rf_latinize.characters[x] || x;
    });
}

tf_rf_latinize.characters = {
    'Á': 'A',
    'Ă': 'A',
    'Ắ': 'A',
    'Ặ': 'A',
    'Ằ': 'A',
    'Ẳ': 'A',
    'Ẵ': 'A',
    'Ǎ': 'A',
    'Â': 'A',
    'Ấ': 'A',
    'Ậ': 'A',
    'Ầ': 'A',
    'Ẩ': 'A',
    'Ẫ': 'A',
    'Ä': 'A',
    'Ǟ': 'A',
    'Ȧ': 'A',
    'Ǡ': 'A',
    'Ạ': 'A',
    'Ȁ': 'A',
    'À': 'A',
    'Ả': 'A',
    'Ȃ': 'A',
    'Ā': 'A',
    'Ą': 'A',
    'Å': 'A',
    'Ǻ': 'A',
    'Ḁ': 'A',
    'Ⱥ': 'A',
    'Ã': 'A',
    'Ꜳ': 'AA',
    'Æ': 'AE',
    'Ǽ': 'AE',
    'Ǣ': 'AE',
    'Ꜵ': 'AO',
    'Ꜷ': 'AU',
    'Ꜹ': 'AV',
    'Ꜻ': 'AV',
    'Ꜽ': 'AY',
    'Ḃ': 'B',
    'Ḅ': 'B',
    'Ɓ': 'B',
    'Ḇ': 'B',
    'Ƀ': 'B',
    'Ƃ': 'B',
    'Ć': 'C',
    'Č': 'C',
    'Ç': 'C',
    'Ḉ': 'C',
    'Ĉ': 'C',
    'Ċ': 'C',
    'Ƈ': 'C',
    'Ȼ': 'C',
    'Ď': 'D',
    'Ḑ': 'D',
    'Ḓ': 'D',
    'Ḋ': 'D',
    'Ḍ': 'D',
    'Ɗ': 'D',
    'Ḏ': 'D',
    'ǲ': 'D',
    'ǅ': 'D',
    'Đ': 'D',
    'Ƌ': 'D',
    'Ǳ': 'DZ',
    'Ǆ': 'DZ',
    'É': 'E',
    'Ĕ': 'E',
    'Ě': 'E',
    'Ȩ': 'E',
    'Ḝ': 'E',
    'Ê': 'E',
    'Ế': 'E',
    'Ệ': 'E',
    'Ề': 'E',
    'Ể': 'E',
    'Ễ': 'E',
    'Ḙ': 'E',
    'Ë': 'E',
    'Ė': 'E',
    'Ẹ': 'E',
    'Ȅ': 'E',
    'È': 'E',
    'Ẻ': 'E',
    'Ȇ': 'E',
    'Ē': 'E',
    'Ḗ': 'E',
    'Ḕ': 'E',
    'Ę': 'E',
    'Ɇ': 'E',
    'Ẽ': 'E',
    'Ḛ': 'E',
    'Ꝫ': 'ET',
    'Ḟ': 'F',
    'Ƒ': 'F',
    'Ǵ': 'G',
    'Ğ': 'G',
    'Ǧ': 'G',
    'Ģ': 'G',
    'Ĝ': 'G',
    'Ġ': 'G',
    'Ɠ': 'G',
    'Ḡ': 'G',
    'Ǥ': 'G',
    'Ḫ': 'H',
    'Ȟ': 'H',
    'Ḩ': 'H',
    'Ĥ': 'H',
    'Ⱨ': 'H',
    'Ḧ': 'H',
    'Ḣ': 'H',
    'Ḥ': 'H',
    'Ħ': 'H',
    'Í': 'I',
    'Ĭ': 'I',
    'Ǐ': 'I',
    'Î': 'I',
    'Ï': 'I',
    'Ḯ': 'I',
    'İ': 'I',
    'Ị': 'I',
    'Ȉ': 'I',
    'Ì': 'I',
    'Ỉ': 'I',
    'Ȋ': 'I',
    'Ī': 'I',
    'Į': 'I',
    'Ɨ': 'I',
    'Ĩ': 'I',
    'Ḭ': 'I',
    'Ꝺ': 'D',
    'Ꝼ': 'F',
    'Ᵹ': 'G',
    'Ꞃ': 'R',
    'Ꞅ': 'S',
    'Ꞇ': 'T',
    'Ꝭ': 'IS',
    'Ĵ': 'J',
    'Ɉ': 'J',
    'Ḱ': 'K',
    'Ǩ': 'K',
    'Ķ': 'K',
    'Ⱪ': 'K',
    'Ꝃ': 'K',
    'Ḳ': 'K',
    'Ƙ': 'K',
    'Ḵ': 'K',
    'Ꝁ': 'K',
    'Ꝅ': 'K',
    'Ĺ': 'L',
    'Ƚ': 'L',
    'Ľ': 'L',
    'Ļ': 'L',
    'Ḽ': 'L',
    'Ḷ': 'L',
    'Ḹ': 'L',
    'Ⱡ': 'L',
    'Ꝉ': 'L',
    'Ḻ': 'L',
    'Ŀ': 'L',
    'Ɫ': 'L',
    'ǈ': 'L',
    'Ł': 'L',
    'Ǉ': 'LJ',
    'Ḿ': 'M',
    'Ṁ': 'M',
    'Ṃ': 'M',
    'Ɱ': 'M',
    'Ń': 'N',
    'Ň': 'N',
    'Ņ': 'N',
    'Ṋ': 'N',
    'Ṅ': 'N',
    'Ṇ': 'N',
    'Ǹ': 'N',
    'Ɲ': 'N',
    'Ṉ': 'N',
    'Ƞ': 'N',
    'ǋ': 'N',
    'Ñ': 'N',
    'Ǌ': 'NJ',
    'Ó': 'O',
    'Ŏ': 'O',
    'Ǒ': 'O',
    'Ô': 'O',
    'Ố': 'O',
    'Ộ': 'O',
    'Ồ': 'O',
    'Ổ': 'O',
    'Ỗ': 'O',
    'Ö': 'O',
    'Ȫ': 'O',
    'Ȯ': 'O',
    'Ȱ': 'O',
    'Ọ': 'O',
    'Ő': 'O',
    'Ȍ': 'O',
    'Ò': 'O',
    'Ỏ': 'O',
    'Ơ': 'O',
    'Ớ': 'O',
    'Ợ': 'O',
    'Ờ': 'O',
    'Ở': 'O',
    'Ỡ': 'O',
    'Ȏ': 'O',
    'Ꝋ': 'O',
    'Ꝍ': 'O',
    'Ō': 'O',
    'Ṓ': 'O',
    'Ṑ': 'O',
    'Ɵ': 'O',
    'Ǫ': 'O',
    'Ǭ': 'O',
    'Ø': 'O',
    'Ǿ': 'O',
    'Õ': 'O',
    'Ṍ': 'O',
    'Ṏ': 'O',
    'Ȭ': 'O',
    'Ƣ': 'OI',
    'Ꝏ': 'OO',
    'Ɛ': 'E',
    'Ɔ': 'O',
    'Ȣ': 'OU',
    'Ṕ': 'P',
    'Ṗ': 'P',
    'Ꝓ': 'P',
    'Ƥ': 'P',
    'Ꝕ': 'P',
    'Ᵽ': 'P',
    'Ꝑ': 'P',
    'Ꝙ': 'Q',
    'Ꝗ': 'Q',
    'Ŕ': 'R',
    'Ř': 'R',
    'Ŗ': 'R',
    'Ṙ': 'R',
    'Ṛ': 'R',
    'Ṝ': 'R',
    'Ȑ': 'R',
    'Ȓ': 'R',
    'Ṟ': 'R',
    'Ɍ': 'R',
    'Ɽ': 'R',
    'Ꜿ': 'C',
    'Ǝ': 'E',
    'Ś': 'S',
    'Ṥ': 'S',
    'Š': 'S',
    'Ṧ': 'S',
    'Ş': 'S',
    'Ŝ': 'S',
    'Ș': 'S',
    'Ṡ': 'S',
    'Ṣ': 'S',
    'Ṩ': 'S',
    'ß': 'ss',
    'Ť': 'T',
    'Ţ': 'T',
    'Ṱ': 'T',
    'Ț': 'T',
    'Ⱦ': 'T',
    'Ṫ': 'T',
    'Ṭ': 'T',
    'Ƭ': 'T',
    'Ṯ': 'T',
    'Ʈ': 'T',
    'Ŧ': 'T',
    'Ɐ': 'A',
    'Ꞁ': 'L',
    'Ɯ': 'M',
    'Ʌ': 'V',
    'Ꜩ': 'TZ',
    'Ú': 'U',
    'Ŭ': 'U',
    'Ǔ': 'U',
    'Û': 'U',
    'Ṷ': 'U',
    'Ü': 'U',
    'Ǘ': 'U',
    'Ǚ': 'U',
    'Ǜ': 'U',
    'Ǖ': 'U',
    'Ṳ': 'U',
    'Ụ': 'U',
    'Ű': 'U',
    'Ȕ': 'U',
    'Ù': 'U',
    'Ủ': 'U',
    'Ư': 'U',
    'Ứ': 'U',
    'Ự': 'U',
    'Ừ': 'U',
    'Ử': 'U',
    'Ữ': 'U',
    'Ȗ': 'U',
    'Ū': 'U',
    'Ṻ': 'U',
    'Ų': 'U',
    'Ů': 'U',
    'Ũ': 'U',
    'Ṹ': 'U',
    'Ṵ': 'U',
    'Ꝟ': 'V',
    'Ṿ': 'V',
    'Ʋ': 'V',
    'Ṽ': 'V',
    'Ꝡ': 'VY',
    'Ẃ': 'W',
    'Ŵ': 'W',
    'Ẅ': 'W',
    'Ẇ': 'W',
    'Ẉ': 'W',
    'Ẁ': 'W',
    'Ⱳ': 'W',
    'Ẍ': 'X',
    'Ẋ': 'X',
    'Ý': 'Y',
    'Ŷ': 'Y',
    'Ÿ': 'Y',
    'Ẏ': 'Y',
    'Ỵ': 'Y',
    'Ỳ': 'Y',
    'Ƴ': 'Y',
    'Ỷ': 'Y',
    'Ỿ': 'Y',
    'Ȳ': 'Y',
    'Ɏ': 'Y',
    'Ỹ': 'Y',
    'Ź': 'Z',
    'Ž': 'Z',
    'Ẑ': 'Z',
    'Ⱬ': 'Z',
    'Ż': 'Z',
    'Ẓ': 'Z',
    'Ȥ': 'Z',
    'Ẕ': 'Z',
    'Ƶ': 'Z',
    'Ĳ': 'IJ',
    'Œ': 'OE',
    'ᴀ': 'A',
    'ᴁ': 'AE',
    'ʙ': 'B',
    'ᴃ': 'B',
    'ᴄ': 'C',
    'ᴅ': 'D',
    'ᴇ': 'E',
    'ꜰ': 'F',
    'ɢ': 'G',
    'ʛ': 'G',
    'ʜ': 'H',
    'ɪ': 'I',
    'ʁ': 'R',
    'ᴊ': 'J',
    'ᴋ': 'K',
    'ʟ': 'L',
    'ᴌ': 'L',
    'ᴍ': 'M',
    'ɴ': 'N',
    'ᴏ': 'O',
    'ɶ': 'OE',
    'ᴐ': 'O',
    'ᴕ': 'OU',
    'ᴘ': 'P',
    'ʀ': 'R',
    'ᴎ': 'N',
    'ᴙ': 'R',
    'ꜱ': 'S',
    'ᴛ': 'T',
    'ⱻ': 'E',
    'ᴚ': 'R',
    'ᴜ': 'U',
    'ᴠ': 'V',
    'ᴡ': 'W',
    'ʏ': 'Y',
    'ᴢ': 'Z',
    'á': 'a',
    'ă': 'a',
    'ắ': 'a',
    'ặ': 'a',
    'ằ': 'a',
    'ẳ': 'a',
    'ẵ': 'a',
    'ǎ': 'a',
    'â': 'a',
    'ấ': 'a',
    'ậ': 'a',
    'ầ': 'a',
    'ẩ': 'a',
    'ẫ': 'a',
    'ä': 'a',
    'ǟ': 'a',
    'ȧ': 'a',
    'ǡ': 'a',
    'ạ': 'a',
    'ȁ': 'a',
    'à': 'a',
    'ả': 'a',
    'ȃ': 'a',
    'ā': 'a',
    'ą': 'a',
    'ᶏ': 'a',
    'ẚ': 'a',
    'å': 'a',
    'ǻ': 'a',
    'ḁ': 'a',
    'ⱥ': 'a',
    'ã': 'a',
    'ꜳ': 'aa',
    'æ': 'ae',
    'ǽ': 'ae',
    'ǣ': 'ae',
    'ꜵ': 'ao',
    'ꜷ': 'au',
    'ꜹ': 'av',
    'ꜻ': 'av',
    'ꜽ': 'ay',
    'ḃ': 'b',
    'ḅ': 'b',
    'ɓ': 'b',
    'ḇ': 'b',
    'ᵬ': 'b',
    'ᶀ': 'b',
    'ƀ': 'b',
    'ƃ': 'b',
    'ɵ': 'o',
    'ć': 'c',
    'č': 'c',
    'ç': 'c',
    'ḉ': 'c',
    'ĉ': 'c',
    'ɕ': 'c',
    'ċ': 'c',
    'ƈ': 'c',
    'ȼ': 'c',
    'ď': 'd',
    'ḑ': 'd',
    'ḓ': 'd',
    'ȡ': 'd',
    'ḋ': 'd',
    'ḍ': 'd',
    'ɗ': 'd',
    'ᶑ': 'd',
    'ḏ': 'd',
    'ᵭ': 'd',
    'ᶁ': 'd',
    'đ': 'd',
    'ɖ': 'd',
    'ƌ': 'd',
    'ı': 'i',
    'ȷ': 'j',
    'ɟ': 'j',
    'ʄ': 'j',
    'ǳ': 'dz',
    'ǆ': 'dz',
    'é': 'e',
    'ĕ': 'e',
    'ě': 'e',
    'ȩ': 'e',
    'ḝ': 'e',
    'ê': 'e',
    'ế': 'e',
    'ệ': 'e',
    'ề': 'e',
    'ể': 'e',
    'ễ': 'e',
    'ḙ': 'e',
    'ë': 'e',
    'ė': 'e',
    'ẹ': 'e',
    'ȅ': 'e',
    'è': 'e',
    'ẻ': 'e',
    'ȇ': 'e',
    'ē': 'e',
    'ḗ': 'e',
    'ḕ': 'e',
    'ⱸ': 'e',
    'ę': 'e',
    'ᶒ': 'e',
    'ɇ': 'e',
    'ẽ': 'e',
    'ḛ': 'e',
    'ꝫ': 'et',
    'ḟ': 'f',
    'ƒ': 'f',
    'ᵮ': 'f',
    'ᶂ': 'f',
    'ǵ': 'g',
    'ğ': 'g',
    'ǧ': 'g',
    'ģ': 'g',
    'ĝ': 'g',
    'ġ': 'g',
    'ɠ': 'g',
    'ḡ': 'g',
    'ᶃ': 'g',
    'ǥ': 'g',
    'ḫ': 'h',
    'ȟ': 'h',
    'ḩ': 'h',
    'ĥ': 'h',
    'ⱨ': 'h',
    'ḧ': 'h',
    'ḣ': 'h',
    'ḥ': 'h',
    'ɦ': 'h',
    'ẖ': 'h',
    'ħ': 'h',
    'ƕ': 'hv',
    'í': 'i',
    'ĭ': 'i',
    'ǐ': 'i',
    'î': 'i',
    'ï': 'i',
    'ḯ': 'i',
    'ị': 'i',
    'ȉ': 'i',
    'ì': 'i',
    'ỉ': 'i',
    'ȋ': 'i',
    'ī': 'i',
    'į': 'i',
    'ᶖ': 'i',
    'ɨ': 'i',
    'ĩ': 'i',
    'ḭ': 'i',
    'ꝺ': 'd',
    'ꝼ': 'f',
    'ᵹ': 'g',
    'ꞃ': 'r',
    'ꞅ': 's',
    'ꞇ': 't',
    'ꝭ': 'is',
    'ǰ': 'j',
    'ĵ': 'j',
    'ʝ': 'j',
    'ɉ': 'j',
    'ḱ': 'k',
    'ǩ': 'k',
    'ķ': 'k',
    'ⱪ': 'k',
    'ꝃ': 'k',
    'ḳ': 'k',
    'ƙ': 'k',
    'ḵ': 'k',
    'ᶄ': 'k',
    'ꝁ': 'k',
    'ꝅ': 'k',
    'ĺ': 'l',
    'ƚ': 'l',
    'ɬ': 'l',
    'ľ': 'l',
    'ļ': 'l',
    'ḽ': 'l',
    'ȴ': 'l',
    'ḷ': 'l',
    'ḹ': 'l',
    'ⱡ': 'l',
    'ꝉ': 'l',
    'ḻ': 'l',
    'ŀ': 'l',
    'ɫ': 'l',
    'ᶅ': 'l',
    'ɭ': 'l',
    'ł': 'l',
    'ǉ': 'lj',
    'ſ': 's',
    'ẜ': 's',
    'ẛ': 's',
    'ẝ': 's',
    'ḿ': 'm',
    'ṁ': 'm',
    'ṃ': 'm',
    'ɱ': 'm',
    'ᵯ': 'm',
    'ᶆ': 'm',
    'ń': 'n',
    'ň': 'n',
    'ņ': 'n',
    'ṋ': 'n',
    'ȵ': 'n',
    'ṅ': 'n',
    'ṇ': 'n',
    'ǹ': 'n',
    'ɲ': 'n',
    'ṉ': 'n',
    'ƞ': 'n',
    'ᵰ': 'n',
    'ᶇ': 'n',
    'ɳ': 'n',
    'ñ': 'n',
    'ǌ': 'nj',
    'ó': 'o',
    'ŏ': 'o',
    'ǒ': 'o',
    'ô': 'o',
    'ố': 'o',
    'ộ': 'o',
    'ồ': 'o',
    'ổ': 'o',
    'ỗ': 'o',
    'ö': 'o',
    'ȫ': 'o',
    'ȯ': 'o',
    'ȱ': 'o',
    'ọ': 'o',
    'ő': 'o',
    'ȍ': 'o',
    'ò': 'o',
    'ỏ': 'o',
    'ơ': 'o',
    'ớ': 'o',
    'ợ': 'o',
    'ờ': 'o',
    'ở': 'o',
    'ỡ': 'o',
    'ȏ': 'o',
    'ꝋ': 'o',
    'ꝍ': 'o',
    'ⱺ': 'o',
    'ō': 'o',
    'ṓ': 'o',
    'ṑ': 'o',
    'ǫ': 'o',
    'ǭ': 'o',
    'ø': 'o',
    'ǿ': 'o',
    'õ': 'o',
    'ṍ': 'o',
    'ṏ': 'o',
    'ȭ': 'o',
    'ƣ': 'oi',
    'ꝏ': 'oo',
    'ɛ': 'e',
    'ᶓ': 'e',
    'ɔ': 'o',
    'ᶗ': 'o',
    'ȣ': 'ou',
    'ṕ': 'p',
    'ṗ': 'p',
    'ꝓ': 'p',
    'ƥ': 'p',
    'ᵱ': 'p',
    'ᶈ': 'p',
    'ꝕ': 'p',
    'ᵽ': 'p',
    'ꝑ': 'p',
    'ꝙ': 'q',
    'ʠ': 'q',
    'ɋ': 'q',
    'ꝗ': 'q',
    'ŕ': 'r',
    'ř': 'r',
    'ŗ': 'r',
    'ṙ': 'r',
    'ṛ': 'r',
    'ṝ': 'r',
    'ȑ': 'r',
    'ɾ': 'r',
    'ᵳ': 'r',
    'ȓ': 'r',
    'ṟ': 'r',
    'ɼ': 'r',
    'ᵲ': 'r',
    'ᶉ': 'r',
    'ɍ': 'r',
    'ɽ': 'r',
    'ↄ': 'c',
    'ꜿ': 'c',
    'ɘ': 'e',
    'ɿ': 'r',
    'ś': 's',
    'ṥ': 's',
    'š': 's',
    'ṧ': 's',
    'ş': 's',
    'ŝ': 's',
    'ș': 's',
    'ṡ': 's',
    'ṣ': 's',
    'ṩ': 's',
    'ʂ': 's',
    'ᵴ': 's',
    'ᶊ': 's',
    'ȿ': 's',
    'ɡ': 'g',
    'ᴑ': 'o',
    'ᴓ': 'o',
    'ᴝ': 'u',
    'ť': 't',
    'ţ': 't',
    'ṱ': 't',
    'ț': 't',
    'ȶ': 't',
    'ẗ': 't',
    'ⱦ': 't',
    'ṫ': 't',
    'ṭ': 't',
    'ƭ': 't',
    'ṯ': 't',
    'ᵵ': 't',
    'ƫ': 't',
    'ʈ': 't',
    'ŧ': 't',
    'ᵺ': 'th',
    'ɐ': 'a',
    'ᴂ': 'ae',
    'ǝ': 'e',
    'ᵷ': 'g',
    'ɥ': 'h',
    'ʮ': 'h',
    'ʯ': 'h',
    'ᴉ': 'i',
    'ʞ': 'k',
    'ꞁ': 'l',
    'ɯ': 'm',
    'ɰ': 'm',
    'ᴔ': 'oe',
    'ɹ': 'r',
    'ɻ': 'r',
    'ɺ': 'r',
    'ⱹ': 'r',
    'ʇ': 't',
    'ʌ': 'v',
    'ʍ': 'w',
    'ʎ': 'y',
    'ꜩ': 'tz',
    'ú': 'u',
    'ŭ': 'u',
    'ǔ': 'u',
    'û': 'u',
    'ṷ': 'u',
    'ü': 'u',
    'ǘ': 'u',
    'ǚ': 'u',
    'ǜ': 'u',
    'ǖ': 'u',
    'ṳ': 'u',
    'ụ': 'u',
    'ű': 'u',
    'ȕ': 'u',
    'ù': 'u',
    'ủ': 'u',
    'ư': 'u',
    'ứ': 'u',
    'ự': 'u',
    'ừ': 'u',
    'ử': 'u',
    'ữ': 'u',
    'ȗ': 'u',
    'ū': 'u',
    'ṻ': 'u',
    'ų': 'u',
    'ᶙ': 'u',
    'ů': 'u',
    'ũ': 'u',
    'ṹ': 'u',
    'ṵ': 'u',
    'ᵫ': 'ue',
    'ꝸ': 'um',
    'ⱴ': 'v',
    'ꝟ': 'v',
    'ṿ': 'v',
    'ʋ': 'v',
    'ᶌ': 'v',
    'ⱱ': 'v',
    'ṽ': 'v',
    'ꝡ': 'vy',
    'ẃ': 'w',
    'ŵ': 'w',
    'ẅ': 'w',
    'ẇ': 'w',
    'ẉ': 'w',
    'ẁ': 'w',
    'ⱳ': 'w',
    'ẘ': 'w',
    'ẍ': 'x',
    'ẋ': 'x',
    'ᶍ': 'x',
    'ý': 'y',
    'ŷ': 'y',
    'ÿ': 'y',
    'ẏ': 'y',
    'ỵ': 'y',
    'ỳ': 'y',
    'ƴ': 'y',
    'ỷ': 'y',
    'ỿ': 'y',
    'ȳ': 'y',
    'ẙ': 'y',
    'ɏ': 'y',
    'ỹ': 'y',
    'ź': 'z',
    'ž': 'z',
    'ẑ': 'z',
    'ʑ': 'z',
    'ⱬ': 'z',
    'ż': 'z',
    'ẓ': 'z',
    'ȥ': 'z',
    'ẕ': 'z',
    'ᵶ': 'z',
    'ᶎ': 'z',
    'ʐ': 'z',
    'ƶ': 'z',
    'ɀ': 'z',
    'ﬀ': 'ff',
    'ﬃ': 'ffi',
    'ﬄ': 'ffl',
    'ﬁ': 'fi',
    'ﬂ': 'fl',
    'ĳ': 'ij',
    'œ': 'oe',
    'ﬆ': 'st',
    'ₐ': 'a',
    'ₑ': 'e',
    'ᵢ': 'i',
    'ⱼ': 'j',
    'ₒ': 'o',
    'ᵣ': 'r',
    'ᵤ': 'u',
    'ᵥ': 'v',
    'ₓ': 'x',
    "Ё":"YO",
    "Й":"I",
    "Ц":"TS",
    "У":"U",
    "К":"K",
    "Е": "E",
    "Н": "N",
    "Г": "G",
    "Ш": "SH",
    "Щ": "SCH",
    "З": "Z",
    "Х": "H",
    "Ъ": "'",
    "ё": "yo",
    "й": "i",
    "ц": "ts",
    "у": "u",
    "к": "k",
    "е": "e",
    "н": "n",
    "г": "g",
    "ш": "sh",
    "щ": "sch",
    "з": "z",
    "х": "h",
    "ъ": "'",
    "Ф": "F",
    "Ы": "I",
    "В": "V",
    "А": "a",
    "П": "P",
    "Р": "R",
    "О": "O",
    "Л": "L",
    "Д": "D",
    "Ж": "ZH",
    "Э": "E",
    "ф": "f",
    "ы": "i",
    "в": "v",
    "а": "a",
    "п": "p",
    "р": "r",
    "о": "o",
    "л": "l",
    "д": "d",
    "ж": "zh",
    "э": "e",
    "Я": "Ya",
    "Ч": "CH",
    "С": "S",
    "М": "M",
    "И": "I",
    "Т": "T",
    "Ь": "'",
    "Б": "B",
    "Ю": "YU",
    "я": "ya",
    "ч": "ch",
    "с": "s",
    "м": "m",
    "и": "i",
    "т": "t",
    "ь": "'",
    "б": "b",
    "ю": "yu"
};