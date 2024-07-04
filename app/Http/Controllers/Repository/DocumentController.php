<?php

namespace App\Http\Controllers\Repository;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public function repositoryDetails()
    {
        $category_dat = DB::select("SELECT REPLACE(REPLACE(ctdoc.denominacion, CHAR(13), ''), CHAR(10), '') AS denominacion, REPLACE(REPLACE(ctdoc.descripcion, CHAR(13), ''), CHAR(10), '') AS descipcion,  COUNT(*) AS COUNT FROM repo_doc.documento doc
                                    JOIN repo_doc.categoria_doc ctdoc ON doc.id_cat_doc = ctdoc.id
                                    GROUP BY ctdoc.denominacion,ctdoc.descripcion");

        return response()->json([
            "status" => true,
            "message" => "Detalles obtenidos con éxito",
            "category" => $category_dat
        ]);                            
    }

    public function repositoryList(Request $request)
    {
        // Obtener los parámetros de búsqueda
        $category = $request->input('category');
        $document = $request->input('document');
        $description = $request->input('description');
        $tagIds = $request->input('tags'); // Supongamos que los tags vienen como un array de IDs

        // Construir la consulta base
        $query = DB::table('repo_doc.documento as doc')
                    ->join('repo_doc.categoria_doc as ctdoc', 'doc.id_cat_doc', '=', 'ctdoc.id')
                    ->join('repo_doc.documento_tags as ctdoctg', 'ctdoctg.id_documento', '=', 'doc.id')
                    ->select(
                        DB::raw("REPLACE(REPLACE(doc.id, CHAR(13), ''), CHAR(10), '') as id"),
                        DB::raw("REPLACE(REPLACE(ctdoc.denominacion, CHAR(13), ''), CHAR(10), '') as categoria"),
                        DB::raw("REPLACE(REPLACE(doc.denominacion, CHAR(13), ''), CHAR(10), '') as documento"),
                        DB::raw("REPLACE(REPLACE(doc.descripcion, CHAR(13), ''), CHAR(10), '') as descripcion"),
                        DB::raw("REPLACE(REPLACE(doc.url, CHAR(13), ''), CHAR(10), '') as url"),
                        DB::raw('GROUP_CONCAT(ctdoctg.tag ORDER BY ctdoctg.tag SEPARATOR ", ") as tags')
                    )
                    ->groupBy('doc.id', 'ctdoc.denominacion', 'doc.denominacion', 'doc.descripcion', 'doc.url');

        // Aplicar filtros basados en los parámetros proporcionados
        if ($category) {
            $query->where('ctdoc.id', "$category");
        }

        if ($document) {
            $query->where('doc.denominacion', 'like', "%$document%");
        }
    
        if ($description) {
            $query->where('doc.descripcion', 'like', "%$description%");
        }

        if ($tagIds && is_array($tagIds)) {
            $query->whereIn('ctdoctg.tag', $tagIds);
        }

        // Ejecutar la consulta
        $category_dat = $query->get();

        // dd($category_dat);

        return response()->json([
            "status" => true,
            "message" => "Detalles obtenidos con éxito",
            "category" => $category_dat
        ]); 
    }

    public function repositoryStoreDoc(Request $request)
    {
        try {

            $save_doc = DB::table('repo_doc.documento')->insert([
                'id_usuario' => auth()->user()->id,
                'id_cat_doc' => $request->input('category'),
                'denominacion' => $request->input('document'),
                'descripcion' => $request->input('description'),
                'url' => $request->input('url'),
                'fec_registro' => date('Y-m-d H:i:s')
            ]);

            $save_tag = DB::table('repo_doc.documento_tags')->insert([
                'id_documento' => $save_doc->id,
                'tag'          => $request->input('tag')
            ]);

            return response()->json([
                "status" => true,
                "message" => "El documento se guardo con éxito",
                "data_doc" => $save_doc,
                "data_tag" => $save_tag
            ]); 

        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Se excedió el tiempo de carga. Inténtelo de nuevo más tarde.",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function repositoryUpdate(Request $request, $id)
    {
        try {

            $update_doc = DB::table('repo_doc.documento')->where('id', $id)->update([
                'id_usuario' => auth()->user()->id,
                'id_cat_doc' => $request->input('category'),
                'denominacion' => $request->input('document'),
                'descripcion' => $request->input('description'),
                'url' => $request->input('url'),
                'fec_registro' => date('Y-m-d H:i:s')
            ]);

            return response()->json([
                "status" => true,
                "message" => "El documento se actualizo con éxito",
                "data_doc" => $update_doc,
            ]); 

        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Se excedió el tiempo de carga. Inténtelo de nuevo más tarde.",
                "error" => $e->getMessage()
            ], 500);
        }
    }

    public function repositoryDelete(Request $request, $id)
    {
        try {

            $delete_doc = DB::table('repo_doc.documento')->where('id', $id)->delete();

            $delete_tag = DB::table('repo_doc.documento_tags')->where('id_documento', $id)->delete();

            return response()->json([
                "status" => true,
                "message" => "El documento se elimino con éxito",
                "data_doc" => $delete_doc,
                "tag_doc" => $delete_tag,
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
