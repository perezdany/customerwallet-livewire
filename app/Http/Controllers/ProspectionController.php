<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Prospection;

use App\Http\Controllers\Calculator;

use App\Http\Controllers\InterlocuteurController;

use App\Http\Controllers\EntrepriseController;
use DB;

class ProspectionController extends Controller
{
    //Handle prospection

    public function GetAll()
    {
        $get = DB::table('prospections')
        ->join('services', 'prospections.service_propose', '=', 'services.id') 
        ->join('entreprises', 'prospections.id_entreprise', '=', 'entreprises.id')
        ->join('interlocuteurs', 'prospections.interlocuteur', '=', 'interlocuteurs.id')
        ->join('utilisateurs', 'prospections.id_utilisateur', '=', 'utilisateurs.id')
        ->get(['prospections.*', 'entreprises.nom_entreprise', 'interlocuteurs.titre', 
        'interlocuteurs.nom', 'interlocuteurs.tel', 'interlocuteurs.fonction', 'services.libele_service', 'utilisateurs.nom_prenoms']);

        return $get;

    }

    public function AddProspection(Request $request)
    {
        $calculator = new Calculator();
        
        //Calcul de la date de fin de contrat
        $date_fin = $calculator->FinProspection($request->duree, $request->date_prospect);

        
        //VOIR SI L'INTERLOCUTERU EXISTE OU PAS EN VUE DE LE CREER
        if($request->entreprise == "autre")//pas entreprise
        {
           
            $add_client  = (new EntrepriseController())->AddEntreprise($request);

            foreach($add_client as $client)
            {
                if($request->interlocuteur == "autre")//L'entreprise n'existe pas 
                {
                
                    $add = (new InterlocuteurController())->AddInterlocuteurWithClient($request, $client->id);
        
                    foreach($add as $interlocuteur)
                    {
                        $Insert = Prospection::create([
                            'service_propose' => $request->service_propose, 
                            'date_prospection' => $request->date_prospect,
                             'date_fin' => $date_fin, 
                             'duree_jours' => $request->duree, 
                             'id_entreprise' => $client->id, 
                             'interlocuteur' => $interlocuteur->id,
                             'id_utilisateur' => auth()->user()->id,
                              
                       ]);
                    }
                        
                }
                else //interlocuteur pas nouveau
                {
                    $Insert = Prospection::create([
                        'service_propose' => $request->service_propose, 
                        'date_prospection' => $request->date_prospect,
                            'date_fin' => $date_fin, 
                            'duree_jours' => $request->duree, 
                            'id_entreprise' => $client->id,  
                            'interlocuteur' => $request->interlocuteur,
                            'id_utilisateur' => auth()->user()->id,
                            
                    ]);
                }
               
            }
            
        }
        else//entreprise pas nouvelle
        {
           
            if($request->interlocuteur == "autre")//L'interlocuteur n'existe pas 
            {
            
                $add = (new InterlocuteurController())->AddInterlocuteurWithClient($request, $request->entreprise);
    
                foreach($add as $interlocuteur)
                {
                    $Insert = Prospection::create([
                        'service_propose' => $request->service_propose, 
                        'date_prospection' => $request->date_prospect,
                            'date_fin' => $date_fin, 
                            'duree_jours' => $request->duree, 
                            'id_entreprise' => $request->entreprise, 
                            'interlocuteur' => $interlocuteur->id,
                            'id_utilisateur' => auth()->user()->id,
                            
                    ]);
                }
                    
            }
            else //interlocuteur pas nouveau
            {
                $Insert = Prospection::create([
                    'service_propose' => $request->service_propose, 
                    'date_prospection' => $request->date_prospect,
                        'date_fin' => $date_fin, 
                        'duree_jours' => $request->duree, 
                        'id_entreprise' => $request->entreprise,  
                        'interlocuteur' => $request->interlocuteur,
                        'id_utilisateur' => auth()->user()->id,
                        
                ]);
            }
        }


       return redirect('welcome')->with('success', 'Enregistrement effectué');
    }

    public function MyOwnProspection($id)
    {
        $get = DB::table('prospections')
          ->join('entreprises', 'prospections.id_entreprise', '=', 'entreprises.id')
          ->join('interlocuteurs', 'prospections.interlocuteur', '=', 'interlocuteurs.id')
          ->join('services', 'prospections.service_propose', '=', 'services.id')
          ->where('prospections.id_utilisateur', '=', $id)
          ->get(['prospections.*', 'entreprises.nom_entreprise', 'interlocuteurs.titre', 'interlocuteurs.nom', 'interlocuteurs.tel', 'interlocuteurs.fonction', 'services.libele_service']);

         return $get;
    }

