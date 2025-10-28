<?php

if (! defined('ABSPATH')) {
    return;
}

$category = get_queried_object();

if (empty($category) || !is_tax($category)) {
    return;
}

$title = get_field('pcBannerTitle', 'term_' . $category->term_id);
if (empty($title)) {
    $title = $category->name;
}
$sub = get_field('pcBannerSubtitle', 'term_' . $category->term_id);
if (empty($sub)) {
    $sub = $category->description;
}
$photo = get_field('pcBannerImage', 'term_' . $category->term_id);
if (empty($photo)) {
    $photo = null;
}

if (empty($title) && empty($sub) && empty($photo)) {
    return;
}

?>

<div class="wc-category__banner wc-category__banner-v1">
    <?php if ($photo) { ?>
        <div class="wc-category__banner-v1__photo">
            <?= renderImage($photo, null, null, '', $title); ?>
        </div>
    <?php } ?>
    <div class="wc-category__banner-v1__text">
        <?php if (!empty($title)) { ?>
            <h1 class="wc-category__banner-v1__title">
                <?= $title; ?>
            </h1>
        <?php } ?>
        <?php if (!empty($sub)) { ?>
            <div class="wc-category__banner-v1__sub">
                <?= $sub; ?>
            </div>
        <?php } ?>
    </div>
</div>