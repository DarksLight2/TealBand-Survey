<?php

declare(strict_types=1);

namespace Tealband\Survey\Services;

use Throwable;
use Tealband\Survey\Traits\CRUD;
use Tealband\Survey\Models\Survey;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Tealband\Survey\Traits\Logger;
use Tealband\Survey\Data\Survey\SurveyDTO;
use Tealband\Survey\Models\EmployeeSession;
use Tealband\Survey\Data\Survey\CreateSurveyDTO;
use Tealband\Survey\Data\Survey\UpdateSurveyDTO;

/**
 * @template-extends CRUD<CreateSurveyDTO, SurveyDTO, UpdateSurveyDTO>
 */
class SurveyService
{
    use CRUD;
    use Logger;

    protected string $model   = Survey::class;
    protected string $baseDTO = SurveyDTO::class;

    public function getAllForUser(string $userId): Collection
    {
        return Survey::query()
            ->where(['user_id' => $userId])
            ->orWhereNull('user_id')
            ->get();
    }

    public function duplicateSurvey(Survey $survey, ?string $userId = null): ?Survey
    {
        try {
            return DB::transaction(function () use ($survey, $userId) {

                $newSurvey = $survey->replicate(['user_id']);
                $newSurvey->user_id = $userId;
                $newSurvey->save();

                foreach ($survey->questions as $question) {

                    $newQuestion = $question->replicate(['survey_id']);
                    $newQuestion->survey_id = $newSurvey->id;
                    $newQuestion->save();

                    foreach ($question->answers as $answer) {

                        $newAnswer = $answer->replicate(['question_id']);
                        $newAnswer->question_id = $newQuestion->id;
                        $newAnswer->save();
                    }
                }

                return $newSurvey;
            });
        } catch (Throwable $th) {
            $this->logger()->error($th->getMessage());
            return null;
        }
    }

    public function createDefaultSurveysForUser(string $userId): void
    {
        Survey::query()
            ->with(['questions.answers'])
            ->whereNull('user_id')
            ->each(function (Survey $survey) use ($userId) {
                $newSurvey = $this->duplicateSurvey($survey, $userId);

                if(is_null($newSurvey)) {
                    $this->logger()->warning("Survey $survey->id was not duplicated");
                }
            });
    }

    public function startSurvey(
        int $surveyId,
        string $employeeId,
        string $departmentId,
        int $milestone = 1
    ): string
    {
        $session = EmployeeSession::query()
            ->firstOrCreate([
                'employee_id' => $employeeId,
                'employee_type' => config('tealband-survey.models.employee'),
                'department_id' => $departmentId,
                'department_type' => config('tealband-survey.models.department'),
                'milestone' => $milestone,
                'survey_id' => $surveyId,
            ]);

        return $session->id;
    }
}
