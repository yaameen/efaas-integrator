<?php

namespace eFaasIntegrator\GrantTypes;

use eFaasIntegrator\Parsers\eFaasParser;
use GuzzleHttp\Client;

class Implicit extends BaseGrant
{

    public function getToken()
    {
        if (request('code')) {
            return $this->getAccessToken(request('code'));
        }
        abort(redirect()->to(config('efaas.app.login_url')));
    }


    public function getAccessToken($code)
    {
        $client = new Client();
        $resp = $client->post(config('efaas.token_route'), [
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => config('efaas.client_id'),
                'client_secret' => config('efaas.client_secret'),
                'code' => $code,
                'redirect_uri' =>  config('efaas.redirect_uri')
            ],
        ]);

        if ($resp->getStatusCode() == 200) {
            $tokenResponse =  eFaasParser::makeParser()->parseToken($resp->getBody());
            if (property_exists($tokenResponse, 'id_token')) {
                return $tokenResponse->access_token;
            }
        }

        abort(302, 'Not authed');
    }
}
