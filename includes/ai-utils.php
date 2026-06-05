<?php
/**

 * Extract JSON string from AI response, ignoring markdown or extra text.

 * Returns array or false on failure.

 */

function pungus_extract_json($response) {

    // Remove markdown code blocks or other formatting

    $json_str = trim($response);



    // Try to find first { ... } JSON substring

    if (preg_match('/\{.*\}/s', $json_str, $matches)) {

        $json_str = $matches[0];

        $data = json_decode($json_str, true);

        if (json_last_error() === JSON_ERROR_NONE) {

            return $data;

        }

    }



    return false;

}