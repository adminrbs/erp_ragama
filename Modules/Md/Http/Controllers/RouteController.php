<?php

namespace Modules\Md\Http\Controllers;

use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Sd\Entities\route;

class RouteController extends Controller
{
    public function getRoutes()
    {
        try {


            $town = DB::select("SELECT * FROM routes");
            return response()->json($town);
        } catch (Exception $ex) {
            return $ex;
        }
    }
}
