<?php

$error=" ";

require "db/connection.php";


if (isset($_POST["actLogin"])) {
		//collect data
		$uEmail=is_email($_POST["uEmail"]);
		$passwd=uncrack($_POST["passwd"]);

		$sqst="select * from `users` where `email`='$uEmail' and `password`='$passwd'";
		$queryresult=mysqli_query($conn,$sqst);
		$arr=mysqli_fetch_array(mysqli_query($conn,$sqst), MYSQLI_BOTH);

		if (mysqli_num_rows($queryresult) == 1) {
				
			//True if the member exists. false otherwise.
			$em=$arr["email"];
			$uname=$arr["name"];
			$type=$arr["role"]; 

			//set cookies to remember the user for 30 days
			setcookie("name",$uname,time()+86400*30,"/","",0);
			setcookie("em",$em,time()+86400*30,"/","",0);
			setcookie("role",$type,time()+86400*30,"/","",0);
			//direct user to homepage.
			
			/* I will use them when handing controls*/
			
			if ($type!='Customer'){
				header("location:adminHome.php");
			}
			else
			{
			header("location:index.php");
			}

			//header("location:home.php");
			}else{
				$error="<div class='w3-container alert text-center alert-slim alert-danger fade in'> <strong>The log in details are invalid</strong> <br> 
				The login details you supplied are invalid. Please try again. <br>
			<a href=\"home.php\" class=\"close\" data-dismiss=\"alert\" aria-label=\"close\"> &times;</a>
			</div>";
			}
	}

if (is_logged_in()) {
require "header1.php";
}
else
{
require "header0.php";
}
?>

<!--
    Slider start
    ============================== -->
    <section id="slider">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="block wow fadeInUp" data-wow-duration="500ms" data-wow-delay="300ms">
                        <div class="title">
                            <h3>Featured <span>Meals</span></h3>
                        </div>
                        <div id="owl-example" class="owl-carousel">
                            <div>
                                <img class="img-responsive" src="images/slider/slider-img-1.jpg" alt="">
                            </div>
                            <div>
                                <img class="img-responsive" src="images/slider/slider-img-2.jpg" alt="">
                            </div>
                            <div>
                                <img class="img-responsive" src="images/slider/slider-img-3.jpg" alt="">
                            </div>
                            <div>
                                <img class="img-responsive" src="images/slider/slider-img-4.jpg" alt="">
                            </div>
                            <div>
                                <img class="img-responsive" src="images/slider/slider-img-1.jpg" alt="">
                            </div>
                            <div>
                                <img class="img-responsive" src="images/slider/slider-img-2.jpg" alt="">
                            </div>
                            <div>
                                <img class="img-responsive" src="images/slider/slider-img-3.jpg" alt="">
                            </div>
                            <div>
                                <img class="img-responsive" src="images/slider/slider-img-4.jpg" alt="">
                            </div>
                        
                        </div>
                    </div>
                </div><!-- .col-md-12 close -->
            </div><!-- .row close -->
        </div><!-- .container close -->
    </section><!-- slider close -->



    <section height="auto" id="about-us">
	<div class="container works clearfix">
		<div class="row">

		<?php 
			echo $error;
		?>
		<div class="frm-login">
			
		<?php

		/*display a login form if the user is not logged in, otherwise, offer him/her the option of a gift card or a table reservation. 
		*/

		if (!is_logged_in())
		{
			echo"
			<!-- Form for login-->
				<div class=\"text-center w3-card-8 ordinary-form\" style=\"\" id=\"frmLogin\">

					<br><h1 class=\"frm-text\">Login </h1><br>	

				<center><img class=\"wow fadeInUp pull-center img-responsive\" data-wow-duration=\"300ms\" data-wow-delay=\"400ms\" src=\"images/cooker-img.png\" alt=\"cooker-img\"></center>

										
					
					<form method=\"POST\" action=\"index.php\">
						<div class=\"frm-content\">
								<div class=\"form-group wow fadeInDown\" data-wow-duration=\"100ms\" data-wow-delay=\"200ms\">
								<label for=\"name\">Email: *</label><br>
								<input type=\"email\" required id=\"name\" name=\"uEmail\" class=\" text-center form-control\" placeholder=\"Email. e.g. example@gmail.com\">
								</div>

								<div class=\"form-group wow fadeInDown\" data-wow-duration=\"300ms\" data-wow-delay=\"400ms\">
								<label for=\"name\">Password: *</label>
								<input type=\"password\" required id=\"name\" name=\"passwd\" class=\" text-center form-control\" placeholder=\"Type your password\">
								</div>
						</div>

						<div class=\"frm-controls\">
								<input type=\"submit\"  name=\"actLogin\" class=\"btn btn-info pull-center\" value=\"Let me in...\">
								<br>
								<h3> <a class=\"frm-text\" href=\"\">Forgot Password</a></h3>
						</div>
							</form>
				</div>
			</div>";
		}

			?>

			<h1 class="heading">A <span>Gift-Card</span> Or A <span> Table</span>? </h1>

			<br><h1 class="frm-text">Buy a Gift card </h1><br>
		
			
		</div>
	</div>
	</section>


<!--
    blog start
    ============================ -->
    <section id="blog">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="block">
                        <h1 class="heading">Selection of <span>Top Foods</span> from our <span>Restaurant</span></h1>
                        <ul>
                        <?php

                        $sq4="SELECT * from `foods` order by 'category' desc limit 6 ";
                        $rr4=mysqli_query($conn,$sq4);

                        $dwow=300; 								//wow delay

                        while ($row=mysqli_fetch_array($rr4,MYSQLI_BOTH)) {
                        	$img=$row['image'];
							$title=$row['caption'];
							$description=$row['description'];
							$category=$row['category'];
							$price=$row['price'];
							$id=$row['id'];

							echo "
							<li class=\"wow fadeInLeft\" data-wow-duration=\"300ms\" data-wow-delay=\"$dwow\">

							<a href='items.php?id=$id'>
                                <div class=\"blog-img\">
                                    <img src=\"images/photo/$img\" alt=\"$title-img\">
                                </div>
                                <div class=\"content-right\">
                                    <h3>$title</h3>
                                    <p>$description and costs just KES. $price per unit</p>
                                </div>
                               </a>
                            </li>
							";

							$dwow+=100;
                        }

                        ?>

                        </ul>
                        <a class="btn btn-default btn-more-info wow bounceIn" data-wow-duration="500ms" data-wow-delay="1200ms" href="#" role="button">More Info</a>
                    </div>
                </div><!-- .col-md-12 close -->
            </div><!-- .row close -->
        </div><!-- .containe close -->
    </section><!-- #blog close -->

<?php
require "footer.php";


?>