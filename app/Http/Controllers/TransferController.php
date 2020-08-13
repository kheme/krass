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
 * @license   All rights retained
 * @link      https://twitter.com/kheme
 */
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Cache;
use DB;

/**
 * Main TransferController class
 *
 * @category  Controller
 * @package   App\Http\Controllers
 * @author    Okiemute Omuta <iamkheme@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights retained
 * @link      https://twitter.com/kheme
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
     * @return json
     */
    public function index(Request $request)
    {
        $filtered_transfers = [];
        $search_keyword     = $request->query('search') ?? null;
        $cached_transfers   = collect(Cache::get('transfers_' . auth()->id() ?? 0) ?? []);
        
        if ($search_keyword) {
            foreach ($cached_transfers as $transfer) {
                if (collect($transfer)->search($search_keyword)) {
                    $filtered_transfers[] = $transfer;
                }
            }
        }

        return response()->json(
            [
                'success' => true,
                'data'    => $filtered_transfers ?? $cached_transfers,
            ]
        );
    }

    /**
     * Creates a new transfer for the authenticated user
     * 
     * @param \Illuminate\Http\Request $request HTTP request
     * 
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return json
     */
    public function store(Request $request)
    {
        $cached_transfers = Cache::get('transfers_' . auth()->id() ?? 0) ?? [];

        try {
            $response = Http::withHeaders([ 'Content-Type' => 'application/json' ])
                ->withToken(env('PAYSTACK_SECRET_KEY'))
                ->post('https://api.paystack.co/transfer', [
                    'source'    => 'balance',
                    'reason'    => $request->reason,
                    'amount'    => $request->amount,
                    'recipient' => $request->recipient
                ])->json();

            if ($response['status']) {
                $cached_transfers[] = $response['data'];

                Cache::put('transfers_' . auth()->id() ?? 0, $cached_transfers);
                
                return response()->json(
                    [
                        'success' => true,
                        'message' => $response['message'],
                        'data'    => $response['data']
                    ]
                );
            }
        } catch (\Exception $exception) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'There was an error adding recipient'
                ],
                400
            );
        }
    }
}
