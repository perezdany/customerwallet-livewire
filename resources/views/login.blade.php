@extends('layouts/auth')

@section('content')
    <p class="login-box-msg">Veuillez vous connecter</p>

    <form action="go_login" method="post">
    @csrf
         @if(session('error'))
                <p class="bg-warning">{{session('error')}}</p>
            @endif
      <div class="form-group has-feedback">
        <input type="email" class="form-control input-lg" placeholder="Email" name="login" required maxlength="30"> 
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control input-lg" placeholder="Mot de passe" name="password"  maxlength="12">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
       
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-aeneas btn-block btn-flat">Connexion</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
    <!--<a href="#">J'ai oublié mon mot de passe</a><br>-->
    <!--<a href="register.html" class="text-center">Register a new membership</a>-->


@endsection