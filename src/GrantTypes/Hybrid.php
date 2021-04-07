<?php namespace eFaasIntegrator\GrantTypes;

class Hybrid extends BaseGrant {

    public function getToken()
    {
        $token_details = session('efaas');
        
        if($token_details = session('efaas') && array_key_exists('access_token', $token_details)) {
            return $token_details['access_token'];
        }

        abort(redirect()->to(config('efaas.app.login_url')));
    }
}