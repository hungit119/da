<?php

namespace App\Http\Controllers;

use App\Models\CheckListItem;
use App\Repositories\CheckListItemRepository;
use App\Services\AiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Nette\Utils\DateTime;

class CheckListItemController extends Controller
{
    private Request $request;
    private CheckListItemRepository $checkListItemRepo;
    private AiService  $aiService;
    public function __construct(
        Request $request,
        CheckListItemRepository $checkListItemRepo,
        AiService $aiService
    )
    {
        $this->request = $request;
        $this->checkListItemRepo = $checkListItemRepo;
        $this->aiService = $aiService;
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

        $now  = Carbon::now()->timezone('asia/ho_chi_minh')->timestamp;
        $dataUpdate = [
            CheckListItem::_IS_CHECKED => $status,
            CheckListItem::_TIME_END => $now * 1000,
        ];

        $checkListItem = $this->checkListItemRepo->find($id);
        if ($checkListItem[CheckListItem::_ESTIMATED_TIME_END] > $now * 1000){
            $dataUpdate[CheckListItem::_JOB_DONE_ON_TIME] = CheckListItem::JOB_DONE_ON_TIME;
        }
        $this->checkListItemRepo->update($id, $dataUpdate);

        $this->status = "success";
        $this->message = "update checklist item successfully";
        return $this->responseData($id);
    }
    public function predictTimeEnd()
    {
        $validated = $this->validateBase($this->request,[
            'user_id' => 'required',
            'time_start' => 'required',
            'job_score' => 'required',
        ]);
        if ($validated){
            $this->code = 400;
            return $this->responseData($validated);
        }

        $userID = $this->request->get('user_id');
        $timeStart = $this->request->get('time_start');
        $jobScore = $this->request->get('job_score');

        $data = $this->checkListItemRepo->getFeature($userID);
        $totalScore = $data->average_job_score * $data->total_of_job;

        $newTotalScore = $totalScore + $jobScore;

        $newTotalOfJob = $data->total_of_job + 1;

        $newAverageJobScore = $newTotalScore / $newTotalOfJob;
        $feature = [
            'number_of_job_done' => $data->number_of_job_done,
            'time_done_average' => json_decode($data->time_done_average),
            'total_of_job_done_on_time' => $data->total_of_job_done_on_time,
            'total_of_job' => $data->total_of_job,
            'average_job_score' => $newAverageJobScore,
            'year_experience' => $data->year_experience,
        ];
        $response = $this->aiService->callFlaskApiFlask($feature);
        $endDate = 0;
        $timeEnd = 0;
        if (isset($response)){
            $completedTime = $response['completed_time'];
            // Convert milliseconds to seconds
            $timeEnd = $this->calculateTimeEnd($timeStart,$completedTime);
            $endDate = Carbon::createFromTimestampMs($timeEnd)->timezone("Asia/Ho_Chi_Minh")->format("Y-m-d H:i:s");
            goto next;

        }
    next:
        $this->message = "predict time end successfully";
        $this->status = "success";
        return $this->responseData([
            'end_date' => $endDate,
            'time_end' => $timeEnd,
        ]);
    }
    private function calculateTimeEnd($timeStart, $hours) {
        // Tạo đối tượng Carbon từ timestamp (mili-giây)
        $startTime = Carbon::createFromTimestampMs($timeStart);

        // Cộng số giờ vào start time
        $endTime = $startTime->addHours($hours);

        // Trả về time_end dưới dạng timestamp mili-giây
        return $endTime->getTimestampMs();
    }
}
