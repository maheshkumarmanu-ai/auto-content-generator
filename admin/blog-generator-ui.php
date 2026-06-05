<?php

// Blog Generator UI

function auto_blog_generator_page() {
    if (!current_user_can('manage_options')) wp_die('Unauthorized');

    $providers = get_ai_providers();
    $selected_provider_key = 'openai';

    foreach ($providers as $key => $provider) {
        if (get_option("content_generator_{$key}_enabled", false)) {
            $selected_provider_key = $key;
            break;
        }
    }

    $api_key = get_option("content_generator_{$selected_provider_key}_api_key", '');
    $model = get_option("content_generator_{$selected_provider_key}_model", $providers[$selected_provider_key]['default_model']);

    if (isset($_POST['generate_blog'])) {
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'pungus_generate_recipe')) {
            echo '<div class="notice notice-error"><p>Nonce verification failed. Please try again.</p></div>';
        } else {
            $topic = sanitize_text_field($_POST['auto_blog_name'] ?? '');
            $image_url = esc_url_raw($_POST['auto_blog_image'] ?? '');
            $tone = sanitize_text_field($_POST['blog_tone'] ?? 'Professional');
            $word_count = sanitize_text_field($_POST['blog_word_count'] ?? '800–1200');
            $blog_purpose = sanitize_text_field($_POST['blog_purpose'] ?? '');
            $blog_audience = sanitize_text_field($_POST['blog_audience'] ?? '');

            if (empty($topic)) {
                echo '<div class="notice notice-error"><p>Please enter a blog topic.</p></div>';
            } elseif (empty($api_key)) {
                echo '<div class="notice notice-error"><p>API key is missing. Please set it in plugin settings.</p></div>';
            } else {
                $prompt = "Write a detailed, in-depth, and informative blog article about the topic: \"$topic\".\n";
                if (!empty($blog_purpose)) {
                    $prompt .= "The purpose of the blog is: $blog_purpose.\n";
                }
                if (!empty($blog_audience)) {
                    $prompt .= "The target audience for this blog is: $blog_audience.\n";
                }
                $prompt .= "The tone of the article should be {$tone}.\n";
                $prompt .= "It should include an introduction, multiple well-structured sections, and a conclusion.\n";
                $prompt .= "Use clear headings and subheadings where appropriate.\n";
                $prompt .= "The content should be SEO-friendly, engaging, and at least {$word_count} words.\n";
                $prompt .= "Avoid markdown. Return plain text only.";

                $response = pungus_call_ai_api($selected_provider_key, $api_key, $model, $prompt);

                if (is_wp_error($response)) {
                    echo '<div class="notice notice-error"><p>Error: ' . esc_html($response->get_error_message()) . '</p></div>';
                } else {
                    $title = ucwords($topic);
                    $post_id = wp_insert_post([
                        'post_title'   => $title,
                        'post_content' => wp_kses_post($response),
                        'post_status'  => 'draft',
                        'post_type'    => 'post',
                    ]);

                    if (is_wp_error($post_id) || !$post_id) {
                        echo '<div class="notice notice-error"><p>Failed to create blog post.</p></div>';
                    } else {
                        if (!empty($image_url)) {
                            pungus_set_featured_image_from_url($image_url, $post_id);
                        }
                        $edit_link = get_edit_post_link($post_id);
                        echo '<div class="notice notice-success"><p>Blog post created as draft! <a href="' . esc_url($edit_link) . '" target="_blank">Edit Blog Post</a></p></div>';
                    }
                }
            }
        }
    }

    $selected_tone = $_POST['blog_tone'] ?? 'Professional';
    $selected_word_count = $_POST['blog_word_count'] ?? '800–1200';
?>


<style>
.tooltip-wrapper {
    position: relative;
    display: inline-block;
    width: 100%;
    margin-bottom: 15px;
}
.tooltip-wrapper input[type="text"],
.tooltip-wrapper input[type="url"],
.tooltip-wrapper select {
    width: 100%;
    padding: 8px 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
.tooltip {
    visibility: hidden;
    width: 220px;
    background-color: #555;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px;
    position: absolute;
    z-index: 1;
    bottom: 125%; /* Position above the input */
    left: 50%;
    margin-left: -110px; /* Center the tooltip */
    opacity: 0;
    transition: opacity 0.3s;
    font-size: 12px;
    line-height: 1.3;
}
.tooltip-wrapper:hover .tooltip {
    visibility: visible;
    opacity: 1;
}

/* Style for tone tabs */
#tone-tabs {
    margin-bottom: 15px;
}
.tone-tab {
    display: inline-block;
    padding: 6px 12px;
    margin: 3px 3px 3px 0;
    background-color: #eee;
    border-radius: 4px;
    cursor: pointer;
    user-select: none;
    font-size: 13px;
    transition: background-color 0.3s ease;
}
.tone-tab.active {
    background-color: #0073aa;
    color: white;
}

