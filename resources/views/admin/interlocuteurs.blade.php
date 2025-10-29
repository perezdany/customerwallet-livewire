@extends('layouts/base')

@php
   
use App\Http\Controllers\InterlocuteurController;

$interlocuteurcontroller = new InterlocuteurController();

use App\Http\Controllers\EntrepriseController;

$entreprisecontroller = new EntrepriseController();

$all =  $interlocuteurcontroller-> GetAll();

@endphp

@section('content')
     @livewire('interlocuteurs')
		
@endsection