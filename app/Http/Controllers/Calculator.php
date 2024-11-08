<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Entreprise;
use App\Models\Prospection;
use App\Models\Prestation;
use App\Models\Contrat;
use App\Models\Paiement;
use App\Models\Service;
use App\Models\Facture;

use DB;

class Calculator extends Controller
{
    //Handle Calculation

    public function FinContrat($jours, $date_debut, $mois, $annee)
    {   //dd('ici');
        $timestamp = strtotime($date_debut);
        //dd($timestamp);
        if($annee !=0 )//il a rempli l'annee
        {
          
            if($mois != 0)//le mois aussi est rempli
            {
               
                $departtime1 = strtotime('+'.$mois.' month', $timestamp);
                $departtime2 = strtotime('+'.$annee.' year', $departtime1);
                $depart = date("Y-m-d", $departtime2);
            }

            if($jours != 0)//le jour aussi est rempli
            {
              
                $departtime1 = strtotime('+'.$jours.' days', $timestamp);
                $departtime2 = strtotime('+'.$annee.' year', $departtime1);

                $depart = date("Y-m-d", $departtime2);
            }
         
            $depart_base = strtotime('+'.$annee.' year', $timestamp);

            $depart = date("Y-m-d", $depart_base);
            

            return $depart;
        }

    
        if($jours == 0)//il a rempli uniquement le mois
        {
           
            if($mois != 0) //si le mois est rempli donc et différent de zéro
            {
             
                //strtotime(‘+’.$duree.’ month’, $dateDepartTimestamp )
                $departtime = strtotime('+'.$mois.' month', $timestamp);
                $depart = date("Y-m-d", $departtime);

                return $depart;
            }
            else
            {
                return redirect('reservation_form')->with('error', 'Vous devez mettre au moins un nombre de jours.');
            }
           
            //return $depart;
        }
        else // le jour est rempli et différent de zéro
        {
            if($mois != 0)// le jours est différent de 0 et le mois aussi
            {
              
                if($jours == 30 OR $jours == 31) // c'est copmme ci ca fait un mois 
                {
                    
                    $departtime =strtotime('+1 month', $timestamp);
                    $add_month = strtotime('+'.$mois.' month', $departtime);
                    
                    $depart = date("Y-m-d", $add_month);
                    return $depart;
                    //echo $depart;
                }
                else 
                {
                   
                    $departtime = strtotime('+'.$mois.' month', $timestamp);
                    $the_final = strtotime('+'.$jours.' days', $departtime);
                    $depart = date("Y-m-d", $the_final);
                    return $depart;
                }
            }
            else //le mois est 0 c'est le jours seul qui est différent de zéro
            {
                
                $departtime = $timestamp + ($jours * 86400);
                $depart = date("Y-m-d", $departtime); 
           
                return $depart;   
            }


           
        }

        

    }

    public function FinProspection($jours, $date_debut)
    {
        $timestamp = strtotime($date_debut);

        if($jours != 0)//il a rempli le jours
        {
            //strtotime(‘+’.$duree.’ month’, $dateDepartTimestamp )
            $departtime = strtotime('+'.$jours.' days', $timestamp);
            $depart = date("Y-m-d", $departtime);

            return $depart;
        }
    }

    //NOMBRE TOTAL DES CLIENTS
    public function CountCustomer()
    {
        $count = Entreprise::where('id_statutentreprise', '!=', 1)
        ->count();
         return  $count;
    }

     //NOMBRE TOTAL DES PROSPECT
     public function CountProspect()
     {
         $count = Entreprise::where('id_statutentreprise', '=', 1)
         ->count();
          return  $count;
     }


     //COMPTER LES CONTRATS EN COURS,
     public function CountContratEncours()
     {
        $today =  date('Y-m-d');

        $count = Contrat::where('fin_contrat', '>', $today)->count();

        return $count;
     }

     public function CountContrat()
     {
       
        $count = Contrat::count();

        return $count;
     }

     public function CountPrestation()
     {
        $count = Prestation::count();

        return $count;
     }

     public function CountProspection()
     {
        $count = Prospection::count();

        return $count;
     }


