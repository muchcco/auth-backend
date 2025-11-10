<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AssistsController extends Controller
{

    public function entities()
    {
        // Ajusta la tabla y campos a los reales de tu esquema
        

        $data = DB::table('db_centros_mac.M_CENTRO_MAC as m')
                    ->join('db_centros_mac.distrito as d','m.ubicacion','=','d.IDDISTRITO')
                    ->join('db_centros_mac.departamento as dep','d.DEPARTAMENTO_ID','=','dep.IDDEPARTAMENTO')                    
                    ->select([
                        'm.IDCENTRO_MAC as id',
                        'm.NOMBRE_MAC as nombre',
                        'dep.NAME_DEPARTAMENTO',
                        'd.NAME_DISTRITO',
                        'm.fecha_apertura',
                        'm.foto_ruta as url'
                    ])
                    ->whereNot('m.idcentro_mac', '5')
                    ->orderBy('nombre', 'asc')
                    ->get();

        return response()->json([
            "status" => true,
            "data"   => $data
        ]);
    }   

    public function entityById($id)
    {
        $row = DB::table('db_centros_mac.M_CENTRO_MAC')
            ->select(['IDCENTRO_MAC as id', 'NOMBRE_MAC as nombre'])
            ->where('IDCENTRO_MAC', $id)
            ->first();

        if (!$row) {
            return response()->json(["status" => false, "message" => "Entidad no encontrada"], 404);
        }

        return response()->json(["status" => true, "data" => $row]);
    }


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
            $dt = Carbon::parse($fecha);

            $fechaFormateada = $dt->toDateString();    
            $año             = $dt->year;              
            $mes             = $dt->month;             

            DB::table('db_centros_mac.M_ASISTENCIA')->insert([
                'IDTIPO_ASISTENCIA' => 1,
                'NUM_DOC'           => $request->numeroDocumento,
                'IDCENTRO_MAC'      => $request->idMac,
                'MES'               => str_pad($mes, 2, '0', STR_PAD_LEFT), 
                'AÑO'               => $año,
                'FECHA'             => $fechaFormateada,
                'HORA'              => $hora, 
                'FECHA_BIOMETRICO'  => $fechaFormateada . ' ' . $hora,                
            ]);

            return response()->json([
                "status"  => true,
                "message" => "Asistencia registrada con éxito",
                "personal"=> $personal
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
