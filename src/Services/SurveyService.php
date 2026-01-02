<?php

declare(strict_types=1);

namespace Tealband\Survey\Services;

use Tealband\Survey\Models\Survey;
use Tealband\Survey\Models\Question;
use Tealband\Survey\Models\Milestone;
use Tealband\Survey\Models\SurveyResponse;
use Tealband\Survey\Models\EmployeeSession;
use Tealband\Survey\Enums\SurveyResponseStatus;
use Tealband\Survey\Enums\EmployeeSessionStatus;
use Tealband\Survey\Events\CreatedMilestoneEvent;
use Tealband\Survey\Contracts\SurveyServiceContract;
use Tealband\Survey\Contracts\AnswerServiceContract;
use Tealband\Survey\Events\SurveyQuestionsIsEndEvent;
use Tealband\Survey\Contracts\SummarizerServiceContract;
use Tealband\Survey\Events\CreatedSurveySessionEvent;
use Tealband\Survey\Events\EmployeeSessionIsFinishedEvent;
use Tealband\Survey\Data\Question\CurrentEmployeeQuestionDTO;
use Tealband\Survey\Contracts\ClarifyingQuestionServiceContract;
use Tealband\Survey\Data\Question\CurrentEmployeeQuestionAnswerDTO;

class SurveyService implements SurveyServiceContract
{
    public function summarizer(): SummarizerServiceContract
    {
        return app(SummarizerServiceContract::class);
    }

    public function answer(): AnswerServiceContract
    {
        return app(AnswerServiceContract::class);
    }

    public function clarifyingQuestion(): ClarifyingQuestionServiceContract
    {
        return app(ClarifyingQuestionServiceContract::class);
    }

    public function getCurrentEmployeeSessionId(string $userId, string $milestoneId): string|null
    {
        $session = EmployeeSession::where([
            'user_id' => $userId,
            'milestone_id' => $milestoneId,
            'status' => EmployeeSessionStatus::Active
        ])
            ->latest()
            ->first();

        return $session?->id;

    }

    public function hasCompletedForEmployee(string $surveyId, string $userId, string $milestoneId): bool
    {
        $session = EmployeeSession::where([
            'user_id' => $userId,
            'milestone_id' => $milestoneId,
            'survey_id' => $surveyId,
        ])
            ->latest()
            ->first();

        if(is_null($session)) return false;

        return $session->status === EmployeeSessionStatus::Finished;
    }

    /**
     * @event EmployeeSessionIsFinishedEvent
     */
    public function markSessionIsFinished(string|EmployeeSession $session): void
    {
        if(is_string($session)) {
            $session = EmployeeSession::find($session);
        }

        $session->status = EmployeeSessionStatus::Finished;
        $session->save();

        event(new EmployeeSessionIsFinishedEvent($session));
    }

    public function hasActiveForEmployee(string $surveyId, string $userId, string $milestoneId): bool
    {
        $session = EmployeeSession::where([
            'user_id' => $userId,
            'milestone_id' => $milestoneId,
            'survey_id' => $surveyId,
            'status' => EmployeeSessionStatus::Active,
        ])
            ->latest()
            ->first();

        return ! is_null($session);
    }

    /**
     * @event CreatedSurveySessionEvent
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

    public function markSurveyResponseAsClosed(SurveyResponse $response, bool $withSaving = true): void
    {
        $response->status = SurveyResponseStatus::Closed;
        if($withSaving) $response->save();
    }

    /**
     * @event SurveyQuestionsIsEndEvent
     */
    public function getCurrentEmployeeQuestion(string $sessionId): CurrentEmployeeQuestionDTO|null
    {
        $session = EmployeeSession::with(['surveyResponse.answer'])
            ->where(['id' => $sessionId])
            ->first();

        if(is_null($session)) return null;

        $answeredQuestions = $session->surveyResponse
            ->where('status', SurveyResponseStatus::Closed)
            ->pluck('question_id')
            ->values()
            ->toArray();

        $questionsAmount = Question::where(['survey_id' => $session->survey_id,])
            ->count();

        $question = Question::with(['answers'])
            ->where([
                'survey_id' => $session->survey_id,
            ])
            ->whereNotIn('id', $answeredQuestions)
            ->first();

        if(is_null($question)) {
            event(new SurveyQuestionsIsEndEvent($session));
            return null;
        }

        $answers = array_map(fn($answer) => new CurrentEmployeeQuestionAnswerDTO(
            id: $answer['id'],
            clarifying: $answer['clarifying'] ?? '',
            weight: $answer['weight'],
        ), $question->answers->toArray());

        return new CurrentEmployeeQuestionDTO(
            id: $question->id,
            text: $question->title,
            keywords: $question->keywords,
            answers: $answers,
            currentQuestionIndex: count($answeredQuestions) + 1,
            amountQuestions: $questionsAmount,
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

    public function getInfo(int $surveyId)
    {
        // TODO: Implement getInfo() method.
    }

    public function analytic(string $milestone)
    {
        // TODO: Implement analytic() method.
    }

    public function getLimitations(int|string $userId): array
    {
        // TODO: Implement getLimitations() method.
    }

    /**
     * @event CreatedMilestoneEvent
     */
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
