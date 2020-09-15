<?php 
session_start();

if(!isset($_SESSION["dashboard_username"])){

	header("location: ../login/");
	echo '<meta http-equiv="refresh" content="0;url=http://www.google.com/" />';
}
elseif(!isset($_SESSION["job_list"])){

	header("location: ../dashboard/");
	echo '<meta http-equiv="refresh" content="0;url=http://www.google.com/" />';
}
//defining username
$username = $_SESSION["dashboard_username"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//naming user data
	$first_name = trim($_POST["first_name"]);
	$last_name = trim($_POST["last_name"]);
	$city = trim($_POST["city"]);
	$state = trim($_POST["state"]);

	//handling formatting errors
	$err_mess = "";
	$state_list = array("al", "ak", "az", "ar", "ca", "co", "ct", "de", "fl", "ga", "hi", "id", "il", "in", "ia", "ks", "ky", "la", "me", "md", "ma", "mi", "mn", "ms", "mo", "mt", "ne", "nv", "nh", "nj", "nm", "ny", "nc", "nd", "oh", "ok", "or", "pa", "ri", "sc", "sd", "tn", "tx", "ut", "vt", "va", "wa", "wv", "wi", "wy");
	$lower_state = strtolower($state);
	if(empty($first_name) or empty($last_name) or empty($city) or empty($state)){
		$err_mess = "*All fields are required";
	}
	elseif(!in_array($lower_state, $state_list)){
		$err_mess = "*State abbreviation needs to be correct";
	}
	else{
		$err_mess = "Data successfully reset! <a href='../dashboard/'>Go To Dashboard</a>";

		$userbase_file = fopen("hidden", "r") or die("Unable to open file.");

		$all_info = array();
		while(($line = fgets($userbase_file))!== false){
			$splitter = explode("=", $line);
			$first_username = $splitter[0];
			$second = $splitter[1];
			$difference = explode("---", $second);
			$second_one = $difference[0];
			$second_two = $difference[1];

			$second_splitter = explode(",", $second_one);
			if($username==$first_username){
				//retreiving and formatting contacts of new line in same place
				$line = $first_username."={".ucfirst($first_name).",".ucfirst($last_name).",".$second_splitter[2].",".ucfirst($city).",".strtoupper($state).",".$second_splitter[5].",".$second_splitter[6]."---".$second_two;
			}
			array_push($all_info, $line);
		}
		fclose($userbase_file);

		$file_userbase = fopen("hidden", "w") or die("Unable to open file.");
		//adding each element of array as line in text file
		foreach ($all_info as $key => $value) {
			fwrite($file_userbase, $value);
		}
		fclose($file_userbase);
	}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="shortcut icon" type="image/x-icon" href="http://www.thejobscale.com/favicon.ico">
		<link rel="icon" type="image/x-icon" href="http://www.thejobscale.com/favicon.ico">
		<title>The Job Scale | Change Information</title>
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
				<a href="../dashboard/"><div>Dashboard</div></a>
			</div>
		</div>
		<div class="for_forgot"><span>Change Information</span></div>
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
								<input type="text" name="first_name" class="textbox_passwords" value="<?php if(isset($_POST["first_name"])) {echo $_POST["first_name"];} ?>" placeholder="<?php if(!isset($_POST["first_name"])) {echo "First Name";} ?>">
							</div>
							<div class="container">
								<input type="text" name="last_name" class="textbox_passwords" value="<?php if(isset($_POST["last_name"])) {echo $_POST["last_name"];} ?>" placeholder="<?php if(!isset($_POST["last_name"])) {echo "Last Name";} ?>">
							</div>
							<div class="container">
								<input type="text" name="city" class="textbox_passwords" value="<?php if(isset($_POST["city"])) {echo $_POST["city"];} ?>" placeholder="<?php if(!isset($_POST["city"])) {echo "City of Residence";} ?>">
							</div>
							<div class="container">
								<input type="text" name="state" class="textbox_passwords" value="<?php if(isset($_POST["state"])) {echo $_POST["state"];} ?>" placeholder="<?php if(!isset($_POST["state"])) {echo "State of Residence (Ex: CA)";} ?>">
							</div>
							<br>
							<div class="container">
								<input type="submit" name="button" value="Reset Information" class="button">
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
