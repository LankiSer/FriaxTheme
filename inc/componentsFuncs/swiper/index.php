<?php
function generateSwiper($id, $options = []) {
    // Default Swiper options
    $defaultOptions = [
        'direction' => 'horizontal',
        'loop' => false,
        'slidesPerView' => 'auto',
        'spaceBetween' => 0,
        'autoHeight' => false,
        'speed' => 2000,
        'autoplay' => [
            'delay' => 3000,
        ],
        'pagination' => [
            'el' => '.swiper-pagination' . $id,
            'clickable' => true,
        ],
        'navigation' => [
            'nextEl' => '.swiper-button-next' . $id,
            'prevEl' => '.swiper-button-prev' . $id,
        ],
        'grabCursor' => 'true',
    ];

    // Merge default options with user-defined options
    $swiperOptions = array_merge($defaultOptions, $options);

    // Convert the PHP array to JSON for use in JavaScript
    $swiperOptionsJson = json_encode($swiperOptions);

    $script = "
    let swiper$id;
    function initializeSwiper$id() {
        swiper$id = new Swiper('.swiper$id', $swiperOptionsJson);
    }

    function updateSwiper$id() {
        // Destroy the current Swiper instance
        if (swiper$id) {
            swiper$id.destroy(true, true);
        }
        // Reinitialize Swiper
        initializeSwiper$id();
    }

    // Initial Swiper setup
    initializeSwiper$id();

    // Update Swiper on window resize
    // jQuery(window).resize(function () {
        //  if (window.matchMedia('(min-width: 768px)').matches) {
        // updateSwiper$id(); // Only update if not mobile
    // }
    // });
    ";

    wp_add_inline_script('swiperJs', $script, 'after');
}
