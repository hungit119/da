<?php

namespace App\Services;

class AiService
{
    private CurlService $curlService;
    public function __construct(CurlService $curlService)
    {
        $this->curlService = $curlService;
    }

    public function predict(array $feature)
    {
        $url = "http://127.0.0.1:5000/predict";
        $data = $feature;
        $response = $this->curlService->curlPost($url,$data);
        return json_decode($response,true);
    }
}
