<?php
// Returns array of supported AI providers and models
// Returns array of AI providers with info

function get_ai_providers() {

    return [

        'openai' => [

            'name' => 'OpenAI',

            'default_model' => 'gpt-4o-mini',

            'models' => [

                'gpt-4o-mini' => 'GPT-4o-mini',

                'gpt-4o' => 'GPT-4o',

                'gpt-3.5-turbo' => 'GPT-3.5 Turbo',

                'text-davinci-003' => 'Text Davinci 003',

            ],

        ],

        'openrouter' => [

            'name' => 'OpenRouter',

            'default_model' => 'openai/gpt-4o-mini',

            'models' => [

                'openai/gpt-4o-mini' => 'OpenRouter GPT-4o-mini',

                'openai/gpt-4o' => 'OpenRouter GPT-4o',

                'openai/gpt-3.5-turbo' => 'OpenRouter GPT-3.5 Turbo',

            ],

        ],

        'anthropic' => [

            'name' => 'Anthropic',

            'default_model' => 'claude-v1',

            'models' => [

                'claude-v1' => 'Claude v1',

                'claude-instant-v1' => 'Claude Instant v1',

            ],

        ],

        'cohere' => [

            'name' => 'Cohere',

            'default_model' => 'command-xlarge-nightly',

            'models' => [

                'command-xlarge-nightly' => 'Command XLarge Nightly',

                'command-large' => 'Command Large',

            ],

        ],

        'ai21' => [

            'name' => 'AI21',

            'default_model' => 'j2-large',

            'models' => [

                'j2-large' => 'J2 Large',

                'j2-jumbo' => 'J2 Jumbo',

            ],

        ],

        'google' => [

            'name' => 'Google PaLM',

            'default_model' => 'chat-bison-001',

            'models' => [

                'chat-bison-001' => 'Chat Bison 001',

            ],

        ],

        'microsoft' => [

            'name' => 'Microsoft',

            'default_model' => 'gpt-4o-mini',

            'models' => [

                'gpt-4o-mini' => 'GPT-4o-mini',

                'gpt-4o' => 'GPT-4o',

            ],

        ],

        'stability' => [

            'name' => 'Stability AI',

            'default_model' => 'stable-diffusion-xl',

            'models' => [

                'stable-diffusion-xl' => 'Stable Diffusion XL',

            ],

        ],

        'aleph-alpha' => [

            'name' => 'Aleph Alpha',

            'default_model' => 'luminous-extended',

            'models' => [

                'luminous-extended' => 'Luminous Extended',

            ],

        ],

        'huggingface' => [

            'name' => 'Hugging Face',

            'default_model' => 'gpt2',

            'models' => [

                'gpt2' => 'GPT-2',

                'gpt-j' => 'GPT-J',

            ],

        ],

        'replicate' => [

            'name' => 'Replicate',

            'default_model' => 'replicate-model-1',

            'models' => [

                'replicate-model-1' => 'Replicate Model 1',

            ],

        ],

        'youcom' => [

            'name' => 'You.com',

            'default_model' => 'you-chat',

            'models' => [

                'you-chat' => 'You Chat',

            ],

        ],

        // Add any other providers if needed here...

    ];

}

