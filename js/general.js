function setCookie(c_name,value,exdays)
{
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value=value + ((exdays==null) ? "" : "; expires="+exdate.toUTCString()+"; path=/;");
    document.cookie=c_name + "=" + c_value;
}
function getCookie(c_name)
{
    var i,x,y,ARRcookies=document.cookie.split(";");
    var result = false;
    for (i=0;i<ARRcookies.length;i++)
    {
        x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
        y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
        x=x.replace(/^\s+|\s+$/g,"");
        if (x==c_name)
        {
            result = y;
        }
    }
    return result;
}
jQuery(document).ready(function() {
    // for show more element in header position (vehicle details)
    ul_width = jQuery('.offer_data ul').width();
    sum = 0;
    jQuery('.offer_data ul').find('li').each(function(){
        el_width =  jQuery(this).outerWidth(true);
        sum += el_width;
        if(sum > ul_width){
            sum = el_width;
            jQuery(this).addClass('new_line_li');
        }
    });

    $(".multi_select_text").click(function(){

        $(".cusel").removeClass("cuselOpen");
        $(".cusel-scroll-wrap").hide();
        jQuery(this).parent().parent().toggleClass("open");
        var thisPositionY = jQuery(this).parent().offset().top;
        jQuery(".multi_select").each(function(){
            if(jQuery(this).parent().offset().top != thisPositionY){
                jQuery(this).parent().parent().removeClass('open');
            }
        });
    });

    //for simple for form search
    jQuery('#tf-seek-form-main_search').submit(function(){
        if(jQuery(this).find('.adv_search_hidden').css('display') == 'none'){
            jQuery(this).find('.adv_search_hidden').remove();
        }
    });

    jQuery('form').submit(function(){
        jQuery('.multi_select_box .select_row input[type="checkbox"]').remove();
    });
    //for reset checkbox CF and RF
    jQuery('a.link-reset').on('click',function(){
        jQuery('label.labelchecked').removeClass('checked');
    });
    // add class last box for last widget box
    jQuery('.sidebar .box:last').addClass('last_box').append('<div class="box_bot"></div>');
    // add testimonials class for after shortcode when is testimonials in footer
    jQuery('.footer_testimonials').parent().parent().addClass('testimonials');
    // add class row_gray for shortcode car_types
    jQuery('.car_types_list').parent().parent().addClass('row_gray');
    // add class brand_list for shorcode brand_list
    jQuery('.brand_list').parent().parent().addClass('brand_list');
    // add class mega-nav-widget for widget text in MG
    jQuery('#topmenu .mega-nav-widget').parent().addClass('mega-nav-widget');
});

maps=[];
var $ = jQuery;
// Topmenu <ul> replace to <select>
function responsive(mainNavigation) {
	var $ = jQuery;
	var screenRes = $('.body_wrap').width();
	
	if (screenRes < 790) {
		jQuery('ul.dropdown').css('display', 'none');
        jQuery('#topm-select').css('display', 'inline-block');
        if(jQuery('#topm-select').length==0){

			/* Replace unordered list with a "select" element to be populated with options, and create a variable to select our new empty option menu */
			$('#topmenu').append('<select class="select_styled" id="topm-select"></select>');
			var selectMenu = $('#topm-select');

			/* Navigate our nav clone for information needed to populate options */
			$(mainNavigation).children('ul').children('li').each(function () {

				/* Get top-level link and text */
				var href = $(this).children('a').attr('href');
				var text = $(this).children('a').text();

				/* Append this option to our "select" */
				if ($(this).is(".current-menu-item") && href != '#') {
					$(selectMenu).append('<option value="' + href + '" selected>' + text + '</option>');
				} else if (href == '#') {
					$(selectMenu).append('<option value="' + href + '" disabled="disabled">' + text + '</option>');
				} else {
					$(selectMenu).append('<option value="' + href + '">' + text + '</option>');
				}

				/* Check for "children" and navigate for more options if they exist */
				if ($(this).children('ul').length > 0) {
					$(this).children('ul').children('li').not(".mega-nav-widget").each(function () {

						/* Get child-level link and text */
						var href2 = $(this).children('a').attr('href');
						var text2 = $(this).children('a').text();

						/* Append this option to our "select" */
						if ($(this).is(".current-menu-item") && href2 != '#') {
							$(selectMenu).append('<option value="'+href2+'" selected> - '+text2+'</option>');
						} else if (href2 == '#') {
							$(selectMenu).append('<option value="'+href2+'" disabled="disabled"># '+text2+'</option>');
						} else {
							$(selectMenu).append('<option value="'+href2+'"> - '+text2+'</option>');
						}

						/* Check for "children" and navigate for more options if they exist */
						if ($(this).children('ul').length > 0) {
							$(this).children('ul').children('li').each(function () {

								/* Get child-level link and text */
								var href3 = $(this).children('a').attr('href');
								var text3 = $(this).children('a').text();

								/* Append this option to our "select" */
								if ($(this).is(".current-menu-item")) {
									$(selectMenu).append('<option value="' + href3 + '" class="select-current" selected>' + text3 + '</option>');
								} else {
									$(selectMenu).append('<option value="' + href3 + '"> -- ' + text3 + '</option>');
								}

							});
						}
					});
				}
			});
        }
        else{
            jQuery('.select_styled2').css('display', 'inline-block');
        }
	} else {
		jQuery('#topm-select').css('display', 'none');
        jQuery('ul.dropdown').css('display', 'inline-block');
	}

	/* When our select menu is changed, change the window location to match the value of the selected option. */
	$(selectMenu).change(function () {
		location = this.options[this.selectedIndex].value;
	});
}

