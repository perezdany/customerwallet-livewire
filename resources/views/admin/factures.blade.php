@extends("layouts.base")

    @section("content")
        @if(isset($id_entreprise))
          
             @if($id_entreprise->count() != 0)
               
                @livewire('factures', ['id_entreprise' => $id_entreprise])
            @else
              @livewire('factures')
             @endif
            
             
        @else
            @livewire('factures')
        @endif
        
       
    @endsection
   