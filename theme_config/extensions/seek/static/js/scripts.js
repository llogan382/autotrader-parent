jQuery(document).ready(function()
{
    tf_reset_jsp();
    make_selected_item_default();
    set_multi_select_z_index()
});

function add_style_for_multi_select(itemId){

    $('.multi_select.' + itemId + ' .multi_select_box').jScrollPane({
        showArrows: true,
        mouseWheelSpeed: 15,
        autoReinitialise: true,
        verticalDragMinHeight: 50
    });
    $('.mutli_select_box.' + itemId + ' input').customInput();
}

function add_style_for_sensitive_multi_select(itemId){

    $('.mutli_select.' + itemId + ' .mutli_select_box').removeClass('jspScrollable').removeData().jScrollPane({
        showArrows: true,
        mouseWheelSpeed: 5,
        autoReinitialise: true,
        verticalDragMinHeight: 50
    });
    $('.multi_select_box.' + itemId + ' input').customInput();
}

function change_values_for_multi_select(itemId){
    var checked_terms = [];
    $('.field_multiselect .' + itemId + ' input:checked').each(function(){
        checked_terms.push(parseInt($(this).val()));
    });

    $('.multi_select_taxonomy.' + itemId ).val((checked_terms.length) ? checked_terms.join(';') : 'all').trigger('terms_changed', {terms: checked_terms});
}

function make_selected_item_default()
{
    jQuery('.field_multiselect.closable .select_row label').bind('click', function()
    {
        var current = jQuery(this);

        current.parents('.multi_select_box').prev('.multi_select_text').text(current.text());
        current.parents('.field_multiselect.closable').removeClass('open');
    });
}

function tf_reset_jsp()
{
    var body = jQuery('body *');
    body.addClass('display_block');
    jQuery('.multi_select_box .jspContainer').each(function()
    {
        var obj= jQuery(this);
        obj.height(obj.children('.jspPane').height());
    });
    body.removeClass('display_block');
}

function set_multi_select_z_index()
{
    var counterIndex = 1000;
    jQuery('.search_row form .multi_select').each(function(){
        jQuery(this).css('z-index', counterIndex--);
    });
}