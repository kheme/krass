<?php
/**
 * Controller for transfer recipients
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
 * Main RecipientController class
 *
 * @category  Controller
 * @package   App\Http\Controllers
 * @author    Okiemute Omuta <iamkheme@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights retained
 * @link      https://twitter.com/kheme
 */
class RecipientController extends Controller
{
    /**
     * Return a list of recipients for the authenticated user
     * 
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return json
     */
    public function index()
    {
        $recipients = [];

        foreach (Cache::get('recipients_' . auth()->id() ?? 0) as $recipient_code => $recipient_data) {
            $temp = $recipient_data;
            $temp['recipient_code'] = $recipient_code;
            $recipients[] = $temp;
        }

        return response()->json(
            [
                'success' => true,
                'data'    => $recipients,
            ]
        );
    }

    /**
     * Creates a new recipient for the authenticated user
     * 
     * @param \Illuminate\Http\Request $request HTTP request
     * 
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return json
     */
    public function store(Request $request)
    {
        $cached_recipients = Cache::get('recipients_' . auth()->id() ?? 0) ?? [];

        try {
            $response = Http::withHeaders([ 'Content-Type' => 'application/json' ])
                ->withToken(env('PAYSTACK_SECRET_KEY'))
                ->post('https://api.paystack.co/transferrecipient', [
                    'name'           => $request->name,
                    'bank_code'      => $request->bank_code,
                    'account_number' => $request->account_number,
                    'type'           => 'nuban',
                    'currency'       => 'NGN'
                ])->json();

            if ($response['status']) {
                $recipient_code = $response['data']['recipient_code'];

                $cached_recipients[$recipient_code] = [
                    'name'           => $request->name,
                    'bank_code'      => $request->bank_code,
                    'account_number' => $request->account_number,
                ];

                Cache::put('recipients_' . auth()->id() ?? 0, $cached_recipients);
                
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Recipient added successfully!',
                        'data'    => [ 'recipient_code' => $recipient_code ]
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

    /**
     * Deletes an existing recipient for the authenticated user
     * 
     * @param string $recipient_code Recipient code of the recipient to delete
     * 
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return json
     */
    public function destroy(string $recipient_code)
    {
        $cached_recipients = Cache::get('recipients_' . auth()->id() ?? 0);

        try {
            $response = Http::withHeaders([ 'Content-Type' => 'application/json' ])
                ->withToken(env('PAYSTACK_SECRET_KEY'))
                ->delete('https://api.paystack.co/transferrecipient/' . $recipient_code)
                ->json();

            if ($response['status']) {
                unset($cached_recipients[$recipient_code]);

                Cache::put('recipients_' . auth()->id() ?? 0, $cached_recipients);
                
                return response()->json(
                    [
                        'success' => true,
                        'message' => 'Recipient deleted successfully!',
                    ]
                );
            }
        } catch (\Exception $exception) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'There was an error deleting recipient'
                ],
                400
            );
        }
    }
}
