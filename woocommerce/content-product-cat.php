<?php
if (! defined('ABSPATH') || empty($category)) {
	exit;
}

$name = get_field('pcCardName', 'term_' . $category->term_id) ?? $category->name;
$desc = $category->description;
$term_id = $category->term_id;
$taxonomy = $category->taxonomy;
$url = get_term_link($term_id);

$thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);

?>

<a class="wc-category__card wc-category__card-v1" href="<?= $url; ?>">
	<?php if (!empty($thumbnail_id)) { ?>
		<div class="wc-category__card-v1__photo">
			<?= renderImage($thumbnail_id, null, null, '', $name); ?>
		</div>
	<?php } ?>
	<div class="wc-category__card-v1-actions">
		<div class="wc-category__card-v1__text">
			<?php if (!empty($desc)) { ?>
				<div class="wc-category__card-v1__desc">
					<?= $desc; ?>
				</div>
			<?php } ?>
			<?php if (!empty($name)) { ?>
				<div class="wc-category__card-v1__name">
					<?= $name; ?>
				</div>
			<?php } ?>
		</div>
		<svg class="wc-category__card-v1__arrow" width="23" height="25" viewBox="0 0 23 25" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M20.0001 23.6777C21.1046 23.6777 22.0001 22.7822 22.0001 21.6777L22.0001 3.67767C22.0001 2.5731 21.1046 1.67767 20.0001 1.67767C18.8955 1.67767 18.0001 2.5731 18.0001 3.67767L18.0001 19.6777L2.00006 19.6777C0.895488 19.6777 5.72698e-05 20.5731 5.72698e-05 21.6777C5.72698e-05 22.7822 0.895488 23.6777 2.00006 23.6777L20.0001 23.6777ZM0.908174 5.41421L18.5858 23.0919L21.4143 20.2635L3.7366 2.58579L0.908174 5.41421Z" fill="white" />
		</svg>
	</div>
	<div class="wc-categroy__card-v1__pseudo">

	</div>
</a>