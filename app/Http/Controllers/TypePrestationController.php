<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Typeprestation;

use DB;

class TypePrestationController extends Controller
{
    //Handle Tpyeprestato=ion

    public function GetAll()
    {
        $get =  Typeprestation::All();

        return $get;
    }

    public function AddTypePrestation(Request $request)
    {
        
        $Insert = Typeprestation::create([
            'libele' => $request->libele, 
       
       ]);

       return back()->with('success', 'Enregistrement effectué');
    }

    public function EditTypePrestationForm(Request $request)
    {
        
       return view('admin/type_prestation',
            [
                'id_edit' => $request->id_typeprest,
            ]
        );
    }

    public function EditTypePrestation(Request $request)
    {

        $affected = DB::table('typeprestations')
        ->where('id', $request->id_type_prestation)
        ->update([
            'libele' => $request->libele,
            
        ]);
        
        return redirect('type_prestation')->with('success', 'modification effectuée');
    }
    
    public function GetById($id)
    {
      
        $get =  Typeprestation::where('id', $id)->get();
      
        return $get;
    }
}
