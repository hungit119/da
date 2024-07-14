<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\BoardHasUser;
use App\Repositories\BoardHasUserRepository;
use App\Repositories\BoardRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoardController extends Controller
{
    private Request         $request;
    private BoardRepository $boardRepo;
    private BoardHasUserRepository $boardHasUserRepo;

    public function __construct(
        Request $request,
        BoardRepository $boardRepo,
        BoardHasUserRepository $boardHasUserRepo
    ) {
        $this->request   = $request;
        $this->boardRepo = $boardRepo;
        $this->boardHasUserRepo = $boardHasUserRepo;
    }

    public function create()
    {
        $validatedData = $this->validateBase($this->request, [
            'name'    => 'required',
            'type'    => 'required',
            'user_id' => 'required'
        ]);

        if ($validatedData) {
            $this->code = 400;
            return $this->responseData($validatedData);
        }

        $name   = $this->request->input('name');
        $type   = $this->request->input('type');
        $avatar = $this->request->input('avatar');
        $userId = $this->request->input('user_id');

        $data = [
            Board::_NAME   => $name,
            Board::_TYPE   => $type,
            Board::_AVATAR => $avatar
        ];
        DB::beginTransaction();
        try {
            $result = $this->boardRepo->create($data);
            if (isset($result)) {
                $data = [
                    BoardHasUser::_USER_ID => $userId,
                    BoardHasUser::_BOARD_ID => $result[Board::_ID],
                ];
                $resultPilot = $this->boardHasUserRepo->create($data);
                if (!isset($resultPilot)){
                    DB::rollBack();
                    $this->code = 400;
                    $this->message = 'Gắn bảng vào user thất bại';
                    return $this->responseData();
                }
                DB::commit();
                $this->message = 'Tạo bảng thành công';
                $this->status = 'success';
                return $this->responseData($result);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $this->code = 400;
            $this->message = $e->getMessage();
            return $this->responseData($e);
        }
    }

    public function list () {
        $validatedData = $this->validateBase($this->request, [
            'user_id' => 'required'
        ]);
        if ($validatedData) {
            $this->code = 400;
            return $this->responseData($validatedData);
        }

        $userId = $this->request->input('user_id');
        $select = [
            Board::TABLE . '.' . Board::_ID,
            Board::TABLE . '.' . Board::_AVATAR,
            Board::TABLE . '.' . Board::_NAME,
        ];

        $listBoard = $this->boardRepo->getListBoardByUserID($select,$userId)->toArray();
        $this->status = "success";
        return $this->responseData($listBoard);
    }

    public function delete () {
        $validatedData = $this->validateBase($this->request, [
            'board_id' => 'required',
        ]);
        if ($validatedData) {
            $this->code = 400;
            return $this->responseData($validatedData);
        }
        $boardId = $this->request->input('board_id');
        $result = $this->boardRepo->update($boardId,[
            Board::_DELETED_AT => time()
        ]);
        if (isset($result)) {
            $this->code = 200;
            return $this->responseData($result);
        }
        $this->code = 400;
        $this->message = 'delete fail';
        return $this->responseData($result);
    }

    public function update () {
        $validatedData = $this->validateBase($this->request, [
            'board_id' => 'required',
        ]);
        if ($validatedData) {
            $this->code = 400;
            return $this->responseData($validatedData);
        }

        $boardId = $this->request->input('board_id');
        $name   = $this->request->input('name');
        $type   = $this->request->input('type');
        $avatar = $this->request->input('avatar');

        $dataUpdate = [];
        if (isset($boardId)){
            $dataUpdate[Board::_NAME] = $name;
        }
        if (isset($type)){
            $dataUpdate[Board::_TYPE] = $type;
        }
        if (isset($avatar)){
            $dataUpdate[Board::_AVATAR] = $avatar;
        }
        $result = $this->boardRepo->update($boardId, $dataUpdate);
        if (isset($result)) {
            $this->code = 200;
            $this->status = "success";
            $this-> message = "Update board success";
            return $this->responseData($result);
        }
        $this->code = 400;
        $this->message = 'update fail';
        return $this->responseData($result);
    }

    public function get () {
        $validatedData = $this->validateBase($this->request, [
            'board_id' => 'required',
        ]);
        if ($validatedData) {
            $this->code = 400;
            return $this->responseData($validatedData);
        }
        $boardId = $this->request->input('board_id');
        $result = $this->boardRepo->find($boardId);
        if (isset($result)) {
            $this->code = 200;
            return $this->responseData($result);
        }
        $this->code = 400;
        $this->message = 'get fail';
        return $this->responseData($result);
    }
}
