<?php

namespace eFaasIntegrator\Parsers;

use Carbon\Carbon;
use eFaasIntegrator\exceptions\AccessDeniedToProfileException;
use Illuminate\Support\Collection;

class eFaasParser {

    public static function makeParser()
    {
        return new self;
    }

    public function parse($object)
    {
        $genders = [
            'M' => 'male',
            'F' => 'female',
        ];
        $response = json_decode($object);

        // assuming if the returned payload includes the name, the rest of the profile would exist.
        if( !property_exists($response, 'name') ) {
            throw new AccessDeniedToProfileException("You must provide access to your profile on eFaas");
        }

        return new Collection([
            'id' => $response->sub,
            'name' => $response->name,
            'name_dv' => str_replace('  ', ' ', $response->fname_dhivehi.' '.$response->mname_dhivehi.' '.$response->lname_dhivehi),
            'gender' => $genders[$response->gender] ?? 'other',
            'date_of_birth' => Carbon::parse($response->birthdate),
            'identity_number' => $response->idnumber,
            'user_state' => (int)$response->user_state,
            'verification_level' => (int)$response->verification_level,
            'user_type' => (int)$response->user_type,
            'is_workpermit_active' => $response->is_workpermit_active == 'True',
            'passport_number' => $response->passport_number,
            'permanent_address' => json_decode($response->address),
            'current_address' => '',
        ]);
    }

    public function parseToken($response)
    {
        return json_decode($response);
    }
    
}