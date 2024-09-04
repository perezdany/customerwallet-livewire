<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Service;

use DB;

class ServiceController extends Controller
{
    //Handle Services (prestation)

    //Récupérer tout
    public function GetAll()
    {
        
        $get = DB::table('services')
        ->join('categories', 'services.id_categorie', '=', 'categories.id')
        
        ->get(['services.*', 'categories.libele_categorie']);

        return $get;
    }

    public function GetById($id)
    {
        $get = Service::where('id', $id)->get();

        return $get;
    }

    public function EditServiceForm(Request $request)
    {
        //dd( $request->id_service);
        return view('admin/services',
        [
            'id_service' => $request->id_service,
        ]
        );
    }

    public function EditService(Request $request )
    {
        //dd($request->id_service);

        $affected = DB::table('services')
        ->where('id', $request->id_service)
            ->update([
            'libele_service' => $request->libele, 
            'description' => $request->description,
            
       ]);
        return redirect('services')->with('success', 'Modification effectuée');
    }

    public function AddService(Request $request)
    {
        $Insert = Service::create([
            'libele_service' => $request->libele,  
            'description' => $request->description,
            
       ]);

       return redirect('services')->with('success', 'Enregistrement effectué');
       
    }

    public function DeleteService(Request $request)
    {
        $deleted = DB::table('services')->where('id', '=', $request->id_service)->delete();

        return redirect('services')->with('success', 'Elément supprimé');
    }

    public function GetByCategorie($id)
    {
        $get = Service::where('id_categorie', $id)->get();

        return $get;
    }
}
