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

			$again_password = trim($again_password);
			$again_confpw = trim($again_confpw);

			$userbase_file = fopen("hidden", "r") or die("Unable to open file.");

			$all_info = array();
			while(($line = fgets($userbase_file))!== false){
				$splitter = explode("=", $line);
				$first_username = $splitter[0];

				$a_username = trim($a_username);
				if($a_username==$first_username){
					//retreiving and formatting contacts of new line in same place
					$line = "delete";
				}
				array_push($all_info, $line);
			}
			fclose($userbase_file);

			$file_userbase = fopen("hidden", "w") or die("Unable to open file.");
			//adding each element of array as line in text file
			foreach ($all_info as $key => $value) {
				if($value=="delete"){
					continue;
				}
				fwrite($file_userbase, $value);
			}
			fclose($file_userbase);

			$a_userbase_file = fopen("hidden", "r") or die("Unable to open file.");

			$all_info_2 = array();
			while(($line = fgets($a_userbase_file))!== false){
				$splitter = explode("=", $line);
				$first_username = $splitter[0];

				$a_username = trim($a_username);
				if($a_username==$first_username){
					//retreiving and formatting contacts of new line in same place
					$line = "delete";
				}
				array_push($all_info_2, $line);
			}
			fclose($a_userbase_file);

			$a_file_userbase = fopen("hidden", "w") or die("Unable to open file.");
			//adding each element of array as line in text file
			foreach ($all_info_2 as $key => $value) {
				if($value=="delete"){
					continue;
				}
				fwrite($a_file_userbase, $value);
			}
			fclose($a_file_userbase);

			session_destroy();

			//redirecting since no errors
			header("location: ../");

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
		<title>The Job Scale | Delete Account</title>
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
			.textbox_passwords{
				padding-left: 5px;
				height: 30px;
				border-radius: 7%;
				width: 55%;
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
		<div class="for_forgot"><span>Delete Account</span></div>
		<hr class="between">
		<br><br><br>
		<div class="main">
			<div class="container">
				<div class="wrapper">
					<div class="email_enter">
						<form class="email_for_reset" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
							<div class="container">
								<span class="err_mess"><?php echo $err_mess; ?></span>
							</div>
							<br>
							<div class="container">
								<input type="text" name="username" value="<?php if(isset($_POST["username"])) {echo $_POST["username"];} ?>" placeholder="<?php if(!isset($_POST["username"])) {echo "Username";} ?>" class="textbox_passwords">
							</div>
							<div class="container">
								<input type="password" name="password" value="<?php if(isset($_POST["password"])) {echo $_POST["password"];} ?>" placeholder="<?php if(!isset($_POST["password"])) {echo "Password";} ?>" class="textbox_passwords">
							</div>
							<br>
							<div class="container">
								<input type="submit" name="button" value="Delete Account" class="button">
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
