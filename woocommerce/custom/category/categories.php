<?php

if (! defined('ABSPATH')) {
    exit;
}

$args = array(
    'taxonomy'   => 'product_cat',
    'hide_empty' => false,
    'parent'     => 0,
    'exclude' => 15
);

$cbCats = get_terms($args);

if (empty($cbCats)) {
    return;
}

?>

<div class="categories-block__container">
    <div class="categories-wrap categories-block__categories-wrap">
        <?php foreach ($cbCats as $key => $term) : ?>
            <?php wc_get_template(
                'content-product-cat.php',
                array(
                    'category' => $term
                )
            ); ?>
        <?php endforeach; ?>
    </div>
</div>

<?php

?>