<?php

declare(strict_types=1);

namespace Tealband\Survey\Services;

use Tealband\Survey\Traits\CRUD;
use Tealband\Survey\Models\Question;
use Tealband\Survey\Data\Question\QuestionDTO;
use Tealband\Survey\Data\Question\CreateQuestionDTO;
use Tealband\Survey\Data\Question\UpdateQuestionDTO;

/**
 * @template-extends CRUD<CreateQuestionDTO, QuestionDTO, UpdateQuestionDTO>
 */
class QuestionService
{
    use CRUD;

    protected string $model = Question::class;
    protected string $baseDTO = QuestionDTO::class;
}
