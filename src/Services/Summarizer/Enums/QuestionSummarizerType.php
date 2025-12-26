<?php

namespace Tealband\Survey\Services\Summarizer\Enums;

enum QuestionSummarizerType: int
{
    case Organization = 0;
    case Team = 1;
    case User = 2;
}
