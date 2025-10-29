<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Service;

use App\Models\Prospection_service;
use App\Models\Prestation_service;

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

    public function GetAllNoSusp()
    {
        
        $get = DB::table('services')
        ->join('categories', 'services.id_categorie', '=', 'categories.id')
        ->where('suspendu', 0)
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
            'id_categorie' => $request->categorie
            
       ]);
        return redirect('services')->with('success', 'Modification effectuée');
    }

    public function AddService(Request $request)
    {
        $Insert = Service::create([
            'libele_service' => $request->libele,  
            'description' => $request->description,
            'id_categorie' => $request->categorie
       ]);

       return redirect('services')->with('success', 'Enregistrement effectué');
       
    }

    public function DeleteService(Request $request)
    {
        $verif =  DB::table('prestation_services')->where('service_id', '=', $request->id_service)->count();
        //dd($verif);
        if($verif != 0)
        {
            //dd('oui oui');
            return redirect('services')->with('error', 'Ce service ne peut être supprimé, un contrat lui est associé');
        }
        else
        {
            //dd('i');
            $deleted = DB::table('services')->where('id', '=', $request->id_service)->delete();
            return redirect('services')->with('success', 'Elément supprimé');
        }
    }

    public function DeleteServiceInContrat(Request $request)
    {
        //dd($request->id);
       // dd(DB::table('prestation_services')->where('id', '=', $request->id)->get());
        $deleted = DB::table('prestation_services')->where('id', '=', $request->id)->delete();

        return back()->with('success', 'Elément supprimé');
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

    public function GetByCategorieNoSusp($id)
    {
        $get = DB::table('services')
        ->where('suspendu', 0)
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

    public function AddServiceInContrat(Request $request)
    {
      //dd($request->all());
        if($request->service == false)//L'utilisateur peut ne pas rempli
        {
           //dd('d');
        }
        else
        {
            //dd('ici');
            for($a = 0; $a < count($request->service); $a++)
            {
                
                $Insert = Prestation_service::create([
        
                    'service_id' =>  $request->service[$a],
                    'contrat_id' => $request->id_contrat,

                ]);
            }
            //dd($Insert);
        }

        return back()->with('success', 'Le service a été ajouté');
    }
}
