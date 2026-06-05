<?php

// Recipe Generator UI
function pungus_auto_recipe_generator_page() {
    if (!current_user_can('manage_options')) wp_die('Unauthorized');

    // Fetch selected provider settings dynamically
    $providers = get_ai_providers();

    // Find enabled provider, fallback to 'openai'
    $selected_provider_key = 'openai';
    foreach ($providers as $key => $provider) {
        if (get_option("content_generator_{$key}_enabled", false)) {
            $selected_provider_key = $key;
            break;
        }
    }

    $api_key = get_option("content_generator_{$selected_provider_key}_api_key", '');
    $model = get_option("content_generator_{$selected_provider_key}_model", $providers[$selected_provider_key]['default_model']);

    if (isset($_POST['generate_recipe'])) {
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'pungus_generate_recipe')) {
            echo '<div class="notice notice-error"><p>Nonce verification failed. Please try again.</p></div>';
        } else {
            $recipe_name = sanitize_text_field($_POST['pungus_recipe_name'] ?? '');
            $image_url = esc_url_raw($_POST['pungus_recipe_image'] ?? '');

            if (empty($recipe_name)) {
                echo '<div class="notice notice-error"><p>Please enter a recipe name.</p></div>';
            } elseif (empty($api_key)) {
                echo '<div class="notice notice-error"><p>API key is missing. Please set it in plugin settings.</p></div>';
            } else {
                $result = pungus_generate_recipe_post($selected_provider_key, $api_key, $model, $recipe_name, $image_url);

                if (is_wp_error($result)) {
                    echo '<div class="notice notice-error"><p>Error: ' . esc_html($result->get_error_message()) . '</p></div>';
                } else {
                    $edit_link = get_edit_post_link($result);
                    echo '<div class="notice notice-success"><p>Recipe generated successfully! <a href="' . esc_url($edit_link) . '" target="_blank">Edit Recipe</a></p></div>';
                }
            }
        }
    }

    ?>
    


  <div class="auto-blog-wrap">
    <h2>Generate Recipe</h2>
    <form method="post" class="auto-blog-form">
        <?php wp_nonce_field('pungus_generate_recipe'); ?>

        <!-- Recipe Name (required) -->
        <input type="text" name="pungus_recipe_name" required placeholder="Recipe Name *"
               value="<?php echo isset($_POST['pungus_recipe_name']) ? esc_attr($_POST['pungus_recipe_name']) : ''; ?>">

        <!-- Reference Link -->
        <input type="url" name="pungus_recipe_reference" placeholder="Reference Link (Optional)"
               value="<?php echo isset($_POST['pungus_recipe_reference']) ? esc_attr($_POST['pungus_recipe_reference']) : ''; ?>">

        <!-- Image URL -->
        <input type="url" name="pungus_recipe_image" placeholder="Image URL (Optional)"
               value="<?php echo isset($_POST['pungus_recipe_image']) ? esc_attr($_POST['pungus_recipe_image']) : ''; ?>">

        <!-- Detailed Description -->
        <textarea name="pungus_recipe_description" rows="4" placeholder="Detailed Description of the Recipe (Optional)"><?php 
            echo isset($_POST['pungus_recipe_description']) ? esc_textarea($_POST['pungus_recipe_description']) : ''; ?></textarea>

        <!-- Ingredients -->
        <textarea name="pungus_recipe_ingredients" rows="4" placeholder="Ingredients (Optional - one per line)"><?php 
            echo isset($_POST['pungus_recipe_ingredients']) ? esc_textarea($_POST['pungus_recipe_ingredients']) : ''; ?></textarea>

        <!-- Special Instructions -->
        <textarea name="pungus_recipe_instructions" rows="3" placeholder="Special Instructions (Optional)"><?php 
            echo isset($_POST['pungus_recipe_instructions']) ? esc_textarea($_POST['pungus_recipe_instructions']) : ''; ?></textarea>

        <!-- Submit Button -->
        <button type="submit" name="generate_recipe" class="generate-btn">Generate Recipe</button>

        <!-- Loader -->
        <div id="loader" style="display: none;">
            <div class="loader"></div>
        </div>
    </form>
