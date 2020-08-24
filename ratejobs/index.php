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
$jobs = $_SESSION["job_list"];
if(in_array("No Job Match", $jobs)){

	header("location: ../dashboard/");
	echo '<meta http-equiv="refresh" content="0;url=http://www.google.com/" />';
}
//redirect if username in text file
$rated_jobs = fopen("../assets/rated_jobs.txt", "r") or die("Not opening file.");

$usernames = array();
while(($line = fgets($rated_jobs))!== false){
	$the_username = explode("=", $line)[0];
	array_push($usernames, $the_username);
}
fclose($rated_jobs);
$username = $_SESSION["dashboard_username"];
if(in_array($username, $usernames)){
	header("location: ../relatedjobs/");
	echo '<meta http-equiv="refresh" content="0;url=http://www.google.com/" />';
}


//for values to scrape
$v_jobs = array();
foreach ($jobs as $this_job) {
	$job_2 = str_replace(" ", "-", $this_job);
	if($job_2==$this_job){
		$job_2 = lcfirst($job_2);
	}
	else{
		$job_list = explode("-", $job_2);
		$the_job_list = array();
		foreach ($job_list as $value) {
			array_push($the_job_list, lcfirst($value));
		}
		$job_2 = implode("-", $the_job_list);
	}
	array_push($v_jobs, $job_2);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	//naming user data
	$wanted_jobs = $_POST;

	$best_jobs = $wanted_jobs["box"];

	//validating data
	$err_mess = "";

	if(count($best_jobs)==2){
		$err_mess = "";

		//opening text file to write to it
		$job_base = fopen("../assets/rated_jobs.txt", "a") or die("Not opening file.");

		$line = "\n".$username."=".$best_jobs[0].",".$best_jobs[1];

		fwrite($job_base, $line);

		fclose($job_base);

		header("location: ../relatedjobs/");
		echo '<meta http-equiv="refresh" content="0;url=http://www.google.com/" />';

	}
	else{
		$err_mess = "*Only your top 2 jobs should be selected";
	}

}



?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="shortcut icon" type="image/x-icon" href="http://www.thejobscale.com/favicon.ico">
		<link rel="icon" type="image/x-icon" href="http://www.thejobscale.com/favicon.ico">
		<title>The Job Scale | Rate Jobs</title>
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
			.checklist_title{
				text-align: center;
			}
			.actual_checks{
				position: relative;
				font-family: 'Indie Flower', cursive;
				font-size: 20px;
				margin: 10px;
				padding: 10px;
				left: 60px;
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
				left: 75px;
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
			.notice{
				text-align: center;
				font-size: 15px;
				justify-content: center;
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
		<div class="for_login"><span>Rate Jobs</span></div>
		<hr class="between">
		<br><br>
		<div class="main">
			<div class="container">
				<div class="wrapper">
					<div class="checklist_title"><i>Select your top 2 jobs:</i></div>
					<form class="rate_form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
						<div class="actual_checks">
							<div id="box1">
								<input type="checkbox" id="box" name="box[]" value=<?php echo $v_jobs[0]; ?>>
								<label><?php echo $jobs[0]; ?></label>
							</div>
							<div id="box2">
								<input type="checkbox" id="box" name="box[]" value=<?php echo $v_jobs[1]; ?>>
								<label><?php echo $jobs[1]; ?></label>
							</div>
							<div id="box3">
								<input type="checkbox" id="box" name="box[]" value=<?php echo $v_jobs[2]; ?>>
								<label><?php echo $jobs[2]; ?></label>
							</div>
							<div id="box4">
								<input type="checkbox" id="box" name="box[]" value=<?php echo $v_jobs[3]; ?>>
								<label><?php echo $jobs[3]; ?></label>
							</div>
							<div id="box5">
								<input type="checkbox" id="box" name="box[]" value=<?php echo $v_jobs[4]; ?>>
								<label><?php echo $jobs[4]; ?></label>
							</div>
							<div id="box6">
								<input type="checkbox" id="box" name="box[]" value=<?php echo $v_jobs[5]; ?>>
								<label><?php echo $jobs[5]; ?></label>
							</div>
							<div id="box7">
								<input type="checkbox" id="box" name="box[]" value=<?php echo $v_jobs[6]; ?>>
								<label><?php echo $jobs[6]; ?></label>
							</div>
							<div id="box8">
								<input type="checkbox" id="box" name="box[]" value=<?php echo $v_jobs[7]; ?>>
								<label><?php echo $jobs[7]; ?></label>
							</div>
							<div id="box9">
								<input type="checkbox" id="box" name="box[]" value=<?php echo $v_jobs[8]; ?>>
								<label><?php echo $jobs[8]; ?></label>
							</div>
							<div id="box10">
								<input type="checkbox" id="box" name="box[]" value=<?php echo $v_jobs[9]; ?>>
								<label><?php echo $jobs[9]; ?></label>
							</div>
							<span class="skill_error"><?php echo $err_mess; ?></span>
							<div id="submit1">
								<input type="submit" id="button" name="submit" value="Submit">
							</div>
						</div>
					</form>
				</div>
			</div>
			<br>
			<div class="container">
				<span class="notice">*Note: Related Jobs Page should take 5 seconds to update.</span>
			</div>
			<br><br>
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