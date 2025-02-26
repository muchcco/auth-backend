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

            $insertedId = DB::table('d_personal_mac')->insertGetId([
                'idcentro_mac' => $request->input('nom_mac'),
                'idpersonal'    => $usuario->IDPERSONAL,
                'idus_reg'      => NULL,
                'status'      => 1,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now()
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
        try {
            // Busca el archivo en la base de datos usando el ID proporcionado
            $file = DB::table('db_centros_mac.A_PERSONAL')->where('IDARCHIVO_PERSONAL', $id)->first();
    
            if ($file) {
                $filePath = public_path($file->NOMBRE_RUTA); // Ruta completa del archivo
                // dd($filePath);
    
                // Comprueba si el archivo físico existe
                if (file_exists($filePath)) {
                    // Intenta eliminar el archivo físico
                    if (@unlink($filePath)) {
                        // Si se elimina correctamente, elimina el registro de la base de datos
                        DB::table('db_centros_mac.A_PERSONAL')->where('IDARCHIVO_PERSONAL', $id)->delete();
                        return response()->json(['status' => true, 'message' => 'Archivo eliminado correctamente.']);
                    } else {
                        return response()->json(['status' => false, 'message' => 'No se pudo eliminar el archivo del sistema de archivos.'], 500);
                    }
                } else {
                    // Si el archivo no existe físicamente, elimina solo el registro
                    DB::table('db_centros_mac.A_PERSONAL')->where('IDARCHIVO_PERSONAL', $id)->delete();
                    return response()->json(['status' => true, 'message' => 'Archivo no encontrado físicamente, pero el registro ha sido eliminado.']);
                }
            } else {
                return response()->json(['status' => false, 'message' => 'Archivo no encontrado en la base de datos.'], 404);
            }
        } catch (\Exception $e) {
            // Captura cualquier error inesperado
            return response()->json(['status' => false, 'message' => 'Error al intentar eliminar el archivo.', 'error' => $e->getMessage()], 500);
        }
    }
    

    public function storeform(Request $request)
    {
        try {
            // Validar campos requeridos
            $request->validate([
                'ape_pat' => 'required|string',
                'ape_mat' => 'required|string',
                'nombre' => 'required|string',
            ], [
                'ape_pat.required' => 'El Apellido Paterno es obligatorio.',
                'ape_mat.required' => 'El Apellido Materno es obligatorio.',
                'nombre.required' => 'El Nombre es obligatorio.',
            ]);

            // Prepara los datos para la actualización/inserción
            $inputs = collect([
                'NUM_DOC' => strtoupper($request->num_doc),
                'IDTIPO_DOC' => strtoupper($request->id_tipo_doc),
                'SEXO' => strtoupper($request->sexo),
                'APE_PAT' => strtoupper($request->ape_pat),
                'APE_MAT' => strtoupper($request->ape_mat),
                'NOMBRE' => strtoupper($request->nombre),
                'TELEFONO' => $request->telefono,
                'CELULAR' => $request->celular,
                'CORREO' => $request->correo,
                'CORREO_INSTITUCIONAL' => $request->correo_institucional,
                'DIRECCION' => strtoupper($request->direccion),
                'IDDISTRITO' => $request->distritoSeleccionado,
                'FECH_NACIMIENTO' => $request->fech_nacimiento,
                'ESTADO_CIVIL' => strtoupper($request->ecivil),
                'DF_N_HIJOS' => $request->df_n_hijos,
                'PCM_TALLA' => strtoupper($request->pcm_talla),
                'IDCARGO_PERSONAL' => $request->cargoSeleccionado,
                'PD_FECHA_INGRESO' => $request->dp_fecha_ingreso,
                'IDMODULO' => strtoupper($request->moduloSeleccionado),
                'TVL_ID' => $request->tlv_id,
                'N_CONTRATO' => strtoupper($request->n_contrato),
                'GI_ID' => $request->gi_id,
                'GI_CARRERA' => strtoupper($request->gi_carrera),
                'GI_CURSO_ESP' => strtoupper($request->gi_curso_esp),
                'DLP_JEFE_INMEDIATO' => strtoupper($request->dlp_jefe_inmediato),
                'DLP_CARGO' => strtoupper($request->dlp_cargo),
                'DLP_TELEFONO' => $request->dlp_telefono,
                'TIP_CAS' => $request->tip_cas,
            ])->filter(function ($value) {
                return $value !== null && $value !== ''; // Solo conservar valores no vacíos
            })->toArray();

            $pending = [];

            // Verificar campos obligatorios y agregar los pendientes
            if (empty($request->ape_pat)) {
                $pending[] = "Apellido Paterno";
            }
            if (empty($request->ape_mat)) {
                $pending[] = "Apellido Materno";
            }
            if (empty($request->nombre)) {
                $pending[] = "Nombre";
            }
            if (empty($request->num_doc)) {
                $pending[] = "Número de Documento";
            }
            if (empty($request->id_tipo_doc)) {
                $pending[] = "Tipo de Documento";
            }
            if (empty($request->sexo)) {
                $pending[] = "Sexo";
            }
            if (empty($request->celular)) {
                $pending[] = "Celular";
            }
            if (empty($request->telefono)) {
                $pending[] = "Teléfono";
            }
            if (empty($request->correo)) {
                $pending[] = "Correo Personal";
            }
            if (empty($request->correo_institucional)) {
                $pending[] = "Correo Institucional";
            }
            if (empty($request->direccion)) {
                $pending[] = "Dirección Actual";
            }
            if (empty($request->departamentoSeleccionado)) {
                $pending[] = "Departamento";
            }
            if (empty($request->provinciaSeleccionada)) {
                $pending[] = "Provincia";
            }
            if (empty($request->distritoSeleccionado)) {
                $pending[] = "Distrito";
            }
            if (empty($request->fech_nacimiento)) {
                $pending[] = "Fecha de Nacimiento";
            }
            if (empty($request->ecivil)) {
                $pending[] = "Estado Civil";
            }
            if (empty($request->df_n_hijos)) {
                $pending[] = "Número de Hijos";
            }
            if (empty($request->pcm_talla)) {
                $pending[] = "Talla de Polo";
            }
            if (empty($request->cargoSeleccionado)) {
                $pending[] = "Cargo";
            }
            if (empty($request->dp_fecha_ingreso)) {
                $pending[] = "Fecha de Ingreso al Centro MAC";
            }
            if (empty($request->tlv_id)) {
                $pending[] = "Modalidad de Contrato";
            }
            if (empty($request->n_contrato)) {
                $pending[] = "Número de Contrato";
            }
            if (empty($request->tip_cas)) {
                $pending[] = "Tipo de CAS (si aplica)";
            }
            if (empty($request->gi_id)) {
                $pending[] = "Grado de Instrucción";
            }
            if (empty($request->gi_carrera)) {
                $pending[] = "Carrera / Profesión";
            }
            if (empty($request->gi_curso_esp)) {
                $pending[] = "Cursos de Especialización (opcional)";
            }
            if (empty($request->dlp_jefe_inmediato)) {
                $pending[] = "Jefe Inmediato Superior";
            }
            if (empty($request->dlp_cargo)) {
                $pending[] = "Cargo del Jefe Inmediato";
            }
            if (empty($request->dlp_telefono)) {
                $pending[] = "Teléfono del Jefe Inmediato";
            }
            if (empty($request->inglesSeleccionado)) {
                $pending[] = "Nivel de Inglés";
            }
            if (empty($request->quechuaSeleccionado)) {
                $pending[] = "Nivel de Quechua";
            }
            if (empty($request->dni) && !$request->hasFile('dni')) {
                $pending[] = "Documento Adjunto (DNI)";
            }

            // // Verificar si hay campos pendientes
            // if (!empty($pending)) {
            //     return response()->json([
            //         "status" => false,
            //         "message" => "Por favor complete los campos obligatorios antes de continuar.",
            //         "pending" => $pending
            //     ], 422);
            // }

            // Actualizar los datos de M_PERSONAL
            DB::table('db_centros_mac.M_PERSONAL')
                ->where('IDPERSONAL', $request->idpersonal)
                ->update(array_merge($inputs, ['UPDATED_AT' => now()]));

            // Manejo del archivo
            if ($request->hasFile('dni')) {
                $estructura_carp = 'personal/num_doc/' . $request->num_doc;

                // Crea el directorio si no existe
                if (!file_exists(public_path($estructura_carp))) {
                    mkdir(public_path($estructura_carp), 0777, true);
                }

                $archivoDNI = $request->file('dni');
                $nombreDNI = $archivoDNI->getClientOriginalName();  
                $formatoDNI = $archivoDNI->getClientOriginalExtension();
                $tamañoEnKBDNI = $archivoDNI->getSize() / 1024; // Tamaño en KB
                $namerutaDNI = $estructura_carp . '/' . $nombreDNI;

                // Mueve el archivo
                $archivoDNI->move(public_path($estructura_carp), $nombreDNI);

                // Actualiza la tabla A_PERSONAL
                DB::table('db_centros_mac.A_PERSONAL')->updateOrInsert(
                    ['IDPERSONAL' => $request->idpersonal, 'NOMBRE_ARCHIVO' => $nombreDNI],
                    [
                        'NOMBRE_RUTA' => $namerutaDNI,
                        'FORMATO_DOC' => $formatoDNI,
                        'PESO_DOC' => $tamañoEnKBDNI,
                        'FECHA_CREACION' => now(),
                    ]
                );
            }

            // Enviar correo si está habilitado
            $configuracion = DB::table('configuration_sist')->where('PARAMETRO', 'CORREO')->first();
            if ($configuracion->FLAG == '1' && $request->correo) {
                $nombres_dat = $request->nombre . ' ' . $request->ape_pat . ' ' . $request->ape_mat;
                Mail::to($request->correo)->send(new ConfirmacionRegistro($nombres_dat, $pending));  // Pasamos los pendientes
            }

            return response()->json([
                "status" => true,
                "message" => "Los datos se han guardado exitosamente.",
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $errores = $e->errors();
            $mensajes = [];
        
            // Recorre cada error y crea un mensaje amigable
            foreach ($errores as $campo => $mensaje) {
                $mensajes[] = $mensaje[0]; // Toma el primer mensaje de error para cada campo
            }
        
            return response()->json([
                "status" => false,
                "message" => "Errores de validación. Por favor, complete los campos obligatorios.",
                "errors" => $mensajes, // Retorna los mensajes amigables
            ], 422);        
        } catch (\Illuminate\Database\QueryException $e) {
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
