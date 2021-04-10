<?php

namespace Src\Common;


trait APITrait {

    

    private function callAPI($methodType, $method, $data)
    {

        $curl = curl_init();
        $url = getenv('SUPERMETRICS_API_DOMAIN') . $method;

        switch ($methodType) {
            case 'POST':
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                   curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                   $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // set our url with curl_setopt()
        curl_setopt($curl, CURLOPT_URL, $url);

        // return the transfer as a string, also with setopt()
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // curl_exec() executes the started curl session
        // $output contains the output string
        $output = curl_exec($curl);

        // close curl resource to free up system resources
        // (deletes the variable made by curl_init)
        curl_close($curl);

        return $output;
    }

    

    public function invalidResponse($message)
    {
        $response['status_code_header'] = 'HTTP/1.1 422 ' . $message;
        $response['error'] = ['error' => $message];
        return $response;
    }

    public function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }

    public function validResponse($body)
    {
        $response['status_code_header'] = 'HTTP/1.1 200 Success';
        $response['body'] = ['data' => $body];
        return $response;
    }

    public function invalidMethodResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 400 Invalid Request Method Type';
        $response['error'] = ['error' => 'Invalid Request Method Type'];
        return $response;
    }
}
