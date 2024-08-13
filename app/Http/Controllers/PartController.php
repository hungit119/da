<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Repositories\BoardRepository;
use App\Repositories\PartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartController extends Controller
{
    //
    private Request         $request;
    private PartRepository  $partRepo;
    private BoardRepository $boardRepo;

    public function __construct(
        Request $request,
        PartRepository $partRepo,
        BoardRepository $boardRepo
    ) {
        $this->request   = $request;
        $this->partRepo  = $partRepo;
        $this->boardRepo = $boardRepo;
    }

    public function create()
    {
        $validated = $this->validateBase($this->request, [
            'name'     => 'required',
            'board_id' => 'required'
        ]);
        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }

        $name    = $this->request->input('name');
        $boardId = $this->request->input('board_id');

        $part = $this->partRepo->create([
            Part::_NAME => $name,
            Part::_BOARD_ID => $boardId
        ]);
        if (!isset($part)){
            $this->code = 500;
            $this->message = "create part fail";
            return $this->responseData();
        }
        $this->status = "success";
        $this->message = "create part success";
        return $this->responseData($part);
    }

    public function list () {
        $validated = $this->validateBase($this->request, [
            'board_id' => 'required'
        ]);
        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }
        $boardId = $this->request->input('board_id');

        $select = [
            Part::_ID,
            Part::_NAME,
            Part::_POSITION
        ];
        $parts = $this->partRepo->getListPartByBoardID($select,$boardId)->toArray();

        $this->message = "list part success";
        $this->status = "success";
        return $this->responseData($parts);
    }

    public function updatePosition () {
        $validated = $this->validateBase($this->request, [
            '*.id' => 'required',
            '*.position' => 'required'
        ]);

        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }
        $parts = $this->request->all();
        DB::beginTransaction();
        try {
            foreach ($parts as $part) {
                $this->partRepo->update($part[Part::_ID],[
                    Part::_POSITION => $part[Part::_POSITION]
                ]);
            }
            DB::commit();
            goto next;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->code = 500;
            $this->message = $e->getMessage();
            return $this->responseData();
        }
        next:
        $this->status = "success";
        $this->message = "update part success";
        return $this->responseData();
    }

    public function updatePart() {
    $validated = $this->validateBase($this->request, [
            'id' => 'required',
            'name' => 'required',
        ]);
        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }

        $id = $this->request->input('id');
        $name = $this->request->input('name');
        $isDeleted = $this->request->input('is_deleted');

        $dataUpdate = [];

        if (isset($name)){
            $dataUpdate[Part::_NAME] = $name;
        }
        if (isset($isDeleted)){
            $dataUpdate[Part::_DELETED_AT] = time();
        }

        $this->partRepo->update($id,$dataUpdate);
        $this->status = "success";
        $this->message = "update part success";
        return $this->responseData();
    }
}
