<?php
/**
 * Form request validator for POST /recipients
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
 * Main CreateRecipientRequest class
 *
 * @category  Validator
 * @package   App\Http\Requests
 * @author    Okiemute Omuta <iamkheme@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights reserved.
 * @link      https://github.com/kheme
 */
class CreateRecipientRequest
{   
    protected $validator;
    protected $transfer_data;

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
            'bank_code'      => 'required|numeric',
            'account_number' => 'required|numeric',
            'name'           => 'required',
        ]);

        $this->transfer_data = [
            'type'           => 'nuban',
            'currency'       => 'NGN'
        ];
    }

    /**
     * Return validated data from the validator, with currency and transfer type
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return array
     */
    public function validatedData() : array
    {
        return array_merge($this->validator->validated(), $this->transfer_data);
    }

    /**
     * Return validated data from the validator, with currency and transfer type
     *
     * @author Okiemute Omuta <iamkheme@gmail.com>
     *
     * @return array
     */
    public function recipientData() : array
    {
        return collect($this->validatedData())->except(array_keys($this->transfer_data))->toArray();
    }
}
