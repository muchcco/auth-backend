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
        $users = DB::select("SELECT 
                                sl.id,
                                sl.name, 
                                CONCAT(dcmp.NOMBRE, ', ', dcmp.APE_PAT, ' ', dcmp.APE_MAT) AS nombreu, 
                                dcmm.NOMBRE_MAC,
                                dcme.ABREV_ENTIDAD,
                                dcmp.CORREO,
                                GROUP_CONCAT(p.description ORDER BY p.description ASC SEPARATOR ', ') AS perfiles
                            FROM users sl
                            JOIN db_centros_mac.m_personal dcmp ON sl.id_personal = dcmp.IDPERSONAL
                            JOIN db_centros_mac.m_centro_mac dcmm ON dcmp.IDMAC = dcmm.IDCENTRO_MAC
                            JOIN db_centros_mac.m_entidad dcme ON dcmp.IDENTIDAD = dcme.IDENTIDAD
                            JOIN user_profile up ON sl.id = up.user_id
                            JOIN profiles p ON up.profile_id = p.id
                            GROUP BY 
                                sl.id, sl.name, dcmp.NOMBRE, dcmp.APE_PAT, dcmp.APE_MAT, dcmm.NOMBRE_MAC,dcme.ABREV_ENTIDAD, dcmp.CORREO;");

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