/* Button style */
.generate-btn {
    background-color: #0073aa;
    color: #fff;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    font-size: 15px;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}
.generate-btn:hover {
    background-color: #005177;
}
</style>

<div class="auto-blog-wrap" style="margin:0 auto;">
    <h1>Generate Blog</h1>

    <form method="post" class="auto-blog-form" style="margin-top:20px;">
        <?php wp_nonce_field('pungus_generate_recipe'); ?>

        <!-- Blog Topic -->
        <div class="tooltip-wrapper">
            <input type="text" name="auto_blog_name" placeholder="Blog Topic *" required 
                   value="<?php echo esc_attr($_POST['auto_blog_name'] ?? ''); ?>">
            <span class="tooltip">Main blog idea. Example: "Top 10 Kitchen Hacks"</span>
        </div>

        <!-- Target Website/Brand -->
        <div class="tooltip-wrapper">
            <input type="text" name="auto_blog_context" placeholder="Target Website or Brand (Optional)"
                   value="<?php echo esc_attr($_POST['auto_blog_context'] ?? ''); ?>">
            <span class="tooltip">Specify the website or brand this blog is for. Leave blank if same as current website.</span>
        </div>

        <!-- Image URL -->
        <div class="tooltip-wrapper">
            <input type="url" name="auto_blog_image" placeholder="Image URL (Optional)" 
                   value="<?php echo esc_attr($_POST['auto_blog_image'] ?? ''); ?>">
            <span class="tooltip">Optional featured image URL for the post.</span>
        </div>

        <!-- Writing Purpose -->
        <div class="tooltip-wrapper">
            <input type="text" name="blog_purpose" placeholder="Purpose of Blog (Optional)" 
                   value="<?php echo esc_attr($_POST['blog_purpose'] ?? ''); ?>">
            <span class="tooltip">What’s the goal? (e.g., Promote product, educate, inspire)</span>
        </div>

        <!-- Target Audience -->
        <div class="tooltip-wrapper">
            <input type="text" name="blog_audience" placeholder="Target Audience (Optional)" 
                   value="<?php echo esc_attr($_POST['blog_audience'] ?? ''); ?>">
            <span class="tooltip">Who are you writing for? (e.g., home cooks, marketers)</span>
        </div>
		
		<!-- Word Count -->
        <div class="tooltip-wrapper">
            <select name="blog_word_count" style="width: 100%; padding: 8px 10px; font-size: 14px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                <?php
                $word_counts = ['800–1200', '1200–1500', '1500–2000', '2000+'];
                foreach ($word_counts as $count) {
                    $selected = ($selected_word_count === $count) ? 'selected' : '';
                    echo "<option value='" . esc_attr($count) . "' $selected>" . esc_html($count) . " words</option>";
                }
                ?>
            </select>
            <span class="tooltip">Desired word count range for the blog post.</span>
        </div>

        <!-- Tone Selection -->
        <div id="tone-tabs">
            <?php
            $tones = ['Professional', 'Casual', 'Humorous', 'Inspirational', 'Persuasive', 'Educational', 'Storytelling', 'Formal', 'Informal', 'Friendly', 'Authoritative', 'Optimistic', 'Serious', 'Conversational', 'Witty', 'Empathetic', 'Motivational', 'Sarcastic', 'Poetic', 'Technical'];
            foreach ($tones as $tone) {
                $active_class = ($selected_tone === $tone) ? 'tone-tab active' : 'tone-tab';
                echo "<span class='" . esc_attr($active_class) . "' data-tone='" . esc_attr($tone) . "'>" . esc_html($tone) . "</span>";
            }
            ?>
        </div>

        <input type="hidden" name="blog_tone" id="blog_tone" value="<?php echo esc_attr($selected_tone); ?>">

        <button type="submit" name="generate_blog" class="generate-btn">Generate Blog Post</button>

        <div id="loader" style="display: none;">
            <div class="loader"></div>
        </div>
    </form>

    <hr>

    <?php
    // Pagination & posts listing (same as before but add Delete button)
    $paged = max(1, intval($_GET['paged'] ?? 1));
    $posts_per_page = intval($_GET['posts_per_page'] ?? 10);

    if (!in_array($posts_per_page, [10, 25, 50])) {
        $posts_per_page = 10;
    }

    $args = [
        'post_type'      => 'post',
        'posts_per_page' => $posts_per_page,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    $query = new WP_Query($args);
    $total_pages = $query->max_num_pages;

    $base_url = remove_query_arg(['paged', 'posts_per_page']);
    ?>

    <form method="get" style="margin-bottom:15px;">
        <label for="posts_per_page">Posts per page: </label>
        <select name="posts_per_page" id="posts_per_page" onchange="this.form.submit()">
            <?php foreach ([10, 25, 50] as $num) : ?>
                <option value="<?php echo esc_attr($num); ?>" <?php selected($posts_per_page, $num); ?>><?php echo esc_html($num); ?></option>
            <?php endforeach; ?>
        </select>

        <?php
        // Keep other query args intact
        foreach ($_GET as $key => $value) {
            if ($key !== 'posts_per_page' && $key !== 'paged') {
                echo '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '">';
            }
        }
        ?>
    </form>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th style="width: 60%;">Title</th>
                <th style="width: 15%;">Status</th>
                <th style="width: 15%;">Date</th>
                <th style="width: 30%;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($query->have_posts()) : ?>
                <?php while ($query->have_posts()) : $query->the_post();
                    $post_id = get_the_ID();
                    $status = get_post_status();
                    $edit_link = get_edit_post_link($post_id);
                    $view_link = get_permalink($post_id);
                    $delete_nonce = wp_create_nonce('delete_post_' . $post_id);
                    $title = get_the_title();

                    $actions = [];
                    $actions[] = '<a class="action-btn edit" href="' . esc_url($edit_link) . '" target="_blank" rel="noopener noreferrer">Edit</a>';
                    $actions[] = '<a class="action-btn view" href="' . esc_url($view_link) . '" target="_blank" rel="noopener noreferrer">View</a>';

                    if (in_array($status, ['draft', 'pending'], true)) {
                        $publish_url = wp_nonce_url(
                            admin_url('post.php?action=edit&post=' . $post_id),
                            'publish_post_' . $post_id
                        );
                        $actions[] = '<a class="action-btn publish" href="' . esc_url($publish_url) . '" onclick="return confirm(\'Publish this post?\');">Publish</a>';
                    }

                    $delete_url = wp_nonce_url(
                        admin_url('admin-post.php?action=auto_blog_delete_post&post_id=' . $post_id),
                        'auto_blog_delete_post_' . $post_id
                    );
                    $actions[] = '<a class="action-btn delete" href="' . esc_url($delete_url) . '" onclick="return confirm(\'Are you sure you want to delete this post?\');">Delete</a>';
                    ?>

                    <tr>
                        <td style="font-weight: 600;"><?php echo esc_html($title); ?></td>
                        <td><?php echo esc_html(ucfirst($status)); ?></td>
                        <td><?php echo esc_html(get_the_date()); ?></td>
                        <td><?php echo implode(' ', $actions); ?></td>
                    </tr>

                <?php endwhile; ?>
            <?php else : ?>
                <tr><td colspan="4">No blog posts found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($total_pages > 1) : ?>
        <div class="tablenav">
            <div class="tablenav-pages">
                <?php
                echo paginate_links([
                    'base'      => add_query_arg('paged', '%#%', $base_url),
                    'format'    => '',
                    'prev_text' => __('&laquo; Prev'),
                    'next_text' => __('Next &raquo;'),
                    'total'     => $total_pages,
                    'current'   => $paged,
                ]);
                ?>
            </div>
        </div>
    <?php endif;

    wp_reset_postdata();
    ?>
</div>

<?php
}

add_action('admin_post_auto_blog_delete_post', 'auto_blog_delete_post_handler');

function auto_blog_delete_post_handler() {
    if (!current_user_can('delete_posts')) {
        wp_die(__('You are not allowed to delete posts.', 'your-textdomain'));
    }

    $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
    $nonce = $_GET['_wpnonce'] ?? '';

    if (!$post_id || !wp_verify_nonce($nonce, 'auto_blog_delete_post_' . $post_id)) {
        wp_die(__('Invalid nonce or post ID.', 'your-textdomain'));
    }

    wp_delete_post($post_id, true);

    wp_safe_redirect(admin_url('admin.php?page=auto-blog-generator&deleted=1'));
    exit;
}

add_action('admin_notices', 'auto_blog_show_delete_notice');

function auto_blog_show_delete_notice() {
    if (isset($_GET['page'], $_GET['deleted']) && $_GET['page'] === 'auto-blog-generator' && $_GET['deleted'] == 1) {
        echo '<div class="notice notice-success is-dismissible"><p>Blog post deleted successfully.</p></div>';
    }
}

