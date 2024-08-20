<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntityController extends Controller
{
    private function centro_mac(){
        // VERIFICAMOS EL USUARIO A QUE CENTRO MAC PERTENECE
        /*================================================================================================================*/
        $us_id = auth()->user()->idcentro_mac;
        $user = DB::table('db_centros_mac.users')->join('db_centros_mac.M_CENTRO_MAC', 'db_centros_mac.M_CENTRO_MAC.IDCENTRO_MAC', '=', 'db_centros_mac.users.idcentro_mac')->where('M_CENTRO_MAC.IDCENTRO_MAC', $us_id)->first();

        $idmac = $user->IDCENTRO_MAC;
        $name_mac = $user->NOMBRE_MAC;
        /*================================================================================================================*/

        $resp = ['idmac'=>$idmac, 'name_mac'=>$name_mac ];

        return (object) $resp;
    }

    public function EntityList()
    {
        $user = auth()->user();

        $roles = $user->getRoleNames();

        $list = DB::table('db_centros_mac.M_MAC_ENTIDAD')
                    ->join('db_centros_mac.M_CENTRO_MAC', 'db_centros_mac.M_CENTRO_MAC.IDCENTRO_MAC', '=', 'db_centros_mac.M_MAC_ENTIDAD.IDCENTRO_MAC')
                    ->join('db_centros_mac.M_ENTIDAD', 'db_centros_mac.M_ENTIDAD.IDENTIDAD', '=', 'db_centros_mac.M_MAC_ENTIDAD.IDENTIDAD')
                    ->when(!$roles->contains('Administrador'), function ($query) use ($user) {
                        // Si no es Administrador, aplica una condición adicional
                        return $query->where('db_centros_mac.M_CENTRO_MAC.IDCENTRO_MAC', '=', $this->centro_mac()->idmac);
                    })
                    ->orderBy('NOMBRE_ENTIDAD', 'ASC')
                    ->get();

        return response()->json([
            "status" => 1,
            "message" => "datos varios",
            "list" => $list
        ]);
    }

    public function entityDetails()
    {
        $user = auth()->user();

        $roles = $user->getRoleNames();

        $entity = DB::table('db_centros_mac.M_ENTIDAD')->orderBy('ABREV_ENTIDAD', 'ASC')->get();

        $mac = DB::table('db_centros_mac.M_CENTRO_MAC')
                        ->when(!$roles->contains('Administrador'), function ($query) use ($user) {
                            // Si no es Administrador, aplica una condición adicional
                            return $query->where('db_centros_mac.M_CENTRO_MAC.IDCENTRO_MAC', '=', $this->centro_mac()->idmac);
                        })
                        ->get();


        return response()->json([
            "status" => 1,
            "message" => "datos varios",
            "entity" => $entity,
            "mac" => $mac,
        ]);

    }

    public function entityStore(Request $request)
    {
        try {
            $ent_mac = DB::table('db_centros_mac.M_MAC_ENTIDAD')->where('IDCENTRO_MAC', $request->mac)->where('IDENTIDAD', $request->entity)->first();

            if(isset($ent_mac)){
                return response()->json([
                    "status" => false,
                    "message" => "La entidad ya fue registrada para el centro mac.",                    
                ], 400);
            }
            

            $save = DB::table('db_centros_mac.M_MAC_ENTIDAD')->insert([
                'IDCENTRO_MAC'      =>      $request->mac,
                'IDENTIDAD'         =>      $request->entity,
                'LOG_US'            =>      auth()->user()->id,
            ]);

            return response()->json([
                "status" => 1,
                "message" => "datos varios",
                "save" => $save
            ]);


        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Se excedió el tiempo de carga. Inténtelo de nuevo más tarde.",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function entityModalStore(Request $request)
    {
        try {

            $save = DB::table('db_centros_mac.M_ENTIDAD')->insert([
                'NOMBRE_ENTIDAD'      =>      $request->nombre,
                'ABREV_ENTIDAD'         =>      $request->nombre_corto,
            ]);

            return response()->json([
                "status" => 1,
                "message" => "datos varios",
                "save" => $save
            ]);


        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Se excedió el tiempo de carga. Inténtelo de nuevo más tarde.",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function entityDelete(Request $request)
    {
        try {

            DB::table('db_centros_mac.M_MAC_ENTIDAD')->where('IDMAC_ENTIDAD', $request->idmacent)->delete();

            return response()->json([
                "status" => 1,
                "message" => "El registro feu eliminado con exito..."
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
