<?php

namespace App\Http\Controllers\API;

use App\Folder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FolderController extends Controller
{

    public function index(){
        return Folder::with('parent','children')->paginate(5);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'folder' =>'required',
        ]);
        try {
            Folder::create([
                'folder' => $request['folder'],
                'parent_id' => $request['parent_id'],
            ]);
            return response()->json(['success'=>'You have successfully Save'],200);
        }
        catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()],400);
        }
    }
    public function update(Request $request, $id)
    {
        $folder=Folder::findOrFail($id);
        $folder->update(
            $request->all()
        );
    }
    public function destroy($id)
    {
        Folder::destroy($id);
    }





}