    public function CountNewCustomer()
    {
        $today = strtotime(date('Y-m-d'));
    
        $count = 0;
        $get = Entreprise::where('id_statutentreprise', '=', 2)->get();
        foreach($get as $get)
        {
            $date_start = strtotime($get->client_depuis);
            $diff_in_days = floor(($today - $date_start) / (60 * 60 * 24));
             //SI C'EST INFERIEUR OU EGAL A 7, ON PEUT DIRE C'EST UN NOUVEAU CLIENT 
            if($diff_in_days <= 7)
            {
                $count =  $count + 1;
            }
        }

        return $count;

    }

    public function SommePaiementContrat($id_contrat)
    {
        $somm = 0;
        $get =  DB::table('paiements')
        ->join('factures', 'paiements.id_facture', '=', 'factures.id')
        ->join('prestations', 'factures.id_prestation', '=', 'prestations.id')
        ->where('prestations.id_contrat', $id_contrat)
        ->get(['paiements.paiement']);

        foreach($get as $get)
        {
            $somm = $somm + $get->paiement;

        }
        
        return $somm;
    }

    //POUR LE GRAPHE MENSUEL
    public function MonthlyChart()
    {
        //FAIRE UNE BOUCLE POUR TOUS LES MOIS DE L'ANNEE
        
        //LE TABLEAU QUI VA RECCUEILLIR LES DONNES 
        $data = [];

        //LE TABLEAU QUI VA RECCUEILLIR LES ENTREPRISES
        $company = [];

        //LE TABLEAU POUR LES POURCENTAGE DE CHAQUE ENTREPRISE CE MOIS CI
        $percent = [];

        //l'année en cours
        $year = date('Y');

        //le mois en cours
        $month = date('m');

        //Le montant total des contrats réalisé
        $total = 0;

       
        //nombre de jours dans le mois
        $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
       
        //LA BOUCLE DES JOURS DU MOIS
        for($i = 1; $i <= $number; $i++)
        {
            $somme = 0;   
            //$first_date = $year."-".$i."01";
            $the_date = $year."-". $month."-".$i;
            //echo $the_date."<br>";
            //LA REQUETE MAINTENANT
            $get = DB::table('contrats')
                ->join('entreprises', 'entreprises.id', '=', 'contrats.id_entreprise')
                ->where('contrats.debut_contrat', '=', $the_date)
                ->select(['contrats.*',])
                ->get();

          
            //FAIRE UN FOREACH POUR FAIRE LA SOMME
            foreach($get as $all)
            {
               //echo $all->montant."<br";
                $somme = $somme + $all->montant;
            }
           
            $total = $total + $somme;

           
            //METTRE DANS LE TABLEAU data
            array_push($data, $somme);
           //dd($data[0]);
            //echo $data[$i]."<br>";
           
            //var_dump($data);
        }          
        //echo  $total;

        //CALCUL POUR LES POURCENTAGES 

        //Prendre toutes les entreprises et pour chaque entrprise recupérer le montant des contrats dans le mois en questions
        $all_entreprises = Entreprise::all();

        foreach($all_entreprises as $all_entreprises)
        {
           //montant de contrat pour chaque entreprise
           $montant = 0;
           for($i = 1; $i <= $number; $i++)
           {
                $the_date = $year."-". $month."-".$i;
                
                $contrats =  DB::table('contrats')
                ->join('entreprises', 'entreprises.id', '=', 'contrats.id_entreprise')
                ->where('contrats.debut_contrat', '=', $the_date)
                ->where('contrats.id_entreprise', '=', $all_entreprises->id)
                ->get();

                foreach($contrats as $contrats)
                {
                    $montant = $montant + $contrats->montant;
                }
        
           }  

           //METTRE LES VALLEURS DANS LES DIFFERENTS TABLEAUX
           if($montant != 0) //Si cette entreprise a rapporter quelque il faut remplir dans le tableu pour le gaph
           {
                array_push($company, $all_entreprises->nom_entreprise);
                 //CALCULER LE POURCENTAGE ET METTRE DANS LE TABLEAU
                $p = ($montant * 100) / $total;
                //echo $total;
                array_push($percent, $p);

           }
              
           
        }

        //AFFICHER LES GRAPHES PAR PRESTATIONS, PAR SERVICES

        //Prendre touts les services et pour chaque service recupérer le total des contrats dans le mois en questions
       
        //TABKEAU QUI VA RECUPERER LES SERVICES
        $serv = [];
        //TABLEAU QUI VA RECUPER LE TOTAL DES PRESTATIONS
        $data_serv = [];

        //Compter toutes les prestations du mois

        $first_date = $year."-".$month."-01";
        $last_date = $year."-".$month."-".$number;

        $compte_prestations =  DB::table('prestation_service')
        ->join('prestations', 'prestation_service.prestation_id', '=', 'prestations.id')
        ->join('services', 'prestation_service.service_id', '=', 'services.id') 
        ->where('prestations.date_prestation', '>=', $first_date)
        ->where('prestations.date_prestation', '<=', $last_date)
        ->count();
         
        //PARCOURIR TOUS LES SERVICES
        $all_services = Service::all();

        foreach($all_services as $all_services)
        {
            //pour récupérer le nombre total de la prestation spécifique ce mois ci
            $compte_prestations_service  = 0;
           
           $compte_prestations_service = DB::table('prestation_service')
           ->join('prestations', 'prestation_service.prestation_id', '=', 'prestations.id')
           ->join('services', 'prestation_service.service_id', '=', 'services.id') 
           ->where('prestations.date_prestation', '>=', $first_date)
           ->where('prestations.date_prestation', '<=', $last_date)
           ->where('prestation_service.service_id', '=', $all_services->id)
           ->count();

           //echo $compte_prestations_service."<br>";
           //Si on a trouvé au moins une occurence de la prestation, on peut mettre dans notre tableau pour partir
          if($compte_prestations_service != 0)
            {
                

                array_push($serv, $all_services->libele_service);

            }
                   
           array_push($data_serv, $compte_prestations_service);
           
        }
        

        return view('graph/monthly', compact('data', 'company', 'percent', 'data_serv', 'serv'));
    }
    
