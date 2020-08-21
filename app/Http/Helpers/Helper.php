<?php
/**
 * Helper file
 *
 * PHP version 7
 *
 * @category  Helper
 * @package   App\Http\Helpers
 * @author    Okiemute Omuta <iamkheme@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights reserved.
 * @link      https://github.com/kheme
 */
namespace App\Http\Helpers;

/**
 * Main Helper class
 *
 * @category  Helper
 * @package   App\Http\Helpers
 * @author    Okiemute Omuta <iamkheme@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights reserved.
 * @link      https://github.com/kheme
 */
class Helper
{
    /**
     * Sends a success json response to the user
     *
     * @param string $message     (Optional) Message to include in response. Defaults to null.
     * @param array  $data        (Optional) Array of data to include in the response. Defaults to [].
     * @param int    $status_code (Optional) HTTP status code for the response. Defaults to 200.
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return json
     */
    static public function successResponse(string $message = null, ? array $data = [], int $status_code = 200) : \Illuminate\Http\JsonResponse
    {
        $response['success'] = true;

        if ($message) {
            $response['message'] = $message;
        }

        if ($data || $data === null) {
            $response['data'] = $data ?? [];
        }
        
        return response()->json($response, $status_code);
    }

    /**
     * Sends a success json response to the user
     *
     * @param array $message     Message to include in the response.
     * @param array $status_code (Optional) HTTP status code for the response. Defaults to 400.
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return json
     */
    static public function errorResponse(string $message, int $status_code = 400) : \Illuminate\Http\JsonResponse
    {        
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status_code);
    }
}