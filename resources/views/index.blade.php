<html>
<head>
<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=1024">
        <link rel="icon" href="{!!asset('images/logo1.jpg')!!}"/>
        <title>Bumik|Enterprise</title>
        <script src="{{asset('newjs/jquery-3.3.1.min.js')}}"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<script>
$(document).ready(function(){
  var username = document.getElementById('username').value;
  var password = document.getElementById('password').value;
  $("#submitbtn").click(function(){
    
  var username = document.getElementById('username').value;
  var password = document.getElementById('password').value;
   

//alert(username+password);
  $.ajax({
    url:'/adminlogin',
    type:'POST',
    headers: { 'X-CSRF-TOKEN': $('input[name=_token]').val() },
    beforeSend:function(){
      $("#submitbtn").attr('disabled','disabled');
    },
    data:{
      username:username,
      password:password
    },
    success:function(data){
      if(data == "Successful"){
        window.location="/admin"
      }
      else{
        document.getElementById('result').innerHTML = data;
        $("#submitbtn").removeAttr('disabled');
      }
    },
    error:function(obj, status, e){
      alert(e);
    }

  });
  


  })

})
</script>
<body>
{{csrf_field()}}
<!-- Just an image -->
<div style="position:fixed;z-index:1px;width:100%">
<nav class="navbar navbar-light bg-dark">
  <a class="navbar-brand" href="#">
    <img src="{{asset('images/logo1.png')}}" width="60" height="60" alt="">
  </a>

  <nav class="nav">
  <a class="nav-link " href="#">Bumike</a>
  <a class="nav-link" href="#">Email</a>
  <a class="nav-link" href="#">Directory</a>
  <a class="nav-link " href="#">Afflate</a>
  <a class="nav-link disabled" href="#">Disabled</a>
</nav>
</nav>
</div>
<div style="width:100%;height:100vh;">
<br/><br/><br/><br/><br/>
<form style="margin-left:50px"> 
  <div class="form-group">
    <label for="exampleInputEmail1">Username</label>
    <input type="text" id="username" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter username">
    
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" id="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
  </div>
  
  
</form>
<button  id="submitbtn" class='btn btn-primary'style="margin-left:50px;">Submit</button><br/>
  <small id="result" style="margin-left:50px;color:#ff0000;font-weight:bold"></small>
<div>


</body>
</html>
