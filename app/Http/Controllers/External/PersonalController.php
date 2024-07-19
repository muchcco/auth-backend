<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PersonalController extends Controller
{
    public function combo()
    {
        $nom_mac = DB::select("SELECT * FROM db_centros_mac.M_CENTRO_MAC ORDER BY NOMBRE_MAC ASC");

        $tip_doc = DB::select("SELECT * FROM db_centros_mac.D_PERSONAL_TIPODOC");

        return response()->json([
            "status" => true,
            "message" => "Detalles obtenidos con éxito",
            "nom_mac" => $nom_mac,
            "tip_doc" => $tip_doc
        ]);
    }

    public function validar(Request $request)
    {
        //VALIDAMOS SI EL USUARIO EXISTE
        /* ========================================================================================================= */

        $per_mac = DB::table("db_centros_mac.M_PERSONAL")->where('NUM_DOC', $request->num_doc)->first();       
        
        /* ========================================================================================================= */

        /** PARA EL TIPO DE ESTATUS DEL PERSONAL **/
        ///  FLAG -> 1 = EL PERSONAL ESTA ACTIVO 
        ///  FLAG -> 2 = EL PERSONAL ESTA INACTIVO PERO PERTENECE AL CENTRO MAC
        ///  FLAG -> 3 = EL PERSONAL EXISTE PERO NO PERTENECE ALGÚN CENTRO MAC

        if(isset($per_mac)){
            $mac = DB::table("db_centros_mac.M_CENTRO_MAC")->where('IDCENTRO_MAC', $per_mac->IDMAC)->first();
            if($per_mac->FLAG == '1'){

                if($per_mac->IDMAC == $request->nom_mac){
                    // return $per_mac;  
                    return response()->json([
                        "status" => true,
                        "message" => "Detalles obtenidos con éxito",
                        "data" => $per_mac,
                    ]);                  
                }else{
                    $response_ = response()->json([
                        'message' => "El personal pertenece al Centro MAC ". $mac->NOMBRE_MAC,
                        'status' => 201,
                        'data' => $per_mac
                    ], 200);
        
                    return $response_;
                }

            }elseif($per_mac->FLAG == '2'){

                if($per_mac->IDMAC == $request->idmac){
                    return $per_mac;                    
                }else{
                    $response_ = response()->json([
                        'message' => "El personal pertenece al Centro MAC ". $mac->NOMBRE_MAC . ", contacte con su especialista TIC del centro MAC para que se desvincule de su cuenta",
                        'status' => 202,
                        'data' => $per_mac
                    ], 200);
        
                    return $response_;
                }                

            }elseif($per_mac->FLAG == '3'){
                $update_doc = DB::table('db_centros_mac.M_PERSONAL')->where('IDPERSONAL', $IDPERSONAL)->update([
                    'IDENTIDAD' => $request->input('entidad'),
                    'FLAG' => 1,
                ]);

                return response()->json([
                    "status" => true,
                    "message" => "Detalles obtenidos con éxito",
                    "data" => $update_doc,
                ]);
            }            

        }else{           

            $personal = DB::table('db_centros_mac.M_PERSONAL')->insert([                
                'IDTIPO_DOC' => $request->input('tip_doc'),
                'NUM_DOC' => $request->input('num_doc'),
                'IDENTIDAD' => $request->input('options'),
                'IDMAC' => $request->input('nom_mac'),
                'CREATED_AT' => date('Y-m-d H:i:s')
            ]);

            return response()->json([
                "status" => true,
                "message" => "Detalles obtenidos con éxito",
                "data" => $personal,
            ]);
        }

        

    }

    public function formdata($num_doc)
    {
        $departamentos = DB::select("SELECT * FROM db_centros_mac.DEPARTAMENTO ");

        $personal = DB::table('db_centros_mac.M_PERSONAL')->leftJoin('db_centros_mac.M_ENTIDAD', 'db_centros_mac.M_ENTIDAD.IDENTIDAD', '=', 'db_centros_mac.M_PERSONAL.IDENTIDAD')
                                ->where('M_PERSONAL.NUM_DOC', $num_doc)
                                ->join('db_centros_mac.M_CENTRO_MAC', 'db_centros_mac.M_CENTRO_MAC.IDCENTRO_MAC', '=', 'db_centros_mac.M_PERSONAL.IDMAC')
                                ->first();


        return response()->json([
            "status" => true,
            "message" => "Detalles obtenidos con éxito",
            "departamentos" => $departamentos,
            "personal" => $personal,
        ]);
    }

    /******************************************************* RECURSOS ***************************************************************************************/

    public function entity($idcentro_mac)
    {
        $idcentro_mac = DB::table('db_centros_mac.M_MAC_ENTIDAD')
                            ->join('db_centros_mac.M_CENTRO_MAC', 'db_centros_mac.M_CENTRO_MAC.IDCENTRO_MAC', '=', 'db_centros_mac.M_MAC_ENTIDAD.IDCENTRO_MAC')
                            ->join('db_centros_mac.M_ENTIDAD', 'db_centros_mac.M_ENTIDAD.IDENTIDAD', '=', 'db_centros_mac.M_MAC_ENTIDAD.IDENTIDAD')
                            ->leftJoin('db_centros_mac.CONFIGURACION_SIST', 'db_centros_mac.CONFIGURACION_SIST.IDCONFIGURACION', '=', 'db_centros_mac.M_MAC_ENTIDAD.TIPO_REFRIGERIO')
                            ->where('M_MAC_ENTIDAD.IDCENTRO_MAC', $idcentro_mac)
                            ->orderBy('M_ENTIDAD.NOMBRE_ENTIDAD', 'ASC')
                            ->get();


        return response()->json([
            "status" => true,
            "message" => "Detalles obtenidos con éxito",
            "options" => $idcentro_mac,
        ]);
    }
}
