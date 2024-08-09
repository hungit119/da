<?php

namespace App\Services;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class CurlService
{
    private $request;

    public function __construct(
        Request $request
    )
    {
        $this->request = $request;
    }

    public function curlPost($url, $data = [], $token = null)
    {
        if (!$token) {
            $token = $this->request->header('token');
        }
        if (!$token) {
            $token = env('TOKEN_TO_SERVER');
        }
        if ($token) {
            $response = Curl::to($url)
                ->withData($data)
                ->withHeader('Token:' . $token)
                ->post();
            return $response;
        }
        $response = Curl::to($url)
            ->withData($data)
            ->post();

        return $response;
    }

    public function curlPostBoxme($url, $data, $token)
    {
        $response = Curl::to($url)
            ->withData($data)
            ->withHeader('Authorization:' . $token)
            ->asJson(true)
            ->post();
        return $response;
    }

    public function curlPostTinToc($url, $data, $token)
    {
        $response = Curl::to($url)
            ->withData($data)
            ->withHeader('Authorization:' . $token)
            ->withHeader('X-CompanyId:1')
            ->asJson(true)
            ->post();
        return $response;
    }


    public function curlPostAsJson($url, $data = [], $token = null)
    {
        if (!$token) {
            $token = $this->request->header('token');
        }
        if (!$token) {
            $token = env('TOKEN_TO_SERVER');
        }
        if ($token) {
            $response = Curl::to($url)
                ->withData($data)
                ->withHeader('Token:' . $token)
                ->asJson(true)
                ->post();
            return $response;
        }
        $response = Curl::to($url)
            ->withData($data)
            ->asJson(true)
            ->post();

        return $response;
    }

    public function curlGet($url, $data = [], $token = null, $checkHeader = true)
    {
        if (!$token || $token == "null") {
            $token = $this->request->header('token');
        }
        if (!$token || $token == "null") {
            $token = $this->request->input('token');
        }
        if (!$token || $token == "null") {
            $token = env('TOKEN_TO_SERVER');
        }
        if ($token && $checkHeader) {
            return Curl::to($url)
                ->withData($data)
                ->withHeader('Token:' . $token)
                ->get();
        }

        return Curl::to($url)
            ->withData($data)
            ->get();
    }

    public function curlGetReturnObject($url, $data = [])
    {
        $response     =  Curl::to($url)
                             ->withData($data)
                             ->returnResponseObject()
                             ->get();

        return $response;
    }

    public function curlPostReturnObject($url, $data = [])
    {
        $response     =  Curl::to($url)
            ->withData($data)
            ->returnResponseObject()
            ->post();

        return $response;
    }

    public function curlPostToServe($url, $data = [])
    {
        $response = Curl::to($url)
            ->withData($data)
            ->withHeader('Token:' . env('TOKEN_TO_SERVER'))
            ->post();

        return $response;
    }

    public function curlGetToServe($url, $data = [])
    {
        $response = Curl::to($url)
            ->withData($data)
            ->withHeader('Token:' . env('TOKEN_TO_SERVER'))
            ->get();

        return $response;
    }

    public function curlGetWithHeader($url, $data = [],$header = null)
    {
        $response = Curl::to($url)
            ->withData($data)
            ->withHeaders($header)
            ->asJson(true)
            ->get();
        return $response;
    }

    public function curlPostUploadFile($url, $data = [], $token = null, $file = null, $nameFile = null, $mimeType = null)
    {
        if (!$mimeType && $nameFile) {
            $mimeType = $file->getClientmimeType();
        }

        if (!$token) {
            $token = $this->request->header('token');
        }
        if (!$token) {
            $token = env('TOKEN_TO_SERVER');
        }
        if ($token) {
            if ($nameFile) {
                $response = Curl::to($url)
                    ->withData($data)
                    ->withHeader('Token:' . $token)
                    ->withFile('file', $file, $mimeType, $nameFile)
                    ->post();
            } else {
                $response = Curl::to($url)
                    ->withData($data)
                    ->withHeader('Token:' . $token)
                    ->withFile('file', $file)
                    ->post();
            }

            return $response;
        }

        if ($nameFile) {
            $response = Curl::to($url)
                ->withData($data)
                ->withFile('file', $file, $mimeType, $nameFile)
                ->post();
        } else {
            $response = Curl::to($url)
                ->withData($data)
                ->withFile('file', $file)
                ->post();
        }

        return $response;
    }

