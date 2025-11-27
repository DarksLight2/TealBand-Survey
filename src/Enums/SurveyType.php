<?php

declare(strict_types=1);

namespace Tealband\Survey\Enums;

enum SurveyType: int
{
    case Trust = 0;
    case Energy = 1;
    case Interest = 2;
    case Possibilities = 3;
    case Understanding = 4;
    case Custom = 5;
}
