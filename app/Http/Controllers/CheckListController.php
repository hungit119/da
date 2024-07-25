<?php

namespace App\Http\Controllers;

use App\Models\CheckList;
use App\Repositories\CheckListRepository;
use Illuminate\Http\Request;

class CheckListController extends Controller
{
    private Request $request;
    private CheckListRepository $checkListRepo;
    public function __construct(
        Request $request,
        CheckListRepository $checkListRepo
    )
    {
        $this->request = $request;
        $this->checkListRepo = $checkListRepo;
    }

    public function create () {
        $validated = $this->validateBase($this->request,[
            'name' => 'required',
            'card_id' => 'required'
        ]);
        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }

        $name = $this->request->get('name');
        $cardId = $this->request->get('card_id');

        $checkList = $this->checkListRepo->create([
           CheckList::_NAME => $name,
           CheckList::_CARD_ID => $cardId
        ]);
        if (!$checkList){
            $this->code = 400;
            $this->message = "create checklist failed";
            return $this->responseData($checkList);
        }

        $this->status = "success";
        $this->message = "create checklist success";
        return $this->responseData($checkList);
    }
}
