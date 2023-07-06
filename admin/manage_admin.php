<?php 
include('top.php');
$msg="";
$name="";
$username="";
$email="";
$password="";
$id="";
if(isset($_GET['id']) && $_GET['id']>0){
	$id=get_safe_value($_GET['id']);
	$row=mysqli_fetch_assoc(mysqli_query($con,"select * from admin where id='$id'"));
	$name=$row['name'];
	$password=$row['password'];
	$username=$row['username'];
	$email=$row['email'];
}

if(isset($_POST['submit'])){
	$name=get_safe_value($_POST['name']);
	$password=get_safe_value($_POST['password']);
	$email=get_safe_value($_POST['email']);
	$username=get_safe_value($_POST['username']);
	if($id==''){
		$sql="select * from admin where email='$email'and username='$username'";
	}else{
		$sql="select * from admin where email='$email' and username='$username' and id!='$email'";
	}	
	if(mysqli_num_rows(mysqli_query($con,$sql))>0){
		$msg="Admin is already added";
	}else{
		if($id==''){
			
			mysqli_query($con,"insert into admin(name,username,password,email) values('$name','$username','$password','$email')");
		}else{
			mysqli_query($con,"update admin set name='$name', password='$password',username='$username', email='$email' where id='$id'");
		}
		redirect('admin.php');
	}
}
?>
<div class="row">
			<h1 class="grid_title ml10 ml15">Manage Admin</h1>
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <form class="forms-sample" method="post">
                    <div class="form-group">
                      <label for="exampleInputName1">Name</label>
                      <input type="text" class="form-control" placeholder="name" name="name" required value="<?php echo $name?>">
                    </div>
					<div class="form-group">
                      <label for="exampleInputName1">Username</label>
                      <input type="text" class="form-control" placeholder="username" name="username" required value="<?php echo $username?>">
                    </div>
					<div class="form-group">
                      <label for="exampleInputName1">Email</label>
                      <input type="email" class="form-control" placeholder="email" name="email" required value="<?php echo $email?>">
					  <div class="error mt8"><?php echo $msg?></div>
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail3" required>Password</label>
                      <input type="textbox" class="form-control" placeholder="Password" name="password"  value="<?php echo $password?>">
                    </div>
                    <button type="submit" class="btn btn-primary mr-2" name="submit">Submit</button>
                  </form>
                </div>
              </div>
            </div>
            
		 </div>
        
<?php include('footer.php');?>