<?php

namespace App\Http\Controllers\API;

use App\File;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index(){
        return File::with('folder')->paginate(5);
    }


    public function store(Request $request)
    {
        $this->validate($request,[
            'file' => 'required'
        ]);
        try {
                $path = public_path('file');
                $file = $request->file;
                $extention = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extention;
                $file->move($path, $filename);
           File::create([
                'folder_id'=> $request['folder_id'],
                'file'=> @$filename,
            ]);
            return response()->json(['success'=>'You have successfully Save'],200);
        }
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],400);
        }
    }
    public function update(Request $request, $id)
    {
        $folder=File::findOrFail($id);
        $folder->update(
            $request->all()
        );
    }
    public function destroy($id)
    {
        File::destroy($id);
    }
}
