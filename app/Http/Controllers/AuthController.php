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

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(request $request) : JsonResponse
    {
        $valid_user = User::whereEmail($request->email)
            ->selectRaw('id, email, password')
            ->firstOrFail();
        
        if (Hash::check($request->password, $valid_user->password) === false) {
            throw new Exception('Invalid access credentials!', 401);
        }

        return successResponse(
            'User authenticated successfully!',
            [ 'token' => $valid_user->createToken('_kheme_')->accessToken ]
        );
    }

    /**
     * Deletes an existing authenticated user
     * 
     * @author Okiemute Omuta <iamkheme@gmail.com>
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy() : JsonResponse
    {
        auth()->user()->token()->revoke();

        return successResponse('Log out successful!');
    }
}
