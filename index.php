<?php 
session_start();

if(isset($_SESSION["dashboard_username"])){

	header("location: dashboard/");
	echo '<meta http-equiv="refresh" content="0;url=http://www.google.com/" />';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//naming the user data
	$all_elements = $_POST;
	$first_name = $all_elements["first_name"];
	$last_name = $all_elements["last_name"];
	$email = $all_elements["email"];
	$city = $all_elements["city"];
	$state = $all_elements["state"];
	$username = $all_elements["username"];
	$password = $all_elements["password"];
	$confpw = $all_elements["confpw"];

	//user data error handling
	$first_name_err = $last_name_err = $email_err = $city_err = $state_err = $username_err = $password_err =  $confpw_err = "";

	$first_name = trim($first_name);
	if (empty($first_name)){
		$first_name_err = "*First name is required";
	}
	else{
		$first_name_err = "";
		$first_name = ucfirst(trim($first_name));
	}

	$last_name = trim($last_name);
	if (empty($last_name)){
		$last_name_err = "*Last name is required";
	}
	else{
		$last_name_err = "";
		$last_name = ucfirst(trim($last_name));
	}

	if(empty($email)){
		$email_err = "*Email is required";
	}
	elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$email_err = "*Invalid email format";
	}
	else{
		$email_err = "";
	}

	$city = trim($city);
	if(empty($city)){
		$city_err = "*City of Residence is required";
	}
	else{
		$city_err = "";
		$city = ucfirst($city);
	}

	$state = trim($state);
	$state_list = array("al", "ak", "az", "ar", "ca", "co", "ct", "de", "fl", "ga", "hi", "id", "il", "in", "ia", "ks", "ky", "la", "me", "md", "ma", "mi", "mn", "ms", "mo", "mt", "ne", "nv", "nh", "nj", "nm", "ny", "nc", "nd", "oh", "ok", "or", "pa", "ri", "sc", "sd", "tn", "tx", "ut", "vt", "va", "wa", "wv", "wi", "wy");
	$lower_state = strtolower($state);

	if(empty($state)){
		$state_err = "*State of Residence is required";
	}
	elseif (!in_array($lower_state, $state_list)) {
		$state_err = "*State abbreviation needs to be correct";
	}
	else{
		$state_err = "";
		$state = strtoupper($state);
	}

	$userbase_file = fopen("assets/userbase.txt", "r") or die("Unable to open file.");

	$all_info = array();
	while(($line = fgets($userbase_file))!== false){
		array_push($all_info, $line);
	}

	$all_usernames = array();
	foreach ($all_info as $key => $value) {
		$for_first_part = explode("=", $value);
		$username_check_for = $for_first_part[0];
		array_push($all_usernames, $username_check_for);
	}
	fclose($userbase_file);

	if (empty($username)){
		$username_err = "*Username is required";
	}
	elseif(preg_match('/\s/',$username)){
		$username_err = "*Username cannot have spaces";
	}
	elseif (in_array(trim($username), $all_usernames)) {
		$username_err = "*Username not available";
	}
	else{
		$username_err = "";
		$username = trim($username);
	}

	if(empty($password)){
		$password_err = "*Password is required";
	}
	elseif(strlen($password)<8){
		$password_err = "*Password needs to be at least 8 characters long";
	}
	elseif (preg_match('/\s/',$password)) {
		$password_err = "*Password cannot have spaces";
	}
	else{
		$password_err = "";
		$password = trim($password);
	}

	if(empty($confpw)){
		$confpw_err = "*Confirming Password is required";
	}
	elseif($confpw==$password){
		$confpw_err = "";
		$confpw = trim($confpw);
	}
	else{
		$confpw_err = "*Confirmed password needs to be the same";
	}


	//taking user data out of array
	array_splice($all_elements, 0, 8);

	//appending skills to new list
	$skills = array();
	foreach ($all_elements as $key => $value) {
		array_push($skills, $value);
	}

	$main_skills = $skills[0];
	$main_skills = array_values($main_skills);

	//skills data error handling
	$skill_list_err = "";
	if (count($main_skills)>3){
		$skill_list_err = "";
	}
	else{
		$skill_list_err = "*Try to select at least 4 skills";
	}

	//adding data to text file, based on condition
	if ($first_name_err == "" and $last_name_err== "" and $email_err=="" and $city_err=="" and $state_err=="" and $username_err=="" and $password_err=="" and $confpw_err=="" and $skill_list_err=="") {
		
		$user_file = fopen("assets/userbase.txt", "a") or die("Not opening file.");
		$user_info = "\n".$username."={".$first_name.",".$last_name.",".$email.",".$city.",".$state.",".$password.",".$confpw."---";

		fwrite($user_file, $user_info);

		foreach ($main_skills as $key => $value) {
			fwrite($user_file, $value.",");
		}

		fwrite($user_file, "}");

		fclose($user_file);

		header("location: login/");

		echo '<meta http-equiv="refresh" content="0;url=http://www.google.com/" />';
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="shortcut icon" type="image/x-icon" href="http://www.thejobscale.com/favicon.ico">
		<link rel="icon" type="image/x-icon" href="http://www.thejobscale.com/favicon.ico">
		<title>The Job Scale | Home</title>
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@300&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital@1&display=swap" rel="stylesheet">
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
				background-color: lightblue;
			}
			.link_about a div{
				color: black;
				font-family: arial;
				letter-spacing: 0.5px;
			}
			.for_perfect{
				padding-top: 40px;
				padding-bottom: 100px;
				width: 100%;
				height: 180px;
				background-color: lightblue;
				font-family: 'Open Sans Condensed', sans-serif;
				letter-spacing: 1px;
				font-size: 90px;
			}
			.for_perfect span{
				position: relative;
				left: 40px;
			}
			.main{
				position: relative;
				width: 100%;
			}
			.aside{
				background-color: black;
				color: salmon;
				padding: 10px;
			}
			.top, .top2{
				text-align: center;
				width: 100%;
				display: flex;
				align-items: center;
				justify-content: center;
			}
			.top h2{
				font-size: 25px;
				font-family: 'Mukta', sans-serif;
			}
			.top h2 a{
				color: lightblue;
				border-bottom: 1px solid lightblue;
			}
			.top h2 a:hover{
				border-bottom: 3px solid salmon;
			}
			.container{
				width: 100%;
				display: flex;
				justify-content: center;
				align-items: center;
			}
			.checklist{
				background-color: salmon;
				padding: 15px;
				padding-top: 5px;
				padding-bottom: 10px;
				border: 2px solid black;
				width: 600px;
				border-radius: 3%;
				font-family: 'Montserrat', sans-serif;
				justify-content: center;
			}
			.survey_title{
				font-size: 40px;
				text-align: center;
				font-family: arial;
			}
			.checklist_title{
				text-align: center;
			}
			.checklist_textbox{
				margin: 15px;
			}
			.beg_text{
				border: 2px solid black;
				background-color: lightblue;
				height: 25px;
				border-radius: 5%;
				padding-left: 10px;
			}
			.beg_text::placeholder{
				color: black;
				opacity: 1;
			}
			.beg_text:hover{
				border: 3px solid black;
				background-color: white;
			}
			.beg_text:focus{
				background-color: black;
				border: 4px solid lightblue;
				color: white;
			}
			.beg_text:focus::placeholder{
				color: white;
			}
			.actual_checks{
				position: relative;
				font-family: 'Indie Flower', cursive;
				font-size: 20px;
				margin: 10px;
				padding: 10px;
				left: 180px;
			}
			.checklist_textbox{
				justify-content: center;
				margin: 7px;
			}
			.error{
				font-size: 15px;
				font-family: arial;
				color: navy;
				margin-bottom: 5px;
				text-align: center;
			}
			.skill_error{
				position: relative;
				color: navy;
				top: 15px;
				font-family: arial;
				font-size: 15px;
			}
			#submit1{
				position: relative;
				top: 25px;
				left: 55px;
			}
			#button{
				background-color: black;
				padding: 10px;
				color: white;
				border-radius: 10%;
				margin-bottom: 20px;
			}
			#button:hover{
				border: 2px solid black;
				background-color: white;
				color: black;
			}
			.already_account{
				text-align: center;
				border: 2px solid salmon;
				width: 27%;
				border-radius: 5%;
				font-size: 20px;
				justify-content: center;
				padding: 5px;
				color: black;
				font-family: 'Montserrat', sans-serif;
			}
			.already_account a{
				color: lightblue;
				font-weight: bold;
				border-bottom: 1px solid black;
				border-top: 1px solid black;
			}
			.already_account a:hover{
				border-bottom: 2px solid black;
				border-top: 2px solid black;
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
				<div class="quote">"Choose a job you love, and you will never have to work a day in your life."</div>
				<div class="author">- Confucius (Chinese Philosopher)</div>
			</div>
			<div class="link_about">
				<a href="login/"><div>Log In</div></a>
			</div>
		</div>
		<div class="for_perfect"><span>Have Little/No Idea What You Wanna Be? Find the Perfect Job For You!!</span></div>
		<div class="main">
			<div class="aside">
				<br><br>
				<div class="top">
					<h2>We provide you with job titles along with job opportunities near you based on the skills you possess. <a href="ourgoal/">Learn more.</a></h2>
				</div>
				<br><br>
			</div>
			<br><br>
			<div class="survey_title">
				<span>Register For Free!!</span>
			</div>
			<br>
			<div class="container">
				<div class="checklist">
					<br>
					<div class="checklist_title">Enter your personal information and select the skills you have fun using or are good at (minimum 4 skills — it takes a minute!):</div>
					<br>
					<form class="check_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
						<div class="container">
							<div class="checklist_textbox" id="for_first_name">
								<div class="error"><?php echo $first_name_err?></div>
								<div class="container">
									<input type="text" name="first_name" value="<?php if(isset($_POST["first_name"])) {echo $_POST["first_name"];} ?>" placeholder="<?php if(!isset($_POST["first_name"])) {echo "First Name";} ?>" class="beg_text">
								</div>
							</div>
						</div>
						<div class="container">
							<div class="checklist_textbox" id="for_last_name">
								<div class="error"><?php echo $last_name_err?></div>
								<div class="container">
									<input type="text" name="last_name" value="<?php if(isset($_POST["last_name"])) {echo $_POST["last_name"];} ?>" placeholder="<?php if(!isset($_POST["last_name"])) {echo "Last Name";} ?>" class="beg_text">
								</div>
							</div>
						</div>
						<div class="container">
							<div class="checklist_textbox" id="for_email">
								<div class="error"><?php echo $email_err?></div>
								<div class="container">
									<input type="text" name="email" value="<?php if(isset($_POST["email"])) {echo $_POST["email"];} ?>" placeholder="<?php if(!isset($_POST["email"])) {echo "Email Address";} ?>" class="beg_text">
								</div>
							</div>
						</div>
						<div class="container">
							<div class="checklist_textbox" id="for_city">
								<div class="error"><?php echo $city_err?></div>
								<div class="container">
									<input type="text" name="city" value="<?php if(isset($_POST["city"])) {echo $_POST["city"];} ?>" placeholder="<?php if(!isset($_POST["city"])) {echo "City of Residence";} ?>" class="beg_text">
								</div>
							</div>
						</div>
						<div class="container">
							<div class="checklist_textbox" id="for_state">
								<div class="error"><?php echo $state_err?></div>
								<div class="container">
									<input type="text" name="state" value="<?php if(isset($_POST["state"])) {echo $_POST["state"];} ?>" placeholder="<?php if(!isset($_POST["state"])) {echo "State of Residence (Ex: CA)";} ?>" class="beg_text">
								</div>
							</div>
						</div>
							<br>
						<div class="container">
							<div class="checklist_textbox" id="for_username">
								<div class="error"><?php echo $username_err?></div>
								<div class="container">
									<input type="text" name="username" value="<?php if(isset($_POST["username"])) {echo $_POST["username"];} ?>" placeholder="<?php if(!isset($_POST["username"])) {echo "Username (No spaces)";} ?>" class="beg_text">
								</div>
							</div>
						</div>
						<div class="container">
							<div class="checklist_textbox" id="for_password">
								<div class="error"><?php echo $password_err?></div>
								<div class="container">
									<input type="password" name="password" value="<?php if(isset($_POST["password"])) {echo $_POST["password"];} ?>" placeholder="<?php if(!isset($_POST["password"])) {echo "Password (Min. 8 Spaces)";} ?>" class="beg_text">
								</div>
							</div>
						</div>
						<div class="container">
							<div class="checklist_textbox" id="for_confpw">
								<div class="error"><?php echo $confpw_err?></div>
								<div class="container">
									<input type="password" name="confpw" value="<?php if(isset($_POST["confpw"])) {echo $_POST["confpw"];} ?>" placeholder="<?php if(!isset($_POST["confpw"])) {echo "Confirm Password";} ?>" class="beg_text">
								</div>
							</div>
						</div>
						
						<div class="actual_checks">
							<div id="box1">
								<input type="checkbox" id="box" name="box[]" value="listening" <?php if(in_array("listening", $main_skills)){echo "checked='checked'";} ?>>
								<label>Listening Skills</label>
							</div>
							<div id="box2">
								<input type="checkbox" id="box" name="box[]" value="adapt" <?php if(in_array("adapt", $main_skills)){echo "checked='checked'";} ?>>
								<label>Adaptibility</label>
							</div>
							<div id="box3">
								<input type="checkbox" id="box" name="box[]" value="communication" <?php if(in_array("communication", $main_skills)){echo "checked='checked'";} ?>>
								<label>Communication Skills</label>
							</div>
							<div id="box4">
								<input type="checkbox" id="box" name="box[]" value="creativity" <?php if(in_array("creativity", $main_skills)){echo "checked='checked'";} ?>>
								<label>Creativity</label>
							</div>
							<div id="box5">
								<input type="checkbox" id="box" name="box[]" value="critical" <?php if(in_array("critical", $main_skills)){echo "checked='checked'";} ?>>
								<label>Critical Thinking Skills</label>
							</div>
							<div id="box6">
								<input type="checkbox" id="box" name="box[]" value="customer service" <?php if(in_array("customer service", $main_skills)){echo "checked='checked'";} ?>>
								<label>Customer Service Skills</label>
							</div>
							<div id="box7">
								<input type="checkbox" id="box" name="box[]" value="decision" <?php if(in_array("decision", $main_skills)){echo "checked='checked'";} ?>>
								<label>Decision Making Skills</label>
							</div>
							<div id="box8">
								<input type="checkbox" id="box" name="box[]" value="interpersonal" <?php if(in_array("interpersonal", $main_skills)){echo "checked='checked'";} ?>>
								<label>Interpersonal Skills</label>
							</div>
							<div id="box9">
								<input type="checkbox" id="box" name="box[]" value="time management" <?php if(in_array("time management", $main_skills)){echo "checked='checked'";} ?>>
								<label>Time Management Skills</label>
							</div>
							<div id="box10">
								<input type="checkbox" id="box" name="box[]" value="leadership" <?php if(in_array("leadership", $main_skills)){echo "checked='checked'";} ?>>
								<label>Leadership Skills</label>
							</div>
							<div id="box11">
								<input type="checkbox" id="box" name="box[]" value="organisation" <?php if(in_array("organisation", $main_skills)){echo "checked='checked'";} ?>>
								<label>Organization Skills</label>
							</div>
							<div id="box12">
								<input type="checkbox" id="box" name="box[]" value="public speaking" <?php if(in_array("public speaking", $main_skills)){echo "checked='checked'";} ?>>
								<label>Public Speaking Skills</label>
							</div>
							<div id="box13">
								<input type="checkbox" id="box" name="box[]" value="problem solving" <?php if(in_array("problem solving", $main_skills)){echo "checked='checked'";} ?>>
								<label>Problem Solving Skills</label>
							</div>
							<div id="box14">
								<input type="checkbox" id="box" name="box[]" value="team" <?php if(in_array("team", $main_skills)){echo "checked='checked'";} ?>>
								<label>Teamworking Skills</label>
							</div>
							<div id="box15">
								<input type="checkbox" id="box" name="box[]" value="administrative" <?php if(in_array("administrative", $main_skills)){echo "checked='checked'";} ?>>
								<label>Administrative Skills</label>
							</div>
							<div id="box16">
								<input type="checkbox" id="box" name="box[]" value="analytical" <?php if(in_array("analytical", $main_skills)){echo "checked='checked'";} ?>>
								<label>Analytical Skills</label>
							</div>
							<div id="box18">
								<input type="checkbox" id="box" name="box[]" value="language" <?php if(in_array("language", $main_skills)){echo "checked='checked'";} ?>>
								<label>Language Skills</label>
							</div>
							<div id="box19">
								<input type="checkbox" id="box" name="box[]" value="marketing" <?php if(in_array("marketing", $main_skills)){echo "checked='checked'";} ?>>
								<label>Marketing Skills</label>
							</div>
							<div id="box20">
								<input type="checkbox" id="box" name="box[]" value="technical" <?php if(in_array("technical", $main_skills)){echo "checked='checked'";} ?>>
								<label>Technical Skills</label>
							</div>
							<div id="box21">
								<input type="checkbox" id="box" name="box[]" value="IT skills" <?php if(in_array("IT", $main_skills)){echo "checked='checked'";} ?>>
								<label>IT Skills</label>
							</div>
							<div id="box23">
								<input type="checkbox" id="box" name="box[]" value="writing" <?php if(in_array("writing", $main_skills)){echo "checked='checked'";} ?>>
								<label>Writing Skills</label>
							</div>
							<div id="box24">
								<input type="checkbox" id="box" name="box[]" value="math" <?php if(in_array("math", $main_skills)){echo "checked='checked'";} ?>>
								<label>Math Skills</label>
							</div>
							<div id="box29">
								<input type="checkbox" id="box" name="box[]" value="financial management" <?php if(in_array("financial management", $main_skills)){echo "checked='checked'";} ?>>
								<label>Financial Management Skills</label>
							</div>
							<div id="box32">
								<input type="checkbox" id="box" name="box[]" value="empathy" <?php if(in_array("empathy", $main_skills)){echo "checked='checked'";} ?>>
								<label>Empathy Skills</label>
							</div>
							<div id="box34">
								<input type="checkbox" id="box" name="box[]" value="patience" <?php if(in_array("patience", $main_skills)){echo "checked='checked'";} ?>>
								<label>Patience</label>
							</div>
							<div id="box35">
								<input type="checkbox" id="box" name="box[]" value="scientific" <?php if(in_array("scientific", $main_skills)){echo "checked='checked'";} ?>>
								<label>Scientific Knowledge Skills</label>
							</div>
							<div id="box36">
								<input type="checkbox" id="box" name="box[]" value="researching" <?php if(in_array("researching", $main_skills)){echo "checked='checked'";} ?>>
								<label>Researching Skills</label>
							</div>
							<div id="box38">
								<input type="checkbox" id="box" name="box[]" value="attention to detail" <?php if(in_array("attention to detail", $main_skills)){echo "checked='checked'";} ?>>
								<label>Attention to Detail</label>
							</div>
							<div id="box39">
								<input type="checkbox" id="box" name="box[]" value="physical" <?php if(in_array("physical", $main_skills)){echo "checked='checked'";} ?>>
								<label>Physical Skills</label>
							</div>
							<div id="box41">
								<input type="checkbox" id="box" name="box[]" value="ability to work under pressure" <?php if(in_array("ability to work under pressure", $main_skills)){echo "checked='checked'";} ?>>
								<label>Ability to Work Under Pressure</label>
							</div>
							<div id="box42">
								<input type="checkbox" id="box" name="box[]" value="dexterity" <?php if(in_array("dexterity", $main_skills)){echo "checked='checked'";} ?>>
								<label>Dexterity</label>
							</div>
							<div id="box44">
								<input type="checkbox" id="box" name="box[]" value="alone" <?php if(in_array("alone", $main_skills)){echo "checked='checked'";} ?>>
								<label>Ability to Work Alone</label>
							</div>
							<div id="box45">
								<input type="checkbox" id="box" name="box[]" value="enthusiasm" <?php if(in_array("enthusiasm", $main_skills)){echo "checked='checked'";} ?>>
								<label>Enthusiasm</label>
							</div>
							<div id="box49">
								<input type="checkbox" id="box" name="box[]" value="business a" <?php if(in_array("business a", $main_skills)){echo "checked='checked'";} ?>>
								<label>Business Acumen Skills</label>
							</div>
							<div id="box52">
								<input type="checkbox" id="box" name="box[]" value="numeracy" <?php if(in_array("numeracy", $main_skills)){echo "checked='checked'";} ?>>
								<label>Numeracy Skills</label>
							</div>
							<div id="box55">
								<input type="checkbox" id="box" name="box[]" value="artist" <?php if(in_array("artist", $main_skills)){echo "checked='checked'";} ?>>
								<label>Artistic Skills</label>
							</div>
							<div id="box56">
								<input type="checkbox" id="box" name="box[]" value="coordina" <?php if(in_array("coordination", $main_skills)){echo "checked='checked'";} ?>>
								<label>Hand-eye Coordination</label>
							</div>
							<span class="skill_error"><?php echo $skill_list_err?></span>
							<div id="submit1">
								<input type="submit" id="button" name="submit" value="Register">
							</div>
						</div>
					</form>
				</div>
			</div>
			<br>
			<div class="container">
				<div class="already_account">Already have an account? <a href="login/">Log In!</a></div>
			</div>
			<br>
			<br>
			<div class="footer">
				<div class="footer_links">
					<span class="about"><a href="about/">About</a></span>
					<span class="contact"><a href="contact/">Contact Us</a></span>
					<span class="privacy"><a href="privacypolicy/">Privacy</a></span>
					<span class="terms"><a href="termsofcontract/">Terms</a></span>
				</div>
				<div class="copyright">
					&copy; The Job Scale, <?php echo date("Y");?> | Job data taken from My Job Search
				</div>
			</div>
		</div>
	</body>
</html>