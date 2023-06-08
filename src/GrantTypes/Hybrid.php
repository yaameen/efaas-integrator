<?php

namespace eFaasIntegrator\GrantTypes;

use GuzzleHttp\Client;
use eFaasIntegrator\Parsers\eFaasParser;

class Hybrid extends BaseGrant
{
    public function getToken()
    {
        $token_details = session('efaas');

        if ($token_details && array_key_exists('id_token', $token_details)) {
            return $token_details['id_token'];
        }

        abort(redirect()->to(config('efaas.app.login_url')));
    }
}
