<?php

declare(strict_types=1);

namespace Tealband\Survey\Services;

use Tealband\Survey\Traits\CRUD;
use Tealband\Survey\Models\Survey;
use Tealband\Survey\Traits\Logger;
use Tealband\Survey\Models\Answer;
use Tealband\Survey\Models\Question;
use Tealband\Survey\Models\Milestone;
use Tealband\Survey\Data\Survey\SurveyDTO;
use Tealband\Survey\Models\EmployeeSession;
use Tealband\Survey\Data\Survey\AnalyticDTO;
use Tealband\Survey\Data\Survey\SurveyInfoDTO;
use Tealband\Survey\Data\Survey\CreateSurveyDTO;
use Tealband\Survey\Data\Survey\UpdateSurveyDTO;
use Tealband\Survey\Enums\EmployeeSessionStatus;
use Tealband\Survey\Events\CreatedMilestoneEvent;
use Tealband\Survey\Contracts\SurveyServiceContract;
use Tealband\Survey\Contracts\AnswerServiceContract;
use Tealband\Survey\Contracts\SummaryServiceContract;
use Tealband\Survey\Events\CreatedSurveySessionEvent;
use Tealband\Survey\Data\Question\CurrentEmployeeQuestionDTO;
use Tealband\Survey\Contracts\ClarifyingQuestionServiceContract;
use Tealband\Survey\Data\Question\CurrentEmployeeQuestionAnswerDTO;

/**
 * @template-extends CRUD<CreateSurveyDTO, SurveyDTO, UpdateSurveyDTO>
 */
class SurveyService implements SurveyServiceContract
{
    use CRUD;
    use Logger;

    protected string $model   = Survey::class;
    protected string $baseDTO = SurveyDTO::class;

    public function answer(): AnswerServiceContract
    {
        return app(AnswerServiceContract::class);
    }

    public function summary(): SummaryServiceContract
    {
        return app(SummaryServiceContract::class);
    }

    public function clarifyingQuestion(): ClarifyingQuestionServiceContract
    {
        return app(ClarifyingQuestionServiceContract::class);
    }

    public function hasCompletedForEmployee(string $surveyId, string $userId, string $milestoneId): bool
    {
        $session = EmployeeSession::where([
            'user_id', $userId,
            'milestone_id' => $milestoneId,
            'survey_id' => $surveyId,
        ])
            ->latest()
            ->first();

        if(is_null($session)) return false;

        return $session->status === EmployeeSessionStatus::Finished;
    }

    public function hasActiveForEmployee(string $surveyId, string $userId, string $milestoneId): bool
    {
        $session = EmployeeSession::where([
            'user_id', $userId,
            'milestone_id' => $milestoneId,
            'survey_id' => $surveyId,
            'status' => EmployeeSessionStatus::Active,
        ])
            ->latest()
            ->first();

        return ! is_null($session);
    }

    /**
     * Create or find survey session id for current employee
     */
    public function newEmployeeSession(
        string $milestoneId,
        string $surveyId,
        string $userId,
        string $orgId,
    ): string
    {
        $session = EmployeeSession::firstOrCreate([
            'milestone_id' => $milestoneId,
            'survey_id' => $surveyId,
            'user_id' => $userId,
            'org_id' => $orgId,
            'status' => EmployeeSessionStatus::Active,
        ])
            ->latest()
            ->first();

        if($session->wasRecentlyCreated) {
            event(new CreatedSurveySessionEvent($session));
        }

        return $session->id;
    }

    public function getCurrentEmployeeQuestion(string $sessionId): CurrentEmployeeQuestionDTO|null
    {
        $session = EmployeeSession::with(['surveyResponse'])
            ->where(['id' => $sessionId])
            ->first();

        if(is_null($session)) return null;

        $answeredQuestions = $session->surveyResponse
            ->where('response', '!==', '')
            ->pluck('question_id')
            ->toArray();

        $question = Question::with(['answers'])
            ->where([
                'survey_id' => $session->survey_id,
            ])
            ->whereNotIn('id', $answeredQuestions)
            ->first();

        if(is_null($question)) return null;

        $answers = array_map(fn($answer) => new CurrentEmployeeQuestionAnswerDTO(
            id: $answer['id'],
            clarifying: $answer['clarifying'] ?? '',
            weight: $answer['weight'],
        ), $question->answers->toArray());

        return new CurrentEmployeeQuestionDTO(
            id: $question->id,
            text: $question->title,
            answers: $answers
        );
    }

    public function getAllForUser(int|string $userId): array
    {
        # TODO Узнать в каком формате вернуть
        return Survey::where(['user_id' => $userId])
            ->get();
    }

    public function createFromTemplate()
    {
        // TODO: Implement createFromTemplate() method.
    }

    public function getInfo(int $surveyId): SurveyInfoDTO
    {
        // TODO: Implement getInfo() method.
    }

    public function analytic(string $milestone): AnalyticDTO
    {
        // TODO: Implement analytic() method.
    }

    public function getLimitations(int|string $userId): array
    {
        // TODO: Implement getLimitations() method.
    }

    public function createMilestone(string $surveyId, string $orgId, int $value): string
    {
        $milestone = Milestone::query()->create([
            'survey_id' => $surveyId,
            'org_id' => $orgId,
            'value' => $value,
        ]);

        event(new CreatedMilestoneEvent($milestone));

        return $milestone->id;
    }
}
