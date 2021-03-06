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
 * @license   All rights reserved.
 * @link      https://github.com/kheme
 */
namespace App\Http\Controllers;

use Cache;
use Exception;
use App\Http\Helpers\Helper;
use App\Http\Requests\CreateRecipientRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;

/**
 * Main RecipientController class
 *
 * @category  Controller
 * @package   App\Http\Controllers
 * @author    Okiemute Omuta <iamkheme@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights reserved.
 * @link      https://github.com/kheme
 */
class RecipientController extends Controller
{
    /**
     * Return a list of recipients for the authenticated user
     * 
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() : JsonResponse
    {
        $recipients = [];

        foreach (Cache::get('recipients_' . auth()->id(), []) as $recipient_code => $recipient_data) {
            $recipients [] = array_merge($recipient_data, [ 'recipient_code' => $recipient_code ]);
        }

        return Helper::successResponse(null, ! $recipients ? null : $recipients);
    }

    /**
     * Creates a new recipient for the authenticated user
     * 
     * @param \Illuminate\Http\Request $request HTTP request
     * 
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateRecipientRequest $request) : JsonResponse
    {
        $response = $this->sendRequest('post', 'https://api.paystack.co/transferrecipient', $request->validatedData());
        
        $cached_recipients = Cache::get('recipients_' . auth()->id(), []);
        $recipient_code    = $response['data']['recipient_code'];

        $cached_recipients[$recipient_code] = $request->recipientData();

        Cache::put('recipients_' . auth()->id(), $cached_recipients);
        
        return Helper::successResponse('Recipient added successfully!', [ 'recipient_code' => $recipient_code ]);
    }

    /**
     * Deletes an existing recipient for the authenticated user
     * 
     * @param string $recipient_code Recipient code of the recipient to delete
     * 
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $recipient_code) : JsonResponse
    {
        $cached_recipients = Cache::get('recipients_' . auth()->id(), []);

        if (! isset($cached_recipients[$recipient_code])) {
            throw new Exception('Recipient not found!', 404);
        }

        $this->sendRequest('delete', 'https://api.paystack.co/transferrecipient/' . $recipient_code);

        unset($cached_recipients[$recipient_code]);

        Cache::put('recipients_' . auth()->id(), $cached_recipients);
        
        return Helper::successResponse('Recipient deleted successfully!');
    }
}
