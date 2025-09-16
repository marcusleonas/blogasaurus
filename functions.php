<?php

// ---------- Sidebars ---------- //


if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => 'Blog',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2>',
        'after_title' => '</h2>',
    ));
}

// ---------- Add Custom Menus ---------- //

add_theme_support('menus');

// ---------- Add Post Thumbnails ---------- //

add_theme_support('post-thumbnails');
add_image_size('xlarge', 1200, 1200);

// ---------- Add Title Tag ---------- //

add_theme_support('title-tag');

// ---------- Add Custom Post Types & Taxonomies ---------- //

register_post_type('custom', array(
    'label' => __('Custom Post Type'),
    'singular_label' => __('Custom Post Type'),
    'public' => true,
    'show_ui' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'rewrite' => true,
    'query_var' => false,
    'has_archive' => false,
    'supports' => array('title', 'editor', 'thumbnail')
));

add_action('init', 'build_taxonomies', 0);

function build_taxonomies()
{
    register_taxonomy('taxo', 'custom', array('hierarchical' => true, 'label' => 'Custom Taxonomy', 'query_var' => true, 'rewrite' => true));
}

// ---------- Include Scripts ---------- //

function blogasaurus_jquery_enqueue()
{
    wp_deregister_script('jquery');
    wp_register_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js', false, null, true);
    wp_enqueue_script('jquery');
}
if (!is_admin()) add_action('wp_enqueue_scripts', 'blogasaurus_jquery_enqueue');

function blogasaurus_enqueue()
{

    // CSS
    wp_register_style('main', get_template_directory_uri() . '/css/main.css');
    wp_enqueue_style('main');

    // JS
    wp_register_script('custom-scripts', get_stylesheet_directory_uri() . '/js/script.js', array('jquery'), false, true);
    wp_enqueue_script('custom-scripts');
}
add_action('wp_enqueue_scripts', 'blogasaurus_enqueue');

// ---------- Add SVG Upload Support ---------- //

function blogasaurus_svg_mime_type($mimes = array())
{
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xm;';
    return $mimes;
}
add_filter('upload_mimes', 'blogasaurus_svg_mime_type');

function blogasaurus_fix_attachment_src($image, $attachment_id, $size, $icon)
{
    if (is_array($image) && preg_match('/\.svg$/i', $image[0]) && $image[1] <= 1) {
        if (is_array($size)) {
            $image[1] = $size[0];
            $image[2] = $size[1];
        } elseif (($xml = simplexml_load_file($image[0])) !== false) {
            $attr = $xml->attributes();
            $viewbox = explode(' ', $attr->viewBox);
            $image[1] = isset($attr->width) && preg_match('/\d+/', $attr->width, $value) ? (int) $value[0] : (count($viewbox) == 4 ? (int) $viewbox[2] : null);
            $image[2] = isset($attr->height) && preg_match('/\d+/', $attr->height, $value) ? (int) $value[0] : (count($viewbox) == 4 ? (int) $viewbox[3] : null);
        } else {
            $image[1] = $image[2] = null;
        }
    }
    return $image;
}
add_filter('wp_get_attachment_image_src', 'blogasaurus_fix_attachment_src', 10, 4);

// ---------- Add Options Page ---------- //

add_action('admin_menu', function () {
    add_menu_page(
        'Theme Settings',
        'Theme Settings',
        'manage_options',
        'theme-settings',
        'blogasaurus_render_settings_page',
        'dashicons-admin-customizer',
        2
    );
});

add_action('admin_init', function () {
    register_setting(
        'blogasaurus_settings_group', // Settings group
        'theme_settings',         // Option name
        [
            'type'              => 'array',
            'sanitize_callback' => 'blogasaurus_sanitize_theme_settings',
            'default'           => [],
        ]
    );

    // Section
    add_settings_section(
        'blogasaurus_general_section',
        'General',
        function () {
            echo '<p>Global settings for your theme.</p>';
        },
        'theme-settings'
    );

    // Fields
    add_settings_field(
        'example',
        'Example',
        'blogasaurus_field_example',
        'theme-settings',
        'blogasaurus_general_section'
    );

    add_settings_field(
        'footer_text',
        'Footer Text',
        'blogasaurus_field_footer_text',
        'theme-settings',
        'blogasaurus_general_section'
    );
});

