<?php
global $wp_query;
if ($wp_query->max_num_pages < 2) {
	return;
}

$post_type = $wp_query->query_vars['post_type'] ?: $args['post_type'];
$pp_page = $wp_query->query_vars['posts_per_page'];
$max_num = $wp_query->max_num_pages;

$taxonomy = '';
$term = '';

if(is_tax()) {
	$taxonomy = $wp_query->query_vars['taxonomy'];
	$term = $wp_query->query_vars['term'];
}

?>
<button class="loadmore-button btn trans"
        data-post-type="<?=$post_type;?>"
        data-per-page="<?=$pp_page;?>"
        data-max-pages="<?=$max_num;?>"
        data-offset="<?=$pp_page;?>"
        data-taxonomy="<?=$taxonomy;?>"
        data-term="<?=$term;?>"
>
	Показать ещё
</button>
<?php
