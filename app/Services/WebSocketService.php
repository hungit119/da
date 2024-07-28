<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use WebSocket\Client;

class WebSocketService
{
    const TREELO_WEB_MEMBER = "TREELO_WEB_MEMBER";
    const TREELO_WEB        = "TREELO_WEB";
    const SERVICE           = "TREELO_WEB";

    private $client;

    public function __construct()
    {
        $this->client = new Client(Config::get("environment.API_SERVICE_WEBSOCKET"));
    }
    public function sendMessage(mixed $message)
    {
        $this->client->send(json_encode($message));
    }
}
