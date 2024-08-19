<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssistsController extends Controller
{
    public function usersAssistsList(Request $request)
    {
        $personal = DB::table('db_centros_mac.M_PERSONAL')->where('NUM_DOC', $request->numeroDocumento)->first();

        if(!$personal){
            return response()->json(['message' => 'No se encontraron resultados'], 200);
        }

        return response()->json([
            "status" => true,
            "message" => "Detalles obtenidos con éxito",
            "personal" => $personal
        ]);
    }

    public function storeAssists(Request $request)
    {
        $personal = DB::table('db_centros_mac.M_PERSONAL')->where('NUM_DOC', $request->numeroDocumento)->first();

        if (!$personal) {
            return response()->json([
                "status" => false,
                "message" => "Personal no encontrado... Consulte con su especialista TIC su registro!",
                "personal" => $personal
            ], 400);
        }

        $hora = $request->horaActual;
        $fecha = $request->fechaActual;

        try {
            $hora = $request->horaActual;
            $fecha = $request->fechaActual;
    
            // Convertir la fecha al formato correcto para MySQL (YYYY-MM-DD)
            $fechaFormateada = Carbon::createFromFormat('d/m/Y', $fecha)->format('Y-m-d');
    
            // Obtener el año y el mes
            $año = Carbon::createFromFormat('d/m/Y', $fecha)->format('Y');
            $mes = Carbon::createFromFormat('d/m/Y', $fecha)->format('m');
    
            // Guardar la asistencia
            DB::table('db_centros_mac.M_ASISTENCIA')->insert([
                'IDTIPO_ASISTENCIA'     =>  1,
                'NUM_DOC'               =>  $request->numeroDocumento,
                'IDCENTRO_MAC'          =>  $personal->IDMAC,
                'MES'                   =>  $mes,
                'AÑO'                   =>  $año,
                'FECHA'                 =>  $fechaFormateada,
                'HORA'                  =>  $hora,
                'FECHA_BIOMETRICO'      =>  $fechaFormateada . ' ' . $hora,
            ]);
    
            return response()->json([
                "status" => true,
                "message" => "Asistencia registrada con éxito",
                "personal" => $personal
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Error al registrar la asistencia.",
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
