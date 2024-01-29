<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Illuminate\Support\Facades\Storage;

class GoogleDriveController extends Controller
{
    public function index()
    {
        $files = Storage::disk("google")->allFiles();
        $directories = Storage::disk("google")->Directories();

        //dd($directories);
        $details = [];
        $visibilities = [];
        //$links = [];
        foreach ($files as $file) {
            $details[] = Storage::disk("google")->getMetadata($file);
            $visibilities[] = Storage::disk("google")->getVisibility($file);
            //$links[] = Storage::disk("google")->url($file);
        }

        //dd($details);
        return view('google-drive.index', compact("details", "visibilities", "directories"));
    }

    public function upload(Request $request)
    {
        /*$this->validate($request, [
            'file' => 'required|mimes:pdf,jpg,jpeg,png',
        ]);*/
        if ($request->hasFile('file')) {
            $this->validate($request, [
                'file' => 'mimes:pdf,jpg,jpeg,png',
            ]);

            //dd($request->file('file'));
            $file = $request->file('file');

            $fileName = $file ->getClientOriginalName();
            $fileType = $file ->getClientOriginalExtension();

            $file = Storage::disk("google")->putFileAs("",$request->file("file"), $fileName);
            //$request->file("file")->store("", "google");

            return redirect()->route('google-drive.index')->with('success', 'Archivo subido exitosamente.');
        }else{

            return redirect()->route('google-drive.index')->with('error', 'No se proporcionó ningún archivo.');
        }

    }

    public function download(Request $request, $filename)
    {
        //dd($filename);
        $detail = Storage::disk("google")->getMetadata($filename);

        $response = Storage::disk("google")->download($filename, $detail["filename"].".".$detail["extension"]);

        $response->send();

        return redirect()->route('google-drive.index');
    }

    public function changeVisibility(Request $request, $filename)
    {
        $visibility = $request->input('visibility');
        Storage::disk("google")->setVisibility($filename, $visibility);
        //$visibility2 = Storage::disk("google")->getVisibility($filename);

        //dd($visibility);
        return redirect()->route('google-drive.index');
    }

    public function deleteFile($filename)
    {
        try {
            Storage::disk("google")->delete($filename);
            return redirect()->route('google-drive.index')->with('success', 'Archivo eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('google-drive.index')->with('error', 'Error al eliminar el archivo: ' . $e->getMessage());
        }
    }
    public function renameFile(Request $request, $filename)
    {
        //dd('funcionaa');
        $newFilename = $request->input('newFilename');
        //dd($newFilename);
        try {
           // Realiza la lógica para cambiar el nombre del archivo, por ejemplo:
            Storage::disk('google')->rename($filename, $newFilename);
            return redirect()->route('google-drive.index')->with('success', 'Nombre cambiado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('google-drive.index')->with('error', 'Error al cambiar el archivo: ' . $e->getMessage());
        }

    }

    public function makedirectory(Request $request){
        $name = $request->input('directory');
        try {
            Storage::disk('google')->makedirectory($name);
             return redirect()->route('google-drive.index')->with('success', 'Directorio exitosamente.');
         } catch (\Exception $e) {
             return redirect()->route('google-drive.index')->with('error', 'Error crear el Directorio: ' . $e->getMessage());
         }
    }

    public function deleteDirectory(Request $request, $directory){
        try {
            Storage::disk('google')->deleteDirectory($directory);
             return redirect()->route('google-drive.index')->with('success', 'Directorio elimiado exitosamente.');
         } catch (\Exception $e) {
             return redirect()->route('google-drive.index')->with('error', 'Error al eliminar el Directorio: ' . $e->getMessage());
         }
    }

    public function viewFile($filename){
        //dd($filename);

        return view('google-drive.view-file', compact('filename'));
    }


    private function getClient()
    {

    }
}
