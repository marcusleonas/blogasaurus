<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <!-- Skip to main content button -->
    <a class="sr-only" href="#main">Skip to Content</a>

    <!-- Header -->
    <header class="navbar">
        <div class="container">
            <div class="navbar__left">
                <a class="navbar__logo" href="<?php bloginfo('url'); ?>">
                    <?php
                    $custom_logo_id = get_theme_mod('custom_logo');
                    $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
                    if (has_custom_logo()) {
                        echo '<img src="' . esc_url($logo[0]) . '" alt="' . get_bloginfo('name') . '">';
                    } else {
                        echo '<h1>' . get_bloginfo('name') . '</h1>';
                    }
                    ?>
                </a>
            </div>

            <div class="navbar__right">
                <button class="navbar__mobile-btn">
                    <i class="fas fa-bars"></i>
                    <i class="fas fa-times"></i>
                    <span class="sr-only">Menu</span>
                </button>

                <nav class="navbar__links">
                    <?php wp_nav_menu(array('menu' => 'Main', 'container' => false)); ?>
                </nav>
            </div>
        </div>
    </header>

    <div class="mobile-menu">
        <?php wp_nav_menu(array('menu' => 'Main', 'container' => false)); ?>
    </div>

    <!-- Content -->
    <main id="main">