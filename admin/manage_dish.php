<?php 
include('top.php');
$msg="";
$category_id="";
$dish="";
$price="";
$type="";
$image="";
$id="";
$image_status='required';
$image_error="";
if(isset($_GET['id']) && $_GET['id']>0){
	$id=get_safe_value($_GET['id']);
	$row=mysqli_fetch_assoc(mysqli_query($con,"select * from dish where id='$id'"));
	$category_id=$row['category_id'];
	$dish=$row['dish'];
	$type=$row['type'];
	$image=$row['image'];
	$image_status='';
	$price=$row['price'];
}

if(isset($_POST['submit'])){
	$category_id=get_safe_value($_POST['category_id']);
	$dish=get_safe_value($_POST['dish']);
	$price=get_safe_value($_POST['price']);
	$food_type=get_safe_value($_POST['type']);
	$added_on=date('Y-m-d h:i:s');
	
	if($id==''){
		$sql="select * from dish where dish='$dish'";
	}else{
		$sql="select * from dish where dish='$dish' and id!='$id'";
	}	
	if(mysqli_num_rows(mysqli_query($con,$sql))>0){
		$msg="Dish already added";
	}else{
		$type=$_FILES['image']['type'];
		if($id==''){
			if($type!='image/jpeg' && $type!='image/png'){
				$image_error="Invalid image format";
			}else{
				$image=rand(111111111,999999999).'_'.$_FILES['image']['name'];
				move_uploaded_file($_FILES['image']['tmp_name'],SERVER_DISH_IMAGE.$image);
				mysqli_query($con,"insert into dish(category_id,dish,status,added_on,image,price,type) values('$category_id','$dish',1,'$added_on','$image','$price','$food_type')");
				redirect('dish.php');
			}
		}else{
			$image_condition='';
			if($_FILES['image']['name']!=''){
				if($type!='image/jpeg' && $type!='image/png'){
					$image_error="Invalid image format";
				}else{
					$image=rand(111111111,999999999).'_'.$_FILES['image']['name'];
					move_uploaded_file($_FILES['image']['tmp_name'],SERVER_DISH_IMAGE.$image);
					$image_condition="image='$image'";
				}
			}
			if($image_error==''){
				$sql="update dish set type='$food_type',price='$price',category_id='$category_id', dish='$dish' , image='$image' where id='$id'";
				mysqli_query($con,$sql);
				redirect('dish.php');
			}
		}
	}
}
$res_category=mysqli_query($con,"select * from category where status='1' order by category asc");
$arrType=array("veg","non-veg");
?>
<div class="row">

			<h1 class="grid_title ml10 ml15">Dish</h1>
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <form class="forms-sample" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                      <label for="exampleInputName1">Category</label>
                      <select class="form-control" name="category_id" required>
						<option value="">Select Category</option>
						<?php
						while($row_category=mysqli_fetch_assoc($res_category)){
							if($row_category['id']==$category_id){
								echo "<option value='".$row_category['id']."' selected>".$row_category['category']."</option>";
							}else{
								echo "<option value='".$row_category['id']."'>".$row_category['category']."</option>";
							}
						}
						?>
					  </select>
					  
                    </div>
					<div class="form-group">
                      <label for="exampleInputName1">Dish</label>
                      <input type="text" class="form-control" placeholder="dish" name="dish" required value="<?php echo $dish?>">
					  <div class="error mt8"><?php echo $msg?></div>
                    </div>
					<div class="form-group">
                      <label for="exampleInputName1">Price</label>
                      <input type="text" class="form-control" placeholder="price" name="price" required value="<?php echo $price?>">
                    </div>
					<div class="form-group">
						<label for="exampleInputName1">Type</label>
						<select class="form-control" name="type" required>
							<option value="">Select Type</option>
							<?php 
							foreach($arrType as $list){
								if($list==$type){
									echo "<option value='$list' selected>".strtoupper($list)."</option>";
								}else{
									echo "<option value='$list'>".strtoupper($list)."</option>";
								}
							}
							?>
						</select>
					</div>
					<div class="form-group">
                      <label for="exampleInputEmail3">Dish Image</label>
                      <input type="file" class="form-control" placeholder="Dish Image" name="image" <?php echo $image_status?>>
					  <div class="error mt8"><?php echo $image_error?></div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary mr-2" name="submit">Submit</button>
                  </form>
                </div>
              </div>
            </div>
            
		 </div>
        
<?php include('footer.php');?>


