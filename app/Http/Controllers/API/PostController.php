<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    
        $data['data'] = Post::all();

        return response()->json([
                    'status' => true,
                    'message' => 'All Post Data',
                    'data' => $data,
              ], 200);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       
         $validateUser = Validator::make(
            $request->all(), 
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'required|mimes:png,jpg,jpeg,gif', 
            ]
        );

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation errors',
                    'errors' => $validateUser->errors()->all(),
                ], 422);
            }

            $img = $request->image;
            // $extension = $img->getClientOriginalExtension();
            // $imagename = time().'.'.$extension;

            $originalName = $img->getClientOriginalName();
            $newName = time() . '_' . $originalName;
            $img->move(public_path('/uploads'), $newName);

            // img path: C:\xampp\htdocs\laravel-api\public\/uploads\1756094375_Salman_Khan.jpg

            $user = Post::create([
                   'title' => $request->title,
                   'description' => $request->description,
                   'image' => $newName,
            ]);
            
            return response()->json([
                'status' => true,
                'message' => 'Post Created Successfully',
                'user' => $user,
            ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       
        $data['data'] = Post::find($id);

        return response()->json([
                'status' => true,
                'message' => 'Post Listing Successfully',
                'user' => $data,
            ], 200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
      
         $validateUser = Validator::make(
            $request->all(), 
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'required|mimes:png,jpg,jpeg,gif', 
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation errors',
                'errors' => $validateUser->errors()->all(),
            ], 422);
        }
      
          // Step 1: Get the old image name (optional use)
            $post = Post::select('id', 'image')->find($id);
            if (!$post) {
                return response()->json([
                    'status' => false,
                    'message' => 'Post not found',
                ], 404);
            }

             // Step 2: Handle image upload if new image is sent
            if ($request->hasFile('image')) {
                $img = $request->file('image');
                $originalName = $img->getClientOriginalName();
                $newName = time() . '_' . $originalName;
                $img->move(public_path('/uploads'), $newName);
            } else {
                $newName = $post->image; // Keep old image if not replaced
            }

            // Step 3: Update the post
            $updated = Post::where('id', $id)->update([
                'title' => $request->title,
                'description' => $request->description,
                'image' => $newName,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Post Updated Successfully',
                'user' => $updated,
            ], 201);

}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imgpath = Post::select('image')->find($id);
        $filepath = $imgpath->image;
        //unlink($filepath);
        $post = Post::where('id', $id)->delete();
        return response()->json([
                'status' => true,
                'message' => 'Post Has been  Removed',
                'user' => $post,
            ], 200);

    }
}
