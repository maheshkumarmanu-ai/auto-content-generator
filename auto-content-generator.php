<?php

/**

 * Plugin Name: Content Generator

 * Plugin URI: https://yemcoders.com/plugins/content-generator

 * Description: Generate high-quality recipes and blog posts using AI. Supports 12+ AI providers with flexible API settings and post management features.

 * Version: 1.0

 * Author: Mahesh Kumar

 * Author URI: https://maheshkumarm.com

 * Company: Yemcoders

 * Company URI: https://yemcoders.com

 * License: GPLv2 or later

 * License URI: https://www.gnu.org/licenses/gpl-2.0.html

 * Text Domain: content-generator

 * Domain Path: /languages

 * Tags: ai, blog generator, recipe generator, content generator, openai, wordpress ai, blog automation

 */




if (!defined('ABSPATH')) exit;


// Include admin UI files
require_once plugin_dir_path(__FILE__) . 'admin/about-page.php';
require_once plugin_dir_path(__FILE__) . 'admin/blog-generator-ui.php';
require_once plugin_dir_path(__FILE__) . 'admin/recipe-generator-ui.php';
require_once plugin_dir_path(__FILE__) . 'admin/settings-page.php';
require_once plugin_dir_path(__FILE__) . 'admin/assets.php'; // ✅ enqueue assets

// Include admin AI Provider, Hadler and Utils
require_once plugin_dir_path(__FILE__) . 'includes/ai-providers.php';
require_once plugin_dir_path(__FILE__) . 'includes/ai-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/ai-utils.php';




// Admin Menu

add_action('admin_menu', function () {

    add_menu_page(

        'Content Generator',

        'Content Generator',

        'manage_options',

        'content_generator_main',

        'content_generator_main_page',

        'dashicons-carrot',

        100

    );



    add_submenu_page(

        'content_generator_main',

        'Recipe Generator',

        'Recipe Generator',

        'manage_options',

        'pungus-auto-recipe-generator',

        'pungus_auto_recipe_generator_page'

    );



    add_submenu_page(

        'content_generator_main',

        'Blog Generator',

        'Blog Generator',

        'manage_options',

        'auto-blog-generator',

        'auto_blog_generator_page'

    );



    add_submenu_page(

        'content_generator_main',

        'Settings',

        'Settings',

        'manage_options',

        'content_generator_settings',

        'content_generator_settings_page'

    );



    add_submenu_page(

        'content_generator_main',

        'Need Help?',

        'Need Help?',

        'manage_options',

        'content_generator_about',

        'content_generator_about_page_callback'

    );

});





// Main dashboard page

function content_generator_main_page() {

    echo '<div class="wrap"><h1>Welcome to Content Generator</h1><p>Select a submenu from the left to start.</p></div>';

}













