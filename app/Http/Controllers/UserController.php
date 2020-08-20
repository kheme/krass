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
 * @license   All rights retained
 * @link      https://twitter.com/kheme
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Main UserController class
 *
 * @category  Controller
 * @package   App\Http\Controllers
 * @author    Okiemute Omuta <iamkheme@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights retained
 * @link      https://twitter.com/kheme
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
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $new_user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => $request->password,
            ]);

            DB::commit();
            
            return response()->json(
                [
                    'success' => true,
                    'message' => 'User created and authenticated successfully',
                    'data'    => [
                        'id'    => $new_user->id,
                        'token' => $new_user->createToken('_kheme_')->accessToken
                    ]
                ]
            );
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json(
                [
                    'success' => 'false',
                    'message' => 'Could not create new user',
                ],
                500
            );
        }
    }
}
