<?php
/**
 * Form request validator for POST /transfers
 *
 * PHP version 7
 *
 * @category  Validator
 * @package   App\Http\Requests
 * @author    Okiemute Omuta <iamkheme@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights reserved.
 * @link      https://github.com/kheme
 */
namespace App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Main CreateTransferRequest class
 *
 * @category  Validator
 * @package   App\Http\Requests
 * @author    Okiemute Omuta <iamkheme@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights reserved.
 * @link      https://github.com/kheme
 */
class CreateTransferRequest
{   
    protected $validator;

    /**
     * Initialize a new instance.
     *
     * @param \Illuminate\Http\Request $request HTTP request
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->validator = Validator::make($request->all(), [
            'reason'     => 'required',
            'amount'    => 'required|integer',
            'recipient' => 'required',
        ]);
    }

    /**
     * Return validated data from the validator
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return array
     */
    public function validatedData() : array
    {
        return array_merge($this->validator->validated(), [
            'source'   => 'source',
            'currency' => 'NGN',
        ]);
    }
}
