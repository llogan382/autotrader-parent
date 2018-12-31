function custom_generator_banner_slider(type,options) {
    shortcode='[banner_slider target="'+options['target']+'"]';
    for(i in options.array) {
        shortcode+="[bslide image='"+options.array[i]["image"]+"' url='" + options.array[i]["url"] +"'"+"][/bslide]";
    }
    shortcode+='[/banner_slider]';
    return shortcode;
}

function custom_obtainer_banner_slider(data) {
    cont=jQuery('.tf_shortcode_option:visible');
    sh_options={};
    sh_options['array']=[];
    sh_options['target']= opt_get('tf_shc_banner_slider_target',cont);

    cont.find('[name="tf_shc_banner_slider_image"]').each(function(i)
    {
        div=jQuery(this).parents('.option');
        image=opt_get(jQuery(this).attr('name'),div);

        div=jQuery(this).parents('.option').nextAll('.option').find('[name="tf_shc_banner_slider_url"]').first().parents('.option');
        url=opt_get(jQuery(this).parents('.option').nextAll('.option').find('[name="tf_shc_banner_slider_url"]').first().attr('name'),div);

        tmp={};

        tmp['url']=url;
        tmp['image']=image;

        sh_options['array'].push(tmp);
    });

    return sh_options;
}

function custom_generator_vehicle_types(title,options) {
    shortcode='[vehicle_types title="'+options['title']+'"]';
    for(i in options.array) {
        shortcode+="[vehicle_type category='"+options.array[i]["category"]+"' image1='" + options.array[i]["image1"] +"' image2='" + options.array[i]["image2"] +"'"+"][/vehicle_type]";
    }
    shortcode+='[/vehicle_types]';
    return shortcode;
}

function custom_obtainer_vehicle_types(data) {
    cont=jQuery('.tf_shortcode_option:visible');
    sh_options={};
    sh_options['array']=[];
    sh_options['title']= opt_get('tf_shc_vehicle_types_title',cont);

    cont.find('[name="tf_shc_vehicle_types_category"]').each(function(i)
    {
        div=jQuery(this).parents('.option');
        category=opt_get(jQuery(this).attr('name'),div);

        div=jQuery(this).parents('.option').nextAll('.option').find('[name="tf_shc_vehicle_types_image1"]').first().parents('.option');
        image1=opt_get(jQuery(this).parents('.option').nextAll('.option').find('[name="tf_shc_vehicle_types_image1"]').first().attr('name'),div);
        div=jQuery(this).parents('.option').nextAll('.option').find('[name="tf_shc_vehicle_types_image2"]').first().parents('.option');
        image2=opt_get(jQuery(this).parents('.option').nextAll('.option').find('[name="tf_shc_vehicle_types_image2"]').first().attr('name'),div);
        tmp={};

        tmp['category']=category;
        tmp['image1']=image1;
        tmp['image2']=image2;

        sh_options['array'].push(tmp);
    });

    return sh_options;
}

function custom_generator_slideshow(type,options) {
    shortcode='[slideshow type_size="'+options['type_size']+'"]';
    for(i in options.array) {
        shortcode+="[slide type='"+options.array[i]["type"]+"' content='" + options.array[i]["content"] +"'"+"][/slide]";
    }
    shortcode+='[/slideshow]';
    return shortcode;
}

function custom_obtainer_slideshow(data) {
    cont=jQuery('.tf_shortcode_option:visible');
    sh_options={};
    sh_options['array']=[];
    sh_options['type_size']= opt_get('tf_shc_slideshow_type_size',cont);

    cont.find('[name="tf_shc_slideshow_type"]').each(function(i)
    {
        div=jQuery(this).parents('.option');
        type=opt_get(jQuery(this).attr('name'),div);

        div=jQuery(this).parents('.option').nextAll('.option').find('[name="tf_shc_slideshow_content"]').first().parents('.option');
        content=opt_get(jQuery(this).parents('.option').nextAll('.option').find('[name="tf_shc_slideshow_content"]').first().attr('name'),div);

        tmp={};

        tmp['type']=type;
        tmp['content']=content;

        sh_options['array'].push(tmp);
    });

    return sh_options;
}

function custom_generator_faq(type,options) {
    var shortcode='[faq title="'+options.title+'"]';
    for(var i in options.array) {
        shortcode+='[faq_question]'+options.array[i]['question']+'[/faq_question]';
        shortcode+='[faq_answer]'+options.array[i]['answer']+'[/faq_answer]';
    }
    shortcode+='[/faq]';
    return shortcode;
}

function custom_obtainer_faq(data) {
    var cont=jQuery('.tf_shortcode_option:visible');
    var sh_options={};
    sh_options['array']=[];
    sh_options['title']=opt_get('tf_shc_faq_title',cont);
    cont.find('[name="tf_shc_faq_question"]').each(function(i) {
        var question=jQuery(this).val();
        var answer=jQuery(this).parents('.option').nextAll('.option').find('[name="tf_shc_faq_answer"]:first').val();
        var tmp={};
        tmp['question']=question;
        tmp['answer']=answer;
        sh_options['array'].push(tmp);
    });
    return sh_options;
}

function custom_generator_tabs(type,options) {
    var shortcode='[tabs class="'+options['class']+'"]';
    for(var i in options.array) {
        shortcode+='[tab title="'+options.array[i]['title']+'"]'+options.array[i]['content']+'[/tab]';
    }
    shortcode+='[/tabs]';
    return shortcode;
}

function custom_obtainer_tabs(data) {
    var cont=jQuery('.tf_shortcode_option:visible');
    var sh_options={};
    sh_options['array']=[];
    sh_options['class']= opt_get('tf_shc_tabs_class',cont);
    cont.find('[name="tf_shc_tabs_title"]').each(function(i) {
        var div=jQuery(this).parents('.option');
         var title=opt_get(jQuery(this).attr('name'),div);
        div=jQuery(this).parents('.option').nextAll('.option').find('[name="tf_shc_tabs_content"]').first().parents('.option');
        var content=opt_get(jQuery(this).parents('.option').nextAll('.option').find('[name="tf_shc_tabs_content"]').first().attr('name'),div);
        var tmp={};
        tmp['title']=title;
        tmp['content']=content;
        sh_options['array'].push(tmp);
    });
    return sh_options;
}

