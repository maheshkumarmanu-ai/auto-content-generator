<?php

// admin/about-page.php

add_action('admin_enqueue_scripts', function($hook) {
    error_log("Admin hook: $hook");
});


function content_generator_about_page_callback() {

    ?>

    <div class="wrap">

        <h1>About Content Generator</h1>

        <p><strong>Version:</strong> 1.0</p>

        <p><strong>Developed by:</strong> <a href="https://maheshkumarm.com" target="_blank" rel="noopener noreferrer">Mahesh Kumar</a> at <a href="https://yemcoders.com" target="_blank" rel="noopener noreferrer">Yemcoders</a></p>



        <hr>



        <h2>Need Help?</h2>

        <p>If you have any issues, feature requests, or feedback, feel free to contact us:</p>

        <ul>

            <li><strong>Support Email:</strong> <a href="mailto:support@yemcoders.com">support@yemcoders.com</a></li>

            <li><strong>Website:</strong> <a href="https://yemcoders.com" target="_blank" rel="noopener noreferrer">https://yemcoders.com</a></li>

        </ul>



        <h3>Documentation</h3>

        <p>Visit our documentation and FAQs at: <a href="https://yemcoders.com/docs/content-generator" target="_blank" rel="noopener noreferrer">https://yemcoders.com/docs/content-generator</a></p>



        <hr>



        <h2>Other Plugins by Yemcoders</h2>



      <style>

    .plugin-grid {

        display: grid;

        grid-template-columns: repeat(3, 1fr);

        gap: 20px;

        margin-bottom: 20px;

    }



    .plugin-card {

        background: #fff;

        border: 1px solid #ddd;

        padding: 15px;

        border-radius: 8px;

        box-sizing: border-box;

        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);

        transition: box-shadow 0.2s ease;

    }



    .plugin-card:hover {

        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);

    }



    .plugin-card h3 {

        margin-top: 0;

    }



    .plugin-card p {

        font-size: 14px;

        color: #555;

        min-height: 60px;

    }



    .plugin-card .button-primary {

        margin-top: 10px;

        display: inline-block;

        background: #0073aa;

        color: #fff;

        padding: 6px 12px;

        border-radius: 4px;

        text-decoration: none;

    }



    .plugin-card .button-primary:hover {

        background: #005a8c;

    }



    /* Responsive: 2 columns on tablets */

    @media (max-width: 900px) {

        .plugin-grid {

            grid-template-columns: repeat(2, 1fr);

        }

    }



    /* Responsive: 1 column on phones */

    @media (max-width: 600px) {

        .plugin-grid {

            grid-template-columns: 1fr;

        }

    }

</style>



<div class="plugin-grid">

<?php

foreach ($plugins as $plugin) {

    echo '<div class="plugin-card">';

    echo '<h3>' . esc_html($plugin['title']) . '</h3>';

    echo '<p>' . esc_html($plugin['desc']) . '</p>';

    echo '<a class="button-primary" href="' . esc_url($plugin['link']) . '" target="_blank" rel="noopener noreferrer">Learn More</a>';

    echo '</div>';

}

?>

</div>





        <?php

        $plugins = [

            [

                'title' => 'SEO Booster',

                'desc'  => 'Improve your site\'s SEO automatically using smart AI suggestions and on-page insights.',

                'link'  => 'https://yemcoders.com/plugins/seo-booster'

            ],

            [

                'title' => 'WooCommerce AI Assistant',

                'desc'  => 'Generate product descriptions, FAQs, and upsell strategies with AI for your Woo store.',

                'link'  => 'https://yemcoders.com/plugins/woo-ai-assistant'

            ],

            [

                'title' => 'Form Enhancer',

                'desc'  => 'Enhance Contact Form 7 or WPForms with validation, styling, and advanced logic.',

                'link'  => 'https://yemcoders.com/plugins/form-enhancer'

            ],

            [

                'title' => 'Landing Page Builder',

                'desc'  => 'Create high-converting landing pages using pre-made blocks and drag & drop editing.',

                'link'  => 'https://yemcoders.com/plugins/landing-page-builder'

            ],

            [

                'title' => 'Newsletter AI',

                'desc'  => 'Auto-generate engaging newsletter content using GPT and send directly to subscribers.',

                'link'  => 'https://yemcoders.com/plugins/newsletter-ai'

            ],

            [

                'title' => 'Social Media Manager',

                'desc'  => 'Plan, schedule, and auto-generate posts across all platforms from your dashboard.',

                'link'  => 'https://yemcoders.com/plugins/social-manager'

            ],

            [

                'title' => 'AI Content Scheduler',

                'desc'  => 'Generate and schedule articles using AI and publish them on your chosen schedule.',

                'link'  => 'https://yemcoders.com/plugins/ai-content-scheduler'

            ],

            [

                'title' => 'Image Optimizer Pro',

                'desc'  => 'Compress, resize, and serve WebP images to speed up your WordPress site.',

                'link'  => 'https://yemcoders.com/plugins/image-optimizer-pro'

            ],

            [

                'title' => 'Security Defender',

                'desc'  => 'Protect your site from spam, brute force, and vulnerabilities with smart security tools.',

                'link'  => 'https://yemcoders.com/plugins/security-defender'

            ]

        ];



        echo '<div class="plugin-row">';

        foreach ($plugins as $plugin) {

            echo '<div class="plugin-card">';

            echo '<h3>' . esc_html($plugin['title']) . '</h3>';

            echo '<p>' . esc_html($plugin['desc']) . '</p>';

            echo '<a class="button-primary" href="' . esc_url($plugin['link']) . '" target="_blank" rel="noopener noreferrer">Learn More</a>';

            echo '</div>';

        }

        echo '</div>';

        ?>

    </div>

    <?php

}

