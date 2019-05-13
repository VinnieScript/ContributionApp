<html>
<head>
<meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=1024">
        <link rel="icon" href="{!!asset('images/logo1.jpg')!!}"/>
        <title>JAMMY CASH VENTURES</title>
        <script src="{{asset('newjs/jquery-3.3.1.min.js')}}"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

</head>
<script>
$(document).ready(function(){
  var id = document.getElementById('id').value;
  if(id!= ""){
$('#logout').click(function(){
  location='/';
})
    $("#balance").click(function(){
      document.getElementById('mainDiv').innerHTML="";
      $("#mainDiv").removeClass('badge badge-success');
      $("#mainDiv").css('color','#000');
      $("#mainDiv").append('<div><div class="alert alert-warning" role="alert" style="font-weight:bold" > CLIENT BALANCE</div><div class="form-group"><label style="float:left">Client Id</label><input type="text" class="form-control" id="clientid" aria-describedby="emailHelp" placeholder="Client Token"></div><div id="balanceresult"></div></div>');
        $("#clientid").blur(function(){
          var id = $(this).val();
         // alert(id);
         // document.getElementById('balanceresult').innerHTML ="Please wait.."
         $.ajax({
                  url:'/clientbalance',
                  type:'post',
                  headers: { 'X-CSRF-TOKEN': $('input[name=_token]').val() },
                 beforeSend:function(){
                  document.getElementById('balanceresult').innerHTML ="Please wait..";
                  $('#clientid').attr('disabled','disabled');
                  
                    },
                  data:{
                    id:id
                  },
                  success:function(data){
                    $('#clientid').removeAttr('disabled');
                    document.getElementById('clientid').value="";
                    document.getElementById('balanceresult').innerHTML ="";
                    $("#balanceresult").append('<div><hr/><div style="width:100%" align="center"><img src="{{asset("images/logo1.png")}}" width="25%"/></div><div class="form-group"><label style="font-weight:bold;">'+data['fullname']+'</label><br/>Available Balance<button class="btn btn-success">'+data['balance']+'</button></div></div>')
                   
                  },
                  error:function(obj, status, e){
                    //alert(e);
                    console.log(e);
                  }
                })

        })

    })
$("#cashout").click(function(){
  document.getElementById('mainDiv').innerHTML="";
  $("#mainDiv").removeClass('badge badge-success');
  $("#mainDiv").css('color','#000');
  $("#mainDiv").append('<div><div class="alert alert-secondary" role="alert" style="font-weight:bold" > CASH OUT POINT</div><div class="form-group"><div class="form-group"><label style="float:left">Start Date</label><input type="text" class="form-control" id="sdate" aria-describedby="emailHelp" placeholder="Start Date"></div><div class="form-group"><label style="float:left">End Date</label><input type="text" class="form-control" id="edate" aria-describedby="emailHelp" placeholder="Enddate"></div><div class="form-group"><label style="float:left">PhoneNumber/Token</label><input type="text" class="form-control" id="text" aria-describedby="emailHelp" placeholder="Enter Phonenumber/Token"></div></div><div id="netbalance"></div><div id="th"></div><div id="cashoutdiv"></div><div id="withdrawal"></div></div>');
  $( "#sdate" ).datepicker();
    $( "#edate" ).datepicker();
    
  
  //var startdate= $("#startdate").val();
  //var enddate= $("#enddate").val();
 // var idcashout= document.getElementById('text').value;

  $("#text").blur(function(){
    var x= document.getElementById('text').value;
    var sdate= document.getElementById('sdate').value;
    var edate= document.getElementById('edate').value;
    var sum = 0;
    if(x!=="" && sdate!="" && edate!=""){
                $.ajax({
                url:'/cashoutfetch',
                  type:'post',
                  headers: { 'X-CSRF-TOKEN': $('input[name=_token]').val() },
                 beforeSend:function(){
                  document.getElementById('cashoutdiv').innerHTML ="Please wait.."
                  $("#text").attr('disabled','disabled');
                  $("#sdate").attr('disabled','disabled');
                  $("#edate").attr('disabled','disabled');
                 },
                  data:{
                    id:x,
                    sdate:sdate,
                    edate:edate
                    
                  },
                  success:function(data){
                   console.log(data);
                   if(data == "No Record Found"){
                    $("#text").removeAttr('disabled','disabled');
                    $("#sdate").removeAttr('disabled','disabled');
                    $("#edate").removeAttr('disabled','disabled');
                    document.getElementById('cashoutdiv').innerHTML = data;
                   $("#cashoutdiv").addClass('alert alert-danger')
                   }
                   else{
                    document.getElementById('cashoutdiv').innerHTML ="";
                    $.each(data['response'], function(key, value) {
                      //sum+= Number(value.amount);
          var radio_with_label = $('<div><table class="table table-dark" style="width:100%"><tr ><td>Fullname</td><td>Identifier/Phonenumber</td><td>Amount</td><td>Date</td></tr><tr><td>'+value.fullname+'</td><td>'+value.identifier+'</td><td>'+value.amount+'</td><td>'+ value.created_at+'</td></tr><table></div><br/>');
          $("#cashoutdiv").addClass('alert alert-secondary')
          $("#cashoutdiv").append(radio_with_label); 
        
        // TARGET -> any valid selector container to radios
        });
        $("#netbalance").addClass('alert alert-success')
        $("#netbalance").html("Available NetBalance for CashOut is " + data['balance'] +" <button id='btn-cashout' class='btn btn-primary' >CashOut!</button>");
        $("#th").addClass('alert alert-info');
        $("#th").html("Transaction History");
        sum = data['balance'];
        $("#btn-cashout").click(function(){
          
          
            $('html,body').animate({
              scrollTop: $("#withdrawal").offset().top
          });
          // alert(x);
           $.ajax({
                  url:'/withdrawal',
                  type:'post',
                  headers: { 'X-CSRF-TOKEN': $('input[name=_token]').val() },
                 beforeSend:function(){
                  document.getElementById('withdrawal').innerHTML ="Please Wait...";
                  $("#btn-cashout").attr('disabled','disabled');
                 },
                  data:{
                    
                    id:x,
                    cashamount:sum,
                    status:'paid'
                  },
                  success:function(data){
                   console.log(data);
                   if(data == "Net Balance Available cannot be Cashed Out!"){
                    $("#withdrawal").addClass('alert alert-danger')
                     document.getElementById('withdrawal').innerHTML = data;
                   }
                   else{
                    $("#withdrawal").addClass('alert alert-success')
                   document.getElementById('withdrawal').innerHTML = data;
                   }
                   
                   //$.each(data, function(key, value) {
                      //sum+= Number(value.amount);
         // var radio_with_label = $('<div></div>');
          
        //$("#cashoutdiv").append(radio_with_label); 
      
       // $("#netbalance").html("Available NetBalance for CashOut is " + sum +" <button id='btn-cashout' class='btn btn-primary' >CashOut!</button>");
        
        
        // TARGET -> any valid selector container to radios
        //});
        //$("#withdrawal").html('<div class="form-group"><label style="float:left">Criteria Information</label><input type="text" class="form-control" id="withdrawal" aria-describedby="emailHelp" value="'+sum+'"></div>');



                  },
                  error:function(obj, status, e){
                    //alert(e);
                    console.log(e);
                  }
                })




        })

                   }
                   
                   
                   
                  },
                  error:function(obj, status, e){
                    alert(e);
                  }
                })

  }
  else{
                document.getElementById('cashoutdiv').innerHTML = "Ensure All Fields are correctly filled";
                   $("#cashoutdiv").addClass('alert alert-danger')
  }


  })







})

setInterval(function(){
  var d = new Date();
  var strDate = (d.getMonth()+1) + "/" + d.getDate() + "/" +  d.getFullYear();
                  $.ajax({
                  url:'/notificationCount',
                  type:'post',
                  headers: { 'X-CSRF-TOKEN': $('input[name=_token]').val() },
                 
                  data:{
                    
                    strDate:strDate
                  },
                  success:function(data){
                    if(data == "empty"){
                      document.getElementById('count').innerHTML = '0';
                    }
                    else{
                      
                      document.getElementById('count').innerHTML = data;
                    }
                   
                  },
                  error:function(obj, status, e){
                    //alert(e);
                    console.log(e);
                  }
                })

},5000);

  $("#approve").click(function(){
  
 document.getElementById('mainDiv').innerHTML="";
  $("#mainDiv").removeClass('badge badge-success');
         $("#mainDiv").css('color','#000');
  loadpendingfortoday();
  function loadpendingfortoday(){
    $.ajax({
      url:'/loadpending',
      type:'POST',
      headers: { 'X-CSRF-TOKEN': $('input[name=_token]').val() },
      data:{
        date:'date'
      },
      success:function(data){
       //alert(data);
       if(data =="No Pending Transaction for Now"){
         document.getElementById('mainDiv').innerHTML=data;
         $("#mainDiv").addClass('badge badge-success');
         $("#mainDiv").css('color','#fff');
       }
       else{
         
        $.each(data, function(key, value) {
          
        var radio_with_label = $('<div class="alert alert-secondary" role="alert" > AWAITING APPROVAL TRANSACTION</div><div><table class="table table-dark" style="width:100%"><tr ><td>Fullname</td><td>Identifier/Phonenumber</td><td>Amount</td><td>Action</td></tr><tr><td>'+value.fullname+'</td><td>'+value.identifier+'</td><td>'+value.amount+'</td><td><button class="approved" style="background-color:#003300;color:#fff"><input type="hidden" value="'+value.id+'">Approve</button><button  style="margin-left:5px; background-color:#ff0000;color:#fff" class="declined"><input type="hidden" value="'+value.id+'">Decline</button></td></tr><table></div><br/>');

      $("#mainDiv").append(radio_with_label); // TARGET -> any valid selector container to radios
      });

      $(".approved").click(function(){
        var id = $(this).find('input').attr('value').valueOf();
        $.ajax({
      url:'/approvepost',
      type:'POST',
      headers: { 'X-CSRF-TOKEN': $('input[name=_token]').val() },
      beforeSend:function(){
        $(".approved").attr('disabled','disabled');
        $(".declined").attr('disabled','disabled');
        
      },
      data:{
        id:id
      },
      success:function(data){
        alert(data);
          $(".approved").removeAttr('disabled');
          $(".declined").removeAttr('disabled');
          loadpendingfortoday;
      
        
      },
      error:function(obj, status, e){
        alert(e);
        document.getElementById('mainDiv').innerHTML=e
      }
  
    });

  });
}
      },
      error:function(obj,status,e){
        alert(e);
      }
    });
}

});
  





  
$("#post").click(function(){
  document.getElementById('mainDiv').innerHTML="";
  $("#mainDiv").removeClass('badge badge-success');
         $("#mainDiv").css('color','#000');
  $("#mainDiv").append('<div><div class="form-group"><div class="alert alert-primary" role="alert" style="float:left">POST Transaction</div><br/><br/><br/><div class="form-group"><label style="float:left">Search Criteria</label><select multiple class="form-control" id="criteria"><option>PhoneNumber</option><option>Token</option><option></select></div><div class="form-group"><label style="float:left">Criteria Information</label><input type="text" class="form-control" id="criteriainfor" aria-describedby="emailHelp" placeholder="Criteria Infor"></div></div><small id="result"></small><div id="fetchresult"></div></div>');
  
  $("#criteriainfor").blur(function(){
    var criteriainfor = $(this).val();
    var criteria = $("#criteria").val();

    //alert(criteria+criteriainfor);
    if(criteria!= "" && criteriainfor!= ""){
     $("#criteriainfor").attr('disabled','disabled');
     $("#criteria").attr('disabled','disabled');
      document.getElementById('result').innerHTML="Please wait..."
      $.ajax({
          url:'/fetchdetails',
          type:'POST',
          headers: { 'X-CSRF-TOKEN': $('input[name=_token]').val() },
          data:{
            criteria:criteria,
            criteriainfor:criteriainfor
          },
          success:function(data){
            console.log(data);
            if(data == "Invalid Details"){
              $("#criteriainfor").removeAttr('disabled');
              $("#criteria").removeAttr('disabled');
             document.getElementById('result').innerHTML=data;
            }
            else{
              document.getElementById('result').innerHTML="";
              $.each(data, function(key, value) {

              var radio_with_label = $('<div id="unappend"><div class="alert alert-primary" role="alert" style="float:left"> POST TRANSACTION</div><br/><br/><br/><div style="float:left"><div class="form-group" style="float:left"><label for="exampleInputEmail1">Client PhoneNumber/Token</label><input type="text" class="form-control" id="token" value="'+value.token+'" disabled="disabled"></div><div class="form-group" style="float:left;margin-left:10px"><label for="exampleInputEmail1">Client Name</label><input type="text" class="form-control" id="fullname" value="'+value.lastname+" "+ value.firstname+'" disabled="disabled"></div><div class="form-group" style="float:left;margin-left:10px"><label for="exampleInputEmail1">Amount to be Paid</label><input type="text" class="form-control" id="amount"></div><div><button  id="submitbutton" class="btn btn-primary">Save</button></div></div></div>');
//alert(value.emailaddress);

              $("#fetchresult").append(radio_with_label); // TARGET -> any valid selector container to radios
              });

              $("#submitbutton").click(function(){
                var token = document.getElementById('token').value;
                var fullname = document.getElementById('fullname').value;
                var amount = document.getElementById('amount').value;
                var d =  new Date();
                var strDate = (d.getMonth()+1) + "/" + d.getDate() + "/" +  d.getFullYear();


                //alert(token+fullname+amount);
                $.ajax({
                  url:'/posttransaction',
                  type:'post',
                  headers: { 'X-CSRF-TOKEN': $('input[name=_token]').val() },
                  beforeSend:function(){
                    document.getElementById('result').innerHTML="Posting Transaction";
                    $("#submitbutton").attr('disabled','disabled');
                  },
                  data:{
                    token:token,
                    fullname:fullname,
                    amount:amount,
                    status:'pending',
                    strDate:strDate
                  },
                  success:function(data){
                    alert(data);
                    $("#criteriainfor").removeAttr('disabled');
                    $("#criteria").removeAttr('disabled');
                    document.getElementById('result').innerHTML=data;
                    document.getElementById('criteriainfor').value="";
                    document.getElementById('fetchresult').innerHTML="";
                  },
                  error:function(obj, status, e){
                    alert(e);
                  }
                })
              });
            }
           
          },
          error:function(obj, status, e){
            alert(e);
            document.getElementById('result').innerHTML = e;
            $("#criteriainfor").removeAttr('disabled');
            $("#criteria").removeAttr('disabled');
          }
          

      })
    }
  })


})


  $("#addcustomer").click(function(){
    document.getElementById('mainDiv').innerHTML="";
    $("#mainDiv").removeClass('badge badge-success');
         $("#mainDiv").css('color','#000');
    $("#mainDiv").append('<div><div class="form-group"><div class="alert alert-primary" role="alert" style="float:left"> Add New Client</div><br/><br/><br/> <label for="exampleInputPassword1" style="float:left">Last Name</label><input type="text" id="lastname" class="form-control"  placeholder="Last Name"></div><div class="form-group"><label for="exampleInputPassword1" style="float:left">First Name</label><input type="text" id="firstname" class="form-control"  placeholder="First Name"></div><div class="form-group"><label for="exampleInputPassword1" style="float:left">Phonenumber</label><input type="text" id="phonenumber" class="form-control"  placeholder="Phonenumber"><small>Please note, the phonenumber must be unique</small></div><div class="form-group"><label for="exampleInputPassword1" style="float:left">Agreed Amount</label><input type="text" id="a_amount" class="form-control"  placeholder="Agreed Amount"></div><div class="form-group"><label for="exampleInputPassword1" style="float:left">Address</label><input type="text" id="address" class="form-control"  placeholder="Address"></div><button  id="submitbtn" class="btn btn-primary">Submit</button><br/><small id="result" style="font-weight:bold;font-size:13px"></small></div>');
    
  
    $("#submitbtn").click(function(){
    
    var firstname = document.getElementById('firstname').value;
    var lastname = document.getElementById('lastname').value;
    var phonenumber = document.getElementById('phonenumber').value;
    var address = document.getElementById('address').value;
    var a_amount = document.getElementById('a_amount').value;
    var token = Math.floor(Math.random()*90000) + 10000;
     
  
    //alert(firstname+lastname+phonenumber+address+token);
    $.ajax({
      url:'/addcustomer',
      type:'POST',
      headers: { 'X-CSRF-TOKEN': $('input[name=_token]').val() },
      beforeSend:function(){
        $("#submitbtn").attr('disabled','disabled');
      },
      data:{
        firstname:firstname,
        lastname:lastname,
        phonenumber:phonenumber,
        address:address,
        a_amount:a_amount,
        token:token
      },
      success:function(data){
        if(data == "Phonenumber has already been used"){
          document.getElementById('result').innerHTML = data;
          $("#submitbtn").removeAttr('disabled');
        }
        else{
          document.getElementById('result').innerHTML = data;
          $("#submitbtn").removeAttr('disabled');
          document.getElementById('firstname').value="";
          document.getElementById('lastname').value="";
          document.getElementById('phonenumber').value="";
          document.getElementById('address').value="";
          document.getElementById('a_amount').value="";
        }
        
        
      },
      error:function(obj, status, e){
        alert(e);
      }
  
    });
    
  
  
    })
  
    
    })

    
    
}  
});
</script>
<body>
{{csrf_field()}}
<!-- Just an image -->
<div style="width:100%">
<nav class="navbar navbar-light bg-light">
  <a class="navbar-brand" href="/admin">
    <img src="{{asset('images/logo1.png')}}" width="60" height="60" alt="">
  </a>

  