    public function SearchMonth(Request $request)
    {
        //dd($request->month);
        //FAIRE UNE BOUCLE POUR TOUS LES MOIS DE L'ANNEE
        
        //LE TABLEAU QUI VA RECCUEILLIR LES DONNES 
        $data = [];

        //LE TABLEAU QUI VA RECCUEILLIR LES ENTREPRISES
        $company = [];

        //LE TABLEAU POUR LES POURCENTAGE DE CHAQUE ENTREPRISE CE MOIS CI
        $percent = [];

        //l'année en cours
        $year = date('Y');

        //le mois en cours
        $month_get = date_parse($request->month);
        //$month_get;
        $month = $month_get['month'];
        //dd($month);
        //FAIRE UN TABLEAU POUR LE MOIS EN FRANCAIS
        $mois_francais = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 
        'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

        $francais = $mois_francais[($month-1)];

        //Le montant total des contrats réalisé
        $total = 0;

        //nombre de jours dans le mois
        $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
       
        //LA BOUCLE DES JOURS DU MOIS
        for($i = 0; $i < $number; $i++)
        {
            $somme = 0;   
            //$first_date = $year."-".$i."01";
            $the_date = $request->month."-".$i;
            
            //LA REQUETE MAINTENANT
            $get = DB::table('contrats')
                ->join('entreprises', 'entreprises.id', '=', 'contrats.id_entreprise')
                ->where('contrats.debut_contrat', '=', $the_date)
                ->select(['contrats.*',])
                ->get();

          
            //FAIRE UN FOREACH POUR FAIRE LA SOMME
            foreach($get as $all)
            {
               //echo $all->montant."<br";
                $somme = $somme + $all->montant;
            }
           
            $total = $total + $somme;

           
            //METTRE DANS LE TABLEAU data
            array_push($data, $somme);
          
        }          
        //echo  $total;

        //CALCUL POUR LES POURCENTAGES 

        //Prendre toutes les entreprises et pour chaque entrprise recupérer le montant des contrats dans le mois en questions
        $all_entreprises = Entreprise::all();

        foreach($all_entreprises as $all_entreprises)
        {
           //montant de contrat pour chaque entreprise
           $montant = 0;
           for($i = 0; $i < $number; $i++)
           {
                $the_date = $year."-". $month."-".$i;
                
                $contrats =  DB::table('contrats')
                ->join('entreprises', 'entreprises.id', '=', 'contrats.id_entreprise')
                ->where('contrats.debut_contrat', '=', $the_date)
                ->where('contrats.id_entreprise', '=', $all_entreprises->id)
                ->get();

                foreach($contrats as $contrats)
                {
                    $montant = $montant + $contrats->montant;
                }
        
           }  

           //METTRE LES VALLEURS DANS LES DIFFERENTS TABLEAUX
           if($montant != 0) //Si cette entreprise a rapporter quelque il faut remplir dans le tableu pour le gaph
           {
                array_push($company, $all_entreprises->nom_entreprise);
                 //CALCULER LE POURCENTAGE ET METTRE DANS LE TABLEAU
                $p = ($montant * 100) / $total;
                //echo $total;
                array_push($percent, $p);

           }
        }

        //AFFICHER LES GRAPHES PAR PRESTATIONS, PAR SERVICES

        //Prendre touts les services et pour chaque service recupérer le total des contrats dans le mois en questions
       
        //TABKEAU QUI VA RECUPERER LES SERVICES
        $serv = [];
        //TABLEAU QUI VA RECUPER LE TOTAL DES PRESTATIONS
        $data_serv = [];

        //Compter toutes les prestations du mois

        $first_date = $request->month."-01";
        $last_date = $request->month."-".$number;

        $compte_prestations =   DB::table('prestation_service')
        ->join('prestations', 'prestation_service.prestation_id', '=', 'prestations.id')
        ->join('services', 'prestation_service.service_id', '=', 'services.id') 
        ->where('prestations.date_prestation', '>=', $first_date)
        ->where('prestations.date_prestation', '<=', $last_date)
        ->count();
         
        //PARCOURIR TOUS LES SERVICES
        $all_services = Service::all();

        foreach($all_services as $all_services)
        {
            //pour récupérer le nombre total de la prestation spécifique ce mois ci
            $compte_prestations_service  = 0;
           
           $compte_prestations_service =   DB::table('prestation_service')
           ->join('prestations', 'prestation_service.prestation_id', '=', 'prestations.id')
           ->join('services', 'prestation_service.service_id', '=', 'services.id') 
           ->where('prestations.date_prestation', '>=', $first_date)
           ->where('prestations.date_prestation', '<=', $last_date)
           ->where('prestation_service.service_id', '=', $all_services->id)
           ->count();

           //echo $compte_prestations_service."<br>";
           //Si on a trouvé au moins une occurence de la prestation, on peut mettre dans notre tableau pour partir
          if($compte_prestations_service != 0)
            {
                
                array_push($serv, $all_services->libele_service);

            }
                   
           array_push($data_serv, $compte_prestations_service);
           
        }
        

        return view('graph/search_monthly', compact('data', 'company', 'percent', 'francais', 'serv', 'data_serv'));
    }


