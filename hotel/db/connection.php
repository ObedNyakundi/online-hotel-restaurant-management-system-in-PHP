<?php






//for OOP uses
$mysqli=new mysqli("localhost","root","","hotel");
//for imperative
$conn=mysqli_connect("localhost","root","","hotel");

//alert failure in connection
if(mysqli_connect_errno()){
	die("<script> alert (\" There was a fatal error connecting to the database\");</script>");
}
else
{
	
		/*****************************************************************
			Uncrack() is the most important and poweful function in this system. It:
				1. counters SQL injection
				2. formats data for SQL statements

			Without uncrack(), 95% of the makers system fails.
			 
			Before editing anything in this page, Kindly contact paulnyaxx@gmail.com.
		******************************************************************/
		function uncrack($data){
				$data=trim($data);
				$data= htmlspecialchars($data);
				$data=stripcslashes($data);

				//replace quotes and forward slashes for SQL
				$data=str_replace('"', '\\"', $data);
				$data=str_replace("'", "\\'", $data);

				return $data;
			};

		//format usernames
		function is_username($data){
			$data=uncrack($data);
			$data=strtolower($data);
			$data=ucwords($data);
			return $data;
		}

		//format emails
		function is_email($data)
		{
			$data=uncrack($data);
			$data=strtolower($data);
			return $data;
		}

		//not so important but is critical too for temporary passwords.
		function random_password() {
		    $alphabet = 'abcdefghijklmnopqrstuvwxyz.ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890@%&';
		    $pass = array(); //remember to declare $pass as an array
		    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		    for ($i = 0; $i < 8; $i++) {
		        $n = rand(0, $alphaLength);
		        $pass[] = $alphabet[$n];
		    }
		    return implode($pass); //turn the array into a string
		}

		function is_logged_in(){
			if (isset($_COOKIE["name"]) && isset($_COOKIE["em"]) && isset($_COOKIE["role"])) {
				//set global variables for logged users and return true
				global $uname, $umail, $type, $funame;

				$uname=$_COOKIE["name"];
				$umail=$_COOKIE["em"];
				$type=$_COOKIE["role"];
				$funame=substr($uname, 0,strpos($uname," "));//first name

				return true;
			}
			else
			{ 
				return false;
			}
		}

		function is_admin()
		{
			//only customers lack administrative powers
			if (is_logged_in() && $_COOKIE["role"]!="Customer"){
				return true;
			}
			else
			{
				return false;
			}
		}
		


}

?>