<?php
/**
 * Created by PhpStorm.
 * User: dadeng
 * Date: 2020/3/3
 * Time: 10:16 PM
 */

namespace Tests\Unit;


use Tests\TestCase;
use App\Core\Auth\LoginProxy;

class LoginProxyTest extends TestCase
{


    public function testPasswordGrant()
    {

        $proxy = new LoginProxy(
            '6',
            'm9XQiHw76rpHCbqyk6WVZN4J9Utqhbz6acjVhQG9'
        );

        $res = $proxy->attemptLogin(
            'ryandadeng@gmail.com',
            123123123
        );

        $this->assertArrayHasKey('expires_in', $res);
        $this->assertArrayHasKey('access_token', $res);




        $res = $proxy->attemptRefresh();
    }
}