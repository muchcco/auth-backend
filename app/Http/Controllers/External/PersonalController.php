<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Mail\ConfirmacionRegistro;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Storage;


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
        // dd($request->all());
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

                $usuarios = User::where('name', $request->num_doc)->first();

                if(isset($usuarios)){
                    $update_users = DB::table('users')->where('id_personal', $per_mac->IDPERSONAL)->update([
                        'idcentro_mac' => $request->input('nom_mac'),
                    ]);
    
                    $update_users_de = DB::table('db_centros_mac.users')->where('idpersonal', $per_mac->IDPERSONAL)->update([
                        'idcentro_mac' => $request->input('nom_mac'),
                    ]);
                }               

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

        $modulos = DB::table('db_centros_mac.M_MODULO')
                                ->join('db_centros_mac.M_ENTIDAD', 'db_centros_mac.M_ENTIDAD.IDENTIDAD', '=', 'db_centros_mac.M_MODULO.IDENTIDAD')
                                ->join('db_centros_mac.M_CENTRO_MAC', 'db_centros_mac.M_CENTRO_MAC.IDCENTRO_MAC', '=', 'db_centros_mac.M_MODULO.IDCENTRO_MAC')
                                ->where('db_centros_mac.M_CENTRO_MAC.IDCENTRO_MAC', $personal->IDMAC)
                                ->where(function($query) {
                                    $query->whereDate('db_centros_mac.M_MODULO.FECHAINICIO', '<=', now()->format('Y-m-d')) // Comparar con la fecha actual en formato 'YYYY-MM-DD'
                                          ->whereDate('db_centros_mac.M_MODULO.FECHAFIN', '>=', now()->format('Y-m-d'));    // Comparar con la fecha actual en formato 'YYYY-MM-DD'
                                })
                                ->orderBy('db_centros_mac.M_MODULO.N_MODULO','ASC')
                                ->get();


        $archivos = DB::select("SELECT * FROM db_centros_mac.A_PERSONAL WHERE IDPERSONAL = $personal->IDPERSONAL");

        return response()->json([
            "status" => true,
            "message" => "Detalles obtenidos con éxito",
            "departamentos" => $departamentos,
            "personal" => $personal,
            "cargos" => $cargos,
            "modulos" => $modulos,
            "archivos" => $archivos
        ]);
    }

    public function downloadFile(Request $request)
    {
        $fileId = $request->id;
        $file = DB::table('db_centros_mac.A_PERSONAL')->where('IDARCHIVO_PERSONAL', $fileId)->first();

        if ($file) {
            // Construye la ruta a partir del directorio `public`
            $filePath = $file->NOMBRE_RUTA;
            $fullPath = public_path($filePath);

            // dd($fullPath);

            if (file_exists($fullPath)) {
                return response()->download($fullPath, $file->NOMBRE_ARCHIVO);
            } else {
                return response()->json(['status' => false, 'message' => 'Archivo no encontrado en la ruta especificada.'], 404);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'Archivo no encontrado en la base de datos.'], 404);
        }
    }

    public function deletefile(Request $request, $id)
    {
        // Busca el archivo en la base de datos usando el ID proporcionado
        $file = DB::table('db_centros_mac.A_PERSONAL')->where('IDARCHIVO_PERSONAL', $id)->first();

        // Verifica si el archivo existe
        if ($file) {
            $filePath = public_path($file->NOMBRE_RUTA); // Ruta completa del archivo

            // Comprueba si el archivo existe en el sistema de archivos
            if (file_exists($filePath)) {
                // Elimina el archivo físico
                unlink($filePath);

                // Elimina el registro de la base de datos
                DB::table('db_centros_mac.A_PERSONAL')->where('IDARCHIVO_PERSONAL', $id)->delete();

                return response()->json(['status' => true, 'message' => 'Archivo eliminado correctamente.']);
            } else {
                return response()->json(['status' => false, 'message' => 'Archivo no encontrado en el sistema de archivos.'], 404);
            }
        } else {
            return response()->json(['status' => false, 'message' => 'Archivo no encontrado en la base de datos.'], 404);
        }
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
                'CORREO_INSTITUCIONAL' => $request->correo_institucional ?: null,
                'DIRECCION' => strtoupper($request->direccion) ?: null,
                'IDDISTRITO' => $request->distritoSeleccionado ?: null,
                'FECH_NACIMIENTO' => $request->fech_nacimiento ?: null,
                'ESTADO_CIVIL' => strtoupper($request->ecivil) ?: null,
                'DF_N_HIJOS' => $request->df_n_hijos ?: null,
                'PCM_TALLA' => strtoupper($request->pcm_talla) ?: null,
                'IDCARGO_PERSONAL' => $request->cargoSeleccionado ?: null,
                'PD_FECHA_INGRESO' => $request->dp_fecha_ingreso ?: null,
                'IDMODULO' => strtoupper($request->moduloSeleccionado) ?: null,
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
                'CORREO' => 'Correo Electrónico Personal',
                'CORREO_INSTITUCIONAL' => 'Correo Electrónico Institucional',
                'DIRECCION' => 'Dirección',
                'DISTRITO_SELECCIONADO' => 'Distrito',
                'FECH_NACIMIENTO' => 'Fecha de Nacimiento',
                'ECIVIL' => 'Estado Civil',
                'DF_N_HIJOS' => 'Número de Hijos',
                'PCM_TALLA' => 'Talla de Polo',
                'CARGO_SELECCIONADO' => 'Cargo',
                'DP_FECHA_INGRESO' => 'Fecha de Ingreso al Centro MAC',
                'IDMODULO' => 'Número de Módulo de Atención',
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
                if (!isset($value) && $value !== '0') {
                    $pending[] = $friendlyFieldNames[$key];
                    unset($inputs[$key]);
                }
            }
            
             // Actualiza la tabla M_PERSONAL
            //  DB::table('db_centros_mac.M_PERSONAL')
            //         ->where('IDPERSONAL', $request->idpersonal)
            //         ->update(array_merge($inputs, ['UPDATED_AT' => date('Y-m-d H:i:s')]));

            DB::select("UPDATE `db_centros_mac`.`M_PERSONAL` 
                        SET `IDTIPO_DOC` = $request->id_tipo_doc, `SEXO` = '$request->sexo', `APE_PAT` = '$request->ape_pat', `APE_MAT` = '$request->ape_mat', `NOMBRE` = '$request->nombre', `TELEFONO` = '$request->telefono', `CELULAR` = '$request->celular', `CORREO` = '$request->correo' , `CORREO_INSTITUCIONAL` = '$request->correo_institucional', `DIRECCION` = '$request->direccion', `IDDISTRITO` = $request->distritoSeleccionado, `FECH_NACIMIENTO` = '$request->fech_nacimiento', `ESTADO_CIVIL` = '$request->ecivil', `DF_N_HIJOS` = '$request->df_n_hijos', `PCM_TALLA` = '$request->pcm_talla', `IDCARGO_PERSONAL` = $request->cargoSeleccionado, `PD_FECHA_INGRESO` = '$request->dp_fecha_ingreso', `IDMODULO` = $request->moduloSeleccionado, `TVL_ID` = $request->tlv_id, `N_CONTRATO` = '$request->n_contrato', `TIP_CAS` = $request->tip_cas, `GI_ID` = $request->gi_id, `GI_CARRERA` = '$request->gi_carrera', `GI_CURSO_ESP` = '$request->gi_curso_esp', `DLP_JEFE_INMEDIATO` = '$request->dlp_jefe_inmediato', `DLP_CARGO` = '$request->dlp_cargo', `DLP_TELEFONO` = '$request->dlp_telefono', `I_INGLES` = '$request->inglesSeleccionado', `I_QUECHUA` = '$request->quechuaSeleccionado',`UPDATED_AT` = '2024-07-24 09:32:30' 
                        WHERE `IDPERSONAL` = $request->idpersonal");
 
            // dd($save);

            // Maneja el archivo 
            if ($request->hasFile('dni')) {
                $estructura_carp = 'personal\\num_doc\\'.$request->num_doc;
            
                // Crea el directorio si no existe
                if (!file_exists(public_path($estructura_carp))) {
                    mkdir(public_path($estructura_carp), 0777, true);
                }
            
                $archivoDNI = $request->file('dni');
                $nombreDNI = $archivoDNI->getClientOriginalName();  // Obtiene el nombre original del archivo
                $formatoDNI = $archivoDNI->getClientOriginalExtension();
                $tamañoEnKBDNI = $archivoDNI->getSize() / 1024; // Tamaño en kilobytes
                $namerutaDNI = public_path($estructura_carp . '\\' . $nombreDNI);
            
                // Mueve el archivo al destino con su nombre original
                $archivoDNI->move(public_path($estructura_carp), $nombreDNI);
            
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

            $configuracion = DB::table('configuration_sist')->where('PARAMETRO', 'CORREO')->first();
            
            if($configuracion->FLAG == '1'){
                if($request->correo){
                    Mail::to($request->correo)->send(new ConfirmacionRegistro($nombres_dat, $pending));
                } 
            }

            return response()->json([
                "status" => true,
                "message" => "Detalles obtenidos con éxito",
                "data" => $inputs,
                "pending" => $pending
            ]);


        } catch (\Illuminate\Database\QueryException $e) {
            $errorMessage = $e->getMessage();
    
            // Detecta el campo que causó el error
            if (preg_match("/for column '(.*?)'/", $errorMessage, $matches)) {
                $columnName = $matches[1];
                $friendlyFieldNames = [
                    'NUM_DOC' => 'Número de Documento',
                    'IDTIPO_DOC' => 'Tipo de Documento',
                    'SEXO' => 'Sexo',
                    'APE_PAT' => 'Apellido Paterno',
                    'APE_MAT' => 'Apellido Materno',
                    'NOMBRE' => 'Nombres',
                    'TELEFONO' => 'Teléfono',
                    'CELULAR' => 'Celular',
                    'CORREO' => 'Correo Electrónico Personal',
                    'CORREO_INSTITUCIONAL' => 'Correo Electrónico Institucional',
                    'DIRECCION' => 'Dirección',
                    'IDDISTRITO' => 'Distrito',
                    'FECH_NACIMIENTO' => 'Fecha de Nacimiento',
                    'ESTADO_CIVIL' => 'Estado Civil',
                    'DF_N_HIJOS' => 'Número de Hijos',
                    'PCM_TALLA' => 'Talla de Polo',
                    'IDCARGO_PERSONAL' => 'Cargo',
                    'PD_FECHA_INGRESO' => 'Fecha de Ingreso',
                    'IDMODULO' => 'Módulo',
                    'TVL_ID' => 'Modalidad de Contrato',
                    'N_CONTRATO' => 'Número de Contrato',
                    'GI_ID' => 'Grado',
                    'GI_CARRERA' => 'Carrera/Profesión',
                    'GI_CURSO_ESP' => 'Cursos de Especialización',
                    'DLP_JEFE_INMEDIATO' => 'Jefe Inmediato',
                    'DLP_CARGO' => 'Cargo',
                    'DLP_TELEFONO' => 'Teléfono del Jefe',
                    'TIP_CAS' => 'Tipo de Contrato',
                ];
    
                $friendlyField = $friendlyFieldNames[$columnName] ?? $columnName;
    
                return response()->json([
                    "status" => false,
                    "message" => "Error en el campo: {$friendlyField}. Por favor, verifica la información ingresada.",
                ], 400);
            }
    
            return response()->json([
                "status" => false,
                "message" => "Error de base de datos: " . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Error inesperado: " . $e->getMessage(),
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
