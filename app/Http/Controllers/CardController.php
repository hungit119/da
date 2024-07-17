<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Part;
use App\Models\PartHasCard;
use App\Repositories\CardRepository;
use App\Repositories\PartHasCardRepository;
use App\Repositories\PartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    private Request        $request;
    private CardRepository $cardRepo;
    private PartHasCardRepository $partHasCardRepo;

    public function __construct(
        Request $request,
        CardRepository $cardRepo,
        PartHasCardRepository $partHasCardRepo
    ) {
        $this->request  = $request;
        $this->cardRepo = $cardRepo;
        $this->partHasCardRepo = $partHasCardRepo;
    }

    public function create()
    {
        $validated = $this->validateBase($this->request,[
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
            if (!isset($card)){
                DB::rollBack();
                $this->code = 500;
                $this->message = "create card failed";
                return $this->responseData();
            }
            $this->partHasCardRepo->create([
                PartHasCard::_CARD_ID => $card[Card::_ID],
                PartHasCard::_PART_ID => $partId,
            ]);

            DB::commit();
            $this->status = "success";
            $this->message = "create card success";
            return $this->responseData($card);
        }catch (\Exception $exception){
            DB::rollBack();
            $this->code = 500;
            $this->message = $exception->getMessage();
            return $this->responseData();
        }

    }

    public function list() {
        $validated = $this->validateBase($this->request,[
            'part_id' => 'required',
        ]);
        if ($validated) {
            $this->code = 400;
            return $this->responseData($validated);
        }

        $partId = $this->request->input('part_id');

        $select = [
            Card::_ID,
            Card::_NAME,
        ];
        $cards = $this->cardRepo->getListCardByPartID($select,$partId)->toArray();
        $this->status = "success";
        $this->message = "get cards success";
        return $this->responseData($cards);
    }
}
