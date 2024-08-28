<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\Profile;

class UsersController extends Controller
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

    public function usersDetails()
    {
        $profile = DB::select("SELECT * FROM profiles");
        
        $roles = Role::pluck('name', 'id');

        return response()->json([
            "status" => 1,
            "message" => "Registro exitoso!",
            "profile"  => $profile,
            "roles" => $roles
        ]);

    }

    public function usersList(Request $request)
    {
        $users = User::select([
            'users.id',
            'users.name',
            DB::raw("CONCAT(personal.NOMBRE, ', ', personal.APE_PAT, ' ', personal.APE_MAT) AS nombreu"),
            'centroMac.NOMBRE_MAC',
            'entidad.ABREV_ENTIDAD',
            'personal.CORREO'
        ])
        ->join('db_centros_mac.m_personal as personal', 'users.id_personal', '=', 'personal.IDPERSONAL')
        ->join('db_centros_mac.m_centro_mac as centroMac', 'personal.IDMAC', '=', 'centroMac.IDCENTRO_MAC')
        ->join('db_centros_mac.m_entidad as entidad', 'personal.IDENTIDAD', '=', 'entidad.IDENTIDAD')
        ->leftJoin('user_profile as up', 'users.id', '=', 'up.user_id')
        ->leftJoin('profiles as p', 'up.profile_id', '=', 'p.id')
        ->leftJoin('model_has_roles as mhr', function ($join) {
            $join->on('users.id', '=', 'mhr.model_id')
                ->where('mhr.model_type', '=', User::class);
        })
        ->leftJoin('roles as r', 'mhr.role_id', '=', 'r.id')
        ->groupBy('users.id', 'users.name', 'personal.NOMBRE', 'personal.APE_PAT', 'personal.APE_MAT', 'centroMac.NOMBRE_MAC', 'entidad.ABREV_ENTIDAD', 'personal.CORREO');

        if (auth()->user()->hasRole('Especialista TIC|Orientador|Asesor|Supervisor|Coordinador')) {
            $users->where('personal.IDMAC', $this->centro_mac()->idmac);
        }

        $users = $users->with([
                    'profiles:description',
                    'roles:name'
                ])
                ->get()
                ->map(function ($user) {
                    $user->perfiles = $user->profiles->pluck('description')->implode(', ');
                    $user->roles = $user->roles->pluck('name')->implode(', ');
                    return $user;
                });


        // dd($users);
    
        return response()->json([
            "status" => 1,
            "message" => "Carga exitosa!",
            "data"  => $users
        ]);
    }    

    public function usersAdd(Request $request)
    {
        try {
            // Crear el nuevo usuario
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->id_personal = $request->id_personal;
            $user->password = Hash::make($request->password);

            $user->save();

            // Asignar roles al usuario
            $roles = array_filter($request->roles);
            foreach ($roles as $roleName) {
                $role = Role::firstOrCreate(['name' => $roleName]);
                $user->assignRole($role);
            }

            // Asociar el perfil al usuario en la tabla intermedia
            $profileId = $request->profile_id;
            $user->profiles()->attach($profileId);

            return response()->json([
                "status" => 1,
                "message" => "Registro exitoso!",
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
