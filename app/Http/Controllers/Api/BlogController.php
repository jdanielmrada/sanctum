<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    // Create
    public function createBlog(Request $request)
    {
        // validation
        $request->validate([
            "title" => "required",
            "content" => "required"
        ]);
        // get id
        $user_id = auth()->user()->id;
        // create new blog and save date
        $blog = new Blog();
        $blog->user_id = $user_id;
        $blog->title = $request->title;
        $blog->content = $request->content;

        $blog->save();
        // return answer
        return response()->json([
            'status' => 1,
            'msg' => 'Registro de datos exitoso'
        ], 200);
    }

    // list
    public function listBlog()
    {
        // get id
        $user_id = auth()->user()->id;
        // compare auth id with blog_user-id
        $blogs = Blog::where('user_id', $user_id)->get();

        // return answer
        return response()->json([
            'status' => 1,
            'msg' => 'Listado de blog',
            'data' => $blogs
        ], 200);
    }

    // show
    public function showBlog($id)
    {
        // get id
        $user_id = auth()->user()->id;
        // exists blog for to show
        if (Blog::where(['user_id' => $user_id, 'id' => $id])->exists()) {
            // show data
            $blog = Blog::find($id);

            // return answer
            return response()->json([
                'status' => 1,
                'msg' => 'Mostrando blog',
                'data' => $blog
            ]);
            // no exists
        } else {
            // return answer
            return response()->json([
                'status' => 0,
                'msg' => 'No se encontro ese blog'
            ], 404);
        }
    }

    // update
    public function updateBlog(Request $request, $id)
    {
        // get id
        $user_id = auth()->user()->id;
        // exists blog for to update
        if (Blog::where(['user_id' => $user_id, 'id' => $id])->exists()) {
            // update data
            $blog = Blog::find($id);

            $blog->title = isset($request->title) ? $request->title : $blog->title;
            $blog->content = isset($request->content) ? $request->content : $blog->content;
            $blog->save();

            // return answer
            return response()->json([
                'status' => 1,
                'msg' => 'Actualizado por completo'
            ]);
            // no exists
        } else {
            // return answer
            return response()->json([
                'status' => 0,
                'msg' => 'No se encontro ese blog'
            ], 404);
        }
    }

    // delete
    public function deleteBlog($id)
    {
        // get id
        $user_id = auth()->user()->id;
        // exists blog for to delete
        if (Blog::where(['user_id' => $user_id, 'id' => $id])->exists()) {
            // delete data
            $blog = Blog::where(['user_id' => $user_id, 'id' => $id]);

            $blog->delete();

            // return answer
            return response()->json([
                'status' => 1,
                'msg' => 'Eliminado por completo'
            ]);
            // no exists
        } else {
            // return answer
            return response()->json([
                'status' => 0,
                'msg' => 'No se encontro ese blog'
            ], 404);
        }
    }
}
