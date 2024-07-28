<?php

if (env('APP_ENV') == 'live') {
    $urlConnectServiceWebsocket = 'ws://localhost:80';

} elseif (env('APP_ENV') == 'dev') {
    $urlConnectServiceWebsocket = 'ws://localhost:80';

} else {
    $urlConnectServiceWebsocket = 'ws://localhost:80';
}

return [
    'API_SERVICE_WEBSOCKET'   => $urlConnectServiceWebsocket,
];
