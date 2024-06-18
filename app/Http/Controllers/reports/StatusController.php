<?php

namespace App\Http\Controllers\reports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class StatusController extends Controller
{
    public function tableStatus(Request $request)
    {
        try{

            $fecha_actual = Carbon::now()->format('Y-m-d');        

            //  dd($fecha_actual);
            $startDate = $request->input('fecha_inicio', $fecha_actual);
            $endDate = $request->input('fecha_fin', $fecha_actual );
            $nom_mac_filter = $request->input('nom_mac', null); 

            $nom_mac_condition = '';
            if ($nom_mac_filter !== null) {
                $nom_mac_condition = "WHERE PivotTable.Nom_mac = '$nom_mac_filter'";
            }
            
            
            $query = "SELECT PivotTable.Nom_mac,
                            TotalRegistros,
                            ISNULL([Abandono], 0) AS Abandono,
                                ISNULL([Llamando], 0) AS Llamando,
                                ISNULL([Cancelado], 0) AS Cancelado,
                                ISNULL([Atención Cerrada], 0) AS Atencion_Cerrada,
                                ISNULL([En espera], 0) AS En_espera,
                                ISNULL([Error de selección], 0) AS Error_de_seleccion,
                                ISNULL([Terminado], 0) AS Terminado,
                                ISNULL([Atención Iniciada], 0) AS Atencion_Iniciada
                    FROM (
                        SELECT Nom_mac, Est_ate, COUNT(*) AS Cantidad
                        FROM cre_atend
                        WHERE fec_ate BETWEEN '$startDate' AND '$endDate'
                        GROUP BY Nom_mac, Est_ate
                    ) AS SourceTable
                    PIVOT (
                        SUM(Cantidad)
                        FOR Est_ate IN ([Abandono], [Llamando], [Cancelado], [Atención Cerrada], [En espera], [Error de selección], [Terminado], [Atención Iniciada], [0])
                    ) AS PivotTable
                    JOIN (
                        SELECT Nom_mac, COUNT(distinct ide_ate) AS TotalRegistros
                        FROM cre_atend
                        WHERE fec_ate BETWEEN '$startDate' AND '$endDate'
                        AND ide_ser <> 130
                        GROUP BY Nom_mac
                    ) AS TotalTable ON PivotTable.Nom_mac = TotalTable.Nom_mac
                    $nom_mac_condition
                    ORDER BY Nom_mac ASC";

            $data = DB::connection('sqlsrv')->select($query);

            return response()->json([
                "status" => true,
                "message" => "Lista de estados",
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
