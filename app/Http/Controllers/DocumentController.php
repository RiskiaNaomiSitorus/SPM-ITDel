<?php

namespace App\Http\Controllers;

use App\Models\DocumentModel;
use App\Models\RoleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\File\Exception\IniSizeFileException;

class DocumentController extends Controller
{
    public function getDocumentManagementView () {
        if (Auth::check()) {
            $documents = DocumentModel::all();
        }

        else {
            $documents = DocumentModel::where('give_access_to', 0)->get();
        }
        $roles = RoleModel::all();

        $data = [
            'roles' => $roles,
            'documents' => $documents
        ];

        return view('document-management', $data);
    }

    public function uploadFile (Request $request) {
//        $request->validate([
//            'file' => 'required|file|mimes:pdf,doc,docx|max:2048', // Contoh: Hanya menerima file PDF, DOC, dan DOCX maksimal 2MB
//            'roles' => 'required|array', // Pastikan roles merupakan array
//            'roles.*' => 'integer|exists:roles,id', // Pastikan setiap role adalah integer dan ada di tabel roles
//        ]);

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();

        try {
            $file->move(public_path('/src/documents'), $filename);
        } catch (IniSizeFileException $e) {
            return redirect()->route('documentManagement')->with('toastData', ['success' => false, 'text' => 'File too big. The maximum file upload limit is 30 MB!']);
        }

        $accessor = implode(';', $request->give_access_to);

        DocumentModel::create([
            'name' => $filename,
            'nomor_dokumen' => $request->nomor_dokumen,
            'directory' => '/src/documents/' . $filename,
            'created_by' => auth()->user()->id,
            'created_at' => now(),
            'give_access_to' => $accessor
        ]);

        return redirect()->route('documentManagement')->with('toastData', ['success' => true, 'text' => 'File uploaded successfully!']);
    }

    public function removeDocument(Request $request) {
        $document = DocumentModel::find($request->id);
        File::delete(public_path($document->directory));
        $document->delete();

        return redirect()->route('documentManagement')->with('toastData', ['success' => true, 'text' => 'Document deleted successfully!']);
    }
}