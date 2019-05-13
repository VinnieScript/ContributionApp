<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\admin;
use App\adnewcustomer;
use App\bookledger;
use App\cashout;

class mainController extends Controller
{
    //
    public function index(){
        return view('index');
    }public function admin(){
        $member = new admin();
        $grant = $member::all();
        return view('admin')->with('id',$grant);
    }

    public function adminlogin(Request $r){
    $member = new admin();
    $paramater = ['username'=>$r->username,'password'=>$r->password];
    $check = $member::where($paramater)->get();
    if(count($check) >0){
        $response = "Successful";
    }
    else{
        $response="Invalid Username/Password";
    }
    return response($response);
        
    }

    public function addcustomer(Request $r){
        $member = new adnewcustomer();
        $check = $member::where('phonenumber','=',$r->phonenumber)->get();
        if(count($check) > 0){
            $response= "Phonenumber has already been used";
        }
        else{

        $member->lastname = $r->lastname;
        $member->firstname = $r->firstname;
        $member->phonenumber = $r->phonenumber;
        $member->address = $r->address;
        $member->agreedamount = $r->a_amount;
        $member->token = $r->token;
        $member->save();
        $response = $r->lastname .' '. $r->firstname .' has been added successfully with Unique card number ' . $r->token;
        }
        return response($response);
    }
    public function fetchdetails(Request $r){
        $member = new adnewcustomer();
        //$criteria = $r->criteria;
    
            
            $check = $member::where('phonenumber','=',$r->criteriainfor)->get();
            if(count($check) > 0){
                $response =$check;
            }
            else{
                $check = $member::where('token','=',$r->criteriainfor)->get();
                if(count($check) > 0){
                    $response = $check;
                }
                else{
                    $response="Invalid Details";
                }
            }
        
        return response($response);
    }
    public function posttransaction(Request $r){
        $member = new bookledger();
        $member->fullname = $r->fullname;
        $member->identifier = $r->token;
        $member->amount = $r->amount;
        $member->approvedstatus = $r->status;
        $member->date = $r->strDate;
        $member->save();
        return response('Transaction Has been Posted');
    }
    public function loadpending(Request $r){
        $member = new bookledger();
        $check  = $member::where('approvedstatus','=','pending')->get();
        if(count($check) > 0){
            $response= $check;
        }
        else{
            $response = "No Pending Transaction for Now";
        }
        return response($response);
    }
    public function approvepost(Request $r){
        $member = new bookledger();
        $update = $member::find($r->id);
        $update->approvedstatus = 'approved';
        $update->save();
        return response('Transaction Approved');
    }
    public function notificationCount(Request $r){
        $member = new bookledger();
        $paramater = ["date"=>$r->strDate,"approvedstatus"=>'pending'];
        $check = $member::where($paramater)->get();
        if(count($check) > 0){
            $response = count($check);
        }
        else{
            $response="empty";
        }
        return response($response);
    }
    public function cashoutfetch(Request $r){
        $member = new bookledger();
        $member1 = new cashout();
        $from = $r->sdate;
        $to = $r->edate;
        $alltimesavings = 0;
        $withdrawal = 0;
        $sum = 0;
        $sum1 = 0;
        $fetch1 = $member1::where('identifier','=',$r->id)->get();
        $parameter = ['identifier'=>$r->id,'approvedstatus'=>'approved'];
        $fetch = $member::where($parameter)->get();
        //$fetch = $member::where('identifier','=',$r->id)->where('date','>=',$r->sdate)->where('date','<=',$r->edate)->get();
        if(count($fetch)){
            $response = $fetch;
            foreach($fetch as $r){
                $sum += $r['amount'];
            }
            $alltimesavings = $sum;
        }

        if(count($fetch1)){
            
            foreach($fetch1 as $r){
                $sum1 += $r['cashoutamount'];
            }
            $withdrawal = $sum1;
        }

        $balance = $alltimesavings - $withdrawal;
        return response()->json([
            'response'=>$response,
            'balance'=>$balance
        ]);
    }
    public function withdrawal(Request $r){
        $member1 = new adnewcustomer();
        $fetch = $member1::where('token','=',$r->id)->first();
        if($fetch){
            if($r->cashamount > $fetch['agreedamount']){
                $member = new  cashout();
                $member->identifier = $r->id;
                $member->totalsavings = $r->cashamount;
                $member->cashoutamount = $r->cashamount;
                $member->status = $r->status;
                 $member->save();
                 $response = $r->id." has successfully Cash Out";
            }
            else{
                $response = "Net Balance Available cannot be Cash Out!";
            }
        }
        

        //$check =  $member::where('identifier','=',$r->id)->get();
        //$checkwidrawal = $member1::where('identifier','=',$r->id)->get();
        //$Alltimesavings = 0;
       // $Alltimewithdrawal = 0;
       // $availablebal = 0;
        //if(count($check) > 0){
            
            //foreach($check as $item){
              //  $Alltimesavings += $item['amount'];
            //}
        //}
        //else{
          //  $Alltimesavings= 0;
       // }
        //if(count($checkwidrawal) > 0){
            
          //  foreach($checkwidrawal as $r){
              //  $Alltimewithdrawal += $r['cashoutamount'];
           // }
        //}
        //else{
          //  $Alltimewithdrawal=0;
        //}
        //$availablebal = $Alltimesavings - $Alltimewithdrawal;
        return response($response);
    }
    public function clientbalance(Request $r){

        $member = new bookledger();
        $member1 = new cashout();
        $member2 = new adnewcustomer();
        $alltimesavings = 0;
        $withdrawal = 0;
        $sum = 0;
        $sum1 = 0;
    
        $parameter = ['identifier'=>$r->id,'approvedstatus'=>'approved'];
        $checkname = $member2::where('token','=',$r->id)->first();
        $check = $member::where($parameter)->get();
        $check1 = $member1::where('identifier','=',$r->id)->get();
        if(count($check) > 0){
            foreach($check as $r){
                $sum += $r['amount'];
            }
            $alltimesavings = $sum;
        }


        if(count($check1) > 0){
            foreach($check1 as $r1){
                $sum1 += $r1['cashoutamount'];
            }
            $withdrawal = $sum1;
        }
        $fullname = $checkname['lastname'] .' '. $checkname['firstname'];
        $balance = $alltimesavings - $withdrawal;
        return response()->json([
            'alltimesavings'=>$alltimesavings,
            'withdrawal'=>$withdrawal,
            'balance'=>$balance,
            'fullname'=>$fullname
        ]);
    }
    
}
