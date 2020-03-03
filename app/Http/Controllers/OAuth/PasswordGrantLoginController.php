<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2020/3/3
 * Time: 8:02 PM
 */

namespace App\Http\Controllers\OAuth;


use App\Core\Auth\LoginProxy;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Response;

class PasswordGrantLoginController extends Controller
{

    private $loginProxy;

    public function __construct(LoginProxy $loginProxy)
    {
        $this->loginProxy = $loginProxy;
    }

    public function login(LoginRequest $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $data = $this->loginProxy->attemptLogin($email, $password);
        $refreshToken = $data['refresh_token'];
        unset($data['refresh_token']);
        return Response::json($data
        )
            ->cookie(
                'refresh_token', $refreshToken, 100, null, null, false, true
            );
    }

    public function refresh(Request $request)
    {
        $refreshToken = Cookie::get('refresh_token');
        $data = $this->loginProxy->attemptRefresh($refreshToken);
        $refreshToken = $data['refresh_token'];
        unset($data['refresh_token']);
        return Response::json($data)
            ->cookie(
                'refresh_token', $refreshToken, 100, null, null, false, true
            );
    }

    public function logout()
    {
        $this->loginProxy->logout();

        return Response::json([], 204);
    }
}