</div>



        <hr>

        <?php
        /// Pagination & posts listing for recipes
$paged = max(1, intval($_GET['paged'] ?? 1));
$posts_per_page = intval($_GET['posts_per_page'] ?? 10); // Set default to 10

if (!in_array($posts_per_page, [10, 25, 50, 100], true)) { // Allowed options
    $posts_per_page = 10; // Default to 10 if not valid
}


$args = [
    'post_type'      => 'osetin_recipe', // Change to custom post type for recipes
    'posts_per_page' => $posts_per_page,
    'paged'          => $paged,
    'orderby'        => 'date',
    'order'          => 'DESC',
];

$query = new WP_Query($args);
$total_pages = $query->max_num_pages;

// Prepare base url for pagination links, keep other query vars intact
$base_url = remove_query_arg(['paged', 'posts_per_page']);
?>

<form method="get" style="margin-bottom:15px;">
    <label for="posts_per_page">Recipes per page: </label>
    <select name="posts_per_page" id="posts_per_page" onchange="this.form.submit()">
        <?php foreach ([10, 25, 50, 100] as $num) : ?> <!-- Added 100 as an option -->
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
                        $title = get_the_title();

                        $actions = [];
                        $actions[] = '<a class="action-btn edit" href="' . esc_url($edit_link) . '" target="_blank" rel="noopener noreferrer">Edit</a>';
                        $actions[] = '<a class="action-btn view" href="' . esc_url($view_link) . '" target="_blank" rel="noopener noreferrer">View</a>';

                        if (in_array($status, ['draft', 'pending'], true)) {
                            $publish_url = wp_nonce_url(
                                admin_url('post.php?action=edit&post=' . $post_id),
                                'publish_post_' . $post_id
                            );
                            $actions[] = '<a class="action-btn publish" href="' . esc_url($publish_url) . '" onclick="return confirm(\'Publish this recipe?\');">Publish</a>';
                        }

                        // Delete URL
                        $delete_url = wp_nonce_url(
                            admin_url('admin-post.php?action=auto_recipe_delete_post&post_id=' . $post_id),
                            'auto_recipe_delete_post_' . $post_id
                        );
                        $actions[] = '<a class="action-btn delete" href="' . esc_url($delete_url) . '" onclick="return confirm(\'Are you sure you want to delete this recipe?\');">Delete</a>';
                        ?>
                        <tr>
                            <td style="font-weight: 600;"><?php echo esc_html($title); ?></td>
                            <td><?php echo esc_html(ucfirst($status)); ?></td>
                            <td><?php echo esc_html(get_the_date()); ?></td>
                            <td><?php echo implode(' ', $actions); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr><td colspan="4">No recipes found.</td></tr>
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

// Function to delete recipe post
add_action('admin_post_auto_recipe_delete_post', 'auto_recipe_delete_post_handler');

function auto_recipe_delete_post_handler() {
    if (!current_user_can('delete_posts')) {
        wp_die(__('You are not allowed to delete posts.', 'your-textdomain'));
    }

    $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
    $nonce = $_GET['_wpnonce'] ?? '';

    if (!$post_id || !wp_verify_nonce($nonce, 'auto_recipe_delete_post_' . $post_id)) {
        wp_die(__('Invalid nonce or post ID.', 'your-textdomain'));
    }

    wp_delete_post($post_id, true);

    // ✅ Corrected slug in redirect
    wp_safe_redirect(admin_url('admin.php?page=pungus-auto-recipe-generator&deleted=1'));
    exit;
}

// Show delete notice
add_action('admin_notices', 'auto_recipe_show_delete_notice');

function auto_recipe_show_delete_notice() {
    // ✅ Corrected slug in notice condition
    if (isset($_GET['page'], $_GET['deleted']) && $_GET['page'] === 'pungus-auto-recipe-generator' && $_GET['deleted'] == 1) {
        echo '<div class="notice notice-success is-dismissible"><p>Recipe deleted successfully.</p></div>';
    }
}




