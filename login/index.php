<?php 
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST"){

	//getting user data
	$a_username = $_POST["username"];
	$a_password = $_POST["password"];

	$err_mess = "";

	//obtaining list of all usernames
	$userbase_file = fopen("hidden", "r") or die("Unable to open file.");

	$all_info = array();
	while(($line = fgets($userbase_file))!== false){
		array_push($all_info, $line);
	}

	$all_passwords = array();
	$all_usernames = array();
	foreach ($all_info as $key => $value) {
		$for_first_part = explode("=", $value);
		$username_check_for = $for_first_part[0];
		$second_part = $for_first_part[1];
		$towards_password = explode(",", $second_part);
		array_push($all_passwords, $towards_password[5]);

		array_push($all_usernames, $username_check_for);
	}
	fclose($userbase_file);
	//validating data
	if(empty($a_username) or empty($a_password)){
		$err_mess = "*Username and Password fields are required";
	}
	//checking username
	elseif(!in_array(trim($a_username), $all_usernames)){
		$err_mess = "*Username not recognized";
	}
	//checking password
	else{
		$username_pos = array_search(trim($a_username), $all_usernames);
		if (in_array(trim($a_password), $all_passwords) and array_search(trim($a_password), $all_passwords)==$username_pos) {
			$err_mess = "";

			$_SESSION["dashboard_username"] = $a_username;

			//redirecting since no errors
			header("location: ../dashboard/");

			echo '<meta http-equiv="refresh" content="0;url=http://www.google.com/" />';
		}
		else{
			$err_mess = "*Your Password is incorrect";
		}
	}

}


?>