    public function YearlyChart()
    {
        //FAIRE UNE BOUCLE POUR TOUS LES MOIS DE L'ANNEE

        //LE TABLEAU QUI VA RECCUEILLIR LES ENTREPRISES
        $company = [];

        //LE TABLEAU QUI VA RECCUEILLIR LES DONNES 
        $data = [];

        //TABLEAU DES POURCENTAGE POUR L'ENTREPRISE
        $percent = [];

        //chiffre d'affaire annuel en cours
        $total_chiffre_annuel = 0;

        //LE TABLEAUD DES MOIS
        $mois_francais = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 
        'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

        //l'année en cours
        $year = date('Y');

        //LA BOUCLE DES 12 MOIS
        for($i = 1; $i <= 12; $i++)
        {
            //Montant total pour chaque mois
            $total = 0;
            //Total des montants dans un mois
            $somme = 0;
            //nombre de jours dans le mois
            $number = cal_days_in_month(CAL_GREGORIAN, $i, $year);

            //$last_date = $year."-".$i."-".$number;
            
            for($j = 1; $j<$number; $j++)
            {
                

                $the_date = $year."-".$i."-".$j;
                //LA REQUETE MAINTENANT
                $get = DB::table('contrats')
                ->join('entreprises', 'entreprises.id', '=', 'contrats.id_entreprise')
                ->where('contrats.debut_contrat', '=', $the_date)
                ->select(['contrats.*',])
                ->get();
 
                //FAIRE UN FOREACH POUR FAIRE LA SOMME
                foreach($get as $all)
                {
                //echo $all->montant."<br";
                    $somme = $somme + $all->montant;
                }
               

            }
            $total = $total + $somme;
            //METTRE DANS LE TABLEAU data
            array_push($data, $total);

            
        } 

        //PAR CLIENT 
        //REQUETE POUR RECUPERER LA RECETTE ANNUELLE
        $first_date = date('Y')."-01-01";
        $last_date = date('Y')."-12-31";
        //dd($last_date);
        $total_annuel = 0;
        $annuelle = DB::table('contrats')
        ->where('debut_contrat', '>=', $first_date )
        ->where('debut_contrat', '<=', $last_date)
        ->get();
        //dd($annuelle);
        foreach($annuelle as $annuelle)
        {
            $total_annuel = $total_annuel + $annuelle->montant;
        }
        //dd($total_annuel);
        //Prendre toutes les entreprises et pour chaque entrprise recupérer le montant des contrats dans le mois en questions
        $all_entreprises = Entreprise::all();

        foreach($all_entreprises as $all_entreprises)
        {    //dd($all_entreprises->id);
            
            //Total des montants pour l'entreprise
            $montant = 0;
            //LA BOUCLE DES 12 MOIS
            for($i = 1; $i <= 12; $i++)
            {
                
                //nombre de jours dans le mois
                $number = cal_days_in_month(CAL_GREGORIAN, $i, $year);

                //$last_date = $year."-".$i."-".$number;
                
                for($j = 1; $j<$number; $j++)
                {
                
                    $the_date = $year."-".$i."-".$j;
                    //LA REQUETE MAINTENANT
                    $contrats =  DB::table('contrats')
                    ->join('entreprises', 'entreprises.id', '=', 'contrats.id_entreprise')
                    ->where('contrats.debut_contrat', '=', $the_date)
                    ->where('contrats.id_entreprise', '=', $all_entreprises->id)
                    ->get();
                   
                    foreach($contrats as $contrats)
                    {
                        $montant = $montant + $contrats->montant;
                    }

                }
                
              
               
            } 
            //echo $total."<br>";

           
            //echo $total."le montant de".$all_entreprises->nom_entreprise." <br>";
            //METTRE LES VALLEURS DANS LES DIFFERENTS TABLEAUX
           
            if($montant != 0) //Si cette entreprise a rapporter quelque il faut remplir dans le tableu pour le gaph
            {
                    array_push($company, $all_entreprises->nom_entreprise);
                    //CALCULER LE POURCENTAGE ET METTRE DANS LE TABLEAU
                    $p = ($montant * 100) / $total_annuel;
                   
                    array_push($percent, $p);

            }

           
           
        }

       //AFFICHER LES GRAPHES PAR PRESTATIONS, PAR SERVICES

        //Prendre touts les services et pour chaque service recupérer le total des contrats dans l'année en questions
       
        //TABKEAU QUI VA RECUPERER LES SERVICES
        $serv = [];
        //TABLEAU QUI VA RECUPER LE TOTAL DES PRESTATIONS
        $data_serv = [];

        //Compter toutes les prestations de l'année

        $first_date = $year."-01-01";
        $last_date = $year."-12-31";
        /*('prestation_service')
        ->join('prestations', 'prestation_service.prestation_id', '=', 'prestations.id')
        ->join('services', 'prestation_service.service_id', '=', 'services.id') 
        ->where('prestation_id', $all->id)    
        ->get(['services.libele_service', 'prestation_service.*']);*/

        $compte_prestations =  DB::table('prestations')
        ->where('prestations.date_prestation', '>=', $first_date)
        ->where('prestations.date_prestation', '<=', $last_date)
        ->count();
         
        //PARCOURIR TOUS LES SERVICES
        $all_services = Service::all();

        foreach($all_services as $all_services)
        {
            //pour récupérer le nombre total de la prestation spécifique ce mois ci
            $compte_prestations_service  = 0;
           
           $compte_prestations_service =  DB::table('prestation_service')
           ->join('prestations', 'prestation_service.prestation_id', '=', 'prestations.id')
           ->join('services', 'prestation_service.service_id', '=', 'services.id') 
           ->where('prestations.date_prestation', '>=', $first_date)
           ->where('prestations.date_prestation', '<=', $last_date)
           
           ->where('prestation_service.service_id', '=', $all_services->id)
           ->count();

           //echo $compte_prestations_service."<br>";
           //Si on a trouvé au moins une occurence de la prestation, on peut mettre dans notre tableau pour partir
            if($compte_prestations_service != 0)
            {
                array_push($serv, $all_services->libele_service);
            }
                   
           array_push($data_serv, $compte_prestations_service);
           
        }
        
        return view('graph/yearly', compact('data', 'mois_francais', 'percent', 'company', 'serv', 'data_serv'));
    }

