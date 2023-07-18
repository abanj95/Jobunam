<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;

class FileUpload extends Controller
{
    public function createForm(){
        return view('file-upload');
    }

    public function fileUpload(Request $req){
        $req->validate([
            'file' => 'required|mimes:pdf,txt|max:2048'
        ]);

        $fileModel = new File;
        if($req->file()) {
            $fileName = time().'_'.$req->file->getClientOriginalName();
            $filePath = $req->file('file')->storeAs('uploads', $fileName, 'public');
            $fileModel->name = time().'_'.$req->file->getClientOriginalName();
            $fileModel->file_path = '/storage/' . $filePath;
            $relatedObjectId = Uuid::uuid4()->toString();
            $fileModel->related_object_id = $relatedObjectId;
            $fileModel->save();

            return back()
            ->with('success','Resume has been uploaded.')
            ->with('file', $fileName)
            ->with('relatedObjectId', $relatedObjectId);
        }
    }
}
