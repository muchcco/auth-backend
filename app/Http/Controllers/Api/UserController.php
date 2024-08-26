<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Profile;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Mail\ConfirmacionUsuario;
use Illuminate\Support\Facades\Mail;
use App\Models\Userint;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            "name" => 'required',
            "email" => 'required|email|unique:users',
            "password" => 'required|confirmed'
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json([
            "status" => 1,
            "message" => "Registro exitoso!",
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            "name" => 'required',
            "password" => 'required',
            "id_perfil" => 'required'
        ]);

        $user = User::where("name", $request->name)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $token = JWTAuth::fromUser($user);
            $roles = $user->getRoleNames();

            return response()->json([
                "status" => true,
                "message" => "Conexión correcta",
                "data" => [
                    "access_token" => $token,
                    "user" => [
                        "id" => $user->id,
                        "name" => $user->name,
                        "email" => $user->email,
                        "roles" => $roles,
                        "perfil" => $request->id_perfil
                    ]
                ]
            ], 200);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Credenciales incorrectas!"
            ], 404);
        }
    }

    public function userProfile(Request $request, $name)
    {
        $user = User::where('name', $name)->first();

        if(!$user){
            return response()->json([
                "status" => '201',
                "message" => "Usuario no encontrado!"
                ], 200);
            
        }       

        $datos_com = DB::connection('mysqlintranet')
                            ->table('M_PERSONAL AS MP')
                            ->join('D_PERSONAL_CARGO AS DPC', 'DPC.IDCARGO_PERSONAL', '=', 'MP.IDCARGO_PERSONAL')
                            ->join('M_CENTRO_MAC AS MCM', 'MCM.IDCENTRO_MAC', '=','MP.IDMAC')
                            ->select(DB::raw("CONCAT(MP.APE_PAT,' ', MP.APE_MAT,', ', MP.NOMBRE) AS NOMBREU"), "DPC.NOMBRE_CARGO AS CARGO", 'MP.NUM_DOC', 'MCM.NOMBRE_MAC')
                            ->where('MP.IDPERSONAL', $user->id_personal)
                            ->first();
                            // dd($datos_com);

        if (!$user) {
            return response()->json([
                "status" => 0,
                "message" => "El usuario no existe."
            ], 404);
        }

        $profiles = $user->profiles()->get();

        $data = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'id_personal' => $user->id_personal,
            ],
            'profiles' => $profiles,
            'datos_com' => $datos_com
        ];

        return response()->json([
            "status" => 1,
            "message" => "Perfiles del usuario",
            "data" => $data
        ]);
    }

    public function allUser()
    {
        $users = User::all();

        return response()->json([
            "status" => true,
            "message" => "Acerca del perfil de usuario",
            "data" => $users
        ]);
    }

    public function refreshToken()
    {
        $token = JWTAuth::parseToken()->refresh();
        $user = JWTAuth::setToken($token)->toUser();

        return response()->json([
            'email' => $user->email,
            'token' => $token
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            "status" => 1,
            "message" => "Sesión cerrada correctamente"
        ]);
    }

    /***************************** MODULO ADMINISTRACION ****************************************************/

    public function userPersonal(Request $request)
    {
        $us_exist = DB::select("SELECT GROUP_CONCAT(id_personal) AS list_us FROM users ;");

        $us_exist_array = array_map('intval', explode(',', $us_exist[0]->list_us));

        $query = DB::table('db_centros_mac.m_personal')
            ->join('db_centros_mac.m_centro_mac', 'db_centros_mac.m_centro_mac.IDCENTRO_MAC', '=', 'db_centros_mac.m_personal.IDMAC')
            ->where('db_centros_mac.m_personal.flag', 1)
            ->whereNotIn('db_centros_mac.m_personal.IDPERSONAL', $us_exist_array)
            ->get();

        $perfil = DB::table('profiles')->get();

        $roles = Role::pluck('name', 'id');

        return response()->json([
            "status" => 1,
            "message" => "datos varios",
            "personal" => $query,
            "profile" => $perfil,
            "roles" => $roles
        ]);
    }



    public function usersStore(Request $request)
    {
        try {

            // Capturamos los datos del request
            $IDPERSONAL = $request->input('IDPERSONAL');
            $nombre = $request->input('nombre');
            $perfil_ = $request->input('perfil_'); // Aquí obtenemos el array de perfiles
            $rol_ = $request->input('rol_');

            // Verificamos si los datos están llegando correctamente
            if (is_null($IDPERSONAL) || is_null($nombre) || is_null($perfil_) || is_null($rol_)) {
                return response()->json([
                    "status" => false,
                    "message" => "Faltan datos en la solicitud."
                ], 400);
            }

            $personal = DB::table('db_centros_mac.m_personal')->where('IDPERSONAL', $request->IDPERSONAL)->first();

            $save = new User;
            $save->name = $personal->NUM_DOC;
            $save->email = $personal->CORREO; 
            $save->id_personal = $request->IDPERSONAL;
            $save->idcentro_mac = $personal->IDMAC;
            $save->password = bcrypt($personal->NUM_DOC);
            $save->save();

            foreach ($request->perfil_ as $perfilId) {
                DB::table('user_profile')->insert([
                    'user_id' => $save->id,  
                    'profile_id' => $perfilId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('model_has_roles')->insert([
                'role_id' => $request->rol_,  
                'model_type' => 'App\\Models\\User',  
                'model_id' => $save->id,
            ]);

            /*** HORA GUARDAMOS LOS DATOS EN LA USERS DE LA TABLA CENTROS_MAC  PARA PODER TENER UNIFORMIDAD */

            $save2 = new Userint;
            $save2->name = $personal->NOMBRE.' '.$personal->APE_PAT.' '.$personal->APE_MAT;
            $save2->email = $personal->NUM_DOC; 
            $save2->idpersonal = $request->IDPERSONAL;
            $save2->idcentro_mac = $personal->IDMAC;
            $save2->password = $save->password;
            $save2->flag = 1;
            $save2->save();

            // $save_2 = DB::table('db_centros_mac.users')->insert([
            //     'name'      =>  $personal->NOMBRE.' '.$personal->APE_PAT.' '.$personal->APE_MAT,
            //     'email'     =>  $personal->CORREO,
            //     'idpersonal'=>  $request->IDPERSONAL,
            //     'password'  =>  bcrypt($personal->NUM_DOC),
            //     'idcentro_mac'  =>  $personal->IDMAC,
            //     'flag'      =>  1,
            //     'created_at' => now(),
            //     'updated_at' => now(),
            // ]);



            DB::table('db_centros_mac.model_has_roles')->insert([
                'role_id' => $request->rol_,  
                'model_type' => 'App\\Models\\User',  
                'model_id' => $save2->id,
            ]);

            /*** FIN  ******/

            $nombres_dat = $personal->NOMBRE.' '.$personal->APE_PAT.' '.$personal->APE_MAT;
            $usuario = $personal->NUM_DOC;
            $password = $personal->NUM_DOC;

            $configuracion = DB::table('configuration_sist')->where('PARAMETRO', 'CORREO')->first();

            if($configuracion->FLAG == '1'){
                if($personal->CORREO){
                    Mail::to($personal->CORREO)->send(new ConfirmacionUsuario($nombres_dat, $usuario , $password));
                } 
            }

            return response()->json([
                "status" => true,
                "message" => "Usuario creado exitosamente.",
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

