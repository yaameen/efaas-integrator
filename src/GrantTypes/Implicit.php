<?php

namespace eFaasIntegrator\GrantTypes;

class Implicit extends BaseGrant
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
