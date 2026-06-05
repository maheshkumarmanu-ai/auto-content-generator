<?php

// Settings page

function content_generator_settings_page() {

    if (!current_user_can('manage_options')) wp_die('Unauthorized');



    if ($_SERVER['REQUEST_METHOD'] === 'POST' && check_admin_referer('content_generator_save_settings')) {

        $providers = get_ai_providers();



        foreach ($providers as $key => $provider) {

            update_option("content_generator_{$key}_enabled", isset($_POST["content_generator_{$key}_enabled"]));

            update_option("content_generator_{$key}_api_key", sanitize_text_field($_POST["content_generator_{$key}_api_key"]));

            update_option("content_generator_{$key}_model", sanitize_text_field($_POST["content_generator_{$key}_model"]));

        }



        echo '<div class="notice notice-success"><p>Settings saved successfully.</p></div>';

    }



    $providers = get_ai_providers();



    ?>



    <div class="wrap">

        <h1>Content Generator Settings</h1>

        <form method="post">

            <?php wp_nonce_field('content_generator_save_settings'); ?>

            <div class="api-settings-container">

                <?php foreach ($providers as $key => $provider): 

                    $enabled = get_option("content_generator_{$key}_enabled", false);

                    $api_key = get_option("content_generator_{$key}_api_key", '');

                    $model = get_option("content_generator_{$key}_model", $provider['default_model']);

                ?>

                <div class="api-provider" id="provider-<?php echo esc_attr($key); ?>">

                    <label class="checkbox-label">

                        <input type="checkbox" name="content_generator_<?php echo esc_attr($key); ?>_enabled" value="1" <?php checked($enabled); ?>>

                        Enable <?php echo esc_html($provider['name']); ?>

                    </label>



                    <label>

                        API Key:<br>

                        <input type="password" name="content_generator_<?php echo esc_attr($key); ?>_api_key" value="<?php echo esc_attr($api_key); ?>">

                    </label>



                    <label>

                        Model:<br>

                        <select name="content_generator_<?php echo esc_attr($key); ?>_model">

                            <?php foreach ($provider['models'] as $model_key => $model_name): ?>

                                <option value="<?php echo esc_attr($model_key); ?>" <?php selected($model_key, $model); ?>>

                                    <?php echo esc_html($model_name); ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </label>

                </div>

                <?php endforeach; ?>

            </div>



            <p><input type="submit" value="Save Settings" class="button button-primary"></p>

        </form>

    </div>

    <?php

}