jQuery(document).ready(function($) {
    // Close field multiselect if you click outside
    function multiSelectCloseOutsideClick() {
        var mouse_is_inside = false;

        $('.multi_select').hover(function(){
            mouse_is_inside=true;
        }, function(){
            mouse_is_inside=false;
        });

        $("body").mouseup(function(){
            if(! mouse_is_inside){
                $(".field_multiselect").removeClass("open");
            }
        });
    }
    multiSelectCloseOutsideClick();

    // Stop propagation for multiselect field
    $('.field_multiselect, .field_multiselect .select_row').click(function(event){
        event.stopPropagation();
    });

    // Disable Empty Links
    $('a[href="#"]').click(function (event) {
        event.preventDefault();
    });

    // sort vehicles by ...
    jQuery(document).on('change','.form_sort #sort_list', function(e){
        var address = jQuery('#tax_permalink').attr("value");
        if(!address) return;
        var selected = jQuery(this).val();
        var prefix = '&';
        if (address.indexOf('?') == -1) prefix = '?';
        var suffix = '';
        switch (selected)
        {
            case '1' :
                suffix += 'order_by=date&order=DESC';
                break;
            case '2' :
                suffix += 'order_by=price&order=DESC';
                break;
            case '3' :
                suffix += 'order_by=price&order=ASC';
                break;
            case '4' :
                suffix += 'order_by=title&order=ASC';
                break;
            case '5' :
                suffix += 'order_by=title&order=DESC';
                break;
        }
        if (suffix != '') window.location = address + prefix + suffix;
    });
    // jump to page
    jQuery('#jumptopage_submit').bind('click', function(){
        var address = jQuery('#tax_permalink').attr("value");
        if(!address) return;
        var page = jQuery(this).prev('.inputSmall').val();
        var num_pages = jQuery('#tax_results').attr("num_pages");
        var order = jQuery('#tax_results').attr("get_order");
        var order_by = jQuery('#tax_results').attr("get_order_by");
        var prefix = '&';
        if (address.indexOf('?') == -1) prefix = '?';
        var suffix = '';
        if (order_by) suffix += 'order_by=' + order_by;
        if (order) suffix += '&order=' + order;

        page = page.match(/\d+$/);
        page = parseInt(page, 10);
        suffix += '&page=' + page;

        window.location = address + prefix + suffix;
        return false;
    });
    //prev next link to pagination
    jQuery('.list_manage .pages .link_prev, .tf_seek_pagination .page_prev').bind('click', function(){
        var address = jQuery('#tax_permalink').attr("value");
        if(!address) return;
        var page = jQuery('#tax_results').attr("page");
        var num_pages = jQuery('#tax_results').attr("num_pages");
        var order = jQuery('#tax_results').attr("get_order");
        var order_by = jQuery('#tax_results').attr("get_order_by");
        if(jQuery(this).attr("rel") == "first") return false;
        if (page == 0 || page == 1) return false;
        var prefix = '&';
        if (address.indexOf('?') == -1) prefix = '?';
        var suffix = '';
        if (order_by) suffix += 'order_by=' + order_by;
        if (order) suffix += '&order=' + order;

        page = page.match(/\d+$/);
        page = parseInt(page, 10);
        page--;
        suffix += '&page=' + page;

        window.location = address + prefix + suffix;
        return false;
    });

    jQuery('.list_manage .pages .link_next, .tf_seek_pagination .page_next').bind('click', function(){
        var address = jQuery('#tax_permalink').attr("value");
        if(!address) return;
        var page = jQuery('#tax_results').attr("page");
        var num_pages = jQuery('#tax_results').attr("num_pages");
        var order = jQuery('#tax_results').attr("get_order");
        var order_by = jQuery('#tax_results').attr("get_order_by");
        if(jQuery(this).attr("rel") == "last") return false;
        if (page == num_pages ) return false;
        var prefix = '&';
        if (address.indexOf('?') == -1) prefix = '?';
        var suffix = '';
        if (order_by) suffix += 'order_by=' + order_by;
        if (order) suffix += '&order=' + order;

        page = page.match(/\d+$/);
        page = parseInt(page, 10);
        if (page == 0) page++;
        page++;
        suffix += '&page=' + page;

        window.location = address + prefix + suffix;
        return false;
    });
    // jump to page (seek_pagination)
    jQuery('.tf_seek_pagination a.page-numbers').bind('click', function(){
        var address = jQuery('#tax_permalink').attr("value");
        if(!address) return;
        var page = jQuery(this).attr('href');
        var num_pages = jQuery('#tax_results').attr("num_pages");
        var order = jQuery('#tax_results').attr("get_order");
        var order_by = jQuery('#tax_results').attr("get_order_by");
        var prefix = '&';
        if (address.indexOf('?') == -1) prefix = '?';
        var suffix = '';
        if (order_by) suffix += 'order_by=' + order_by;
        if (order) suffix += '&order=' + order;

        page = page.match(/\d+$/);
        page = parseInt(page, 10);
        suffix += '&page=' + page;

        window.location = address + prefix + suffix;
        return false;
    });
    // save vehicle in cookie
    jQuery('.offer_price').delegate('.link-save','click', function(){
        var id = jQuery(this).attr("rel");
        var saved_prop = getCookie('favorite_posts');
        if(saved_prop)saved_prop = saved_prop.split(',');
        else saved_prop = [];
        var pos = jQuery.inArray(id,saved_prop);
        saved_prop.push(id);
        if(saved_prop.length != 1){
            jQuery('.bookmarker a').html('<span>' + saved_prop.length + '</span> ' + tfuse_translations.seek_post_plural);
        }else{
            jQuery('.bookmarker a').html('<span>' + saved_prop.length + '</span> ' + tfuse_translations.seek_post_singular);
        }
        if(saved_prop.length){
            jQuery('.bookmarker').css("display", "block");
        }else{
            jQuery('.bookmarker').css("display", "none");
        }
        saved_prop = saved_prop.join();
        setCookie('favorite_posts', saved_prop, 366);
        jQuery(this).removeClass('link-save').addClass('link-saved').attr('title',tfuse_translations.remove_offer).html(tfuse_translations.remove_offer);
        return false;
    });
    // delete a favorite vehicle
    jQuery('.offer_price').delegate('.link-saved','click', function(){
        var id = jQuery(this).attr("rel");
        var saved_prop = getCookie('favorite_posts');
        if(saved_prop)saved_prop = saved_prop.split(',');
        else saved_prop = [];
        var pos = jQuery.inArray(id,saved_prop);
        if(pos != -1)
        {
            saved_prop = jQuery.grep(saved_prop, function(value) {
                return value != id;
            });
        }
        if(saved_prop.length != 1){
            jQuery('.bookmarker a').html('<span>' + saved_prop.length + '</span> ' + tfuse_translations.seek_post_plural);
        }else{
            jQuery('.bookmarker a').html('<span>' + saved_prop.length + '</span> ' + tfuse_translations.seek_post_singular);
        }
        if(saved_prop.length){
            jQuery('.bookmarker').css("display", "block");
        }else{
            jQuery('.bookmarker').css("display", "none");
        }
        saved_prop = saved_prop.join();
        setCookie('favorite_posts', saved_prop, 366);
        jQuery(this).removeClass('link-saved').addClass('link-save').attr('title', tfuse_translations.save_offer).html(tfuse_translations.save_offer);
        return false;
    });

    // Remove links outline in IE 7
	$("a").attr("hideFocus", "true").css("outline", "none");


// style Select, Radio, Checkbox
		var deviceAgent = navigator.userAgent.toLowerCase();
		var agentID = deviceAgent.match(/(iphone|ipod|ipad)/);
		if (agentID) {
        cuSel({changedEl: "select.select_styled:not(.cusel)", visRows: 8, scrollArrows: true});	 // Add arrows Up/Down for iPad/iPhone
		} else {
        cuSel({changedEl: "select.select_styled:not(.cusel)", visRows: 8, scrollArrows: true});
		}		
    
	if ($("div,p").hasClass("input_styled")) {
		$(".input_styled input").customInput();
	}

// centering dropdown submenu (not mega-nav)
	$(".dropdown > li:not(.mega-nav)").hover(function(){
		var dropDown = $(this).children("ul");
		var dropDownLi = $(this).children().children("li").innerWidth();		
		var posLeft = ((dropDownLi - $(this).innerWidth())/2);
		dropDown.css("left",-posLeft);		
	});	
	
// reload topmenu on Resize
	var mainNavigation = $('#topmenu').clone();
	responsive(mainNavigation);
	
    $(window).resize(function() {		
        var screenRes = $('.body_wrap').width();
        responsive(mainNavigation);
    });	
	
// responsive megamenu
	var screenRes = $(window).width();   
	
    if (screenRes < 750) {
		$(".dropdown li.mega-nav").removeClass("mega-nav");		
	} 
	if (screenRes > 750) {				
		mega_show();		
    } 		
	
	function mega_show(){		
		$('.dropdown li').hoverIntent({
			sensitivity: 5,
			interval: 50, 
			over: subm_show, 
			timeout: 0, 
			out: subm_hide
		});
	}
	function subm_show(){	
		if ($(this).hasClass("parent")) {
			$(this).addClass("parentHover");
		};		
		$(this).children("ul.submenu-1").fadeIn(50);		
	}
	function subm_hide(){ 
		$(this).removeClass("parentHover");
		$(this).children("ul.submenu-1").fadeOut(50);		
	}
		
	$(".dropdown ul").parent("li").addClass("parent");
	$(".dropdown li:first-child, .pricing_box li:first-child, .sidebar .widget-container:first-child, .f_col .widget-container:first-child").addClass("first");
	$(".dropdown li:last-child, .pricing_box li:last-child, .widget_twitter .tweet_item:last-child, .sidebar .widget-container:last-child, .f_col .widget-container li:last-child").addClass("last");
	$(".dropdown li:only-child").removeClass("last").addClass("only");	
	$(".sidebar .current-menu-item, .sidebar .current-menu-ancestor").prev().addClass("current-prev");				
	
// tabs		
	if ($("ul").hasClass("tabs")) {		
		$("ul.tabs").tabs("> .tabcontent", {tabs: 'li', effect: 'fade'});	
	}
	if ($("ul").is(".tabs.linked")) {		
		$("ul.tabs").tabs("> .tabcontent", {effect: 'fade'});
	}
	
// odd/even
	$("ul.recent_posts > li:odd, ul.popular_posts > li:odd, .styled_table table>tbody>tr:odd, .boxed_list > .boxed_item:odd, .grid_layout .post-item:odd").addClass("odd");
	$(".widget_recent_comments ul > li:even, .widget_recent_entries li:even, .widget_twitter .tweet_item:even, .widget_archive ul > li:even, .widget_categories ul > li:even, .widget_nav_menu ul > li:even, .widget_links ul > li:even, .widget_meta ul > li:even, .widget_pages ul > li:even, .offer_specification li:even").addClass("even");
	
// cols
	$(".row .col:first-child").addClass("alpha");
	$(".row .col:last-child").addClass("omega");

// toggle content
	$(".toggle_content").hide(); 	
	$(".toggle").toggle(function(){
		$(this).addClass("active");
		}, function () {
		$(this).removeClass("active");
	});
	
	$(".toggle").click(function(){
		$(this).next(".toggle_content").slideToggle(300,'easeInQuad');
	});

// pricing
	if (screenRes > 750) {
		// style 2
		$(".pricing_box ul").each(function () {
			$(".pricing_box .price_col").css('width',$(".pricing_box ul").width() / $(".pricing_box .price_col").size() - 10);			
		});
		
		var table_maxHeight = -1;
		$('.price_item .price_col_body ul').each(function() {
			table_maxHeight = table_maxHeight > $(this).height() ? table_maxHeight : $(this).height();
		});
		$('.price_item .price_col_body ul').each(function() {
			$(this).height(table_maxHeight);
		});	
	} 
	
// buttons	
		$(".btn, .post-share a, .btn-submit").hover(function(){
			$(this).stop().animate({"opacity": 0.80});
		},function(){
			$(this).stop().animate({"opacity": 1});
		});	

// Smooth Scroling of ID anchors	
  function filterPath(string) {
  return string
    .replace(/^\//,'')
    .replace(/(index|default).[a-zA-Z]{3,4}$/,'')
    .replace(/\/$/,'');
  }
  var locationPath = filterPath(location.pathname);
  var scrollElem = scrollableElement('html', 'body');
 
  $('a[href*="#"].anchor').each(function() {
    $(this).click(function(event) {
    var thisPath = filterPath(this.pathname) || locationPath;
    if (  locationPath == thisPath
    && (location.hostname == this.hostname || !this.hostname)
    && this.hash.replace(/#/,'') ) {
      var $target = $(this.hash), target = this.hash;
      if (target && $target.length != 0) {
        var targetOffset = $target.offset().top;
          event.preventDefault();
          $(scrollElem).animate({scrollTop: targetOffset}, 400, function() {
            location.hash = target;
          });
      }
    }
   });	
  });
 
  // use the first element that is "scrollable"
  function scrollableElement(els) {
    for (var i = 0, argLength = arguments.length; i <argLength; i++) {
      var el = arguments[i],
          $scrollElement = $(el);
      if ($scrollElement.scrollTop()> 0) {
        return el;
      } else {
        $scrollElement.scrollTop(1);
        var isScrollable = $scrollElement.scrollTop()> 0;
        $scrollElement.scrollTop(0);
        if (isScrollable) {
          return el;
        }
      }
    }
    return [];
  }
  
	// prettyPhoto lightbox, check if <a> has atrr data-rel and hide for Mobiles
	if($('a').is('[data-rel]') && screenRes > 600) {
        $('a[data-rel]').each(function() {
			$(this).attr('rel', $(this).data('rel'));
		});		
		$("a[rel^='prettyPhoto']").prettyPhoto({
            social_tools:false,
            deeplinking:false
        });
    }
	  
});

$(window).load(function() {
    var $=jQuery;
// mega dropdown menu	
	$('.dropdown .mega-nav > ul.submenu-1').each(function(){  
		var liItems = $(this);
		var Sum = 0;
		var liHeight = 0;
		if (liItems.children('li').length > 1){
			$(this).children('li').each(function(i, e){
				Sum += $(e).outerWidth(true);
			});
			$(this).width(Sum);
			liHeight = $(this).innerHeight();	
			$(this).children('li').css({"height":liHeight-30});					
		}
		var posLeft = 0;
		var halfSum = Sum/2;
		var screenRes = $(window).width();	
		if (screenRes > 960) {
			var mainWidth = 940; // width of main container to fit in.
		} else {
			var mainWidth = 744; // for iPad.
		}
		var parentWidth = $(this).parent().width();			
		var margLeft = $(this).parent().position();		
		margLeft = margLeft.left;		
		var margRight = mainWidth - margLeft - parentWidth;		
		var subCenter = halfSum - parentWidth/2;						
		if (margLeft >= halfSum && margRight >= halfSum) {			
			liItems.css("left",-subCenter);
		} else if (margLeft<halfSum) {
			liItems.css("left",-margLeft-1);
		} else if (margRight<halfSum) {
			posLeft = Sum - margRight - parentWidth - 10;
			liItems.css("left",-posLeft);					
		}
	});	
});