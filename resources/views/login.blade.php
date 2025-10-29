@extends('layouts/auth')

@section('content')

  <div class="card card-outline card-aeneas">
    <div class="card-header text-center">
      <a href="/" class="h1"><b>Portefeuille </b>Client</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Veuillez vous connecter</p>

      <form action="go_login" method="post"> 
        @csrf
        @if(session('error'))
          <p class="bg-red">{{session('error')}}</p>
        @endif
        <div class="input-group mb-3">
           <input type="email" class="form-control input-lg" placeholder="Email" name="login" required maxlength="30"> 
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control input-lg" placeholder="Mot de passe" name="password"  maxlength="12">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
            <div class="icheck-primary">
              <!--<input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>-->
            </div>
          </div>
          <!-- /.col -->
          <div class="col-6">
            <button type="submit" class="btn btn-aeneas btn-block">Connexion</button>
          </div>
          <!-- /.col -->
        </div>
      </form>


      <!--<p class="mb-1">
        <a href="forgot-password.html">I forgot my password</a>
      </p>
      <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p>-->
    </div>
    <!-- /.card-body -->
  </div>
    
  

@endsection