<button type="button" class="btn btn-primary">
  Notifications <span class="badge badge-light" id="count">0</span>
</button>
<span class="badge badge-primary" style="cursor:pointer">Approved</span>
<span class="badge badge-secondary" style="cursor:pointer">Disapproved</span>
<span class="badge badge-success" style="cursor:pointer">Completed Transation</span>
<span class="badge badge-danger" style="cursor:pointer">TransactionHistory</span>
<span class="badge badge-warning" style="cursor:pointer" id="balance">Client's Balance</span>
<span class="badge badge-info" style="cursor:pointer">Help</span>
<span class="badge badge-dark" style="cursor:pointer" id="logout">@if($id) @foreach($id as $r) {{$r->username}} @endforeach @endif</span>
<input type="hidden" id="id" value="@if($id) @foreach($id as $r) {{$r->username}} @endforeach @endif"/>
</nav>
</div>

<div class="container" style="float:left;margin:10px;">
  <div class="row">
    <div class=".col-4">
    <ul class="list-group">
  <li class="list-group-item" style="cursor:pointer" id="addcustomer">AddCustomer</li>
  <li class="list-group-item" style="cursor:pointer" id="post">PostTransaction</li>
  <li class="list-group-item" style="cursor:pointer" id="approve">Approve</li>
  <li class="list-group-item" style="cursor:pointer" id="cashout">CashOut</li>
  
</ul>
    </div>
    <div class="col-sm" id="mainDiv" align="center" >
    <img src="{{asset('images/logo1.png')}}" width="40%"/>
    <h1>JAMMY CASH VENTURES</h1>
    <small>...your best service our priority</small>
    
    </div>
   


</div>

<div class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Modal body text goes here.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>




</body>
</html>
