<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttentionController extends Controller
{

    public function formDetails(Request $request)
    {
        $nom_mac = DB::connection('sqlsrv')->select("select * 
                                from Par_Nom_Mac
                                where Nom_Mac != 'null'");
        $servicio = DB::connection('sqlsrv')->select("select * from par_ent order by nom_ent asc");

        return response()->json([
            "status" => true,
            "message" => "Detalles obtenidos con éxito",
            "nom_mac" => $nom_mac,
            "servicio" => $servicio
        ]);
    }


    public function tableAttention(Request $request)
    {
        try {
            $data = DB::connection('sqlsrv')->table('cre_atend as c')
                                                ->join('par_ent as p', 'c.Ide_ser', '=', 'p.ide_ent')
                                                ->select(
                                                    'c.Nom_mac',
                                                    'p.nom_ent',
                                                    'c.Hra_llg',
                                                    'c.Hra_lla',
                                                    'c.Tpo_esp',
                                                    'c.Hra_ini',
                                                    'c.Tpo_ate',
                                                    'c.Fin_ate',
                                                    'c.Tpo_tot',
                                                    'c.Num_tik',
                                                    'c.Est_ate',
                                                    'c.Fec_ate',
                                                    DB::raw("'Presencial' as presencial"),
                                                    DB::raw("CASE c.des_pri
                                                                WHEN '1' THEN 'NO PREFERENCIAL'
                                                                ELSE 'PREFERENCIAL'
                                                            END as TIPO_aTE")
                                                )
                                                ->where(function($query) use ($request) {                                
                                                    $fecha_I = date("Y-m-d");
                                                    $fecha_F = date("Y-m-d");
                                                    if($request->fecha_inicio != '' && $request->fecha_fin != '' ){
                                                        $query->where('fec_ate', '>=', $request->fecha_inicio);
                                                        $query->where('fec_ate', '<=', $request->fecha_fin);
                                                    }else{
                                                        $query->where('fec_ate', '<=', $fecha_I);
                                                        $query->where('fec_ate', '>=', $fecha_F);
                                                    }
                                                })
                                                ->where(function($query) use ($request) {
                                                    if($request->servicio != '' ){
                                                        $query->where('Ide_ser', $request->servicio);
                                                    }
                                                })
                                                // ->whereIn('Nom_mac', ['MAC Callao', 'MAC Ventanilla'])
                                                ->where(function($query) use ($request) {
                                                    if($request->nom_mac == "0" ){
                                                        $query->whereIn('Nom_mac' , [$request->nom_mac]);
                                                    }
                                                })
                                                ->orderBy('Nom_mac')
                                                ->orderBy('Fec_ate')
                                                ->get();

            // dd($data);
            
            return response()->json([
                "status" => true,
                "message" => "Lista de atenciones",
                "data" => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Se excedió el tiempo de carga. Inténtelo de nuevo más tarde.",
                "error" => $e->getMessage()
            ], 500);
        }
    }
}
