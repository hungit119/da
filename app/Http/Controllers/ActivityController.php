<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Repositories\ActivitiesRepository;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    private Request $request;
    private ActivitiesRepository $activitiesRepo;
    public function __construct(
        Request $request,
        ActivitiesRepository $activitiesRepo
    )
    {
        $this->request = $request;
        $this->activitiesRepo = $activitiesRepo;
    }

    public function createComment() {
        $validated = $this->validateBase($this->request,[
            'content'=>'required',
            'user_id'=>'required',
            'card_id'=>'required',
        ]);

        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }

        $content = $this->request->get('content');
        $userID = $this->request->get('user_id');
        $cardID = $this->request->get('card_id');
        $parentID = $this->request->get('parent_id');

        $dataComment = [
            Activity::_USER_ID => $userID,
            Activity::_CONTENT => $content,
            Activity::_CARD_ID => $cardID,
            Activity::_TIME => time()
        ];
        if (isset($parentID)){
            $dataComment[Activity::_PARENT_ID] = $parentID;
        }
        $comment = $this->activitiesRepo->create($dataComment);
        $this->message = "Create comment successfully";
        $this->status = "success";
        return $this->responseData($comment);
    }

    public function getListComment() {
        $validated = $this->validateBase($this->request,[
            'card_id'=>'required',
        ]);

        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }

        $cardID = $this->request->get('card_id');

        $listComment = $this->activitiesRepo->findByCardID($cardID)->toArray();

        $this->status = 'success';
        $this->message = "Get list comment successfully";
        return $this->responseData($listComment);
    }
}
