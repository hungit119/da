<?php

namespace App\Http\Controllers;

use App\Models\Socket;
use App\Services\WebSocketService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private Request $request;
    private WebSocketService $socketService;
    public function __construct(
        Request $request,
        WebSocketService $socketService
    )
    {
        $this->request = $request;
        $this->socketService = $socketService;
    }

    public function sendNoti() {

        $validated = $this->validateBase($this->request,[
           'user_id' => 'required',
           'board_id' => 'required'
        ]);
        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }

        $userID = $this->request->get('user_id');
        $boardID = $this->request->get('board_id');

        $message = [
            'type'      => WebSocketService::TREELO_WEB_MEMBER,
            'service'   => WebSocketService::SERVICE,
            'condition' => [
                'user_id' => $userID,
                'board_id' => $boardID
            ],
            'data'      => [
                'action'       => Socket::ACTION_USER_ACCEPT_JOIN_BOARD,
                'board_id'      => $boardID,
            ]
        ];
        $this->socketService->sendMessage($message);

        $this->status = 'success';
        $this->message = "send noti success";
        return $this->responseData();
    }
}