// Function to generate recipe post using AI
function pungus_generate_recipe_post($provider, $api_key, $model, $recipe_name, $image_url = '') {
    // Prepare AI prompt to generate detailed recipe JSON
    
    
    $extra_context = "";

// Add optional instructions to the prompt
if (!empty($reference_link)) {
    $extra_context .= "Reference link: {$reference_link}\n";
}
if (!empty($user_description)) {
    $extra_context .= "User-provided description:\n{$user_description}\n\n";
}
if (!empty($user_ingredients)) {
    $extra_context .= "Suggested ingredients:\n{$user_ingredients}\n\n";
}
if (!empty($user_instructions)) {
    $extra_context .= "Special instructions from user:\n{$user_instructions}\n\n";
}

    
    $prompt = "Generate a valid JSON object with the following fields for a recipe called '{$recipe_name}'.

$extra_context

{
    \"long_description\": \"Write 3 to 5 detailed paragraphs about the dish, including its origin, popularity, taste, when it’s typically eaten, how it’s served, and any cultural background.\",
    \"sub_title\": \"Write a catchy subtitle for the recipe in about 5 to 7 words.\",
    \"ingredients\": [
        {
            \"name\": \"Ingredient name with amount, e.g. 500g sardines\",
            \"amount\": \"Amount if separate from name (optional)\",
            \"note\": \"Any special note about the ingredient, or empty string if none\",
            \"separator\": \"Grouping or section name for ingredients, e.g. 'For the gravy' or empty string if none\"
        }
    ],
    \"steps\": [\"Step 1 description\", \"Step 2 description\", ...],
    \"nutrition\": {
        \"calories\": 0,
        \"protein\": 0,
        \"total_fat\": 0,
        \"saturated_fat\": 0,
        \"trans_fat\": 0,
        \"cholesterol\": 0,
        \"sodium\": 0,
        \"total_carbohydrates\": 0,
        \"dietary_fiber\": 0,
        \"sugars\": 0,
        \"added_sugars\": 0,
        \"calcium\": 0,
        \"iron\": 0,
        \"potassium\": 0
    },
    \"excerpt\": \"A short 1–2 line summary of the recipe.\",
    \"cook_time\": 0,
    \"preparation_time\": 0,
    \"serves\": 0,
    \"difficulty\": \"Easy / Medium / Hard\",
    \"seo_title\": \"SEO-friendly title.\",
    \"seo_description\": \"Meta description (max 150 characters).\",
    \"quick_description\": \"Write a short and catchy summary of the recipe in 1 to 2 sentences. Highlight what makes it unique, tasty, or appealing to try.\",
    \"tags\": [\"tag1\", \"tag2\", ...],
    \"step_titles\": [\"Title for Step 1\", \"Title for Step 2\", ...],
    \"step_durations\": [0, 0, ...],
    \"searchable_ingredients\": false,
    \"cooking_time\": 0,
    \"total_time\": 0,
    \"cooking_temperature\": \"Temperature in degrees.\"
}

