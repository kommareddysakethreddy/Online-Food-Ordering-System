<?php
include ("header.php");
include('smtp/PHPMailerAutoload.php');
if(!isset($_SESSION['ORDER_ID'])){
	redirect(FRONT_SITE_PATH.'shop');
}
$id=$_SESSION['ORDER_ID'];
$sql="update dish_invoice set order_id='$id' where order_id=0";
mysqli_query($con,$sql);
$sq2="select payment_status,payment_type from payment where order_id='$id'";
$res=mysqli_query($con,$sq2);
$sql3 = "select user.* from user,order_master where order_master.id='$id' and order_master.user_id=user.id";
$res3=mysqli_query($con,$sql3);
if(mysqli_num_rows($res3)>0){
    $orderRow1=mysqli_fetch_assoc($res3);
}
if(mysqli_num_rows($res)>0)
{
    $row=mysqli_fetch_assoc($res);
    if($row['payment_status']='success' && $row['payment_type']='stripe')
    {
        $html=orderEmail($id,$orderRow1['id']);
		send_email($orderRow1['email'],$html,"VIEAT: Bill For Your Recent Successful Order!" );
    }
    else if($row['payment_type']='cod')
    {
        $html=orderEmail($id,$orderRow1['id']);
		send_email($orderRow1['email'],$html,"VIEAT: Order Successfully placed!" );
    }
}
?>

<div class="breadcrumb-area gray-bg">
            <div class="container">
                <div class="breadcrumb-content">
                    <ul>
                        <li><a href="<?php echo FRONT_SITE_PATH?>shop">Home</a></li>
                        <li class="active">Order Placed </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="about-us-area pt-50 pb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-7 d-flex align-items-center">
                        <div class="overview-content-2">
                            <h2>Order has been placed successfully. <br/><br/>Order Id <?php echo $_SESSION['ORDER_ID']?></h2>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

<?php
unset($_SESSION['ORDER_ID']);
include("footer.php");
?>