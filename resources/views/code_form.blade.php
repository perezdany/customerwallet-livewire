@extends('layouts/auth')

@section('content')
    <div class="card">
        <div class="card-body">
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

            <div class="input-group mb-3">
                <input type="password" class="form-control input-lg" value="{{$id}}" name="id"  style="display:none;">
                <div class="input-group-append" style="display:none;">
                    <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($password))

            <div class="input-group mb-3">
                <input type="password" class="form-control input-lg" value="{{$password}}" name="password"  style="display:none;">
                <div class="input-group-append" style="display:none;">
                    <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($login))
            <div class="input-group mb-3">
               <input type="password" class="form-control input-lg" value="{{$login}}" name="login"  style="display:none;">
                <div class="input-group-append" style="display:none;">
                    <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>
        @endif

        <div class="input-group mb-3">
           <input type="number" class="form-control input-lg" placeholder="Code" name="code" required maxlength="4"> 
          <div class="input-group-append" style="display:none;">
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
                <button type="submit" class="btn btn-aeneas btn-block">Envoyer</button>
            </div>
            <!-- /.col -->
           
        </div>
    </form>
        </div>
       
    </div>
  
  
@endsection