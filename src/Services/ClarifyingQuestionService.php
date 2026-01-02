<?php

declare(strict_types=1);

namespace Tealband\Survey\Services;

use Tealband\Survey\Facades\Survey;
use Tealband\Survey\Models\Question;
use Tealband\Survey\Services\AI\AiService;
use Tealband\Survey\Models\SurveyResponse;
use Tealband\Survey\Events\SavedClarifyResponseEvent;
use Tealband\Survey\Events\GeneratedClarifyQuestionEvent;
use Tealband\Survey\Contracts\ClarifyingQuestionServiceContract;
use Tealband\Survey\Events\EmployeeAnswerSavedWithoutClarifyingEvent;

class ClarifyingQuestionService implements ClarifyingQuestionServiceContract
{
    /**
     * @event SurveyQuestionsIsEndEvent
     * @event GeneratedClarifyQuestionEvent
     * @event EmployeeAnswerSavedWithoutClarifyingEvent
     */
    public function generate(string $employeeSessionId): string
    {
        $aiClarifying = '';
        $question = Survey::getCurrentEmployeeQuestion($employeeSessionId);
        $questionIntent = Question::query()->select('intent')->find($question->id)->intent;
        $surveyResponse = SurveyResponse::query()
            ->with(['answer:clarifying,id,prompt,weight'])
            ->where([
                'employee_session_id' => $employeeSessionId,
                'question_id' => $question->id,
            ])
            ->latest()
            ->first();

        $text = "Main question: $question->text; Clarifying question: {$surveyResponse->answer->clarifying}; Clarifying answer: $surveyResponse->comment; Weight: {$surveyResponse->answer->weight}; User answer comment: {$surveyResponse->answer->comment};";
        $prompts = [
            ['role' => 'system', 'content' => $questionIntent],
            ['role' => 'user', 'content' => $text],
        ];

        $response = AiService::clarifyingQuestion()->handle(array_merge(config('tealband-survey.clarifying-question.prompts'), $prompts));

        if(json_validate($response)) {
            $data = json_decode($response, true);
            if($data['need_clarifying']) {
                $aiClarifying = $data['content'];
            } else {
                event(new EmployeeAnswerSavedWithoutClarifyingEvent($surveyResponse));
                Survey::markSurveyResponseAsClosed($surveyResponse);
                return '';
            }
        }

        $surveyResponse->ai_clarifying = $aiClarifying;
        $surveyResponse->save();

        event(new GeneratedClarifyQuestionEvent($surveyResponse));

        return $aiClarifying;
    }

    /**
     * @event SurveyQuestionsIsEndEvent
     */
    public function comment(string $employeeSessionId, string $comment): void
    {
        $question = Survey::getCurrentEmployeeQuestion($employeeSessionId);
        $surveyResponse = SurveyResponse::query()
            ->with(['answer:clarifying,id,prompt'])
            ->where([
                'employee_session_id' => $employeeSessionId,
                'question_id' => $question->id,
            ])
            ->latest()
            ->first();

        $surveyResponse->comment = $comment;
        $surveyResponse->save();
    }

    /**
     * @event SavedClarifyResponseEvent
     * @event SurveyQuestionsIsEndEvent
     */
    public function userAnswer(string $employeeSessionId, string $answer): void
    {
        $question = Survey::getCurrentEmployeeQuestion($employeeSessionId);
        $surveyResponse = SurveyResponse::query()
            ->where([
                'employee_session_id' => $employeeSessionId,
                'question_id' => $question->id,
            ])
            ->latest()
            ->first();

        $surveyResponse->response = $answer;
        Survey::markSurveyResponseAsClosed($surveyResponse, false);
        $surveyResponse->save();

        event(new SavedClarifyResponseEvent($surveyResponse));
    }
}