    public function SearchYear(Request $request)
    {
        
        //FAIRE UNE BOUCLE POUR TOUS LES MOIS DE L'ANNEE

        //LE TABLEAU QUI VA RECCUEILLIR LES ENTREPRISES
        $company = [];

        //LE TABLEAU QUI VA RECCUEILLIR LES DONNES 
        $data = [];

        //TABLEAU DES POURCENTAGE POUR L'ENTREPRISE
        $percent = [];

        //chiffre d'affaire annuel en cours
        $total_chiffre_annuel = 0;

        //LE TABLEAUD DES MOIS
        $mois_francais = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 
        'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

        //l'année en cours
        $year_get = date_parse($request->year);

        $year = $year_get['year'];
        

        //LA BOUCLE DES 12 MOIS
        for($i = 1; $i <= 12; $i++)
        {
            //Montant total pour chaque mois
            $total = 0;
            //Total des montants dans un mois
            $somme = 0;
            //nombre de jours dans le mois
            $number = cal_days_in_month(CAL_GREGORIAN, $i, $year);

            //$last_date = $year."-".$i."-".$number;
            
            for($j = 1; $j<=$number; $j++)
            {
                

                $the_date = $year."-".$i."-".$j;
                //LA REQUETE MAINTENANT
                $get = DB::table('contrats')
                ->join('entreprises', 'entreprises.id', '=', 'contrats.id_entreprise')
                ->where('contrats.debut_contrat', '=', $the_date)
                ->select(['contrats.*',])
                ->get();
 
                //FAIRE UN FOREACH POUR FAIRE LA SOMME
                foreach($get as $all)
                {
                    //echo $all->montant."<br";
                    $somme = $somme + $all->montant;
                }
               

            }
            $total = $total + $somme;
            //METTRE DANS LE TABLEAU data
            array_push($data, $total);

            
        } 

        //PAR CLIENT 
        //REQUETE POUR RECUPERER LA RECETTE ANNUELLE
        $first_date = $year."-01-01";
        $last_date = $year."-12-31";
        //dd($last_date);
        //dd($last_date);
        $total_annuel = 0;
        $annuelle = DB::table('contrats')
        ->where('debut_contrat', '>=', $first_date )
        ->where('debut_contrat', '<=', $last_date)
        ->get();
        //dd($annuelle);
        foreach($annuelle as $annuelle)
        {
            $total_annuel = $total_annuel + $annuelle->montant;
        }
        //dd($total_annuel);
        //Prendre toutes les entreprises et pour chaque entrprise recupérer le montant des contrats dans le mois en questions
        $all_entreprises = Entreprise::all();

        foreach($all_entreprises as $all_entreprises)
        {    //dd($all_entreprises->id);
            
            //Total des montants pour l'entreprise
            $montant = 0;
            //LA BOUCLE DES 12 MOIS
            for($i = 1; $i <= 12; $i++)
            {
                
                //nombre de jours dans le mois
                $number = cal_days_in_month(CAL_GREGORIAN, $i, $year);

                //$last_date = $year."-".$i."-".$number;
                
                for($j = 1; $j<$number; $j++)
                {
                
                    $the_date = $year."-".$i."-".$j;
                    //LA REQUETE MAINTENANT
                    $contrats =  DB::table('contrats')
                    ->join('entreprises', 'entreprises.id', '=', 'contrats.id_entreprise')
                    ->where('contrats.debut_contrat', '=', $the_date)
                    ->where('contrats.id_entreprise', '=', $all_entreprises->id)
                    ->get();
                   
                    foreach($contrats as $contrats)
                    {
                        $montant = $montant + $contrats->montant;
                    }

                }
                
              
               
            } 
            
            //METTRE LES VALLEURS DANS LES DIFFERENTS TABLEAUX
           
            if($montant != 0) //Si cette entreprise a rapporter quelque il faut remplir dans le tableu pour le gaph
            {
                    array_push($company, $all_entreprises->nom_entreprise);
                    //CALCULER LE POURCENTAGE ET METTRE DANS LE TABLEAU
                    $p = ($montant * 100) / $total_annuel;
                   
                    array_push($percent, $p);

            }

           
           
        }

        //AFFICHER LES GRAPHES PAR PRESTATIONS, PAR SERVICES

        //Prendre touts les services et pour chaque service recupérer le total des contrats dans l'année en questions
       
        //TABKEAU QUI VA RECUPERER LES SERVICES
        $serv = [];
        //TABLEAU QUI VA RECUPER LE TOTAL DES PRESTATIONS
        $data_serv = [];

        //Compter toutes les prestations de l'année

        $first_date = $year."-01-01";
        $last_date = $year."-12-31";

        $compte_prestations =  DB::table('prestation_service')
        ->join('prestations', 'prestation_service.prestation_id', '=', 'prestations.id')
        ->join('services', 'prestation_service.service_id', '=', 'services.id') 
        ->where('prestations.date_prestation', '>=', $first_date)
        ->where('prestations.date_prestation', '<=', $last_date)
        ->count();
         
        //PARCOURIR TOUS LES SERVICES
        $all_services = Service::all();

        foreach($all_services as $all_services)
        {
            //pour récupérer le nombre total de la prestation spécifique ce mois ci
            $compte_prestations_service  = 0;
           
           $compte_prestations_service =   DB::table('prestation_service')
           ->join('prestations', 'prestation_service.prestation_id', '=', 'prestations.id')
           ->join('services', 'prestation_service.service_id', '=', 'services.id') 
           ->where('prestations.date_prestation', '>=', $first_date)
           ->where('prestations.date_prestation', '<=', $last_date)
           ->where('prestation_service.service_id', '=', $all_services->id)
           ->count();

           //echo $compte_prestations_service."<br>";
           //Si on a trouvé au moins une occurence de la prestation, on peut mettre dans notre tableau pour partir
          if($compte_prestations_service != 0)
            {
                array_push($serv, $all_services->libele_service);
            }
                   
           array_push($data_serv, $compte_prestations_service);
           
        }
      
       
       return view('graph/search_yearly', compact('data', 'mois_francais', 'percent', 'company', 'data_serv', 'serv', 'year'));
    }