Return JSON only. No markdown or explanation.";


    // Call AI API to generate recipe JSON
    $response = pungus_call_ai_api($provider, $api_key, $model, $prompt);
    if (is_wp_error($response)) {
        return $response; // Return error on API failure
    }

    // Extract JSON data from AI response
    $data = pungus_extract_json($response);
    if (!$data) {
        return new WP_Error('json_parse_error', 'Could not parse JSON');
    }

    // Extract main fields from AI data
    $long_description = $data['long_description'] ?? '';
    $nutrition = $data['nutrition'] ?? [];

    // Nutrition table HTML (optional - currently commented out)
    // $table = ''; // Define as empty if not used
    // $disclaimer text
    $disclaimer = '<p><em><strong>Disclaimer:</strong> Nutritional data is approximate and may vary based on ingredients and portion size.</em></p>';

    // Prepare post content combining description, nutrition table, and disclaimer
    $post_content = '<p>' . nl2br(esc_html($long_description)) . '</p>' . (isset($table) ? $table : '') . $disclaimer;

    // Insert new recipe post (draft)
    $post_id = wp_insert_post([
        'post_title'   => $recipe_name,
        'post_content' => $post_content,
        'post_status'  => 'draft',
        'post_type'    => 'osetin_recipe',
        'post_excerpt' => wp_trim_words($long_description, 80, '...'),
    ]);

    // Handle post insertion errors
    if (is_wp_error($post_id) || !$post_id) {
        return new WP_Error('post_error', 'Failed to create recipe post');
    }

    // Update ACF/meta fields with parsed AI data
    pungus_update_acf_fields($post_id, $data);

    // Assign categories based on keywords in recipe name (up to 3 categories)
    $keywords = array_slice(array_filter(explode(' ', strtolower($recipe_name))), 0, 5);
    $category_ids = [];

    foreach ($keywords as $keyword) {
        $term = term_exists($keyword, 'category');
        if ($term && !is_wp_error($term)) {
            $category_ids[] = (int) ($term['term_id'] ?? $term['term_taxonomy_id']);
        }
        if (count($category_ids) >= 3) {
            break;
        }
    }

    if (!empty($category_ids)) {
        wp_set_post_terms($post_id, $category_ids, 'category');
    }

    // Set featured image if image URL provided
    if (!empty($image_url)) {
        pungus_set_featured_image_from_url($image_url, $post_id);
    }

    // Return created post ID on success
    return $post_id;
}

