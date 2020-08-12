<?php
/**
 * Controller for authenticating a user
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

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
use Auth;

/**
 * Main AuthController class
 *
 * @category  Controller
 * @package   App\Http\Controllers
 * @author    Okiemute Omuta <iamkheme@gmail.com>
 * @copyright 2020 Okiemute Omuta. All rights reserved.
 * @license   All rights retained
 * @link      https://twitter.com/kheme
 */
class AuthController extends Controller
{
    /**
     * Create a new authenticated user
     * 
     * @param \Illuminate\Http\Request $request HTTP request
     * 
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return json
     */
    public function store(request $request)
    {
        $valid_user = User::whereEmail($request->email)
            ->selectRaw('id, email, password')
            ->first();
        
        if ($valid_user === false) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'User not found!',
                ],
                404
            );
        }
        
        if (Hash::check($request->password, $valid_user->password) === false) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Invalid access credentials!',
                ],
                404
            );
        }

        return response()->json(
            [
                'success' => true,
                'message' => 'User authenticated successfully!',
                'data' => [
                    'token' => $valid_user->createToken('_kheme_')->accessToken
                ]
            ]
        );
    }

    /**
     * Deletes an existing authenticated user
     * 
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return json
     */
    public function destroy(request $request)
    {
        auth()->user()->token()->revoke();
        return response()->json(
            [
                'success' => true,
                'message' => 'Log out successful!',
            ],
            404
        );
    }
}
