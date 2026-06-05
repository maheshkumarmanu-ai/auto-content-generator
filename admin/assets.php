<?php
// Admin asset loader for specific plugin pages
add_action('admin_enqueue_scripts', 'content_generator_admin_assets');
function content_generator_admin_assets($hook) {
    $assets = array(
        'content-generator_page_pungus-auto-recipe-generator' => array(
            'css' => 'admin/css/recipe-generator.css',
            'js'  => 'admin/js/recipe-generator.js',
        ),
        'content-generator_page_auto-blog-generator' => array(
            'css' => 'admin/css/blog-generator.css',
            'js'  => 'admin/js/blog-generator.js',
        ),
        'content-generator_page_content_generator_about' => array(
            'css' => 'admin/css/about-page.css',
            'js'  => 'admin/js/about-page.js',
        ),
        'content-generator_page_content_generator_settings' => array(
            'css' => 'admin/css/settings.css',
            'js'  => 'admin/js/settings.js',
        ),
    );

    if (isset($assets[$hook])) {
        $plugin_url = plugin_dir_url(dirname(__FILE__));
        
        if (!empty($assets[$hook]['css'])) {
            wp_enqueue_style(
                'content-generator-css-' . $hook,
                $plugin_url . $assets[$hook]['css'],
                array(),
                '1.0.0'
            );
        }

        if (!empty($assets[$hook]['js'])) {
            wp_enqueue_script(
                'content-generator-js-' . $hook,
                $plugin_url . $assets[$hook]['js'],
                array('jquery'),
                '1.0.0',
                true
            );
        }
    }
}