// Function to update ACF fields or fallback meta fields for the recipe post
function pungus_update_acf_fields($post_id, $data) {
    // Check if ACF plugin functions are available
    if (function_exists('update_field')) {
        // Update featured image field
        update_field('acf_featured_image', $data['featured_image'] ?? '', $post_id);

        // Update taxonomy-like ACF fields (categories, cuisines, features)
        if (!empty($data['recipe_category']) && is_array($data['recipe_category'])) {
            update_field('recipe_category', $data['recipe_category'], $post_id);
        }
        if (!empty($data['recipe_cuisine']) && is_array($data['recipe_cuisine'])) {
            update_field('recipe_cuisine', $data['recipe_cuisine'], $post_id);
        }
        if (!empty($data['recipe_features']) && is_array($data['recipe_features'])) {
            update_field('recipe_features', $data['recipe_features'], $post_id);
        }

        // Update various single value fields
        update_field('layout_type_for_single_recipe', $data['layout_type'] ?? 'default', $post_id);
        update_field('quick_description', $data['quick_description'] ?? '', $post_id);
        update_field('recipe_serves', $data['serves'] ?? '', $post_id);
        update_field('recipe_difficulty', $data['difficulty'] ?? '', $post_id);
        update_field('recipe_preparation_time', $data['preparation_time'] ?? '', $post_id);
        update_field('recipe_just_cooking_time', $data['cooking_time'] ?? '', $post_id);
        update_field('recipe_cooking_time', $data['total_time'] ?? '', $post_id);
        update_field('recipe_cooking_temperature', $data['cooking_temperature'] ?? '', $post_id);
        update_field('searchable_ingredients', $data['searchable_ingredients'] ?? false, $post_id);
        update_field('sub_title', $data['sub_title'] ?? '', $post_id);

        // Update Ingredients repeater field
        if (!empty($data['ingredients']) && is_array($data['ingredients'])) {
            $ingredients = array_map(function ($item) {
                if (is_array($item) || is_object($item)) {
                    // Structured ingredient item
                    $name = $item['name'] ?? ($item->name ?? '');
                    $amount = $item['amount'] ?? ($item->amount ?? '');
                    $note = $item['note'] ?? ($item->note ?? '');
                    $separator = $item['separator'] ?? ($item->separator ?? '');
                } else {
                    // If ingredient is string, parse amount and name
                    if (preg_match('/^([\d\/.\s\w]+)\s+(.*)$/', $item, $matches)) {
                        $amount = trim($matches[1]);
                        $name = trim($matches[2]);
                    } else {
                        $amount = '';
                        $name = $item;
                    }
                    $note = '';
                    $separator = '';
                }

                return [
                    'ingredient_name'   => $name,
                    'ingredient_amount' => $amount,
                    'ingredient_note'   => $note,
                    'separator'         => $separator,
                ];
            }, $data['ingredients']);

            update_field('ingredients', $ingredients, $post_id);
        }

        // Update Steps repeater field
        if (!empty($data['steps']) && is_array($data['steps'])) {
            $steps = [];
            foreach ($data['steps'] as $i => $desc) {
                $steps[] = [
                    'step_title'       => $data['step_titles'][$i] ?? '',
                    'step_duration'    => $data['step_durations'][$i] ?? '',
                    'step_description' => $desc,
                ];
            }
            update_field('steps', $steps, $post_id);
        }

        // Update Nutrition repeater with Google Rich Meta fields
        if (!empty($data['nutrition']) && is_array($data['nutrition'])) {
            $nutritions = [];

            $google_fields = [
                'calories'            => ['label' => 'Calories', 'unit' => 'kcal'],
                'proteinContent'      => ['label' => 'Protein', 'unit' => 'g'],
                'fatContent'          => ['label' => 'Fat', 'unit' => 'g'],
                'carbohydrateContent' => ['label' => 'Carbohydrates', 'unit' => 'g'],
                'saturatedFatContent' => ['label' => 'Saturated Fat', 'unit' => 'g'],
                'transFatContent'     => ['label' => 'Trans Fat', 'unit' => 'g'],
                'cholesterolContent'  => ['label' => 'Cholesterol', 'unit' => 'mg'],
                'fiberContent'        => ['label' => 'Fiber', 'unit' => 'g'],
                'sodiumContent'       => ['label' => 'Sodium', 'unit' => 'mg'],
                'sugarContent'        => ['label' => 'Sugar', 'unit' => 'g'],
            ];

            foreach ($google_fields as $field_key => $info) {
                $label = $info['label'];
                $unit = $info['unit'];
                $value = '';

                // Search nutrition data keys for matching label
                foreach ($data['nutrition'] as $key => $val) {
                    if (stripos($key, strtolower($label)) !== false) {
                        $value = $val;
                        break;
                    }
                }

                // Add only if value found and not empty
                if ($value !== '') {
                    $formatted_value = is_numeric($value) ? $value . ' ' . $unit : $value;

                    $nutritions[] = [
                        'nutrition_name'         => $label,
                        'nutrition_value'        => $formatted_value,
                        'nutrition_extra_info'   => 'Per serving',
                        'google_rich_meta_field' => $field_key,
                    ];
                }
            }

            update_field('nutritions', $nutritions, $post_id);
        }

        // Update WordPress default tags taxonomy
        if (!empty($data['tags']) && is_array($data['tags'])) {
            wp_set_post_terms($post_id, $data['tags'], 'post_tag');
        }

    } else {
        // Fallback: update post meta fields directly if ACF not available
        update_post_meta($post_id, 'long_description', $data['long_description'] ?? '');
        update_post_meta($post_id, 'ingredients', $data['ingredients'] ?? []);
        update_post_meta($post_id, 'steps', $data['steps'] ?? []);
        update_post_meta($post_id, 'nutrition', $data['nutrition'] ?? []);
        update_post_meta($post_id, 'excerpt', $data['excerpt'] ?? '');
        update_post_meta($post_id, 'cook_time', $data['cook_time'] ?? '');
        update_post_meta($post_id, 'preparation_time', $data['preparation_time'] ?? '');
        update_post_meta($post_id, 'serves', $data['serves'] ?? '');
        update_post_meta($post_id, 'difficulty', $data['difficulty'] ?? '');
        update_post_meta($post_id, 'seo_title', $data['seo_title'] ?? '');
        update_post_meta($post_id, 'seo_description', $data['seo_description'] ?? '');
        update_post_meta($post_id, 'quick_description', $data['quick_description'] ?? '');
        update_post_meta($post_id, 'tags', $data['tags'] ?? []);
        update_post_meta($post_id, 'step_titles', $data['step_titles'] ?? []);
        update_post_meta($post_id, 'step_durations', $data['step_durations'] ?? []);
        update_post_meta($post_id, 'searchable_ingredients', false);
    }
}
