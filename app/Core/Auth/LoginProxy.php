<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2020/3/3
 * Time: 9:49 PM
 */

namespace App\Core\Auth;


use App\User;
use Illuminate\Http\Request as LaravelRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LoginProxy
{
    const REFRESH_TOKEN = 'refreshToken';

    private $clientId;
    private $clientSecret;

    public function __construct($clientId = '6', $clientSecret = 'm9XQiHw76rpHCbqyk6WVZN4J9Utqhbz6acjVhQG9')
    {
        $this->clientSecret = $clientSecret;
        $this->clientId = 6;
    }

    public function attemptLogin(
        $username,
        $password,
        $scopes = []
    )
    {
        return $this->proxy(
            'password',
            [
                'username' => $username,
                'password' => $password,
                'scopes' => $this->formatScopes($scopes)
            ]
        );
    }


    public function attemptRefresh($refreshToken, array $scopes = [])
    {
        return $this->proxy(
            'refresh_token',
            [
                'refresh_token' => $refreshToken,
                'scopes' => $this->formatScopes($scopes)
            ]
        );
    }


    public function proxy($grantType, array $data = [])
    {
        $data = array_merge($data, [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => $grantType
        ]);

        $req = LaravelRequest::create('/oauth/token', 'POST', $data);
        /**
         * @var Response $response
         */
        $response = app()->handle($req);

        if ($response->getStatusCode() !== 200) {
            throw new HttpException(403, $response->getContent());
        }

        $data = json_decode($response->getContent(), 1);

        return $data;
    }


    public function logout()
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $accessToken = $user->token();

        // revoke refresh token
        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        // revoke access token
        $accessToken->revoke();
    }


    public function formatScopes($scopes = [])
    {
        return $scopes ? implode(' ', $scopes) : '';
    }

}