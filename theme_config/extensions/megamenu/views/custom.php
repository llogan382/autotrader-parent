<?php
$item = $settings['item_settings'];

$children = $settings['children'];

if(!empty($children)): ?>
    <a href="<?php print $item['url']; ?>">
        <span><?php echo $item['item_title'];?></span>
    </a>
    <ul class="submenu-2">
        <?php foreach ($children as $child) :
            $target = $class = $class_style = '';
            if(!empty($child['classes'])) $class = implode(' ',$child['classes']);
            if($class != '') $class_style = 'class="'.$class.'"';
            if($child['target'] != '') $target = 'target="'.$child['target'].'"';
        ?>
        <li class="menu-level-2">
            <a <?php echo $target; echo $class_style; ?> href="<?php echo $child['url'];?>">
                <span><?php echo $child['title'];?></span>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
<?php endif;?>