    public function RetriveAll()
    {
        $get = DB::table('prospections')
        ->join('entreprises', 'prospections.id_entreprise', '=', 'entreprises.id')
        ->join('interlocuteurs', 'prospections.interlocuteur', '=', 'interlocuteurs.id')
        ->join('services', 'prospections.service_propose', '=', 'services.id')
        ->join('utilisateurs', 'prospections.id_utilisateur', '=', 'utilisateurs.id')
        ->get(['prospections.*', 'entreprises.nom_entreprise', 'interlocuteurs.titre', 'interlocuteurs.nom', 'interlocuteurs.fonction', 'interlocuteurs.tel', 'services.libele_service', 'utilisateurs.nom_penoms']);

        return $get;
    }

    public function EditProspForm(RequEst $request)
    {
        return view('admin/edit_prospection',
            [
                'id' => $request->id_prospection,
            ]
        );
    }

    
    public function GetById($id_prospection)
    {
        $get = DB::table('prospections')
        ->join('entreprises', 'prospections.id_entreprise', '=', 'entreprises.id')
        ->join('interlocuteurs', 'prospections.interlocuteur', '=', 'interlocuteurs.id')
        ->join('services', 'prospections.service_propose', '=', 'services.id')
        ->where('prospections.id', '=', $id_prospection)
        ->get(['prospections.*', 'entreprises.nom_entreprise', 'interlocuteurs.titre', 'interlocuteurs.nom', 'interlocuteurs.tel', 'interlocuteurs.email', 'interlocuteurs.fonction', 'services.libele_service']);

        return $get;

    }

    public function EditProspection(Request $request)
    {
        $calculator = new Calculator();
        
        //Calcul de la date de fin de contrat
        $date_fin = $calculator->FinProspection($request->duree, $request->date_prospect);

        
        //VOIR SI L'INTERLOCUTERU EXISTE OU PAS EN VUE DE LE CREER
        if($request->entreprise == "autre")//L'entrprise n'y est pas
        {
           
            $add_client  = (new EntrepriseController())->AddEntreprise($request);

            foreach($add_client as $client)
            {
                if($request->interlocuteur == "autre")//L'interloctueur n'existe pas 
                {
                
                    $add = (new InterlocuteurController())->AddInterlocuteurWithClient($request, $client->id);
        
                    foreach($add as $interlocuteur)
                    {
                       
                        $affected =  DB::table('prospections')
                        ->update([
                            'service_propose' => $request->service_propose, 
                            'date_prospection' => $request->date_prospect,
                             'date_fin' => $date_fin, 
                             'duree_jours' => $request->duree, 
                             'id_entreprise' => $client->id, 
                             'interlocuteur' => $interlocuteur->id,
                            
                              
                       ]);

                      
                    }
                        
                }
                else //interlocuteur pas nouveau
                {
                    $affected =  DB::table('prospections')
                    ->where('id', $request->id_prospection)
                        ->update([
                        'service_propose' => $request->service_propose, 
                        'date_prospection' => $request->date_prospect,
                            'date_fin' => $date_fin, 
                            'duree_jours' => $request->duree, 
                            'id_entreprise' => $client->id,  
                            
                            'id_utilisateur' => auth()->user()->id,
                            
                    ]);

                    $Interloc = DB::table('interlocuteurs')
                    ->where('id', $request->id_interlocuteur)
                       ->update([
                        'titre' => $request->titre,
                         'nom' => $request->nom, 
                         'tel' => $request->tel,
                          'email' => $request->email, 
                          'fonction' => $request->fonction, 
                          
                           
                        ]);

                }
               
            }
            
        }
        else//entreprise pas nouvelle
        {
           
           
            if($request->interlocuteur == "autre")//L'interlocuteur n'existe pas 
            {
            
                $add = (new InterlocuteurController())->AddInterlocuteurWithClient($request, $request->entreprise);
    
                foreach($add as $interlocuteur)
                {
                    $affected =  DB::table('prospections')
                    ->where('id', $request->id_prospection)
                    ->update([
                        'service_propose' => $request->service_propose, 
                        'date_prospection' => $request->date_prospect,
                            'date_fin' => $date_fin, 
                            'duree_jours' => $request->duree, 
                            'id_entreprise' => $request->entreprise, 
                            'interlocuteur' => $interlocuteur->id,
                            'id_utilisateur' => auth()->user()->id,
                            
                    ]);
                }
                    
            }
            else //interlocuteur pas nouveau
            {
                //dd($request->service_propose);
                $affected =  DB::table('prospections')
                ->where('id', $request->id_prospection)
                ->update([
                    'service_propose' => $request->service_propose, 
                    'date_prospection' => $request->date_prospect,
                    'date_fin' => $date_fin, 
                    'duree_jours' => $request->duree, 
                    'id_entreprise' => $request->entreprise,  
                    'id_utilisateur' => auth()->user()->id,
                        
                ]);

                $Interloc = DB::table('interlocuteurs')
                ->where('id', $request->id_interlocuteur)
                ->update([
                 'titre' => $request->titre,
                  'nom' => $request->nom, 
                  'tel' => $request->tel,
                   'email' => $request->email, 
                   'fonction' => $request->fonction, 
                   
                    
                 ]);
            }
        }


       return redirect('prospection')->with('success', 'Modification effectué');

    }


}
