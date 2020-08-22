<?php
/**
 * User controller
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
use App\Http\Requests\CreateUserRequest;
use App\Models\User;

/**
 * Main UserController class
 *
 * @category  Controller
 * @package   App\Http\Controllers
 * @author    Okiemute Omuta <iamkheme@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights reserved.
 * @link      https://github.com/kheme
 */
class UserController
{
    /**
     * Creat a new user
     *
     * @param \Illuminate\Http\Request $request HTTP request
     * 
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return json
     */
    public function store(CreateUserRequest $request)
    {
        $new_user = User::create($request->validatedData());

        return Helper::successResponse(
            'User created and authenticated successfully',
            [
                'id'    => $new_user->id,
                'token' => $new_user->createToken('_kheme_')->accessToken
            ]
        );
    }
}
