<?php

namespace Homeful\KwYCCheck\Http\Controllers;

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Storage;       
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Exception\RequestException;

class EngageSparkController extends Controller
{   

    public function formatContactBody(Request $request)
    {
        $jsonBody = [
            "mobile" => $request->mobile,
            "firstName" => $request->firstName,
            "lastName" => $request->lastName,
            "groupId" => $request->groupId
        ];
        $response = $this->saveContact($jsonBody);
        return $response;


    }
    public function saveContact(array $arr_request = null ) {

            $jsonBody = [
                "fullPhoneNumber" => $arr_request['mobile'],
                "phoneNumberCountry" => "PH",
                "firstName" => $arr_request['firstName'],
                "lastName" => $arr_request['lastName'],
                "language" => "Filipino",
                "groups" => $arr_request['groupId']
            ];
        $headers = [
            'Content-Type' => "application/json",
            'Authorization' => "Token ".config('kwyc-check.engagespark.apiKey'),
        ];

        $requestURL = "https://api.engagespark.com/v1/organizations/" . config('kwyc-check.engagespark.orgId') . "/contacts/";

        $client = new Client();
        $response = $client->post($requestURL, [
            'json' => $jsonBody,
            'headers' => $headers
        ]);
    
        $responseBody = $response->getBody()->getContents();
        return $responseBody;
    }
    
}
