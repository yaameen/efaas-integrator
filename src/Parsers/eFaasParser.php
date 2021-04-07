<?php

namespace eFaasIntegrator\Parsers;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class eFaasParser {

    public static function makeParser()
    {
        return new self;
    }

    public function parse($object)
    {
        // name	varchar(255)	 
    // name_dv	varchar(255)	 
    // gender	enum('male','female','other')	 
    // date_of_birth	date	 
    // identity_number	varchar(255) NULL	 
    // passport_number	varchar(255) NULL	 
    // permanent_address_id	int(10) unsigned	 
    // current_address_id	int(10) unsigned NULL	 


    // "name": "Mohamed KG Farish",
    // "given_name": "Mohamed",
    // "family_name": "Farish",
    // "middle_name": "",
    // "gender": "M",
    // "idnumber": "A099420",
    // "email": "faishknights@gmdail.com",
    // "phone_number": "9939900",
    // "address": "{\"AddressLine1\": \" Light Garden\", \"AddressLine2\": \" \", \"Road\": \" \", \"AtollAbbreviation\": \"K\", \"IslandName\": \"Male'\", \"HomeNameDhivehi\": \"ލައިޓްގާރޑްން\", \"Ward\": \"Maafannu\", \"Country\": \"462\" }",
    // "fname_dhivehi": "މުޙައްމަދު",
    // "mname_dhivehi": "",
    // "lname_dhivehi": "ފާރިޝް",
    // "user_type": "1",
    // "verification_level": "300",
    // "user_state": "3",
    // "birthdate": "10/28/1987",
    // "passport_number": "",
    // "is_workpermit_active": "False",
    // "updated_at": "9/10/2013 8:56:58 AM",
    // "sub": "c2c2f269-571d-433c-b51a-cc7eee8041f8"
        $genders = [
            'M' => 'male',
            'F' => 'female',
        ];
        $response = json_decode($object);
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
        $response = json_decode($response);
        return $response;
    }
    
}