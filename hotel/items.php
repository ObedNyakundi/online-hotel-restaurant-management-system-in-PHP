<?php
require "db/connection.php";



if (is_logged_in()) {
require "header1.php";
}
else
{
require "header0.php";
}
?>
 <section height="auto" id="about-us">
	<div class="container works clearfix">
		<div class="row">

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
                        <h1 class="heading"><span>Top Foods</span> from our <span>Restaurant</span></h1>
                        <ul>
                        <?php

                        $sq4="SELECT * from `foods` order by 'category' desc";
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
                      
                    </div>
                </div><!-- .col-md-12 close -->
            </div><!-- .row close -->
        </div><!-- .containe close -->
    </section><!-- #blog close -->


<?php

require "footer.php";

?>