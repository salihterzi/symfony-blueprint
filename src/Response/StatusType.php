<?php

namespace App\Response;

enum StatusType: string
{
    case SUCCESS = 'success';
    case FAIL = 'fail';
    case INFO = 'info';
}