    public function VerifyIfFactureRegle($id_facture, $montant)
    {
        //somme des paiement
        $somme_paiement = 0;
        //Récuperer tout les paiement de la facture ['paiements.paiement']

        $all_paiements = DB::table('paiements')
        ->join('factures', 'paiements.id_facture', '=', 'factures.id')
        ->where('paiements.id_facture', $id_facture)
        ->get();

        foreach($all_paiements as $all_paiements)
        {
            $somme_paiement =  $somme_paiement + $all_paiements->paiement;
        }
       
        //SOUSTRACTION
        $rest = $montant - $somme_paiement;
        
        return $rest;
    }

    public function RetrunMontantRest($id_facture, $montant)
    {
        //somme des paiement
        $somme_paiement = 0;
        //Récuperer tout les paiement de la facture ['paiements.paiement']
        //dd($id_facture);
        $all_paiements = DB::table('paiements')
        ->join('factures', 'paiements.id_facture', '=', 'factures.id')
        ->where('paiements.id_facture', $id_facture)
        ->get();

        foreach($all_paiements as $all_paiements)
        {
            $somme_paiement =  $somme_paiement + $all_paiements->paiement;
        }
       
        //SOUSTRACTION
        $rest = $montant - $somme_paiement;
        
        return $rest;
    }

    public function CountFactureNonRegleDepasse()
    {
        $today = date('Y-m-d');
        
        $count = Facture::where('date_reglement', '<', $today)
        ->where('reglee', 0)
        ->count();

        return $count;
    }


}
