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
            'check_list_id' => 'required'
        ]);

        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }

        $name = $this->request->get('name');
        $checkListID = $this->request->get('check_list_id');

        $checklistItem = $this->checkListItemRepo->create([
            CheckListItem::_NAME => $name,
            CheckListItem::_CHECK_LIST_ID => $checkListID,
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
}
