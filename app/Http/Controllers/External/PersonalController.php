<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Mail\ConfirmacionRegistro;
use Illuminate\Support\Facades\Mail;


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
                $update_doc = DB::table('db_centros_mac.M_PERSONAL')->where('IDPERSONAL', $per_mac->IDPERSONAL)->update([
                    'IDENTIDAD' => $request->input('entidad'),
                    'IDMAC' => $request->input('nom_mac'),
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
                'IDENTIDAD' => $request->input('entidad'),
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

        $cargos = DB::select("SELECT * FROM db_centros_mac.D_PERSONAL_CARGO ");

        $personal = DB::table('db_centros_mac.M_PERSONAL')->leftJoin('db_centros_mac.M_ENTIDAD', 'db_centros_mac.M_ENTIDAD.IDENTIDAD', '=', 'db_centros_mac.M_PERSONAL.IDENTIDAD')
                                ->where('M_PERSONAL.NUM_DOC', $num_doc)
                                ->leftJoin('db_centros_mac.M_CENTRO_MAC', 'db_centros_mac.M_CENTRO_MAC.IDCENTRO_MAC', '=', 'db_centros_mac.M_PERSONAL.IDMAC')
                                ->leftJoin('db_centros_mac.DISTRITO', 'db_centros_mac.DISTRITO.IDDISTRITO', '=','db_centros_mac.M_PERSONAL.IDDISTRITO') // DONDE VIVE
                                ->first();


        return response()->json([
            "status" => true,
            "message" => "Detalles obtenidos con éxito",
            "departamentos" => $departamentos,
            "personal" => $personal,
            "cargos" => $cargos,
        ]);
    }

    public function storeform(Request $request)
    {
        // dd($request->id_tipo_doc);
        try {
            
            $inputs = [
                'NUM_DOC' => strtoupper($request->num_doc) ?: null,
                'IDTIPO_DOC' => strtoupper($request->id_tipo_doc) ?: null,
                'SEXO' => strtoupper($request->sexo) ?: null,
                'APE_PAT' => strtoupper($request->ape_pat) ?: null,
                'APE_MAT' => strtoupper($request->ape_mat) ?: null,
                'NOMBRE' => strtoupper($request->nombre) ?: null,
                'TELEFONO' => $request->telefono ?: null,
                'CELULAR' => $request->celular ?: null,
                'CORREO' => $request->correo ?: null,
                'DIRECCION' => strtoupper($request->direccion) ?: null,
                'IDDISTRITO' => $request->distritoSeleccionado ?: null,
                'FECH_NACIMIENTO' => $request->fech_nacimiento ?: null,
                'ESTADO_CIVIL' => strtoupper($request->ecivil) ?: null,
                'DF_N_HIJOS' => $request->df_n_hijos ?: null,
                'PCM_TALLA' => strtoupper($request->pcm_talla) ?: null,
                'IDCARGO_PERSONAL' => $request->cargoSeleccionado ?: null,
                'PD_FECHA_INGRESO' => $request->dp_fecha_ingreso ?: null,
                'NUMERO_MODULO' => strtoupper($request->n_modulo) ?: null,
                'TVL_ID' => $request->tlv_id ?: null,
                'N_CONTRATO' => strtoupper($request->n_contrato) ?: null,
                'GI_ID' => $request->gi_id ?: null,
                'GI_CARRERA' => strtoupper($request->gi_carrera) ?: null,
                'GI_CURSO_ESP' => strtoupper($request->gi_curso_esp) ?: null,
                'DLP_JEFE_INMEDIATO' => strtoupper($request->dlp_jefe_inmediato) ?: null,
                'DLP_CARGO' => strtoupper($request->dlp_cargo) ?: null,
                'DLP_TELEFONO' => $request->dlp_telefono ?: null,
                'TIP_CAS' => $request->tip_cas ?: null,
            ];

            $friendlyFieldNames = [
                'NUM_DOC' => 'Número de Documento',
                'ID_TIPO_DOC' => 'Tipo de Documento',
                'SEXO' => 'Sexo',
                'APE_PAT' => 'Apellido Paterno',
                'APE_MAT' => 'Apellido Materno',
                'NOMBRE' => 'Nombres',
                'TELEFONO' => 'Teléfono',
                'CELULAR' => 'Celular',
                'CORREO' => 'Correo Electrónico',
                'DIRECCION' => 'Dirección',
                'DISTRITO_SELECCIONADO' => 'Distrito',
                'FECH_NACIMIENTO' => 'Fecha de Nacimiento',
                'ECIVIL' => 'Estado Civil',
                'DF_N_HIJOS' => 'Número de Hijos',
                'PCM_TALLA' => 'Talla de Polo',
                'CARGO_SELECCIONADO' => 'Cargo',
                'DP_FECHA_INGRESO' => 'Fecha de Ingreso al Centro MAC',
                'NUMERO_MODULO' => 'Número de Módulo de Atención',
                'TLV_ID' => 'Modalidad de Contrato',
                'N_CONTRATO' => 'Número de Contrato',
                'GI_ID' => 'Grado',
                'GI_CARRERA' => 'Carrera/Profesión',
                'GI_CURSO_ESP' => 'Cursos de Especialización',
                'DLP_JEFE_INMEDIATO' => 'Jefe Inmediato Superior',
                'DLP_CARGO' => 'Cargo',
                'DLP_TELEFONO' => 'Teléfono del Jefe Inmediato',
            ];
            
            // front
            $pending = [];
            foreach ($inputs as $key => $value) {
                if ($value === NULL || $value === '') {
                    $pending[] = $friendlyFieldNames[$key];
                    unset($inputs[$key]);
                }
            }
            
             // Actualiza la tabla M_PERSONAL
            //  DB::table('db_centros_mac.M_PERSONAL')
            //         ->where('IDPERSONAL', $request->idpersonal)
            //         ->update(array_merge($inputs, ['UPDATED_AT' => date('Y-m-d H:i:s')]));

            DB::select("UPDATE `db_centros_mac`.`M_PERSONAL` 
                        SET `NUM_DOC` = $request->num_doc, `IDTIPO_DOC` = $request->id_tipo_doc, `SEXO` = $request->sexo, `APE_PAT` = '$request->ape_pat', `APE_MAT` = '$request->ape_mat', `NOMBRE` = '$request->nombre', `TELEFONO` = $request->telefono, `CELULAR` = $request->celular, `CORREO` = '$request->correo', `DIRECCION` = '$request->direccion', `IDDISTRITO` = $request->distritoSeleccionado, `FECH_NACIMIENTO` = '$request->fech_nacimiento', `ESTADO_CIVIL` = '$request->ecivil', `DF_N_HIJOS` = $request->df_n_hijos, `PCM_TALLA` = '$request->pcm_talla', `IDCARGO_PERSONAL` = $request->cargoSeleccionado, `PD_FECHA_INGRESO` = '$request->dp_fecha_ingreso', `NUMERO_MODULO` = '$request->n_modulo', `TVL_ID` = $request->tlv_id, `N_CONTRATO` = '$request->n_contrato', `TIP_CAS` = $request->tip_cas, `GI_ID` = $request->gi_id, `GI_CARRERA` = '$request->gi_carrera', `GI_CURSO_ESP` = '$request->gi_curso_esp', `DLP_JEFE_INMEDIATO` = '$request->dlp_jefe_inmediato', `DLP_CARGO` = '$request->dlp_cargo', `DLP_TELEFONO` = $request->dlp_telefono, `UPDATED_AT` = '2024-07-24 09:32:30' 
                        WHERE `IDPERSONAL` = $request->idpersonal");
 
            // dd($save);

            // Maneja el archivo PDF
            if ($request->hasFile('dni')) {
                $estructura_carp = 'personal\\num_doc\\'.$request->num_doc;
                if (!file_exists(public_path($estructura_carp))) {
                    mkdir(public_path($estructura_carp), 0777, true);
                }

                $archivoDNI = $request->file('dni');
                $nombreDNI = $archivoDNI->getClientOriginalName();
                $formatoDNI = $archivoDNI->getClientOriginalExtension();
                $tamañoEnKBDNI = $archivoDNI->getSize() / 1024; // Tamaño en kilobytes
                $namerutaDNI = public_path($estructura_carp);
                $archivoDNI->move($namerutaDNI, $request->num_doc);

                // Inserta o actualiza en la tabla a_personal
                DB::table('db_centros_mac.A_PERSONAL')->updateOrInsert(
                    ['IDPERSONAL' => $request->idpersonal, 'NOMBRE_ARCHIVO' => $nombreDNI],
                    [
                        'NOMBRE_RUTA' => $estructura_carp.'\\'.$nombreDNI,
                        'FORMATO_DOC' => $formatoDNI,
                        'PESO_DOC' => $tamañoEnKBDNI,
                        'FECHA_CREACION' => date('Y-m-d H:i:s')
                    ]
                );
            }

            $nombres_dat = $request->nombre.' '.$request->ape_pat.' '.$request->ape_mat;

            $pending2 = [];
            foreach ($inputs as $key => $value) {
                if ($value === NULL || $value === '') {
                    $pending2[] = $friendlyFieldNames[$key];
                }
            }

            if($request->correo){
                Mail::to($request->correo)->send(new ConfirmacionRegistro($nombres_dat, $pending));
            }           
    
            return response()->json([
                "status" => true,
                "message" => "Detalles obtenidos con éxito",
                "data" => $inputs,
                "pending" => $pending
            ]);


        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Se excedió el tiempo de carga. Inténtelo de nuevo más tarde.",
                "error" => $e->getMessage()
            ], 500);
        }
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

    public function provincias($departamento_id)
    {
        $provincias = DB::table('db_centros_mac.PROVINCIA')->where('DEPARTAMENTO_ID', $departamento_id)->get();


        return response()->json([
            "status" => true,
            "message" => "Detalles obtenidos con éxito",
            "provincias" => $provincias,
        ]);
    }

    public function distritos($provincia_id)
    {
        $distritos = DB::table('db_centros_mac.DISTRITO')->where('PROVINCIA_ID', $provincia_id)->get();

        return response()->json([
            "status" => true,
            "message" => "Detalles obtenidos con éxito",
            "distritos" => $distritos,
        ]);
    }
}
