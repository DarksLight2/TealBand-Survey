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
use Tealband\Survey\Data\Survey\AnalyticDTO;
use Tealband\Survey\Data\Survey\SurveyInfoDTO;
use Tealband\Survey\Data\Survey\CreateSurveyDTO;
use Tealband\Survey\Data\Survey\UpdateSurveyDTO;
use Tealband\Survey\Contracts\SurveyServiceContract;
use Tealband\Survey\Contracts\AnswerServiceContract;
use Tealband\Survey\Contracts\SummaryServiceContract;
use Tealband\Survey\Data\Question\CurrentEmployeeQuestionDTO;
use Tealband\Survey\Contracts\ClarifyingQuestionServiceContract;

/**
 * @template-extends CRUD<CreateSurveyDTO, SurveyDTO, UpdateSurveyDTO>
 */
class SurveyService implements SurveyServiceContract
{
    use CRUD;
    use Logger;

    protected string $model   = Survey::class;
    protected string $baseDTO = SurveyDTO::class;

    public function hasCompletedForEmployee(int|string $employeeId, int $surveyId): bool
    {
        # TODO
        return false;
    }

    public function hasActiveForEmployee(int|string $employeeId): SurveyDTO|bool
    {
        # TODO
        return false;
    }

    /**
     * Return survey session id for current employee
     */
    public function newEmployeeSession(
        int $milestone,
        int $surveyId,
        int|string $employeeId,
        int|string|null $departmentId,
    ): string
    {
        return '';
    }

    public function getCurrentEmployeeQuestion(): CurrentEmployeeQuestionDTO|null
    {

    }

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

    public function answer(): AnswerServiceContract
    {
        // TODO: Implement answer() method.
    }

    public function summary(): SummaryServiceContract
    {
        // TODO: Implement summary() method.
    }

    public function clarifyingQuestion(): ClarifyingQuestionServiceContract
    {
        // TODO: Implement clarifyingQuestion() method.
    }

    public function createFromTemplate()
    {
        // TODO: Implement createFromTemplate() method.
    }

    public function getInfo(int $surveyId): SurveyInfoDTO
    {
        // TODO: Implement getInfo() method.
    }

    public function analytic(int $milestone): AnalyticDTO
    {
        // TODO: Implement analytic() method.
    }

    public function getLimitations(int|string $userId): array
    {
        // TODO: Implement getLimitations() method.
    }
}
