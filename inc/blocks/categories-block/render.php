<?php
$classes = isset($block['className']) ? $block['className'] : '';
$align   = (isset($block['align']) && !empty($block['align'])) ? 'align' . $block['align'] : '';

$fields = get_fields();

if (empty($fields)) {
    return;
}

?>
<div class="categories-block <?= $classes; ?> <?= $align; ?>">
    <div class="container">
        <div class="categories-block__container">
            <?php if (!empty($fields['cbTitle'])) { ?>
                <h2 class="categories-block__title">
                    <?= $fields['cbTitle']; ?>
                </h2>
            <?php } ?>
            <div class="categories-wrap categories-block__categories-wrap">

            </div>
        </div>
    </div>
</div>