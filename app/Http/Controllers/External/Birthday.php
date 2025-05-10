<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Birthday extends Controller
{
    public function listMac()
    {
        $mac = DB::table('db_centros_mac.M_CENTRO_MAC')->get();

        return response()->json([
            "status" => true,
            "message" => "Detalles obtenidos con éxito",
            "options" => $mac,
        ]);
    }
}
