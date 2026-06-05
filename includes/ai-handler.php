<?php

/**

 * Call AI API for given provider and prompt.

 * You need to implement provider-specific API calls here.

 * Return string response or WP_Error on failure.

 */

require_once plugin_dir_path(__FILE__) . 'ai-providers.php';

function pungus_call_ai_api($provider, $api_key, $model, $prompt) {

    $headers = [

        'Content-Type' => 'application/json',

    ];

    $body = [];

    $url = '';



    switch ($provider) {

        case 'openai':

            $url = 'https://api.openai.com/v1/chat/completions';

            $headers['Authorization'] = 'Bearer ' . $api_key;

            $body = [

                'model' => $model,

                'messages' => [['role' => 'user', 'content' => $prompt]],

                'max_tokens' => 1500,

                'temperature' => 0.7,

            ];

            break;



        case 'openrouter':

            $url = 'https://openrouter.ai/api/v1/chat/completions';

            $headers['Authorization'] = 'Bearer ' . $api_key;

            $body = [

                'model' => $model,

                'messages' => [['role' => 'user', 'content' => $prompt]],

                'max_tokens' => 1500,

                'temperature' => 0.7,

            ];

            break;



        case 'anthropic':

            $url = 'https://api.anthropic.com/v1/messages';

            $headers['x-api-key'] = $api_key;

            $headers['anthropic-version'] = '2023-06-01';

            $body = [

                'model' => $model,

                'messages' => [['role' => 'user', 'content' => $prompt]],

                'max_tokens' => 1500,

                'temperature' => 0.7,

            ];

            break;



        case 'cohere':

            $url = 'https://api.cohere.ai/v1/chat';

            $headers['Authorization'] = 'Bearer ' . $api_key;

            $body = [

                'model' => $model,

                'message' => $prompt,

                'temperature' => 0.7,

                'max_tokens' => 1500,

            ];

            break;



        case 'ai21':

            $url = "https://api.ai21.com/studio/v1/$model/complete";

            $headers['Authorization'] = 'Bearer ' . $api_key;

            $body = [

                'prompt' => $prompt,

                'temperature' => 0.7,

                'maxTokens' => 1500,

            ];

            break;



        case 'google':

            $url = 'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent?key=' . $api_key;

            $body = [

                'contents' => [['parts' => [['text' => $prompt]]]],

            ];

            break;



        case 'microsoft':

            $url = "https://api.openai.azure.com/v1/chat/completions"; // Adjust to region-specific endpoint

            $headers['api-key'] = $api_key;

            $headers['OpenAI-Model'] = $model;

            $body = [

                'messages' => [['role' => 'user', 'content' => $prompt]],

                'temperature' => 0.7,

                'max_tokens' => 1500,

            ];

            break;



        case 'stability':

            $url = 'https://api.stability.ai/v1/generate/text'; // Example URL

            $headers['Authorization'] = 'Bearer ' . $api_key;

            $body = [

                'model' => $model,

                'text_prompts' => [['text' => $prompt]],

                'cfg_scale' => 7,

            ];

            break;



        case 'alephalpha':

            $url = 'https://api.aleph-alpha.com/complete';

            $headers['Authorization'] = 'Bearer ' . $api_key;

            $body = [

                'model' => $model,

                'prompt' => $prompt,

                'maximum_tokens' => 1500,

                'temperature' => 0.7,

            ];

            break;



        case 'huggingface':

            $url = "https://api-inference.huggingface.co/models/{$model}";

            $headers['Authorization'] = 'Bearer ' . $api_key;

            $body = [

                'inputs' => $prompt,

            ];

            break;



        case 'replicate':

            $url = 'https://api.replicate.com/v1/predictions';

            $headers['Authorization'] = 'Token ' . $api_key;

            $body = [

                'version' => $model,

                'input' => ['prompt' => $prompt],

            ];

            break;



        case 'you':

            $url = 'https://api.you.com/v1/chat'; // Hypothetical URL

            $headers['Authorization'] = 'Bearer ' . $api_key;

            $body = [

                'model' => $model,

                'prompt' => $prompt,

            ];

            break;



        default:

            return new WP_Error('unknown_provider', 'Unknown AI provider');

    }



    $response = wp_remote_post($url, [

        'headers' => $headers,

        'body' => wp_json_encode($body),

        'timeout' => 60,

    ]);



    if (is_wp_error($response)) return $response;



    $code = wp_remote_retrieve_response_code($response);

    $resp_body = wp_remote_retrieve_body($response);



    if ($code !== 200) {

        return new WP_Error('api_error', "API returned status {$code}: {$resp_body}");

    }



    $json = json_decode($resp_body, true);



    // Handle response based on provider

    switch ($provider) {

        case 'openai':

        case 'openrouter':

        case 'microsoft':

            return $json['choices'][0]['message']['content'] ?? '';



        case 'anthropic':

            return $json['content'][0]['text'] ?? '';



        case 'cohere':

            return $json['text'] ?? '';



        case 'ai21':

            return $json['completions'][0]['data']['text'] ?? '';



        case 'google':

            return $json['candidates'][0]['content']['parts'][0]['text'] ?? '';



        case 'stability':

            return $json['artifacts'][0]['text'] ?? '';



        case 'alephalpha':

            return $json['completions'][0]['completion'] ?? '';



        case 'huggingface':

            if (isset($json[0]['generated_text'])) return $json[0]['generated_text'];

            return is_string($json) ? $json : wp_json_encode($json);



        case 'replicate':

            return $json['output'] ?? '';



        case 'you':

            return $json['text'] ?? '';



        default:

            return new WP_Error('unknown_response', 'Could not parse provider response.');

    }

}
