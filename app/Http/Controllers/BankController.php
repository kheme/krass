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
 * @license   All rights retained
 * @link      https://twitter.com/kheme
 */
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Cache;
use DB;

/**
 * Main BankController class
 *
 * @category  Controller
 * @package   App\Http\Controllers
 * @author    Okiemute Omuta <iamkheme@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights retained
 * @link      https://twitter.com/kheme
 */
class BankController extends Controller
{
    /**
     * Return a list of banks cached from Paystack
     * 
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return json
     */
    public function index()
    {
        return response()->json(
            [
                'success' => true,
                'data'    =>  Cache::rememberForever(
                    'paystack_banks',
                    function () {
                        return Http::get('https://api.paystack.co/bank')->json()['data'];
                    }
                )
            ]
        );
    }
}
