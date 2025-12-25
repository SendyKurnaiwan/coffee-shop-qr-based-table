<?php

namespace App\Http\Controllers;

use Illuminate\foundation\Auth\Access\AuthorizesRequests;
use Illuminate\foundation\Bus\DispatchesJobs;
use Illuminate\foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
