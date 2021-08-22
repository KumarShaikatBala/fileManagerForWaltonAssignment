<?php

namespace App\Http\Controllers\API;

use App\Folder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FolderController extends Controller
{

    public function index(){
        return Folder::paginate(5);
    }


    public function store(Request $request)
    {
        $this->validate($request,[
            'folder' =>'required',
        ]);
         Folder::create([
            'folder' => $request['folder'],
        ]);
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
