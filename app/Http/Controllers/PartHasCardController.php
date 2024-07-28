<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\PartHasCard;
use App\Repositories\PartHasCardRepository;
use Illuminate\Http\Request;

class PartHasCardController extends Controller
{
    private Request $request;
    private PartHasCardRepository $partHasCardRepo;
    public function __construct(
        Request $request,
        PartHasCardRepository $partHasCardRepo
    )
    {
        $this->request = $request;
        $this->partHasCardRepo = $partHasCardRepo;
    }

    public function updatePartCard () {
        $validated = $this->validateBase($this->request,[
           'card_id' => 'required',
           'source_part_id' => 'required',
           'destination_part_id' => 'required',
        ]);

        if ($validated) {
            $this->code = 400;
            $this->responseData($validated);
        }

        $cardID = $this->request->input('card_id');
        $sourcePartID = $this->request->input('source_part_id');
        $destinationPartID = $this->request->input('destination_part_id');

        $result = $this->partHasCardRepo->updateCardToPart($cardID, $sourcePartID, $destinationPartID);

        if (!$result){
            $this->code = 500;
            $this->message = "update part has card failed";
            $this->responseData($result);
        }
        $this->message = "Update part card success";
        $this->responseData($result);
    }
}
