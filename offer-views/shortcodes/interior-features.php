<?php
    $features = wp_get_post_terms($post->ID, TF_SEEK_HELPER::get_post_type() . '_interior_features', array("fields" => "names"));
    if(sizeof($features)) :
?>
<div class="col col_1_4">
    <h3><?php _e('Interior Features', 'tfuse'); ?></h3>
    <ul>
        <?php foreach($features as $feature) echo '<li>' . $feature . '</li>'; ?>
    </ul>
</div>
<?php endif; ?>