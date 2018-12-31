<?php

if (!defined('TFUSE'))
    exit('Direct access forbidden.');

    global $search;
?>
<!-- search -->
<div class="middle_row row_white search_row search_before<?php echo ($search == 'advanced_search') ? ' full_search' : '' ;?>">
    <div class="container">
        <?php
        if (!empty($search)):
            switch ($search)
            {
                case 'main_search'     :   TF_SEEK_HELPER::print_form('main_search');
                    ?>
                    <script type="text/javascript" >
                        jQuery(document).ready(function() {
                            var $ = jQuery;
                            // Show/Hide Advanced Search
                            $(".adv_search_hidden").hide();
                            $("#adv_search_open").click(function(){
                                if ($(this).closest(".search_form").hasClass("advsearch_hide")) {
                                    $(".adv_search_hidden").stop().slideDown();
                                    $(this).html("<?php _e('Close Advanced Search', 'tfuse'); ?>");
                                } else {
                                    $(".adv_search_hidden").stop().slideUp();
                                    $(this).html("<?php _e('Advanced Search', 'tfuse'); ?>");
                                }
                                $(this).closest(".search_form").toggleClass("advsearch_hide");
                            });
                        });
                    </script>
                    <?php
                    break;
                case 'advanced_search' :   TF_SEEK_HELPER::print_form('extended_search');
                    break;
                default:
                    break;
            }
        endif;
        ?>

    </div><!--/ container -->
</div><!--/ search -->