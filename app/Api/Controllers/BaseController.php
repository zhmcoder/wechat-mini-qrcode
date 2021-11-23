<?php

namespace App\Api\Controllers;

use App\Api\Traits\ApiResponseTraits;
use App\Api\Traits\TokenTraits;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    const CODE_SHOW_MSG = 2001;
    const CODE_ERROR_CODE = -1;
    const CODE_SUCCESS_CODE = 0;

    use TokenTraits, ApiResponseTraits;
}

