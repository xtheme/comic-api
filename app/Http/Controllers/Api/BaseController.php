<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\CacheTrait;

class BaseController extends Controller
{
    use CacheTrait;
}
