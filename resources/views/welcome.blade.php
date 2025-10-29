@extends('layouts/dash')
@php
    use App\Http\Controllers\ServiceController;

    use App\Http\Controllers\ControllerController;

    use App\Http\Controllers\ContratController;

    use App\Http\Controllers\EntrepriseController;

    use App\Http\Controllers\TypePrestationController;

    use App\Http\Controllers\InterlocuteurController;

    use App\Http\Controllers\FactureController;

    use App\Http\Controllers\CategorieController;

    use App\Http\Controllers\Calculator;

    $calculator = new Calculator();

    $facturecontroller = new FactureController();

    $categoriecontroller = new CategorieController();

    $servicecontroller = new ServiceController();

    $typeprestationcontroller = new TypePrestationController();

    $contratcontroller = new ContratController();

    $entreprisecontroller = new EntrepriseController();

    $interlocuteurcontroller = new InterlocuteurController();

    $my_own =  $facturecontroller->FactureDateDepassee();
    $count_non_reglee = $calculator->CountFactureNonRegleDepasse();

    use App\Models\Contrat;
    use App\Models\Facture;
    use App\Models\Paiement;
    use App\Models\Prestation;
    use App\Models\Entreprise;
     
@endphp

@section('content')
    @php
        /*CODE POUR METTRE A JOUR LES CONTRATS RECONDUITS*/
        $contrats = $contratcontroller->RetriveAll();
        foreach($contrats as $contrats)
        {
            //VERIFIER LA DATE DE FIN DE CONTRAT
            if($contrats->fin_contrat <= date('Y-m-d')) //SON ETAT EST MIS SUR TERMINE OU BIEN SA DATE DE FIN EST DEPASSEE
            {
                //LE CONTRAT EST FINI, VERIFIONS SI IL EST RECONDUIT
                if($contrats->reconduction == 0)//Pas reconduit
                {
                   
                }
                else
                {
                    if($contrats->reconduction == 1 AND $contrats->etat == 1)//Reconduit et en cours
                    {
                        //ON A LES DUREES DANS LA TABLE DONC ON SE SERT DE CA POUR LA MISE A JOURS
                        //DES QUE LE CONTRAT EST RECONDUIT IL EST EN COURS
                        //echo $contrats->titre_contrat."--".$contrats->fin_contrat."<br>";
                        $date_debut = strtotime($contrats->debut_contrat);
                        $date_fin = strtotime($contrats->fin_contrat);
                        //$diff_in_days = floor(($date_fin - $date_debut ) / (60 * 60 * 24));//on obtient ca en jour
                        $nouvelle_date_fin = $calculator->FinContrat(intval($contrats->jours), $contrats->fin_contrat, intval($contrats->mois), intval($contrats->annees));
                        //ACTUALISATION DE LA DATE
                        /*$timestamp = strtotime($contrats->fin_contrat);
                        $departtime1 = strtotime('+'.$diff_in_days.' days', $timestamp);
        
                        $nouvelle_date_fin = date("Y-m-d", $departtime1);*/

                        //MISE A JOUR DE L'ENREGISTREMENT DANS LA TABLE
                        $affected = DB::table('contrats')
                        ->where('id', $contrats->id)
                        ->update([
                            'fin_contrat'=> $nouvelle_date_fin,  
                            'etat' => 1
                        ]);
                    }
                   
                }
                
            }
        }

        //CODE POUR METTRE LES DATES DES CLIENTS DEPUIS DANS LA TABLE ENTREPRISE
        $entreprises = Entreprise::all();
        foreach($entreprises as $entreprise)
        {
            //ON VA VOIR LES CONTRATS MAINTENANT 
            if($entreprise->client_depuis == null)
            {
                $count_contrats = Contrat::where('id_entreprise', $entreprise->id)->orderBy('debut_contrat', 'ASC')->limit(1)->count();
                //dd($contrats);
                if($count_contrats == 0)
                {
                    //ON A PAS TROUVE DE CONTRAT
                    //dd('ici');
                    /*$edit =  DB::table('entreprises')
                    ->where('id',  $entreprise->id)
                    ->update([
                        'client_depuis' => "2014-01-01",
                    ]);*/
                }
                else
                {
                    $contrats = Contrat::where('id_entreprise', $entreprise->id)->orderBy('debut_contrat', 'ASC')->limit(1)->get();
                    foreach($contrats as $contrat)
                    {
                        //METTRE A JOUR LE CHAMP CLIENT DEPUIS DE LA TABLE ENTREPRISE

                        $edit =  DB::table('entreprises')
                        ->where('id',  $entreprise->id)
                        ->update([
                            'client_depuis' => $contrat->debut_contrat,
                        ]);

                    }
                 
                }
                
            }
        }
    @endphp

    @php
        //CODE POUR INSERER LES DATES DES TABLES PRESTATIONS CES CODES SONT A SUPPRIMER APRES
        /*$contrats = Contrat::all();
        foreach($contrats as $contrats)
        {
            $prestation = DB::table('prestations')
            ->join('contrats', 'prestations.id_contrat', '=', 'contrats.id')
            ->where('prestations.id_contrat', $contrats->id)->get();

            foreach($prestation as $prestation)
            {
                $edit = DB::table('prestations')
                ->where('id_contrat', $contrats->id)
                ->update([ 'date_prestation' => $contrats->debut_contrat,]);

            }
        }
        */
    @endphp

    @php
        //CODE POUR AJOUTER LES PAIEMENTS CE CODE SERA SUPPRIME APRES LA PRESENTATION
       /* $factures = Facture::where('reglee', 1)->get();
        //dd($factures);
        foreach($factures as $factures)
        {
          //CREER DES PAIEMENT POUR CETTE FACTURE UN PAIMENT PLUTOT
                $Insert = Paiement::create([
                'paiement' => $factures->montant_facture,
             'id_facture' => $factures->id,
              'date_paiement' => $factures->date_reglement, 
              'updated_at' => date('Y-m-d H:i:s'), 
              'created_by' => date('Y-m-d H:i:s'),
              'created_by' => 1,]);
        }*/
        //CODE POUR TRANSFERER LES ID TYPE PRESTATION DE LA PRESTATION VERS LA TABLE CONTRAT

        /*$contrat = Contrat::all();
      
        foreach($contrat as $contrat)
        {
            $p = Prestation::where('id_contrat', $contrat->id)->get();
           //echo($contrat->id)."ddd/<br>";
           //dd($p);
            foreach($p as $p)
            {
                $affected = DB::table('contrats')
                ->where('id', $contrat->id)
                ->update(['id_type_prestation' => $p->id_type_prestation]);

               
                
            }
            //dd($affected);
                
        }*/
            

    @endphp
       
    @include("layouts/components/alerts")
   
    <!-- Main row -->  

@endsection
     
    
   