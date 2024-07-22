<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Utilisateur;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

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
                                // Authentication was successful...
                                
                            $request->session()->regenerate();//regeneger la session
        
                            return redirect()->route('home'); //si l'utilisateur était sur une ancienne page après la connexion ca le renvoi la bas dans le cas contraire sur la page d'accueil welcome
        
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
 
}
