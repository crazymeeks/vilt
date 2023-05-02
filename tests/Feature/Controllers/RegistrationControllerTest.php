<?php


namespace Tests\Feature\Controllers;

use Tests\TestCase;
use Mockery\MockInterface;
use Ixudra\Curl\CurlService;

class RegistrationControllerTest extends TestCase
{


    
    public function testShouldRegisterUser()
    {


        $request = [
            'account_type' => 'this-must-be-the-real-account-type-uuid',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'username' => 'jdoe',
            'email' => 'christian@secuna.io',
            'password' => 'password',
            'password_confirmation' => 'password',
            'agree_tac' => 1,
        ];

        $this->mock(CurlService::class, function (MockInterface $mock) use ($request) {
            $mock->shouldReceive('to')
                 ->with('https://stg-api.secuna.io/api/v1/register')
                 ->andReturnSelf();
            $mock->shouldReceive('withData')
                 ->with($request)
                 ->andReturnSelf();
            $mock->shouldReceive('withHeader')
                 ->with('x-api-key: VplI/8G]Bz5Go+mCjzZh1')
                 ->andReturnSelf();
            $mock->shouldReceive('post')
                 ->andReturn(json_decode(json_encode([
                    'success' => true,
                    'message' => 'A confirmation link has been sent to email.',
                    'type' => 'ACCOUNT_CREATED',
                    'data' => json_decode(json_encode([
                        'access_token' => 'the-jwt-token',
                        'user_type' => 'org_user'
                    ]))
                 ])));
        });


        $response = $this->json('POST', route('api.v1.register'), $request);

        $this->assertEquals('Registration successful', $response->original['message']);
    }


    public function testShouldNotRegisterWhenApiRespondedWithError()
    {


        $request = [
            'account_type' => 'this-must-be-the-real-account-type-uuid',
            'firstname' => 'John',
            'lastname' => 'Doe',
            'username' => 'jdoe',
            'email' => 'christian@secuna.io',
            'password' => 'password',
            'password_confirmation' => 'password',
            'agree_tac' => 1,
        ];

        $this->mock(CurlService::class, function (MockInterface $mock) use ($request) {
            $mock->shouldReceive('to')
                 ->with('https://stg-api.secuna.io/api/v1/register')
                 ->andReturnSelf();
            $mock->shouldReceive('withData')
                 ->with($request)
                 ->andReturnSelf();
            $mock->shouldReceive('withHeader')
                 ->with('x-api-key: VplI/8G]Bz5Go+mCjzZh1')
                 ->andReturnSelf();
            $mock->shouldReceive('post')
                 ->andReturn(json_decode(json_encode([
                    'success' => false,
                    'message' => 'A confirmation link has been sent to email.',
                    'type' => 'ACCOUNT_CREATED',
                    'data' => json_decode(json_encode([
                        'access_token' => 'the-jwt-token',
                        'user_type' => 'org_user'
                    ]))
                 ])));
        });


        $response = $this->json('POST', route('api.v1.register'), $request);

        $this->assertEquals('Registration error', $response->original['message']);
    }
}