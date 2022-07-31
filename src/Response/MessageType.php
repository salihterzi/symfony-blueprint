<?php

namespace App\Response;

enum MessageType: string
{
    case SUCCESS = 'success';
    case SUCCESS_LOGIN = 'success.login';
    case ERROR = 'error';
    case ERROR_INVALID_REQUEST = 'error.invalid_request';
    case ERROR_ACCESS_DENIED ='error.access_denied';
    case ERROR_LOGIN_BAD_CREDENTIAL ='error.login_bad_credential';
}
