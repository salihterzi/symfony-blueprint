<?php

namespace App\Response;

enum MessageType: string
{
    case SUCCESS = 'success';
    case SUCCESS_LOGIN = 'success.login';
    case ERROR = 'error';
}
