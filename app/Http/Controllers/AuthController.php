<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Utilisateur;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Mail;

use App\Mail\Authentification;
use DB;

class AuthController extends Controller
{
     //Handle authentications

     public function AdminLogin(Request $request)
     {
        //ON VA VERIFIER SI L'UTILISATEUR EST ACTIF
       
        $user = Utilisateur::where('login', $request->login)->count();

        
        if($user == 0)
        {
            //dd('ll') ;
            return back()->with('error', 'Email ou mot de passe incorrect');
        }
        else
        {
            $user = Utilisateur::where('login', $request->login)->get();

            foreach($user as $user)
            {
                if($user->active == 0)
                {
                    //dd($user);
                    return back()->with('error', 'Utilisateur inactif! Veuillez contacter l\'administrateur.');
                }     
                else
                {
                    
                    
                    if (Auth::guard('web')->attempt(['login' => $request->login, 'password' => $request->password, ])) 
                    {
                        //ON le deconnecte 
                        Auth::logout();

                        $request->session()->invalidate();
                    
                        $request->session()->regenerateToken();
                        //dd('ici');
                        // Authentication was successful...
                        //ON VA LUI FOURNIR UN CODE A SON MAIL POUR SE CONNECTER 
                        $code = rand(1000, 9999);

                        $affected = DB::table('utilisateurs')
                        ->where('id', $user->id)
                        ->update(['login_token' => $code]);

                        $data = ['code' => $code, 'nom' => $user->nom_prenoms, ];
        
                        Mail::to($user->login)->send(new Authentification($data));

                        return view('code_form', [
                            'success' => 'Un code vous a été envoyé à l\'adresse '.$request->login,
                            'id' => $user->id,
                            'login' => $user->login,
                            'password' => $request->password
                        ]);

    
                    }
                    else
                    {
                        return back()->with('error', 'Email ou mot de passe incorrect');
                    }
                    
                }

            }

        }
       
    
         
     }


 
    public function logoutUser(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
    
        $request->session()->regenerateToken();

        //dd(session('pseudo'));
        return  redirect()->route('login');
    }

    public function LoginCode(Request $request)
    {   
        

        $ch = strval($request->code);
        if(strlen($ch) > 4)
        {
            //rediriger pour lui dire que c'est trop long
                return view('code_form',
                [
                    'error' => 'les données saisies ne doivent pas dépasser 4 caractères',
                    'password' => $request->password
                ]
            );
        }
        

        if(Auth::guard('web')->attempt(['login_token' => $request->code, 'password' => $request->password]))
        {
             
            //ON VA VERIFIER SI C'EST SA PREMIERE FOIS DE SE CONNECTER
            $count_login = Utilisateur::where('id', $request->id)->get();

            foreach($count_login as $count_login)
            {
                 //SI C'EST SA PREMIERE FOIS LE REDIRIGER VERS LE FORMULAIRE POUR MODIFIER SON MOT DE PASSE
                if($count_login->count_login == 0)
                {
                    //ON le deconnecte 
                    Auth::logout();

                    $request->session()->invalidate();
                
                    $request->session()->regenerateToken();
             
                    return view('edit_pass_firstlog',
                        [
                            'id' =>  $request->id
                        ]
                    );
                }
            }
            

            $request->session()->regenerate();//regeneger la session
        
            return redirect()->route('home'); //si l'utilisateur était sur une ancienne page après la connexion ca le renvoi la bas dans le cas contraire sur la page d'accueil welcome
                
        }
        else
        {
            return view('code_form',
                [
                    'error' => 'Code Incorrect',
                    'password' => $request->password
                ]
            );
        }
        
    }
 
}