<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="shortcut icon" type="image/x-icon" href="http://www.thejobscale.com/favicon.ico">
		<link rel="icon" type="image/x-icon" href="http://www.thejobscale.com/favicon.ico">
		<title>The Job Scale | Login</title>
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@300&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital@1&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Mukta:wght@500&display=swap" rel="stylesheet">
		<style type="text/css">
			*{
				margin: 0;
				padding: 0;
				text-decoration: none;
				list-style: none;
			}
			.header{
				position: relative;
				height: 110px;
				width: 100%;
				background-color: black;
			}
			.title{
				float: left;
				color: salmon;
			}
			.the{
				-moz-user-select: none;
				-webkit-user-select: none;
				position: relative;
				left: 50px;
				top:5px;
				font-family: 'Open Sans Condensed', sans-serif;
				letter-spacing: 3px;
			}
			.skill{
				-moz-user-select: none;
				-webkit-user-select: none;
				position: relative;
				left: 70px;
				top: 40px;
				font-family: 'Indie Flower', cursive;
				letter-spacing: 3px;
				font-size: 35px;
			}
			.scale{
				-moz-user-select: none;
				-webkit-user-select: none;
				position: relative;
				top: 10px;
				left: 90px;
				font-family: arial;
				font-weight: bold;
				font-size: 50px;
			}
			.motto{
				position: relative;
				float: right;
				color: salmon;
				right: 250px;
				font-family: 'Montserrat', sans-serif;
			}
			.quote{
				-moz-user-select: none;
				-webkit-user-select: none;
				position: relative;
				top: 25px;
				font-size: 20px;
			}
			.author{
				-moz-user-select: none;
				-webkit-user-select: none;
				position: relative;
				top: 50px;
				text-align: center;
			}
			.link_about{
				color: black;
				background-color: salmon;
				float: right;
				position: relative;
				left: 660px;
				top: 45px;
				border-radius: 10%;
				padding: 5px;
			}
			.link_about:hover{
				background-color: gainsboro;
			}
			.link_about a div{
				color: black;
				font-family: arial;
				letter-spacing: 0.5px;
			}
			.for_login{
				padding-top: 40px;
				padding-bottom: 40px;
				width: 100%;
				height: 20px;
				background-color: salmon;
				font-family: 'Open Sans Condensed', sans-serif;
				letter-spacing: 2px;
				font-size: 65px;
				text-align: center;
			}
			.for_login span{
				position: relative;
				bottom: 40px;
			}
			.between{
				background-color: black;
				height: 20px;
			}
			.main h2{
				text-align: center;
				font-family: arial;
				font-size: 50px;
			}
			.container{
				width: 100%;
				display: flex;
				justify-content: center;
				align-items: center;
			}
			.wrapper{
				width: 28%;
				justify-content: center;
				font-family: 'Montserrat', sans-serif;
				background-color: lightblue;
				border-radius: 10%;
				border: 4px solid salmon;
				padding: 30px;
			}
			.new_user{
				font-size: 18px;
			}
			.new_user a{
				color: salmon;
				border-bottom: 1px solid black;
				font-weight: bold;
			}
			.new_user a:hover{
				color: salmon;
				border-bottom: 2px solid salmon;
			}
			.data_reentry{
				margin-top: 30px;
			}
			.the_error{
				font-size: 15px;
				color: navy;
				text-align: center;
			}
			.user_info{
				margin-top: 10px;
			}
			.log_text{
				margin-bottom: 10px;
				height: 25px;
				background-color: salmon;
				padding-left: 10px;
				border: 2px solid black;
				border-radius: 5%;
				color: black;
			}
			#password{
				margin-top: 10px;
			}
			.log_text::placeholder{
				color: black;
				opacity: 1;
			}
			.log_text:hover{
				border: 3px solid salmon;
				background-color: black;
			}
			.log_text:hover::placeholder{
				color: white;
			}
			.log_text:hover{
				color: white;
			}
			.log_text:focus{
				padding-left: 5px;
				color: white;
			}
			.submit{
				margin-top: 20px;
			}
			.submit input{
				padding: 6px;
				background-color: salmon;
				border-radius: 10%;	
			}
			.submit input:hover{
				background-color: white;
				border: 2px solid salmon;
			}
			.password_forgot{
				margin-top: 15px;
				font-size: 14px;
				border-bottom: 2px solid salmon;
			}
			.password_forgot a{
				color: black;
			}
			.password_forgot a:hover{
				font-weight: bold;
				border-bottom: 4px solid salmon;
			}
			.footer{
				position: relative;
				width: 100%;
				height: 70px;
				background-color: black;
				padding-top: 20px;
				padding-bottom: 20px;
			}
			.footer_links{
				text-align: center;
			}
			.footer_links span a{
				color: salmon;
				border-bottom: 1px solid lightblue;
			}
			.footer_links span a:hover{
				color: lightblue;
				border-bottom: 1px solid salmon;
			}
			.footer_links span{
				margin: 10px;
			}
			.copyright{
				position: relative;
				-moz-user-select: none;
				-webkit-user-select: none;
				color: salmon;
				top: 50px;
				text-align: center;
			}
		</style>
	</head>
	<body>
		<div class="header">
			<div class="title">
				<span class="the">the</span>
				<span class="skill">Job</span>
				<span class="scale">Scale</span>
			</div>
			<div class="motto">
				<div class="quote"><i>"Choose a job you love, and you will never have to work a day in your life."</i></div>
				<div class="author"><i>- Confucius (Chinese Philosopher)</i></div>
			</div>
			<div class="link_about">
				<a href="../"><div>Home</div></a>
			</div>
		</div>
		<div class="for_login"><span>Login</span></div>
		<hr class="between">
		<br><br>
		<div class="main">
			<div class="container">
				<div class="wrapper">
					<span class="new_user">New to The Job Scale? <a href="../">Create Account</a></span>
					<div class="data_reentry">
						<form class="login_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
							<div class="container">
								<span class="the_error"><?php echo $err_mess; ?></span>
							</div>
							<div class="container">
								<div class="user_info" id="username">
									<input type="text" name="username" value="<?php if(isset($_POST["username"])) {echo $_POST["username"];} ?>" placeholder="<?php if(!isset($_POST["username"])) {echo "Username";} ?>" class="log_text">
								</div>
							</div>
							<div class="container">
								<div class="user_info" id="password">
									<input type="password" name="password" value="<?php if(isset($_POST["password"])) {echo $_POST["password"];} ?>" placeholder="<?php if(!isset($_POST["password"])) {echo "Password";} ?>" class="log_text">
								</div>
							</div>
							<div class="container">
								<div class="submit">
									<input type="submit" name="submit" id="button" value="Log In">
								</div>
							</div>
						</form>
					</div>
					<br>
					<div class="container">
						<span class="password_forgot"><a href="../forgotpassword/">Forgot Password?</a></span>
					</div>
				</div>
			</div>
			<br><br><br>
			<div class="footer">
				<div class="footer_links">
					<span class="about"><a href="../about/">About</a></span>
					<span class="contact"><a href="../contact/">Contact Us</a></span>
					<span class="privacy"><a href="../privacypolicy/">Privacy</a></span>
					<span class="terms"><a href="../termsofcontract/">Terms</a></span>
					<span class="delete"><a href="../deleteaccount/">Delete Account</a></span>
				</div>
				<div class="copyright">
					&copy; The Job Scale, <?php echo date("Y");?> | Job data taken from My Job Search
				</div>
			</div>
		</div>
	</body>
</html>
