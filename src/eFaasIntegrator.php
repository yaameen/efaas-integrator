<?php

namespace eFaasIntegrator;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use eFaasIntegrator\GrantTypes\Hybrid;
use eFaasIntegrator\GrantTypes\Implicit;

class eFaasIntegrator
{

    public function buildQuery()
    {
        $query = http_build_query([
            'client_id' => config('efaas.client_id'),
            'redirect_uri' => config('efaas.redirect_uri'),
            'response_type' => config('efaas.response_type'),
            'scope' => config('efaas.scope'),
            'grant_type' => config('efaas.grant_type'),
            'nonce' => Str::random(10),
            'state' => Str::random(10),
        ]);

        return config('efaas.oauth_route') . '/authorize?' . $query;
    }

    public function buildLogoutQuery($id_token, $session_state)
    {
        $query = http_build_query([
            'id_token_hint' => $id_token,
            'post_logout_redirect_uris' => [config('efaas.post_logout_redirect_uri')],
            'state' => $session_state,

        ]);
        return config('efaas.end_session_uri') . '?' . $query;
    }

    public function efaasLogout()
    {
        if (session('efaas')) {
            abort(redirect($this->buildLogoutQuery()));
        }
    }

    public function isValidState($state)
    {
        return session()->get('efaas.session_state') == $state;
    }

    public function setSession($data)
    {
        if (!session('efaas')) {
            session()->put('efaas', $data);
        }


        return $this;
    }

    public function getUser()
    {
        $grant_type = $this->makeGrantType();

        if ($user = $grant_type->getUser()) {
            Auth::login($user, true);
            return $user;
        }
        $this->flush();
        return false;
    }

    public function home()
    {
        if ($intended = session('url.intended')) {
            session()->forget('url.intended');
            return url($intended);
        }
        return url(config('efaas.app.home'));
    }

    public function flush()
    {
        if (request()->hasSession()) {
            request()->session()->flush();
        }
        Auth::logout();
        return $this;
    }


    public function makeGrantType()
    {
        return app($this->getGrantType());
    }

    public function getGrantType()
    {
        $grant_type = Str::studly(config('efaas.grant_type'));

        // #todo move suitably
        $available_grants = [
            'AuthorizationCode' => Hybrid::class,
            'Implicit' => Implicit::class,
        ];

        if (array_key_exists($grant_type, $available_grants)) {
            return $available_grants[$grant_type];
        }

        throw new Exception("Invalid grant type for eFaas.");
    }
}
