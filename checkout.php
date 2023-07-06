<?php
include ("header.php");
require("config.php");
$insert_id="";
$total_price="";
$cartArr=getUserFullCart();
if(count($cartArr)>0){
}else{
	redirect(FRONT_SITE_PATH.'shop');
}
if(isset($_SESSION['FOOD_USER_ID'])){
	$is_show='';
	$box_id='';
	$final_show='show';
	$final_box_id='payment-2';
}else{
	error_reporting(0);
	$is_show='show';
	$box_id='payment-1';
	$final_show='';
	$final_box_id='';
}
$userArr=getUserDetailsByid();
$is_error='';
if(isset($_POST['place_order'])){
	if($is_error==''){
		$payment_type=get_safe_value($_POST['payment_type']);
		$added_on=date('Y-m-d h:i:s');
		$sql="insert into order_master(user_id,total_price,order_status,added_on,cancel_by) values('".$_SESSION['FOOD_USER_ID']."','$totalPrice','1','$added_on',' ')";
		mysqli_query($con,$sql);
		$insert_id=mysqli_insert_id($con);
		$_SESSION['ORDER_ID']=$insert_id;
		$sql2="insert into payment(user_id,payment_type,payment_status,order_id) values('".$_SESSION['FOOD_USER_ID']."','$payment_type','pending','$insert_id')";
		mysqli_query($con,$sql2);
		emptyCart();
		$getUserDetailsBy=getUserDetailsByid();
		if($payment_type=='cod'){
			redirect(FRONT_SITE_PATH.'success');
		}
		else if($payment_type=='stripe'){
			$total_price=$totalPrice;?>
			&nbsp;
			<center><h5 style="color:red"> Please click 'Pay With Card' to proceed </h5></center>
			&nbsp;
			<center>
			<form action="submit.php" method="post">	
								<script
								src="https://checkout.stripe.com/checkout.js" class="stripe-button"
								data-key="<?php echo $publishableKey ?>"
								data-amount="<?php echo $total_price*100 ?>"
								data-name="VIEAT"
								data-description="Online Food Ordering And Delivery Platform"
								data-image="http://127.0.0.1:7882/vieat_final/admin/assets/images/pay.png"
								data-currency="inr"
								data-email="<?php echo $userArr['email']?>"
								>
								</script>				
			</form>
			</center>
			&nbsp;
			<?php
			$upd="update payment set payment_status='success' where order_id='$insert_id'";
			mysqli_query($con,$upd);
		}
	}
}
?>
<div class="checkout-area pb-80 pt-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9">
                        <div class="checkout-wrapper">
                            <div id="faq" class="panel-group">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5 class="panel-title"><span>1.</span> <a data-toggle="collapse" data-parent="#faq" href="#payment-1">Checkout method</a></h5>
                                    </div>
                                    <div id="<?php echo $box_id?>" class="panel-collapse collapse <?php echo $is_show?>">
                                        <div class="panel-body">
                                            <div class="row">
                                                
                                                <div class="col-lg-12">
                                                    <div class="checkout-login">
                                                        <div class="title-wrap">
                                                            <h4 class="cart-bottom-title section-bg-white">LOGIN</h4>
                                                        </div>
                                                        <p>&nbsp;</p>
                                                        <form method="post" id="frmLogin">
                                                            <div class="login-form">
                                                                <label>Email Address * </label>
                                                                <input name="user_email" placeholder="Email" required>
                                                            </div>
                                                            <div class="login-form">
                                                                <label>Password *</label>
                                                                <input type="password" name="user_password" placeholder="Password" required>
																<input type="hidden" name="type" value="login"/>
																<input type="hidden" name="is_checkout" value="yes" id="is_checkout"/>
                                                            </div>
                                                        
                                                         <div class="checkout-login-btn">
                                                            <button type="submit" id="login_submit" class="my_btn">Login</button>
															<a href="<?php echo FRONT_SITE_PATH?>login_register" style="background-color: #e02c2b;color:#fff;">Register Now</a>
                                                        </div>
														<div id="form_login_msg"></div>
														</form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h5 class="panel-title"><span>2.</span> <a data-toggle="collapse" data-parent="#faq" href="#payment-2">Other information</a></h5>
                                    </div>
                                    <div id="<?php echo $final_box_id?>" class="panel-collapse collapse <?php echo $final_show?>">
                                        <div class="panel-body">
											<form method="post">
												<div class="billing-information-wrapper">
													<div class="row">
														<div class="col-lg-3 col-md-6">
															<div class="billing-info">
																<label>First Name</label>
																<input type="text" name="checkout_name" required value="<?php echo $userArr['name']?>">
															</div>
														</div>
														<div class="col-lg-3 col-md-6">
															<div class="billing-info">
																<label>Email Address</label>
																<input type="email"  name="checkout_email" required value="<?php echo $userArr['email']?>">
															</div>
														</div>
														<div class="col-lg-3 col-md-6">
															<div class="billing-info">
																<label>Mobile</label>
																<input type="text"  name="checkout_mobile" required value="<?php echo $userArr['mobile']?>">
															</div>
														</div>
														<div class="col-lg-12 col-md-12">
															<div class="billing-info">
																<label>Address</label>
																<input type="text"  name="checkout_address" required value="<?php echo $userArr['address']?>">
															</div>
														</div>
													
														<div class="ship-wrapper">
														<div class="single-ship">
															<input type="radio" name="payment_type" value="cod">
															<label>Cash on Delivery(COD)</label>
														</div>
														<div class="single-ship">
															<input type="radio" name="payment_type" value="stripe"   checked="checked">
															<label>Online Payment</label>
														</div>
														<div class="billing-back-btn ">
														<div class="billing-btn">
															<button type="submit" class="float" name="place_order">Place Your Order</button>
														</div>
														
													</div>
													</div>
													
												</div>
											</form>
                                        </div>
                                    </div>
                                </div>
                                
						   </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="checkout-progress">
                            <div class="shopping-cart-content-box">
								<h4 class="checkout_title">Cart Details</h4>
								<ul>
								
									<?php //prx($cartArr);
									foreach($cartArr as $key=>$list){ ?>
									<div class="grid-list-product-wrapper">
                            			<div class="product-grid product-view pb-20">
											<li class="product-wrapper">
												<div class="product-img">
                                                    <a href="javascript:void(0)">
                                                        <img src="<?php echo SITE_DISH_IMAGE.$list['image']?>" alt="">
														<br>
                                                    </a>
												</div>
										</div>
									</div>			
										<div class="shopping-cart-title">
											<h4><a href="#"><?php echo $list['dish']?></a></h4>
											<h6>Qty: <?php echo $list['qty']?></h6>
											<span><?php echo 
														$list['qty']*$list['price'];?> Rs</span>
										</div>
										
									</li>
									<?php } ?>
								</ul>
								<div class="shopping-cart-total">
									<h4>Total : <span class="shop-total"><?php echo $totalPrice?> Rs</span></h4>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
include("footer.php");
?>
