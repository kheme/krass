<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Handle POST requests to Paystack
     *
     * @param string $url
     * @param array $request_data
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return array
     */
    protected function sendRequest(string $method, string $url, array $request_data = []) : array
    {
        $response = Http::withHeaders([ 'Content-Type' => 'application/json' ])
            ->withToken(env('PAYSTACK_SECRET_KEY'))
            ->{$method}($url, $request_data);
        
        if (! $response->successful()) {
            throw new Exception($response->json()['message'], $response->status());
        }

        return $response->json();
    }
}
