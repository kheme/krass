<?php
/**
 * Controller for transfers
 *
 * PHP version 7
 *
 * @category  Controller
 * @package   App\Http\Controllers
 * @author    Okiemute Omuta <iamkheme@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights reserved.
 * @link      https://github.com/kheme
 */
namespace App\Http\Controllers;

use App\Http\Helpers\Helper;
use App\Http\Requests\CreateTransferRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Cache;
use DB;
use Exception;

/**
 * Main TransferController class
 *
 * @category  Controller
 * @package   App\Http\Controllers
 * @author    Okiemute Omuta <iamkheme@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights reserved.
 * @link      https://github.com/kheme
 */
class TransferController extends Controller
{
    /**
     * Return a list of transfer history for the authenticated user
     * 
     * @param \Illuminate\Http\Request $request HTTP request
     * 
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        $search_keyword   = $request->query('search') ?? null;
        $cached_transfers = collect(Cache::get('transfers_' . auth()->id(), []))->toArray();
        
        if ($search_keyword) {
            foreach ($cached_transfers as $transfer) {
                if (collect($transfer)->search($search_keyword)) {
                    $filtered_transfers[] = $transfer;
                }
            }
        }
        
        $transfers = $filtered_transfers ?? $cached_transfers;

        return Helper::successResponse(null, $transfers);
    }

    /**
     * Creates a new transfer for the authenticated user
     * 
     * @param \Illuminate\Http\Request $request HTTP request
     * 
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateTransferRequest $request) : JsonResponse
    {
        $cached_transfers = Cache::get('transfers_' . auth()->id(), []);
        
        $response = Http::withHeaders([ 'Content-Type' => 'application/json' ])
                ->withToken(env('PAYSTACK_SECRET_KEY'))
                ->post('https://api.paystack.co/transfer', $request->validatedData());

        if (! $response->successful()) {
            throw new Exception($response->json()['message'], $response->status());
        }

        $cached_transfers[] = $response->json()['data'];

        Cache::put('transfers_' . auth()->id(), $cached_transfers);
        
        return Helper::successResponse($response['message'], $response['data']);
    }
}
