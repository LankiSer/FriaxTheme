<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Theme
 */

$logo = get_theme_logo();
$company_name = get_theme_company_name();
$company_subtitle = get_theme_company_subtitle();
$phone = get_theme_phone();
$phone_display = get_theme_phone_display();
$address = get_theme_address();
$working_hours = get_theme_working_hours();
$working_hours_display = get_theme_working_hours_display();


?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Public+Sans:ital,wght@0,100..900;1,100..900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<header id="header" class="site-header">
        <div class="header-container">
            <div class="header-wrapper">
                <div class="header-logo">
                    <a href="/" class="logo">
                        <?= $logo ?>
                    </a>
                </div>
                <nav class="header-nav">
                    <?php
                    wp_nav_menu( [
                        'theme_location'  => 'TopMenu',
                        'container'       => false,
                        'menu'            => '',
                        'menu_class'      => 'nav-menu',
                        'echo'            => true,
                        'fallback_cb'     => 'wp_page_menu',
                        'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'depth'           => 2,
                    ] );
                    ?>
                </nav>
                
                <div class="header-contacts">
                    <?php if (!empty($phone)): ?>
                        <div class="phone-section">
                            <a href="tel:<?= $phone ?>" class="phone-link">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22 16.92V19.92C22.0011 20.1985 21.9441 20.4742 21.8325 20.7293C21.7209 20.9844 21.5573 21.2136 21.3521 21.4019C21.1468 21.5901 20.9046 21.7335 20.6407 21.8227C20.3769 21.9119 20.0974 21.9451 19.82 21.92C16.7428 21.5856 13.787 20.5341 11.19 18.85C8.77382 17.3147 6.72533 15.2662 5.18999 12.85C3.49997 10.2412 2.44824 7.27099 2.11999 4.18C2.095 3.90347 2.12787 3.62476 2.21649 3.36162C2.30512 3.09849 2.44756 2.85669 2.63476 2.65162C2.82196 2.44655 3.0498 2.28271 3.30379 2.17052C3.55777 2.05833 3.83233 2.00026 4.10999 2H7.10999C7.59531 1.99522 8.06679 2.16708 8.43376 2.48353C8.80073 2.79999 9.03997 3.23945 9.10999 3.72C9.23662 4.68007 9.47144 5.62273 9.80999 6.53C9.94454 6.88792 9.97366 7.27691 9.89391 7.65088C9.81415 8.02485 9.62886 8.36811 9.35999 8.64L8.08999 9.91C9.51355 12.4135 11.5865 14.4864 14.09 15.91L15.36 14.64C15.6319 14.3711 15.9751 14.1858 16.3491 14.1061C16.7231 14.0263 17.1121 14.0554 17.47 14.19C18.3773 14.5286 19.3199 14.7634 20.28 14.89C20.7658 14.9605 21.2094 15.2032 21.5265 15.5715C21.8437 15.9399 22.0122 16.4091 22 16.89V16.92Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="phone-number"><?= $phone_display ?></span>
                            </a>
                            <div class="order-call">Заказать звонок</div>
                        </div>
                    <?php endif; ?>
                    <div class="cart-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 3H5L5.4 5M7 13H17L21 5H5.4M7 13L5.4 5M7 13L4.7 15.3C4.3 15.7 4.6 16.5 5.1 16.5H17M17 13V17C17 18.1 16.1 19 15 19H9C7.9 19 7 18.1 7 17V13M17 13H7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                </div>
                
                <div class="burger open_menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>


        <div id="mobile-mnu">
            <div id="close-mnu"><svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M19.207 6.207a1 1 0 0 0-1.414-1.414L12 10.586 6.207 4.793a1 1 0 0 0-1.414 1.414L10.586 12l-5.793 5.793a1 1 0 1 0 1.414 1.414L12 13.414l5.793 5.793a1 1 0 0 0 1.414-1.414L13.414 12l5.793-5.793z" fill="white"/></svg></div>
            <a href="/" class="logo-holder">
                <?= $logo ?>
                <?php if (!empty($company_name)): ?>
                    <div class="site-name-holder">
                        <div class="site-name"><?= $company_name ?></div>
                        <?php if (!empty($company_subtitle)): ?>
                            <div class="site-subtitle"><?= $company_subtitle ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </a>
            <?php
            wp_nav_menu( [
                'theme_location'  => 'mobileMenu',
                'container'       => false,
                'menu'            => '',
                'menu_class'      => 'menuTop',
                'echo'            => true,
                'fallback_cb'     => 'wp_page_menu',
                'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'depth'           => 2,
            ] );
            ?>
            <?php if (!empty($phone)): ?>
                <div class="phones-holder">
                    <a href="tel:<?= $phone ?>" class="phone"><?= $phone_display ?></a>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($address)): ?>
                <div class="address-holder">
                    <div class="address"><?= $address ?></div>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($working_hours)): ?>
                <div class="working-hours-holder">
                    <div class="working-hours"><?= $working_hours ?></div>
                </div>
            <?php endif; ?>
        </div>
	</header><!-- #masthead -->
