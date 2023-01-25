<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 *  @OA\Info(
 *      version="1.0.0",
 *      title="Krémmánia API",
 *      description="Krémmánia API Dokumentáció",
 *      @OA\Contact(
 *          name="API Support",
 *          email="avengersdev@centralmediacsoport.hu",
 *      )
 *  )
 */
class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;
}
