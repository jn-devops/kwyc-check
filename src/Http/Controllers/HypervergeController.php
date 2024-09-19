<?php

namespace Homeful\KwYCCheck\Http\Controllers;

use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Storage;       
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Exception\RequestException;

class HypervergeController extends Controller
{
    private function base_url() {
        return config('kwyc-check.credential.base_url'); 
    }
    private function default_filestore(){
        return config('kwyc-check.credential.defaultFileStorePath');
    }
    private function get_header($transactionId){

        $header = [
            'appId' => config('kwyc-check.credential.appId'),
            'appKey' => config('kwyc-check.credential.appKey'),
            'transactionId' => $transactionId
        ];
        return $header;
    }

    public function validate_live_url(Request $request){
        $headers = $this->get_header($request->referenceCode);
        $requestURL = $this->base_url()."/checkLiveness";
       
        if (!file_exists($request->imageURL)) {   
        return "File not found.";
        } 
        try{
            $client = new Client();
            $response = $client->post($requestURL, [
                'multipart' => [
                    [
                        'name'     => 'image',
                        'contents' => fopen($request->imageURL, 'r')
                    ]
                    ],
                'headers' => $headers
            ]);
            $responseBody = $response->getBody()->getContents();
            $resJSON = json_decode($responseBody);
            return $resJSON->result->summary;
        }
     catch (\Exception $e) {
        return ['action' => 'Image is unprocessable',
                'details' =>  $e->getMessage()];
        } 
    }
    public function validate_live_base64(Request $request){

        $headers = $this->get_header($request->referenceCode);
        $requestURL = $this->base_url()."/checkLiveness";
       
        $base64Img = substr($request->base64img, strpos($request->base64img, ',') + 1);
        $base64Img = base64_decode($request->base64img);
        Storage::put('public/image/' .  $request->referenceCode.".JPEG", $base64Img);
        
        $client = new Client();
        $response = $client->post($requestURL, [
            'multipart' => [
                [
                    'name'     => 'image',
                    'contents' => fopen(storage_path('app/public/image/'.  $request->referenceCode.".JPEG"), 'r')
                ]
                ],
            'headers' => $headers
        ]);
        $responseBody = $response->getBody()->getContents();
        Storage::delete('public/image/' .$request->referenceCode.".JPEG");
        // dd($responseBody);
        return $responseBody;
    }
    public function validate_id(Request $request){
     
        $headers = $this->get_header($request->referenceCode);
        $requestURL = $this->base_url()."/readId";
        
                if (file_exists($request->imageURL)) {
                return "File not found.";
                } 
        // if (file_exists(storage_path('image/' . $request->referenceCode.".JPG"))) {
        //     $imageURL =  storage_path('image/'.$request->referenceCode.".JPG");
        // }
        // else{
        //     return "File not found.";
        // }
        $client = new Client();
        // dd($request->documentId);
        $response = $client->post($requestURL, [
            'multipart' => [
                [
                    'name'     => 'image',
                    'contents' => fopen($imageURL, 'r')
                ],
                [
                    'name' => 'countryId',
                    'contents' => $request->countryId
                ],
                [
                    'name' => 'documentId',
                    'contents' => $request->documentId
                ]
                ],
            'headers' => $headers
        ]);
        $responseBody = $response->getBody()->getContents();

        return $responseBody;
    }
    public function face_match(Request $request){
        $headers = $this->get_header($request->image1Path);
        $requestURL = $this->base_url()."/matchFace";
        
        if (file_exists($request->image1URL)) {
            $image1URL =  storage_path('image/'.$request->image1Path.".JPG");
            } 
        else{
            return "File not found.";
            }
        if (file_exists($request->image2URL)) {
            $image2URL =  storage_path('image/'.$request->referenceCode.".JPG");
            } 
        else{
            return "File not found.";
        }
        
        $body =[ 
            [
            'name' => 'image1',
            'contents' =>fopen($image1URL, 'r')
            ],
            [
            'name' => 'image2',
            'contents' =>fopen($image2URL, 'r')
            ]
            ];

        if($request->type){
        $body[] =
        [
            'name' => 'type',
            'contents' => $request->type
        ];
         }
        // dd($body);
        $client = new Client();
        // dd($requestURL )
        // dd($request->documentId);
        $response = $client->post($requestURL, [
                'multipart' => $body,
                'headers' => $headers
            ]);
            
            $responseBody = $response->getBody()->getContents();
            
            // $responseBody = $response->getMessage();
            // dd($responseBody);
            // dd(json_decode( $response->getBody()->getContents()));
            return $responseBody;
       
    }
    public function face_match_base64(Request $request){
        dd(config('kwyc-check.credential.base_url'));
        $headers = $this->get_header($request->referenceCode);
        $requestURL = $this->base_url()."/matchFace";

        $base64Img = substr($request->base64img, strpos($request->base64img, ',') + 1);
        $base64Img = base64_decode($request->base64img);

        Storage::put('public/image/' .  $request->referenceCode.'.JPEG', $base64Img);
        $request->merge(['imageURL' => Storage::path('public/image/'.$request->referenceCode.".JPEG")]);
        //check liveliness
        try{
            $live_response = $this->validate_live_url($request);
            if($live_response->action != 'pass'){
                // return  $live_response->action;
                return ["Message" =>"Failed face check.",
                "Summary" => $live_response->details];
            }  
        }
        catch(e)
        {
            return ["Message" =>"Error in live check."];
        }

        $client = new Client();
        $filepath = $request->imagePath ? $request->imagePath :$this->default_filestore();

        if (file_exists(Storage::path('public/image/'.$request->referenceCode.".JPEG"))) {
            // $image2URL =  storage_path($filepath.'/'.$request->referenceCode.".JPG");
            $image1URL =  Storage::path('public/image/'.$request->referenceCode.".JPEG");          
        }
        else{
            return ["Message" =>"File not found."];
        }
        if (file_exists(storage_path($filepath.'/' . $request->referenceCode.".JPG"))) {
            $image2URL =  storage_path($filepath.'/'.$request->referenceCode.".JPG");  
        }
        else{
            Storage::delete('public/image/' .$request->referenceCode.".JPEG");//delete create image
            return ["Message" =>"File not found."];
        }
        $body =[ 
            [
            'name' => 'image1',
            'contents' =>fopen($image1URL, 'r')
            ],
            [
            'name' => 'image2',
            'contents' =>fopen($image2URL, 'r')
            ]
            ];

        if($request->type){
        $body[] =
        [
            'name' => 'type',
            'contents' => $request->type
        ];
         }
        $client = new Client();
        $response = $client->post($requestURL, [
                'multipart' => $body,
                'headers' => $headers
            ]);
            
            $responseBody = $response->getBody()->getContents();
            Storage::delete('public/image/' .$request->referenceCode.".JPEG");//delete create image
            return $responseBody;
       
    }

    public function save_temp_base64($base64img, $referenceCode){
        $base64Img = substr($base64img, strpos($base64img, ',') + 1);
        $base64Img = base64_decode($base64img);
        Storage::put('public/image/' .$referenceCode.".JPEG", $base64Img);
        return 'public/image/' .$referenceCode.".JPEG" ;
    }

}
