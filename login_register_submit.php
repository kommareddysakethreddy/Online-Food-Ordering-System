<?php
session_start();
include('database.inc.php');
include('function.inc.php');
include('constant.inc.php');
include('smtp/PHPMailerAutoload.php');

$type=get_safe_value($_POST['type']);
$added_on=date('Y-m-d h:i:s');
if($type=='register'){
	$name=get_safe_value($_POST['name']);
	$email=get_safe_value($_POST['email']);
	$mobile=get_safe_value($_POST['mobile']);
	$password=get_safe_value($_POST['password']);
	$address=get_safe_value($_POST['address']);
	
	$check=mysqli_num_rows(mysqli_query($con,"select * from user where email='$email'"));
	if($check>0){
		$arr=array('status'=>'error','msg'=>'Email id already registered','field'=>'email_error');
	}else{
		$new_password=password_hash($password,PASSWORD_BCRYPT);
		$rand_str=rand_str();
		
		mysqli_query($con,"insert into user(name,email,mobile,password,status,added_on,address) values('$name','$email','$mobile','$new_password','1','$added_on','$address')");
		mysqli_query($con,"insert into email_verification(email,email_verify,rand_str) values('$email','$email_verify','$rand_str')");
		$id=mysqli_insert_id($con);
		
		$html=FRONT_SITE_PATH."verify/".$rand_str;
		send_email($email,$html,'VIEAT: Verify your email id');
		
		
		$arr=array('status'=>'success','msg'=>'Thank you for register. Please check your email id, to verify your account','field'=>'form_msg');
	}
	echo json_encode($arr);
}

if($type=='login'){
	$email=get_safe_value($_POST['user_email']);
	$password=get_safe_value($_POST['user_password']);
	
	$res=mysqli_query($con,"select user.*,email_verification.email_verify,email_verification.rand_str from user,email_verification 
	where user.email='$email' and email_verification.email='$email'");
	$check=mysqli_num_rows($res);
	if($check>0){	
		$row=mysqli_fetch_assoc($res);
		$status=$row['status'];
		$email_verify=$row['email_verify'];
		$dbpassword=$row['password'];
		if($email_verify==1){
			if($status==1){
				if(password_verify($password,$dbpassword)){
					$_SESSION['FOOD_USER_ID']=$row['id'];
					$_SESSION['FOOD_USER_NAME']=$row['name'];
					$_SESSION['FOOD_USER_EMAIL']=$row['email'];
					$arr=array('status'=>'success','msg'=>'');
					
					if(isset($_SESSION['cart']) && count($_SESSION['cart'])>0){
						foreach($_SESSION['cart'] as $key=>$val){
							manageUserCart($_SESSION['FOOD_USER_ID'],$val['qty'],$key);
						}
					}
					
				}else{
					$arr=array('status'=>'error','msg'=>'Please enter correct password');
				}
			}else{
				$arr=array('status'=>'error','msg'=>'Your account has been deactivated.');
			}
		}else{
			$arr=array('status'=>'error','msg'=>'Please verify your email id');
		}
	}else{
		$arr=array('status'=>'error','msg'=>'Please enter valid email id');	
	}
	echo json_encode($arr);
}

if($type=='forgot'){
	$email=get_safe_value($_POST['user_email']);
	
	$res=mysqli_query($con,"select user.*,email_verification.email_verify,email_verification.rand str from user,email_verification 
	where user.email='$email' and email_verification.email='$email'");
	$check=mysqli_num_rows($res);
	if($check>0){	
		$row=mysqli_fetch_assoc($res);
		$status=$row['status'];
		$email_verify=$row['email_verify'];
		$id=$row['id'];
		if($email_verify==1){
			if($status==1){
				$rand_password=rand(11111,99999);
				$new_password=password_hash($rand_password,PASSWORD_BCRYPT);
				mysqli_query($con,"update user set password='$new_password' where id='$id'");
				$html=$rand_password;
				send_email($email,$html,'New Password');
				$arr=array('status'=>'success','msg'=>'Password has been reset and send it to your email id');
				
			}else{
				$arr=array('status'=>'error','msg'=>'Your account has been deactivated.');
			}
		}else{
			$arr=array('status'=>'error','msg'=>'Please verify your email id');
		}
	}else{
		$arr=array('status'=>'error','msg'=>'Please enter valid email id');	
	}
	echo json_encode($arr);
}
?>