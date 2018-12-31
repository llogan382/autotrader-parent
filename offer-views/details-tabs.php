<?php
$details_tabs = tfuse_options('details_tabs',array());
if(sizeof($details_tabs) && (!empty($details_tabs[0]['tab_title']) || !empty($details_tabs[0]['tab_content']))) :
    $tabs = 0; ?>

    <!-- details tabs -->
    <div class="details_tabs">

        <ul class="tabs linked">
            <?php
            foreach($details_tabs as $key => $tab) :
                if(empty($tab['tab_title']) && empty($tab['tab_content'])) continue;
                $tabs++;
                echo '<li><a href="#content_tab_' . $key . '">' . $tab['tab_title'] . '</a></li>';
            endforeach;
            ?>
        </ul>

        <?php
        foreach($details_tabs as $key => $tab) :
            if(empty($tab['tab_title']) && empty($tab['tab_content'])) continue;
            echo '<div id="content_tab_' . $key . '" class="tabcontent clearfix">';
                $content = str_replace('%%content%%', $post->post_content, $tab['tab_content']);
                $content = apply_filters('themefuse_shortcodes', $content);

                if(is_numeric(mb_strpos($content, '%%send_to_a_friend%%'))){
                    ob_start(); get_template_part('offer-views/shortcodes/send','to-a-friend'); $send_to_a_friend = ob_get_contents(); ob_end_clean();
                    $content = str_replace('%%send_to_a_friend%%',  $send_to_a_friend, $content);
                }

                if(is_numeric(mb_strpos($content, '%%contact_seller%%'))){
                    ob_start(); get_template_part('offer-views/shortcodes/contact','seller'); $contact_seller = ob_get_contents(); ob_end_clean();
                    $content = str_replace('%%contact_seller%%', $contact_seller, $content);
                }

                if(is_numeric(mb_strpos($content, '%%interior_features%%'))){
                    ob_start(); get_template_part('offer-views/shortcodes/interior','features'); $interior_features = ob_get_contents(); ob_end_clean();
                    $content = str_replace('%%interior_features%%', $interior_features, $content);
                }

                if(is_numeric(mb_strpos($content, '%%exterior_features%%'))){ //var_dump(1);
                    ob_start(); get_template_part('offer-views/shortcodes/exterior','features'); $exterior_features = ob_get_contents(); ob_end_clean();
                    $content = str_replace('%%exterior_features%%', $exterior_features, $content);
                }

                if(is_numeric(mb_strpos($content, '%%safely_features%%'))){
                    ob_start(); get_template_part('offer-views/shortcodes/safely','features'); $safely_features = ob_get_contents(); ob_end_clean();
                    $content = str_replace('%%safely_features%%', $safely_features, $content);
                }

                if(is_numeric(mb_strpos($content, '%%extras%%'))){
                    ob_start(); get_template_part('offer-views/shortcodes/extras'); $extras = ob_get_contents(); ob_end_clean();
                    $content = str_replace('%%extras%%', $extras, $content);
                }

            echo $content;
            echo '</div>';
        endforeach;
        ?>

    </div><!--/ details tabs -->
<?php endif; ?>
