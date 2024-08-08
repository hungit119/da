<?php

namespace App\Http\Controllers;

use App\Models\CheckListItem;
use App\Repositories\CheckListItemRepository;
use Illuminate\Http\Request;

class CheckListItemController extends Controller
{
    private Request $request;
    private CheckListItemRepository $checkListItemRepo;
    public function __construct(
        Request $request,
        CheckListItemRepository $checkListItemRepo
    )
    {
        $this->request = $request;
        $this->checkListItemRepo = $checkListItemRepo;
    }

    public function create () {
        $validated = $this->validateBase($this->request,[
            'name' => 'required',
            'check_list_id' => 'required',
            'time_start' => 'required',
            'job_score' => 'required',
            'estimate_time_end' => 'required'
        ]);

        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }

        $name = $this->request->get('name');
        $checkListID = $this->request->get('check_list_id');
        $timeStart = $this->request->get('time_start');
        $jobScore = $this->request->get('job_score');
        $estimatedTimeEnd = $this->request->get('estimate_time_end');

        $checklistItem = $this->checkListItemRepo->create([
            CheckListItem::_NAME => $name,
            CheckListItem::_CHECK_LIST_ID => $checkListID,
            CheckListItem::_TIME_START => $timeStart,
            CheckListItem::_JOB_SCORE => $jobScore,
            CheckListItem::_ESTIMATED_TIME_END => $estimatedTimeEnd
        ]);

        if (!$checklistItem) {
            $this->code = 400;
            $this->message = "create checklist item failed";
            return $this->responseData($checkListID);
        }

        $this->message = "create checklist item successfully";
        $this->status = "success";
        return $this->responseData($checklistItem);

    }
    public function update () {
        $validated = $this->validateBase($this->request,[
            'id' => 'required',
            'status' => 'required',
        ]);

        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }
        $id = $this->request->get('id');
        $status = $this->request->get('status');

        $dataUpdate = [
            CheckListItem::_IS_CHECKED => $status,
            CheckListItem::_TIME_END => time(),
        ];

        $checkListItem = $this->checkListItemRepo->find($id);
        if ($checkListItem[CheckListItem::_ESTIMATED_TIME_END] > time()){
            $dataUpdate[CheckListItem::_JOB_DONE_ON_TIME] = CheckListItem::JOB_DONE_ON_TIME;
        }
        $this->checkListItemRepo->update($id, $dataUpdate);

        $this->status = "success";
        $this->message = "update checklist item successfully";
        return $this->responseData($id);
    }
}