function blogasaurus_sanitize_theme_settings($input)
{
    $output = [];

    $output['example'] = isset($input['example'])
        ? sanitize_text_field($input['example'])
        : '';

    if (isset($input['footer_text'])) {
        $allowed = [
            'a'      => ['href' => [], 'title' => [], 'rel' => [], 'target' => []],
            'strong' => [],
            'em'     => [],
            'br'     => [],
        ];
        $output['footer_text'] = wp_kses($input['footer_text'], $allowed);
    } else {
        $output['footer_text'] = '';
    }

    return $output;
}

function blogasaurus_get_option($key, $default = '')
{
    $opts = get_option('theme_settings', []);
    return isset($opts[$key]) && $opts[$key] !== '' ? $opts[$key] : $default;
}

function blogasaurus_field_example()
{
    $val = blogasaurus_get_option('example');
    echo '<input type="text" name="theme_settings[example]" value="' . esc_attr($val) . '" class="regular-text" />';
}

function blogasaurus_field_footer_text()
{
    $val = blogasaurus_get_option('footer_text');
    echo '<textarea name="theme_settings[footer_text]" value="' . esc_attr($val) . '" class="regular-text"></textarea>';
}

function blogasaurus_render_settings_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }
?>
    <div class="wrap">
        <h1>Theme Settings</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('blogasaurus_settings_group');
            do_settings_sections('theme-settings');
            submit_button();
            ?>
        </form>
    </div>
<?php
}

// ---------- Remove Admin Bar ---------- //

function blogasaurus_remove_admin_bar()
{
    show_admin_bar(false);
}
add_action('after_setup_theme', 'blogasaurus_remove_admin_bar');

// ---------- Custom Admin Styles ---------- //

function blogasaurus_login_logo()
{
?>
    <style type="text/css">
        body {
            --blogasaurus-primary: hsla(201, 100%, 14%, 1.00);
            --blogasaurus-secondary: hsla(40, 91%, 91%, 1.00);
            --blogasaurus-hover: hsla(203, 39%, 57%, 1.00);

            background-color: var(--blogasaurus-primary) !important;
        }

        .login #backtoblog a,
        .login #nav a {
            color: #fff !important;
        }

        input {
            border-radius: 0 !important;
        }

        input[type="checkbox"]:focus,
        input[type="color"]:focus,
        input[type="date"]:focus,
        input[type="datetime-local"]:focus,
        input[type="datetime"]:focus,
        input[type="email"]:focus,
        input[type="month"]:focus,
        input[type="number"]:focus,
        input[type="password"]:focus,
        input[type="radio"]:focus,
        input[type="search"]:focus,
        input[type="tel"]:focus,
        input[type="text"]:focus,
        input[type="time"]:focus,
        input[type="url"]:focus,
        input[type="week"]:focus,
        select:focus,
        textarea:focus {
            border-color: var(--blogasaurus-primary) !important;
            box-shadow: none !important;
        }

        .login #login_error,
        .login .message,
        .login .success {
            border-left-color: var(--blogasaurus-secondary) !important;
        }

        .login #backtoblog a:hover,
        .login #nav a:hover,
        .login h1 a:hover {
            color: var(--blogasaurus-hover) !important;
        }

        #login h1 a,
        .login h1 a {
            background-image: url("<?php echo get_stylesheet_directory_uri(); ?>/img/mlh-white.svg");

            width: 250px;
            height: 69px;

            background-size: 100% auto;
            background-repeat: no-repeat;
            margin-bottom: 30px;
        }

        .wp-core-ui .button-group.button-large .button,
        .wp-core-ui .button.button-large {
            background-color: var(--blogasaurus-primary) !important;
            border: none;
            border-radius: 0;
            box-shadow: none;
            font-weight: 500;
            text-shadow: none;
            text-transform: uppercase;
            transition: all .25s ease;
        }

        .wp-core-ui .button-group.button-large .button:hover,
        .wp-core-ui .button.button-large:hover {
            background: var(--blogasaurus-hover) !important;
        }

        .language-switcher .dashicons.dashicons-translation:before {
            color: var(--blogasaurus-secondary) !important;
        }

        #wpadminbar {
            background-color: var(--blogasaurus-primary) !important;
        }
    </style>
<?php
}
add_action('login_enqueue_scripts', 'blogasaurus_login_logo');

function blogasaurus_login_logo_url()
{
    return "https://marcusleonasharvey.co.uk";
}
add_filter('login_headerurl', 'blogasaurus_login_logo_url');

function blogasaurus_login_logo_title()
{
    return "Marcus Leonas Harvey";
}
add_filter('login_headertext', 'blogasaurus_login_logo_title');
