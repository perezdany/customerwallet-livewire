@extends('layouts/auth')

@section('content')
    <p class="login-box-msg">Veuillez saisir le code</p>

    <form action="login_code" method="post">
        @csrf
         @if(session('error'))
                <p class="bg-warning">{{session('error')}}</p>
        @endif

        @if(isset($success))
                <p class="bg-success">{{$success}}</p>
        @endif

         @if(isset($error))
                <p class="bg-warning">{{$error}}</p>
        @endif

        @if(isset($id))
            <div class="form-group has-feedback">
                <input type="password" class="form-control input-lg" value="{{$id}}" name="id"  style="display:none;">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
        @endif

        @if(isset($password))
            <div class="form-group has-feedback">
                <input type="password" class="form-control input-lg" value="{{$password}}" name="password"  style="display:none;">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
        @endif

        @if(isset($login))
            <div class="form-group has-feedback">
                <input type="password" class="form-control input-lg" value="{{$login}}" name="login"  style="display:none;">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
        @endif
        
        <div class="form-group has-feedback">
            <input type="number" class="form-control input-lg" placeholder="Code" name="code" required maxlength="4"> 
            
        </div>
      
        <div class="row">
        
            <!-- /.col -->
            <div class="col-xs-4">
            <button type="submit" class="btn btn-aeneas btn-block btn-flat">Envoyer</button>
            </div>
            <!-- /.col -->
        </div>
    </form>
  
@endsection