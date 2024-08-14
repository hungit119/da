<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Card;
use App\Models\Part;
use App\Models\PartHasCard;
use App\Repositories\AttachmentRepository;
use App\Repositories\CardRepository;
use App\Repositories\PartHasCardRepository;
use App\Repositories\PartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    private Request               $request;
    private CardRepository        $cardRepo;
    private PartHasCardRepository $partHasCardRepo;
    private AttachmentRepository $attachmentRepo;
    public function __construct(
        Request $request,
        CardRepository $cardRepo,
        PartHasCardRepository $partHasCardRepo,
        AttachmentRepository $attachmentRepo
    ) {
        $this->request         = $request;
        $this->cardRepo        = $cardRepo;
        $this->partHasCardRepo = $partHasCardRepo;
        $this->attachmentRepo  = $attachmentRepo;
    }

    public function create()
    {
        $validated = $this->validateBase($this->request, [
            'name'    => 'required',
            'part_id' => 'required',
        ]);
        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }
        $name   = $this->request->input('name');
        $partId = $this->request->input('part_id');

        DB::beginTransaction();
        try {
            $card = $this->cardRepo->create([
                Card::_NAME => $name,
            ]);
            if (!isset($card)) {
                DB::rollBack();
                $this->code    = 500;
                $this->message = "create card failed";
                return $this->responseData();
            }
            $this->partHasCardRepo->create([
                PartHasCard::_CARD_ID => $card[Card::_ID],
                PartHasCard::_PART_ID => $partId,
            ]);

            DB::commit();
            $this->status  = "success";
            $this->message = "create card success";
            return $this->responseData($card);
        } catch (\Exception $exception) {
            DB::rollBack();
            $this->code    = 500;
            $this->message = $exception->getMessage();
            return $this->responseData();
        }

    }

    public function list()
    {
        $validated = $this->validateBase($this->request, [
            'part_id' => 'required',
        ]);
        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }

        $partId = $this->request->input('part_id');

        $select        = [
            Card::_ID,
            Card::_NAME,
        ];
        $cards         = $this->cardRepo->getListCardByPartID($select, $partId)->toArray();
        $this->status  = "success";
        $this->message = "get cards success";
        return $this->responseData($cards);
    }

    public function saveCard()
    {
        $validated = $this->validateBase($this->request, [
            'card_id'     => 'required',
            'name'        => 'required',
            'description' => 'required',
        ]);
        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }
        $cardId      = $this->request->input('card_id');
        $name        = $this->request->input('name');
        $description = $this->request->input('description');
        $images      = $this->request->input('images');
        $files        = $this->request->input('files');

        $card = $this->cardRepo->findByID($cardId);
        if (!isset($card)) {
            $this->code    = 500;
            $this->message = "card not found";
            return $this->responseData();
        }
        DB::beginTransaction();
        try {
            $result = $this->cardRepo->update($card[Card::_ID], [
                Card::_NAME        => $name,
                Card::_DESCRIPTION => $description,
            ]);

            if (!$result) {
                DB::rollBack();
                $this->code    = 500;
                $this->message = "update card failed";
                return $this->responseData();
            }

            $newAttachment = [];
            $attachments   = [];
            foreach ($images as $image) {
                if (!isset($image[Attachment::_ID])){
                    $newAttachment[] = $image;
                }
                else {
                    $attachments[] = $image;
                }
            }

            $this->attachmentRepo->insert($this->prepareAttachmentInsertData($newAttachment,$card[Card::_ID]));

            foreach ($attachments as $attachment) {
                $dataUpdate = [];
                if ($attachment[Attachment::_URL]) {
                    $dataUpdate[Attachment::_URL] = $attachment[Attachment::_URL];
                }
                if ($attachment[Attachment::_CONTENT]) {
                    $dataUpdate[Attachment::_CONTENT] = $attachment[Attachment::_CONTENT];
                }

                $this->attachmentRepo->update($attachment[Attachment::_ID], $dataUpdate);
            }
            DB::commit();
            $this->status  = "success";
            $this->message = "update card success";
            return $this->responseData();
        }catch (\Exception $e) {
            DB::rollBack();
            $this->code    = 500;
            $this->message = $e->getMessage();
            return $this->responseData();
        }


    }

    private function prepareAttachmentInsertData(array $newAttachment, $cardID)
    {
        $dataInsert = [];
        foreach ($newAttachment as $attachment) {
            $dataInsert[] = [
                Attachment::_URL => $attachment['url'],
                Attachment::_TYPE => Attachment::TYPE_IMAGE,
                Attachment::_CARD_ID => $cardID,
                Attachment::_CONTENT => $attachment['content'],
                Attachment::_CREATED_AT => date('Y-m-d H:i:s'),
                Attachment::_UPDATED_AT => date('Y-m-d H:i:s'),
            ];
        }
        return $dataInsert;
    }

    public function updateCard()
    {
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
            $dataUpdate[Card::_NAME] = $name;
        }
        if (isset($isDeleted)){
            $dataUpdate[Card::_DELETED_AT] = time();
        }
        $this->cardRepo->update($id, $dataUpdate);
        $this->status  = "success";
        $this->message = "update card success";
        return $this->responseData();
    }
}
