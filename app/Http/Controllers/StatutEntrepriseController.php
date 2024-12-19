<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Statutentreprise;

class StatutEntrepriseController extends Controller
{
    //handle statut entreprise

    public function GetAll()
    {
        $get = Statutentreprise::all();

        return $get;
    }

    public function GetById($id)
    {
        $get = Statutentreprise::where('id', $id)->get();

        return $get;
    }
}
