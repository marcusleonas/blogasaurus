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
        <div>
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

            <button class="navbar__mobile-menu">
                <i class="fas fa-bars"></i>
                <i class="fas fa-times"></i>
                <span class="accessibility">Menu</span>
            </button>

            <nav class="navbar__links">
                <?php wp_nav_menu(array('menu' => 'Main', 'container' => false)); ?>
            </nav>
        </div>
    </header>

    <!-- Content -->
    <main id="main">