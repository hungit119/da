<?php

namespace App\Services;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
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
        $data = [
            'feature' => $feature
        ];
        $response = $this->curlService->curlPost($url,$data);
        return json_decode($response,true);
    }
    public function sendDataToFlaskApi($data)
    {
        $client = new Client();
        // URL của Flask API
        $url = 'http://127.0.0.1:5000/predict';

        // Gửi yêu cầu POST
        $response = $client->post($url, [
            'feature' => $data, // Gửi dữ liệu dưới dạng JSON
        ]);

        // Nhận phản hồi từ Flask API
        $responseBody = $response->getBody()->getContents();
        return $responseBody;
    }

    public function callFlaskApi()
    {
        $response = Http::post('http://127.0.0.1:5000/predict', [
            'feature' => [
                'average_job_score' => 14,
                'year_experience' => 3,
                'number_of_job_done' => 10,
                'time_done_average' => 10.65,
                'total_of_job_done_on_time' => 5,
                'total_of_job' => 20,
            ],
        ]);

        if ($response->successful()) {
            // Handle successful response
            return $response->json();
        } else {
            // Handle error response
            return response()->json(['error' => 'Failed to call Flask API'], 500);
        }
    }
    public function callFlaskApiFlask($data)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post('http://127.0.0.1:5000/predict', [
            'feature' => $data,
        ]);

        if ($response->successful()) {
            return $response->json();
        } else {
            return response()->json(['error' => 'Failed to call Flask API'], 500);
        }
    }
}
