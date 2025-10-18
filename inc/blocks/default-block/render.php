<?php
$classes = isset($block['className']) ? $block['className'] : '';
$align   = (isset($block['align']) && !empty($block['align'])) ? 'align'.$block['align'] : '';



?>
<div class="name-block <?=$classes;?> <?=$align;?>">

</div>