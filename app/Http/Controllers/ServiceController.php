<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Service;

use App\Models\Prospection_service;

use DB;

class ServiceController extends Controller
{
    //Handle Services (prestation)

    //Récupérer tout
    public function GetAll()
    {
        
        $get = DB::table('services')
        ->join('categories', 'services.id_categorie', '=', 'categories.id')
        ->orderBy('libele_service', 'asc')
        
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

    public function DeleteServiceInProspection(Request $request)
    {
        
        $deleted = DB::table('prospection_services')->where('id', '=', $request->id)->delete();

        return back()->with('success', 'Elément supprimé');
    }

    public function DeleteServiceInFicheProspection(Request $request)
    {
        
        $deleted = DB::table('prospection_services')->where('id', '=', $request->id_service)->delete();

        return view('dash/prospect_about',
            [
                'id_entreprise' => $request->id_entreprise,
                'success' => 'Elément supprimé'
            ]
        );
    }

    public function DeleteServiceInFicheCustomer(Request $request)
    {
        
        $deleted = DB::table('prestation_services')->where('id', '=', $request->id_service)->delete();

        return view('dash/fiche_customer',
            [
                'id_entreprise' => $request->id_entreprise,
                'success' => 'Elément supprimé'
            ]
        );
    }


    public function DeleteServiceInPrestation(Request $request)
    {
        
        $deleted = DB::table('prestation_services')->where('id', '=', $request->id)->delete();

        return back()->with('success', 'Elément supprimé');
    }


    public function GetByCategorie($id)
    {
        $get = DB::table('services')
        ->where('id_categorie', $id)
        ->orderBy('libele_service', 'asc')
        ->get();

        return $get;
    }

    public function AddServiceInFiche(Request $request)
    {
      
        if($request->service_propose == false)//L'utilisateur peut ne pas rempli
        {
           
        }
        else
        {
            for($a = 0; $a < count($request->service_propose); $a++)
            {
                
                $Insert = Prospection_service::create([
        
                    'service_id' =>  $request->service_propose[$a],
                    'prospection_id' => $request->id_prospection,

                ]);
            }
        }

        return view('dash/prospect_about',
            [
                'id_entreprise' => $request->id_entreprise,
                'success' => 'Service ajouté'
            ]
        );
    }
}
