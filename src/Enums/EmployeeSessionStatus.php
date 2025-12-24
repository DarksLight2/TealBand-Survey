<?php

declare(strict_types=1);

namespace Tealband\Survey\Enums;

enum EmployeeSessionStatus: int
{
    case Active = 0;
    case Finished = 1;
}
