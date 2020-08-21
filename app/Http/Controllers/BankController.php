<?php
/**
 * Controller for banks
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
use Illuminate\Support\Facades\Http;
use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Http\JsonResponse;

/**
 * Main BankController class
 *
 * @category  Controller
 * @package   App\Http\Controllers
 * @author    Okiemute Omuta <iamkheme@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights reserved.
 * @link      https://github.com/kheme
 */
class BankController extends Controller
{
    /**
     * Retrieve a list of banks from Paystack and cache until the end of the day
     * 
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() : JsonResponse
    {
        return Helper::successResponse(null, Cache::remember(
            'paystack_banks',
            Carbon::now()->endOfDay(),
            function () {
                return Http::get('https://api.paystack.co/bank')->throw()->json()['data'];
            }
        ));
    }
}
