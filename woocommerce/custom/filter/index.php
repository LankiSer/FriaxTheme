<?php
defined('ABSPATH') || exit;
?>

<?php if ((wc_get_loop_prop('total') > -1) || is_search()) { ?>
    <div class="wc-catalog__sidebar">
        <svg class="wc-catalog__sidebar-close" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M7 17L17.0028 7M7 7L17.0028 17" stroke="white" stroke-width="1.5" stroke-linecap="round" />
        </svg>
        <?php
        if (is_active_sidebar('sidebar-shop')) {
            dynamic_sidebar('sidebar-shop');
        }
        ?>
    </div>
<?php } ?>