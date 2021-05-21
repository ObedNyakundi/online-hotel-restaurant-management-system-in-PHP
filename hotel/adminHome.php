<?php

//A list of the common global variables
$error=" ";

/*"<div class='w3-container alert alert-slim alert-success fade in'> <strong>Bravo XYZ!</strong> <br> 
				Your new post has been listed <br>
			<a href=\"home.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a>
			</div>";
*/

require "db/connection.php";

if (is_admin()) {

/***************************************
		Definition of errors
state 0: default state; is ignored
state 1: a new food has been registered
state 2: new food registration failed.
state 3: a new user was added successfully
state 4: failed to register new user
state 5: new cattegory was added
state 6: failed to add new category
state 7: new role was added
state 8: failed to add a new user role 


***************************************/
if (isset($_GET['state'])) {

	$state=$_GET['state'];
	switch ($state) {
		case 1: $error="<div class='w3-container alert alert-slim alert-success fade in'> <strong>Bravo!</strong> <br> 
				Your new food has been listed <br>
			<a href=\"home.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a>
			</div>";
			break;

		case 2: $error="<div class='w3-container alert alert-slim alert-warning fade in'> <strong>Sorry ...</strong> <br> 
				we faced a little problem adding your new food listing. if this problem persists, please contact the administrator <br>
			<a href=\"home.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a>
			</div>";
			break;

		case 3: $error="<div class='w3-container alert alert-slim alert-success fade in'> <strong>Bravo!</strong> <br> 
				The new user has been added successfully. <br>
			<a href=\"home.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a>
			</div>";
			break;

		case 4: $error="<div class='w3-container alert alert-slim alert-warning fade in'> <strong>Sorry ...</strong> <br> 
				we faced a little problem adding the new user. if this problem persists, please contact the administrator <br>
			<a href=\"home.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a>
			</div>";
			break;

		case 5: $error="<div class='w3-container alert alert-slim alert-success fade in'> <strong>Bravo!</strong> <br> 
				The new category has been added successfully. <br>
			<a href=\"home.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a>
			</div>";
			break;

		case 6: $error="<div class='w3-container alert alert-slim alert-warning fade in'> <strong>Sorry ...</strong> <br> 
				we faced a little problem adding the new category. if this problem persists, please contact the administrator <br>
			<a href=\"home.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a>
			</div>";
			break;

		case 7: $error="<div class='w3-container alert alert-slim alert-success fade in'> <strong>Bravo!</strong> <br> 
				The new user role has been added successfully. <br>
			<a href=\"home.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a>
			</div>";
			break;

		case 8: $error="<div class='w3-container alert alert-slim alert-warning fade in'> <strong>Sorry ...</strong> <br> 
				we faced a little problem adding the new role. if this problem persists, please contact the administrator <br>
			<a href=\"home.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a>
			</div>";
			break;
		
		default:
			# code...
			break;
	}
}

/***************************************
			list of actions
***************************************/

/************************************
		adding a new food listing
*************************************/
if (isset($_POST['addFood'])) {
	//collect the data
	$foodName=is_username($_POST['foodName']);
	$descr=uncrack($_POST['descr']);
	$type0=uncrack($_POST['type0']);
	$price=uncrack($_POST['price']);

	//variable to hold upload errors
	$upload_errors=" ";

	//upload and verify image
	$picName=$_FILES['pic']['name'];
	$type =$_FILES['pic']['type'];
	$picSize =$_FILES['pic']['size'];
	$extention=strtolower(substr($picName,strpos($picName,".")+1));
	//determine the temporary location 
	$tmpLoc=$_FILES['pic']['tmp_name'];

	if(isset($picName)){
				//check if the selected file name is not null
				if(!empty($picName)){
					//next validate the image type
					if ($type=="image/gif" || $type=="image/jpeg" || $type=="image/jpg" || $type=="image/png") {
						//Next I will validate the image size to appx 3mb
						if ($picSize <= 5000000) {
						$newNm="food_".$foodName.".".$extention;
						$loc="./images/photo/";
						if(!file_exists($loc.$newNM)){
						if(move_uploaded_file($tmpLoc, $loc.$newNm)){
							//Upload was a success. do nothing.
						}else{$upload_errors="Error uploading your file.. ";}
					}else{
						//delete old file
						unlink($loc.$newNM);
						//upload new file
						if(move_uploaded_file($tmpLoc, $loc.$newNm)){
							//Upload was a success. do nothing.
						}else{$upload_errors= "Error uploading your file.. ";}
					}
				}
				else{$upload_errors= "The system only allows image files up to 5mb";}
			}
			else{$upload_errors= "The Selected file format is not accepted";}
			} else{
					$upload_errors= "You did not chose a photo";
				}
			}

			//append them to the global error variable and display them
			$error.="<h1>".$upload_errors."</h1>";

			//sql statement to the infromation
			$sq0="insert into foods (`caption`,`description`, `category`, `price`,`image` )
			values ('$foodName','$descr','$type0','$price','$newNm')";

			//attempt an insertion
			if (mysqli_query($conn,$sq0)) {
				header("location:adminHome.php?state=1");
			}
			else{
				header("location:adminHome.php?state=2");
			}
}



/************************************
	  adding a new administrator
*************************************/

if (isset($_POST['addUser'])) {
	//collect data
	$username=is_username($_POST['ttname']);
	$usermail=is_email($_POST['uemail']);
	$role=uncrack($_POST['type0']);
	$password=random_password();

	//email details
	$header="Your login details";
	$body="
Hello $username,
here are your login details to the site:
email: $usermail
password: $password.
	";

	$sq="INSERT into `users` (`name`, `email`, `role`, `password`)
	values ('$username','$usermail','$role','$password')
	";

	if (mysqli_query($conn,$sq)) {
		//mail
		mail($usermail, $header, $body);
		header("location:adminHome.php?state=3");
	}
	else
	{
		header("location:adminHome.php?state=4");
	}
}


/************************************
	  adding a new Category
*************************************/
if (isset($_POST['addCat'])) {
	//collect
	$lbl=is_username($_POST['ttname']);
	$sq="insert into `food_categories` (`label`) values ('$lbl') ";

	if (mysqli_query($conn,$sq)) {
		header("location:adminHome.php?state=5");
	}
	else
	{
		header("location:adminHome.php?state=6");
	}
}

/************************************
	  adding a new Role
*************************************/
if (isset($_POST['addRole'])) {
	//collect
	$lbl=is_username($_POST['ttname']);
	$sq="insert into `admin_roles` (`label`) values ('$lbl') ";

	if (mysqli_query($conn,$sq)) {
		header("location:adminHome.php?state=7");
	}
	else
	{
		header("location:adminHome.php?state=8");
	}
}




require "header2.php";
?>



    <section height="auto" id="about-us">

    <center> <?php echo $error; ?></center>


	<div class="container works clearfix">
		<div class="row">

		
			<div class="left-panel col-md-3 pull-left w3-card-2">
			<div class="overlay-div">
				
					<div>
						<center> <h1 style="color:#ff530a; text-shadow:3px 3px white;">Menu</h1></center>

						<div class="panel panel-default">
						  <div class="panel-heading w3-black"> I Want To View...<i class="fa fa-eye"></i> </div>
						  <div class="panel-body">
						  <ul>
						  	<li> <a class="side-links" href="./adminHome.php#foods">Foods</a></li>
						  	<li> <a class="side-links" href="./adminHome.php#foodCategories">Food categories</a></li>
						  	<li><a class="side-links" href="">Orders</a></li>
						  	<li><a class="side-links" href="./adminHome.php#admins">Administrators</a></li>
						  	<li> <a class="side-links" href="./adminHome.php#adminRoles">Roles of users</a></li>
						  </ul>
						  </div>
						</div>

						<div class="panel panel-default">
						  <div class="panel-heading w3-black">I Want To Add...<i class="fa fa-plus"></i> </div>
						  <div class="panel-body">
						  <ul>
						  	<li><a class="side-links" onclick="unhide('frmAddfood');" href="#">New food</a></li>
						  	<li><a class="side-links" onclick="unhide('frmAddCat');" href="#">New food category</a></li>
						  	<li><a class="side-links" onclick="unhide('frmAdduser');" href="#">New User</a></li>
						  	<li><a class="side-links" onclick="unhide('frmAddRole');" href="#">New User role</a></li>
						  </ul>
						  </div>
						</div>


					</div>
			</div>
			</div>
		

			<div class="main-panel col-md-8 w3-card-2 pull-right">
			<div>

			
				<center><h1><span>Administrative Controls</span></h1></center>


							<!-- Form for sddition of meals-->
							
				<div class="text-center w3-card-4 ordinary-form" style="display:none" id="frmAddfood">
				<h1 style="color:#ff530a;; border-radius: 50%"><i class="fa fa-close pull-right" onclick="hide('frmAddfood')" title="Close Form"></i></h1>
						<h1 class="frm-text">Add a New Food </h1><br>					
					
					<form method="POST" enctype="multipart/form-data" action="adminHome.php">
						<div class="frm-content">
								<div class="form-group wow fadeInDown" data-wow-duration="100ms" data-wow-delay="200ms">
								<label for="name">Food Name: *</label>
								<input type="text" autofocus required id="name" name="foodName" class=" text-center form-control" placeholder="Name. e.g. Chapati">
								</div>

								<div class="form-group wow fadeInDown" data-wow-duration="300ms" data-wow-delay="400ms">
								<label for="upi">Description: (optional)</label>
								<textarea id="upi" cols="7" name="descr" class=" text-center form-control" placeholder="An african dish made of wheat flour"></textarea>
								</div>

								<div class="form-group wow fadeInDown" data-wow-duration="500ms" data-wow-delay="800ms">
								<label for="type0">Cateory: *</label>
	                            <select required name="type0" id="type0" title="please select type designation" class="form-control text-center">
	                                    <option value=""> *** Select Type *** </option>
	                                    <?php 
	                                        $qq="select `label`, `id` from `food_categories`";
	                                        $rr=mysqli_query($conn,$qq);
	                                        while ($row=mysqli_fetch_array($rr,MYSQLI_BOTH)) {
	                                            $title= $row['label'];
	                                            $fId=$row['id'];
	                                            echo "<option value=\"$title\"> *** $title *** </option>";
	                                        }
	                                    ?>
	                            </select>
	                            </div>

								<div class="form-group wow fadeInDown" data-wow-duration="600ms" data-wow-delay="700ms">
								<label for="price">price: *</label>
								<input type="text" required id="price" name="price" class=" text-center form-control" placeholder="Price e.g. 45.00">
								</div>


								<div class="form-group wow fadeInDown" data-wow-duration="900ms" data-wow-delay="1200ms"> 
								<label for="dop">image: *</label>
								<input type="file" required id="dop" name="pic" class=" text-center form-control" placeholder="select image">
								</div>
						</div>

						<div class="frm-controls">
								<input type="submit" onclick="hide('frmAddfood')" name="addFood" class="btn btn-info pull-center" value="Add new food">
						</div>
							</form>
				</div>


				<!-- Form for sddition of users-->
				<div class="text-center w3-card-4 ordinary-form" style="display:none" id="frmAdduser">
				<h1 style="color:#ff530a;; border-radius: 50%"><i class="fa fa-close pull-right" onclick="hide('frmAdduser')" title="Close Form"></i></h1>
						<h1 class="frm-text">Add a New User </h1><br>					
					
					<form method="POST" action="adminHome.php">
						<div class="frm-content">
								<div class="form-group wow fadeInDown" data-wow-duration="100ms" data-wow-delay="200ms">
								<label for="name">Name: *</label>
								<input type="text" autofocus required id="name" name="ttname" class=" text-center form-control" placeholder="Name. e.g. Obed Nyakundi">
								</div>

								<div class="form-group wow fadeInDown" data-wow-duration="300ms" data-wow-delay="400ms">
								<label for="uemail">Email: *</label>
								<input type="email" required id="uemail" name="uemail" class=" text-center form-control" placeholder="e-mail. e.g. example@example.com">
								</div>

								<div class="form-group wow fadeInDown" data-wow-duration="500ms" data-wow-delay="800ms">
								<label for="type0">What role does he/she play? *</label>
	                            <select required name="type0" id="type0" title="please select type designation" class="form-control text-center">
	                                    <option value=""> *** Select role *** </option>
	                                    <?php 
	                                        $qq="select `label` from `admin_roles`";
	                                        $rr=mysqli_query($conn,$qq);
	                                        while ($row=mysqli_fetch_array($rr,MYSQLI_BOTH)) {
	                                            $title= $row['label'];
	                                            echo "<option value=\"$title\"> *** $title *** </option>";
	                                        }
	                                    ?>
	                            </select>
	                            </div>
						</div>

						<div class="frm-controls">
								<input type="submit" onclick="hide('frmAdduser')" name="addUser" class="btn btn-info pull-center" value="Add new user">
						</div>
							</form>
				</div>


				<!-- Form for addition of food category-->
				<div class="text-center w3-card-4 ordinary-form" style="display:none" id="frmAddCat">
				<h1 style="color:#ff530a;; border-radius: 50%"><i class="fa fa-close pull-right" onclick="hide('frmAddCat')" title="Cancel"></i></h1>
						<h1 class="frm-text">Add a New Food category </h1><br>					
					
					<form method="POST" action="adminHome.php">
						<div class="frm-content">
								<div class="form-group wow fadeInDown" data-wow-duration="100ms" data-wow-delay="200ms">
								<label for="name">Food Category Name: *</label>
								<input type="text" autofocus required id="name" name="ttname" class=" text-center form-control" placeholder="Name. e.g. Chinese dishes">
								</div>
						</div>

						<div class="frm-controls">
								<input type="submit" onclick="hide('frmAddCat')" name="addCat" class="btn btn-info pull-center" value="Add new category">
						</div>
							</form>
				</div>

				<!-- Form for addition of User roles-->
				<div class="text-center w3-card-4 ordinary-form" style="display:none" id="frmAddRole">
				<h1 style="color:#ff530a;; border-radius: 50%"><i class="fa fa-close pull-right" onclick="hide('frmAddRole')" title="Cancel"></i></h1>
						<h1 class="frm-text">Add a New Role </h1><br>					
					
					<form method="POST" action="adminHome.php">
						<div class="frm-content">
								<div class="form-group wow fadeInDown" data-wow-duration="100ms" data-wow-delay="200ms">
								<label for="name">User Role caption: *</label>
								<input type="text" autofocus required id="name" name="ttname" class=" text-center form-control" placeholder="caption. e.g. Store Keeper">
								</div>
						</div>

						<div class="frm-controls">
								<input type="submit" onclick="hide('frmAddRole')" name="addRole" class="btn btn-info pull-center" value="Add new role">
						</div>
							</form>
				</div>

				<a name="foods"></a>
				<div class="back-div">
					<div class="front-div">

						<center> <h1 style="color:#ff530a; text-shadow:2px 2px grey;"><u>Our menu of foods</u></h1></center>
						
						<div id="search-bar form-group">
				<form method="post" action="">
					<input type="text" autofocus placeholder="Hi <?php echo $funame; ?>, Just start typing to search for food records" name="search-text" class="form-control text-center" onkeyup="showresult(this.value,'ser','txtHint');">
					<center><button  class="btn btn-info" id="btn-srch" type="submit" name="search-main"><i class="fa fa-search -fa-lg"></i> Search</button></center>
				</form>
				</div>

						<div>
						<br>

						<!-- displays search results table-->
						<div id="txtHint">
					
						</div>

						<table class="text-center table-responsive w3-table w3-bordered w3-striped w3-border">
						<tr class="w3-black text-center">
							<th>Image</th>
							<th>Title</th>
							<th>Description</th>
							<th>Category</th>
							<th>Price</th>
							<th colspan="2" class="text-center">Actions</th>
						</tr>
						<?php
							$qr="SELECT * from `foods` order by `id` desc limit 10";
							$rr=mysqli_query($conn, $qr);
							while ($row=mysqli_fetch_array($rr,MYSQLI_BOTH)) {
								$img=$row['image'];
								$title=$row['caption'];
								$description=$row['description'];
								$category=$row['category'];
								$price=$row['price'];
								echo "
									<tr>
										<td><img src='./images/photo/$img' class='table-thumb'></td>
										<td>$title</td>
										<td>$description</td>
										<td>$category</td>
										<td>$price</td>
										<td>edit</td>
										<td>delete</td>
									</tr>
								";
							}
						?>

						</table>

					</div>
				</div>
				</div>


				<a name="foodCategories"></a>
				<div class="back-div">
					<div class="front-div">
					<center> <h1 style="color:#ff530a; text-shadow:2px 2px grey;"><u>Our Food Categories</u></h1></center> <br>

					<table class="text-center table-responsive w3-table w3-bordered w3-striped w3-border">
						<tr class="w3-black text-center">
							<th>Title</th>
							<th colspan="2" class="text-center">Actions</th>
						</tr>
						<?php
						$sq1="SELECT * from `food_categories` order by `id` desc limit 10 ";
						$rr1=mysqli_query($conn,$sq1);
						while ($row=mysqli_fetch_array($rr1,MYSQLI_BOTH)) {
							$label=$row['label'];
							echo "
							<tr>
								<td>$label</td>
								<td>edit</td>
								<td>delete</td>

							</tr>
							";
						}

						?>
						</table>

					</div>
				</div>

				<a name="admins"></a>
				<div class="back-div">
					<div class="front-div">

						<center> <h1 style="color:#ff530a; text-shadow:2px 2px grey;"><u>Our List of Administrators</u></h1></center>
						
						<div id="search-bar form-group">
				<form method="post" action="">
					<input type="text" autofocus placeholder="Hi <?php echo $funame; ?>, Just start typing to search for administrator records" name="search-text" class="form-control text-center" onkeyup="showresult(this.value,'ser','txtHint2');">
					<center><button  class="btn btn-info" id="btn-srch" type="submit" name="search-main"><i class="fa fa-search -fa-lg"></i> Search</button></center>
				</form>
				</div>

						<div>
						<br>

						<!-- displays search results table-->
						<div id="txtHint2">
					
						</div>

						<table class="text-center table-responsive w3-table w3-bordered w3-striped w3-border">
						<tr class="w3-black text-center">
							<th>Name</th>
							<th>Email</th>
							<th>Role</th>
							<th colspan="2" class="text-center">Actions</th>
						</tr>
						<?php
							$qr="SELECT * from `users` order by `role` asc limit 10";
							$rr=mysqli_query($conn, $qr);
							while ($row=mysqli_fetch_array($rr,MYSQLI_BOTH)) {
								$nm=$row['name'];
								$em=$row['email'];
								$role=$row['role'];
								echo "
									<tr>
										<td>$nm</td>
										<td>$em</td>
										<td>$role</td>
										<td>edit</td>
										<td>delete</td>
									</tr>
								";
							}
						?>

						</table>

					</div>
				</div>
				</div>


				<a name="adminRoles"></a>
				<div class="back-div">
					<div class="front-div">
					<center> <h1 style="color:#ff530a; text-shadow:2px 2px grey;"><u>System Administrative Roles </u></h1></center> <br>

					<table class="text-center table-responsive w3-table w3-bordered w3-striped w3-border">
						<tr class="w3-black text-center">
							<th>Role</th>
							<th colspan="2" class="text-center">Actions</th>
						</tr>
						<?php
						$sq1="SELECT * from `admin_roles` order by `id` desc limit 10 ";
						$rr1=mysqli_query($conn,$sq1);
						while ($row=mysqli_fetch_array($rr1,MYSQLI_BOTH)) {
							$label=$row['label'];
							echo "
							<tr>
								<td>$label</td>
								<td>edit</td>
								<td>delete</td>

							</tr>
							";
						}

						?>
						</table>

					</div>
				</div>
			
			</div>	
			</div>
			
	

			</div>
		</div>
	</div>
	</section>



<?php
require "footer.php";
}
else
{
	header("location:index.php");
}


?>