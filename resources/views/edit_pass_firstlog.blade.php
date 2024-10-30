@extends('layouts/auth')

@section('content')
    <p class="login-box-msg">Première connexion: Veuillez Modifier votre mot de passe pour accéder à l'application</p>

    <form action="update_pass_firstlog" method="post">
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

       
        @csrf
        
        <div class="box-body">
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" maxlength="12" class="form-control  input-lg" required name="password" id="pwd1">
            </div>
            
            <div class="form-group">
                <label>Confirmer le mot de passe</label>
                <input type="password" maxlength="12" class="form-control  input-lg" required  id="pwd2" equired onkeyup="verifyPassword()">
            </div>
            <div class="col-md-12 form-group" id="match">            
            </div>
            
                        
        </div>
        <!-- /.box-body -->

        <div class="box-footer">
            <button type="submit" class="btn btn-primary" id="bt" >MODIFIER</button>
        </div>
        <script type="text/javascript">
                                        
            /*UN SCRIPT QUI VA VERFIER SI LES DEUX PASSWORDS MATCHENT*/
            function verifyPassword()
            {
                var msg; 
                var str = document.getElementById("pwd1").value; 
                var button = document.getElementById("bt")

                var text1 = document.getElementById('pwd1').value;
                var text2 = document.getElementById('pwd2').value;
                
                
                if((text1 == text2))
                {  
                    
                    
                    var theText = "<p style='color:green'>Correspond.</p>"; 
                    button.removeAttribute("disabled");
                    document.getElementById("match").innerHTML= theText; 
                    
                }
                else
                {
                    
                    var theText = "<p style='color:red'>Ne correspond pas.</p>";
                    document.getElementById("match").innerHTML= theText;
                    button.setAttribute("disabled", "true");
                }
            }
                    
        </script>     
</form>
  
@endsection