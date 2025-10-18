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

$logo = wp_get_attachment_image(theme('logo'),'full');
$phones = @settings('phones');
$emails = @settings('emails');
$socials = @settings('socials');
$address = @settings('addresses');


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
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<header id="header" class="site-header">
        <div class="header-wrapper">
            <div class="menu-wrapper">
                <a href="/" class="logo"><?= $logo ?></a>
                <?php
                wp_nav_menu( [
                    'theme_location'  => 'TopMenu',
                    'container'       => false,
                    'menu'            => '',
                    'menu_class'      => 'TopMenu',
                    'echo'            => true,
                    'fallback_cb'     => 'wp_page_menu',
                    'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                    'depth'           => 2,
                ] );
                ?>
            </div>
            <div class="burger open_menu">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>


        <div id="mobile-mnu">
            <div id="close-mnu"><svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M19.207 6.207a1 1 0 0 0-1.414-1.414L12 10.586 6.207 4.793a1 1 0 0 0-1.414 1.414L10.586 12l-5.793 5.793a1 1 0 1 0 1.414 1.414L12 13.414l5.793 5.793a1 1 0 0 0 1.414-1.414L13.414 12l5.793-5.793z" fill="white"/></svg></div>
            <a href="/" class="logo-holder">
                <?php if(!empty($logo)) { ?>
                    <img src="<?=$logo?>" alt="">    
                <?php } ?>
                <?php if(!empty($siteTitle)) { ?>
                    <div class="site-name-holder">
                        <div class="site-name"><?php echo $siteTitle?></div>
                        <?php if(!empty($siteSubtitle)) { ?>
                            <div class="site-subtitle"><?php echo $siteSubtitle?></div>
                        <?php } ?>
                    </div>
                <?php } ?>
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
            <?php if($phones){ ?>
                <div class="phones-holder">
                    <?php foreach($phones as $phone){ ?>
	                    <a href="<?=format('phone', $phone['value']);?>" class="phone"><?=$phone['value'];?></a>
                    <?php } ?>
                </div>
            <?php } ?>
            <?php if (!empty($emails)): ?>
                <div class="emails-holder">
                    <?php foreach ($emails as $email){ ?>
                        <a href="mailto:<?=$email['value']; ?>" class="email"><?php echo $email['name']; ?></a>
                    <?php } ?>
                </div>
            <?php endif ?>
            <?php if (!empty($addresses)): ?>
                <div class="address-holder">
                    <?php foreach($addresses as $address){ ?>
	                    <div class="address"><?=$address['value'];?></div>
                    <?php } ?>
                </div>
            <?php endif ?>
            <?php if (!empty($socials)): ?>
                <div class="soc-holder">
                    <?php foreach ($socials as $item) { ?>
                        <a href="<?=$item['value'];?>" class="soc"><?=get_image($item['icon'],[24,24]);?></a>
                    <?php } ?>
                </div>
            <?php endif ?>
        </div>
	</header><!-- #masthead -->
