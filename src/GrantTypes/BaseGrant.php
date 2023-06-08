<?php

namespace eFaasIntegrator\GrantTypes;

use GuzzleHttp\Client;
use eFaasIntegrator\Parsers\eFaasParser;

abstract class BaseGrant
{

    abstract function getToken();

    public function getUser()
    {
        if (session()->has('efaas.resolved_user')) {
            return session()->get('efaas.resolved_user');
        }

        $client = new Client();

        $resp = $client->get(config('efaas.oauth_route') . '/userinfo', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getToken()
            ]
        ]);


        if ($resp->getStatusCode() == 200) {
            $user =  eFaasParser::makeParser()->parse($resp->getBody());
            $model = app()->make(config('efaas.user_model'));
            return tap($model->makeUser($user), function ($user) {
                session()->put('efaas.resolved_user', $user);
            });
        }
    }
}
