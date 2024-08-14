<?php

namespace App\Http\Controllers;

use App\Models\CheckListItem;
use App\Repositories\CheckListItemRepository;
use App\Repositories\UserRepository;
use App\Services\AiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Nette\Utils\DateTime;

class CheckListItemController extends Controller
{
    private Request $request;
    private CheckListItemRepository $checkListItemRepo;
    private UserRepository $userRepo;
    private AiService  $aiService;
    public function __construct(
        Request $request,
        CheckListItemRepository $checkListItemRepo,
        UserRepository $userRepo,
        AiService $aiService
    )
    {
        $this->request = $request;
        $this->checkListItemRepo = $checkListItemRepo;
        $this->userRepo = $userRepo;
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
        $is_deleted = $this->request->get('is_deleted');
        if (isset($is_deleted)){
            $this->checkListItemRepo->update($id,[
                CheckListItem::_DELETED_AT => time(),
            ]);
            $this->message = "delete checklist item successfully";
            $this->status = "success";
            return $this->responseData();
        }

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
        if (isset($data)){
            $numberOfJobDone = $data->number_of_job_done;
            $totalOfJobDoneOnTime = $data->total_of_job_done_on_time;
            $totalOfJob = $data->total_of_job;
            $timeDoneAverage = json_decode($data->time_done_average);
            $yearExperience = $data->year_experience;
        }
        else {
            $numberOfJobDone = 0;
            $totalOfJobDoneOnTime = 0;
            $totalOfJob = 0;
            $timeDoneAverage = 0;
            $yearExperience = $this->userRepo->find($userID)->year_experience;

            $totalScore = 0;
            $newTotalOfJob = 1;
            $newTotalScore = $totalScore + $jobScore;
            goto next_step;
        }
        $totalScore = $data->average_job_score * $data->total_of_job;

        $newTotalScore = $totalScore + $jobScore;

        $newTotalOfJob = $data->total_of_job + 1;

        next_step:

        $newAverageJobScore = $newTotalScore / $newTotalOfJob;
        $feature = [
            'number_of_job_done' => $numberOfJobDone,
            'time_done_average' => $timeDoneAverage,
            'total_of_job_done_on_time' => $totalOfJobDoneOnTime,
            'total_of_job' => $totalOfJob,
            'average_job_score' => $newAverageJobScore,
            'year_experience' => $yearExperience
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
