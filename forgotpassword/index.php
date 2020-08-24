<?php 

if($_SERVER["REQUEST_METHOD"] == "POST"){

	//getting email and username
	$again_email = $_POST["email_reenter"];
	$again_username = $_POST["username_reenter"];
	$again_password = $_POST["password_reenter"];
	$again_confpw = $_POST["confpw_reenter"];

	//seeing if email and username exist
	$userbase_file = fopen("../assets/userbase.txt", "r") or die("Unable to open file.");

	$all_info = array();
	while(($line = fgets($userbase_file))!== false){
		array_push($all_info, $line);
	}

	$all_usernames = array();
	$all_emails = array();
	foreach ($all_info as $key => $value) {
		$for_first_part = explode("=", $value);
		$username_check_for = $for_first_part[0];
		array_push($all_usernames, $username_check_for);
		$second_part = $for_first_part[1];
		$towards_email = explode(",", $second_part);
		array_push($all_emails, $towards_email[2]);
	}
	fclose($userbase_file);

	$err_mess = "";
	//validating data
	if(empty($again_email) or empty($again_username)){
		$err_mess = "*Username/Email is essential to reset password";
	}
	elseif(!in_array($again_email, $all_emails) or !in_array($again_username, $all_usernames)){
		if(!in_array($again_email, $all_emails) and in_array($again_username, $all_usernames)){
			$err_mess = "*Email not found (check for spaces or typos) <a href='../'>Create New Account</a>";
		}
		elseif(in_array($again_email, $all_emails) and !in_array($again_username, $all_usernames)){
			$err_mess = "*Username not found (check for spaces or typos) <a href='../'>Create New Account</a>";
		}
		else{
			$err_mess = "*Username and Email not found (check for spaces or typos) <a href='../'>Create New Account</a>";
		}
	}
	else{
		$username = $again_username;
		$email = $again_email;

		//password and confpw requirements
		if(empty($again_password) or empty($again_confpw)){
			$err_mess = "*Both password fields are required";
		}
		elseif(strlen($again_password)<8){
			$err_mess = "*Password needs to be at least 8 characters long";
		}
		elseif(preg_match('/\s/',$again_password)) {
			$err_mess = "*Password cannot have spaces";
		}
		elseif($again_confpw==$again_password){
			$err_mess = "Password successfully reset! <a href='../login/'>Log In</a>";
			$again_password = trim($again_password);
			$again_confpw = trim($again_confpw);

			$userbase_file = fopen("../assets/userbase.txt", "r") or die("Unable to open file.");

			$all_info = array();
			while(($line = fgets($userbase_file))!== false){
				$splitter = explode("=", $line);
				$first_username = $splitter[0];
				$second = $splitter[1];
				$difference = explode("---", $second);
				$second_one = $difference[0];
				$second_two = $difference[1];

				$second_splitter = explode(",", $second_one);
				$second_email = $second_splitter[2];
				if($username==$first_username and $email==$second_email){
					//retreiving and formatting contacts of new line in same place
					$line = $first_username."=".$second_splitter[0].",".$second_splitter[1].",".$second_splitter[2].",".$second_splitter[3].",".$second_splitter[4].",".$again_password.",".$again_confpw."---".$second_two;
				}
				array_push($all_info, $line);
			}
			fclose($userbase_file);

			$file_userbase = fopen("../assets/userbase.txt", "w") or die("Unable to open file.");
			//adding each element of array as line in text file
			foreach ($all_info as $key => $value) {
				fwrite($file_userbase, $value);
			}
			fclose($file_userbase);


		}
		else{
			$err_mess = "*Confirmed password needs to be the same";
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
		<title>The Job Scale | Forgot Password</title>
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
			.for_forgot{
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
			.for_forgot span{
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
			.err_mess{
				font-size: 14px;
			}
			.err_mess a{
				color: salmon;
				border-bottom: 2px solid black;
				font-weight: bold;
				font-size: 16px;
			}
			.err_mess a:hover{
				border-bottom: 4px solid black;
			}
			.textbox_passwords{
				padding-left: 5px;
				height: 30px;
				border-radius: 7%;
				width: 57%;
				background-color: black;
				color: white;
				margin-top: 5px;
				margin-bottom: 5px;
			}
			.textbox_passwords::placeholder{
				color: white;
			}
			.textbox_passwords:hover{
				background-color: gainsboro;
				color: black;
			}
			.textbox_passwords:hover::placeholder{
				color: black;
			}
			.textbox_passwords:focus{
				background-color: black;
				color: white;
				border: 2px solid white;
			}
			.textbox_passwords:focus::placeholder{
				color: white;
			}
			.button{
				padding: 5px;
				letter-spacing: 0.3px;
				border-radius: 7%;
				background-color: white;
			}
			.button:hover{
				background-color: black;
				color: white;
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
				<a href="../login/"><div>Log In</div></a>
			</div>
		</div>
		<div class="for_forgot"><span>Forgot Password</span></div>
		<hr class="between">
		<br><br><br>
		<div class="main">
			<div class="container">
				<div class="wrapper">
					<div class="email_enter">
						<form class="email_for_reset" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
							<div class="container">
								<span class="err_mess"><?php echo $err_mess?></span>
							</div>
							<br>
							<div class="container">
								<input type="text" name="email_reenter" value="<?php if(isset($_POST["email_reenter"])) {echo $_POST["email_reenter"];} ?>" placeholder="<?php if(!isset($_POST["email_reenter"])) {echo "Enter Email";} ?>" class="textbox_passwords">
							</div>
							<div class="container">
								<input type="text" name="username_reenter" value="<?php if(isset($_POST["username_reenter"])) {echo $_POST["username_reenter"];} ?>" placeholder="<?php if(!isset($_POST["username_reenter"])) {echo "Enter Username";} ?>" class="textbox_passwords">
							</div>
							<div class="container">
								<input type="password" name="password_reenter" value="<?php if(isset($_POST["password_reenter"])) {echo $_POST["password_reenter"];} ?>" placeholder="<?php if(!isset($_POST["password_reenter"])) {echo "Enter New Password (Min. 8 Chars)";} ?>" class="textbox_passwords">
							</div>
							<div class="container">
								<input type="password" name="confpw_reenter" value="<?php if(isset($_POST["confpw_reenter"])) {echo $_POST["confpw_reenter"];} ?>" placeholder="<?php if(!isset($_POST["confpw_reenter"])) {echo "Confirm New Password";} ?>" class="textbox_passwords">
							</div>
							<br>
							<div class="container">
								<input type="submit" name="button" value="Reset Password" class="button">
							</div>
						</form>
					</div>
				</div>
			</div>
			<br><br><br><br>
			<div class="footer">
				<div class="footer_links">
					<span class="about"><a href="../about/">About</a></span>
					<span class="contact"><a href="../contact/">Contact Us</a></span>
					<span class="privacy"><a href="../privacypolicy/">Privacy</a></span>
					<span class="terms"><a href="../termsofcontract/">Terms</a></span>
				</div>
				<div class="copyright">
					&copy; The Job Scale, <?php echo date("Y");?> | Job data taken from My Job Search
				</div>
			</div>
		</div>
	</body>
</html>