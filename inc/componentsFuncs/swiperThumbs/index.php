<?php
/* 
generateThumbsSlider('1', [
    'thumbs' => [
        // Override default thumbs options if needed
    ],
    'product' => [
        // Override default product options if needed
    ],
]);

<?php if (!empty($pGal)) {
    renderComponentFunc(getComponentFunc('shared/swiperThumbs/index.php')); // here is js function and template html
    generateThumbsSlider($uniqId); // js generate 
?>
    <?= templateThumbsSlider($uniqId, $pGal); ?>
<?php } ?>

*/

function generateThumbsSlider($id, $options = [])
{
    // Default options for the gallery-thumbs Swiper
    $defaultThumbsOptions = [
        'el' => '.gallery-thumbs' . $id,
        'direction' => 'horizontal',
        'slidesPerView' => 'auto',
        'lazy' => false,
        'speed' => 700,
        'navigation' => [
            'nextEl' => '.swiper-button-next-thumbs' . $id,
            'prevEl' => '.swiper-button-prev-thumbs' . $id,
        ],
        'pagination' => [
            'el' => '.swiper-pagination' . $id,
            'clickable' => true,
        ],
        'breakpoints' => [
            // Example breakpoints
            320 => [
                'direction' => 'horizontal',
            ],
            900 => [
                'direction' => 'horizontal',
            ],
            901 => [
                'direction' => 'horizontal',
            ],
            1920 => [
                'direction' => 'horizontal',
            ],
        ]
    ];

    // Default options for the gallery-top Swiper
    $defaultProductOptions = [
        'direction' => 'horizontal',
        'slidesPerView' => 1,
        'observer' => true,
        'observeParents' => true,
        'spaceBetween' => 10,
        'navigation' => [
            'nextEl' => '.swiper-button-next-thumbs' . $id,
            'prevEl' => '.swiper-button-prev-thumbs' . $id,
        ],
        'pagination' => [
            'el' => '.swiper-pagination' . $id,
            'clickable' => true,
        ],
        'speed' => 700,
        'autoplay' => [
            'delay' => 3000,
        ],
        'thumbs' => [
            'swiper' => [
                'el' => '.gallery-thumbs' . $id,
                'slidesPerView' => 'auto',
                'speed' => 700,
            ],
        ],
        'breakpoints' => [
            // Example breakpoints
            320 => [
                'direction' => 'horizontal',
            ],
            900 => [
                'direction' => 'horizontal',
            ],
            901 => [
                'direction' => 'horizontal',
            ],
            1920 => [
                'direction' => 'horizontal',
            ],
        ]
    ];

    // Merge default options with user-defined options
    $thumbsOptions = array_merge($defaultThumbsOptions, $options['thumbs'] ?? []);
    $productOptions = array_merge($defaultProductOptions, $options['product'] ?? []);

    // Convert PHP arrays to JSON for use in JavaScript
    $thumbsOptionsJson = json_encode($thumbsOptions);
    $productOptionsJson = json_encode($productOptions);

    $script = "
    let swiperThumbs$id;
    let swiperProduct$id;

    function initializeThumbsSlider$id() {
        console.log('Thumbs Options:', $thumbsOptionsJson); // Log the options
        swiperThumbs$id = new Swiper('.gallery-thumbs$id', $thumbsOptionsJson);
        swiperProduct$id = new Swiper('.gallery-top$id', $productOptionsJson);
    }

    function updateThumbsSlider$id() {
        // Destroy current Swiper instances if they exist
        if (swiperThumbs$id) {
            swiperThumbs$id.destroy(true, true);
        }
        if (swiperProduct$id) {
            swiperProduct$id.destroy(true, true);
        }
        // Reinitialize Swiper
        initializeThumbsSlider$id();
    }

    // Initial Swiper setup
    jQuery(document).ready(function() {
        initializeThumbsSlider$id();
    });

    // Update Swiper on window resize
    jQuery(window).resize(function () {
        updateThumbsSlider$id();
    });
    ";

    wp_add_inline_script('swiperJs', $script, 'after');
}

function templateThumbsSlider($id = null, $gal = null, $title = null)
{
    if (empty($id) || empty($gal)) {
        return;
    }
    custom_enqueue_assets(null, 'inc/componentsFuncs/swiperThumbs/index.js')

    // Display the main gallery and thumbnail gallery
?>
    <?php if (count($gal) > 1) { ?>
        <div class="single-product__swiper-holder swiper-thumbs__holder" data-lenis-prevent-touch data-lenis-prevent>
            <div class="holder">
                <div class="swiper gallery-top" data-lenis-prevent-touch>
                    <div class="swiper-wrapper">
                        <?php foreach ($gal as $item) { ?>
                            <div class="swiper-slide">
                                <div class="single__product-main__photo holder-photo" href="<?= esc_url(wp_get_attachment_image_url($item, 'full')); ?>" data-fancybox="work-gal">
                                    <?= renderImage($item, null, null, '', esc_attr($title)); ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="thumbs">
                <div class="swiper gallery-thumbs" data-lenis-prevent-touch data-lenis-prevent>
                    <div class="swiper-wrapper">
                        <?php foreach ($gal as $item) { ?>
                            <div class="swiper-slide">
                                <div class="single__product-thumbs__photo">
                                    <?= renderImage($item, null, null, '', esc_attr($title)); ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="single-product__swiper-holder__pagination swiper-pagination-gallery-top"></div>
        </div>
    <?php } ?>
    <?php if (count($gal) == 1) { ?>
        <div class="single-product__swiper-holder swiper-thumbs__holder">
            <div class="holder">
                <div class="swiper gallery-top" data-lenis-prevent-touch>
                    <div class="swiper-wrapper">
                        <?php foreach ($gal as $item) { ?>
                            <div class="swiper-slide">
                                <div class="single__product-main__photo holder-photo" href="<?= esc_url(wp_get_attachment_image_url($item, 'full')); ?>" data-fancybox="work-gal">
                                    <?= renderImage($item, null, null, '', esc_attr($title)); ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="thumbs" style="display:none;">
                <div class="swiper gallery-thumbs" data-lenis-prevent-touch data-lenis-prevent>
                    <div class="swiper-wrapper">
                        <?php foreach ($gal as $item) { ?>
                            <div class="swiper-slide">
                                <div class="single__product-thumbs__photo">
                                    <?= renderImage($item, null, null, '', esc_attr($title)); ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
<?php
}