    public function curlPostData($url, $data = [])
    {
        $response = Curl::to($url)
            ->withData($data)
            ->post();

        return $response;
    }

    public function curlPostAuth($url, $data, $checkHeader = false, $token = '')
    {
        if ($checkHeader) {
            $response = Curl::to($url)
                ->withData(json_encode($data))
                ->withHeader('Content-Type:application/json')
                ->withHeader('Authorization:' . $token)
                ->post();
            return $response;
        }
        $response = Curl::to($url)
            ->withData($data)
            ->post();
        return $response;
    }

    public function curlPutAuth($url, $data, $checkHeader = false, $token = '')
    {
        if ($checkHeader) {
            $response = Curl::to($url)
                ->withData(json_encode($data))
                ->withHeader('Content-Type:application/json')
                ->withHeader('Authorization:' . $token)
                ->put();
            return $response;
        }
        $response = Curl::to($url)
            ->withData($data)
            ->put();
        return $response;
    }

    public function curlGetAuth($url, $data, $checkHeader = false, $token = '')
    {
        if ($checkHeader) {
            $response = Curl::to($url)
                ->withData(json_encode($data))
                ->withHeader('Authorization:' . $token)
                ->get();
            return $response;
        }
        $response = Curl::to($url)
            ->withData($data)
            ->get();

        return $response;
    }

    public function curlPut($url, $XPartnerCode, $XNonce, $XTimestamp, $signature, $data)
    {
        $response = Curl::to($url)
            ->withData($data)
            ->withHeader('X-Partner-Code:' . $XPartnerCode)
            ->withHeader('X-Nonce:' . $XNonce)
            ->withHeader('X-Timestamp:' . $XTimestamp)
            ->withHeader('X-Signature:' . $signature)
            ->withHeader('Content-Type:application/json')
            ->asJson(true)
            ->put();

        return $response;
    }

    public function pushEventClevertap($url, $accountId, $passCode, $data = [])
    {
        $response = Curl::to($url)
            ->withData($data)
            ->withHeader('X-CleverTap-Account-Id:' . $accountId)
            ->withHeader('X-CleverTap-Passcode:' . $passCode)
            ->withHeader('Content-Type:application/json; charset=utf-8')
            ->post();

        return $response;
    }

    public function curlPostSSC($url, $data, $token)
    {
        $response = Curl::to($url)
            ->withData($data)
            ->withHeader('Authorization:' . $token)
            ->post();
        return $response;
    }

    public function curlPostWithHeader($url, $data = [], $header=[])
    {
        $response = Curl::to($url)
            ->withData($data)
            ->withHeaders($header)
            ->asJson(true)
            ->post();
        return $response;
    }

    public function curlPutWithHeaders($url, $data = [], $headers=[]) {
        $response = Curl::to($url)
            ->withData($data)
            ->withHeaders($headers)
            ->asJson(true)
            ->put();

        return $response;
    }

    public function curlGetWithHeaders($url, $data, $headers)
    {
        return Curl::to($url)
            ->withData($data)
            ->withHeaders($headers)
            ->get();
    }

    public function curlDownload($link, $dir, $pathFile)
    {
        if (is_dir($dir) === false) {
            mkdir($dir, 0777, true);
        }
        return Curl::to($link)
            ->download($pathFile);
    }

    public function curlPostUploadWithFile($url, $data = [], $token = null, $file = null)
    {
        if (!$token) {
            $token = $this->request->header('token');
        }
        if (!$token) {
            $token = env('TOKEN_TO_SERVER');
        }

        if ($token) {
            $response = Curl::to($url)
                ->withData($data)
                ->withHeader('Token:' . $token)
                ->withFile('file', $file)
                ->post();
            return $response;
        }
        $response = Curl::to($url)
            ->withData($data)
            ->withFile('file', $file)
            ->post();

        return $response;
    }

    public function postDataToCleverTapCRM($data)
    {
        $url = config('environment.API_CLEVERTAP') . "/1/upload";
        if (is_array($data)) {
            $data = json_encode($data);
        }

        $accountId = '8WR-644-4R6Z';
        $passCode  = 'SCY-SAA-AWUL';

        $response = Curl::to($url)
            ->withData($data)
            ->withHeader('X-CleverTap-Account-Id:' . $accountId)
            ->withHeader('X-CleverTap-Passcode:' . $passCode)
            ->withHeader('Content-Type:application/json; charset=utf-8')
            ->post();

        if (is_string($response)) {
            $response = json_decode($response, true);
        }
        return $response;
    }
}
