<?php

namespace App\Http\Controllers\Repository;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{

    private function buildCategoryHierarchy(array $categories, $parentId = null)
    {
        $branch = [];

        foreach ($categories as $category) {
            if ($category['id_categoria_padre'] == $parentId) {
                $children = $this->buildCategoryHierarchy($categories, $category['id']);
                if ($children) {
                    $category['children'] = $children;
                }
                $branch[] = $category;
            }
        }

        return $branch;
    }

    public function repositoryTreeview()
    {
        // Obtener todas las categorías
        $categories = DB::select("SELECT id, 
                                        REPLACE(REPLACE(denominacion, CHAR(13), ''), CHAR(10), '') as denominacion,
                                        id_categoria_padre 
                                        FROM repo_doc.categoria_doc");

        // Convertir el resultado en un array asociativo
        $categoriesArray = [];
        foreach ($categories as $category) {
            $categoriesArray[] = (array) $category;
        }

        // Construir la jerarquía de categorías
        $categoriesHierarchy = $this->buildCategoryHierarchy($categoriesArray);

        return response()->json([
            "status" => true,
            "message" => "Categorías obtenidas con éxito",
            "data" => $categoriesHierarchy
        ]);
    }  


    public function repositoryList(Request $request)
    {
        // Obtener los parámetros de búsqueda
        $categoryId = $request->input('category');
        $document = $request->input('document');
        $description = $request->input('description');
        $tagIds = $request->input('tags'); 

        $sql = "
            SELECT 
                REPLACE(REPLACE(doc.id, CHAR(13), ''), CHAR(10), '') as id,
                " . ($categoryId ? "ch.full_path as categoria," : "ctdoc.denominacion as categoria,") . "
                REPLACE(REPLACE(doc.denominacion, CHAR(13), ''), CHAR(10), '') as documento,
                REPLACE(REPLACE(doc.descripcion, CHAR(13), ''), CHAR(10), '') as descripcion,
                REPLACE(REPLACE(doc.url, CHAR(13), ''), CHAR(10), '') as url,
                GROUP_CONCAT(REPLACE(REPLACE(tag.denominacion, CHAR(13), ''), CHAR(10), '') ORDER BY tag.denominacion SEPARATOR ', ') as tags
            FROM 
                repo_doc.documento as doc
            JOIN 
                repo_doc.categoria_doc as ctdoc ON doc.id_cat_doc = ctdoc.id
            " . ($categoryId ? "JOIN category_hierarchy ch ON ctdoc.id = ch.id" : "") . "
            JOIN 
                repo_doc.documento_tags as doc_tag ON doc_tag.id_documento = doc.id
            JOIN 
                repo_doc.tag as tag ON tag.id = doc_tag.id_tag
        ";

        if ($categoryId) {
            $sql = "
                WITH RECURSIVE category_hierarchy AS (
                    SELECT 
                        id,
                        denominacion,
                        id_categoria_padre,
                        denominacion AS full_path
                    FROM 
                        repo_doc.categoria_doc
                    WHERE 
                        id = $categoryId
                    UNION ALL
                    SELECT 
                        c.id,
                        c.denominacion,
                        c.id_categoria_padre,
                        CONCAT(ch.full_path, ', ', c.denominacion) AS full_path
                    FROM 
                        repo_doc.categoria_doc c
                    JOIN 
                        category_hierarchy ch ON c.id_categoria_padre = ch.id
                )
                " . $sql;
        }

        $conditions = [];
        $bindings = [];


        if ($document) {
            $conditions[] = "doc.denominacion LIKE :document";
            $bindings['document'] = "%$document%";
        }

        if ($description) {
            $conditions[] = "doc.descripcion LIKE :description";
            $bindings['description'] = "%$description%";
        }

        if ($tagIds && is_array($tagIds) && count($tagIds) > 0) {
            $placeholders = implode(',', array_fill(0, count($tagIds), '?'));
            $conditions[] = "doc_tag.id_tag IN ($placeholders)";
            $bindings = array_merge($bindings, $tagIds);
        }

        if (count($conditions) > 0) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " GROUP BY doc.id, " . ($categoryId ? "ch.full_path" : "ctdoc.denominacion") . ", doc.denominacion, doc.descripcion, doc.url";
      
        $category_dat = DB::select($sql, $bindings);

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
