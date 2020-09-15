<?php 
session_start();

//retreiving dom html parser file
include "hidden";

if(!isset($_SESSION["dashboard_username"])){

	header("location: ../login/");
	echo '<meta http-equiv="refresh" content="0;url=http://www.google.com/" />';
}
elseif(!isset($_SESSION["job_list"])){

	header("location: ../dashboard/");
	echo '<meta http-equiv="refresh" content="0;url=http://www.google.com/" />';
}
$rated_jobs = fopen("hidden", "r") or die("Not opening file.");

//redirecting to rate page if username not in text file
$usernames = array();
while(($line = fgets($rated_jobs))!== false){
	$the_username = explode("=", $line)[0];
	array_push($usernames, $the_username);
}
fclose($rated_jobs);
$username = $_SESSION["dashboard_username"];
if(!in_array($username, $usernames)){
	header("location: ../ratejobs/");
	echo '<meta http-equiv="refresh" content="0;url=http://www.google.com/" />';
}

if ($_SERVER["REQUEST_METHOD"] == "POST" and isset($_POST["submit"])) {

	//remove user data from rated_jobs text file and redirect user to rate page
	$the_rated_jobs = fopen("hidden", "r") or die("Unable to open file.");

	$all_info = array();
	while(($line = fgets($the_rated_jobs))!== false){
		$splitter = explode("=", $line);
		$first_username = $splitter[0];

		$username = trim($username);
		if($username==$first_username){
			//retreiving and formatting contacts of new line in same place
			$line = "delete";
		}
		array_push($all_info, $line);
	}
	fclose($the_rated_jobs);

	$rated_the_jobs = fopen("hidden", "w") or die("Unable to open file.");
	//adding each element of array as line in text file
	foreach ($all_info as $key => $value) {
		if($value=="delete"){
			continue;
		}
		fwrite($rated_the_jobs, $value);
	}
	fclose($rated_the_jobs);

	//redirecting
	header("location: ../ratejobs/");

	echo '<meta http-equiv="refresh" content="0;url=http://www.google.com/" />';
}

//getting the two best jobs for user
$rated_jobs_2 = fopen("hidden", "r") or die("Not opening file.");
$best_jobs = array();
while(($line = fgets($rated_jobs_2))!== false){
	$the_username = explode("=", $line)[0];
	//checking to see if right line
	if($username==$the_username){
		$best_jobs = explode(",", explode("=", $line)[1]);
	}
}
fclose($rated_jobs_2);
//formatting jobs to display to user
$v_jobs = array();
foreach ($best_jobs as $right_job) {
	$right_job_2 = explode("-", $right_job);
	if($right_job_2==$right_job){
		$right_job_2 = ucfirst($right_job_2);
	}
	else{
		$capped = array();
		foreach ($right_job_2 as $value) {
			array_push($capped, ucfirst($value));
		}
		$right_job_2 = implode(" ", $capped);
	}
	array_push($v_jobs, $right_job_2);
}

//function to scrape My Job Search
function scraper($jobs){
	$scraper_list = array();
	//iterating through the top two jobs
	foreach ($jobs as $job) {
		//initializing scrape
		$html = file_get_html("https://www.myjobsearch.com/careers/".$job.".html");

		//scraping actual content
		foreach ($html->find("div.related_jobs ") as $div) {
			foreach ($div->find("ul") as $ul) {
				foreach ($ul->find("li") as $li) {
					array_push($scraper_list, $li->plaintext);
				}
			}
		}

	}
	return $scraper_list;
}

$o_related_list = scraper($best_jobs);

//making sure there are no repeats
$related_list = array();
foreach ($o_related_list as $value) {
	if(!in_array($value, $related_list)){
		array_push($related_list, $value);
	}
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="shortcut icon" type="image/x-icon" href="http://www.thejobscale.com/favicon.ico">
		<link rel="icon" type="image/x-icon" href="http://www.thejobscale.com/favicon.ico">
		<title>The Job Scale | Job Updates</title>
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
			.for_about{
				padding-top: 40px;
				padding-bottom: 40px;
				width: 100%;
				height: 180px;
				background-color: lightblue;
				font-family: 'Open Sans Condensed', sans-serif;
				letter-spacing: 1px;
				font-size: 110px;
			}
			.for_about span{
				position: relative;
				left: 40px;
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
			.jobs_title{
				font-size: 25px;
				font-family: 'Montserrat', sans-serif;
				text-align: center;
				justify-content: center;
			}
			.jobs{
				width: 75%;
			}
			.jobs_ol{
				font-family: 'Open Sans Condensed', sans-serif;
				font-size: 50px;
				margin: 30px;
			}
			.jobs_ol li{
				margin: 30px;
			}
			mark{
				background-color: salmon;
				color: black;
				padding-left: 5px;
				padding-right: 5px;
				border-radius: 5%;
			}
			#button{
				background-color: lightblue;
				padding: 10px;
				color: black;
				font-weight: bold;
				border-radius: 10%;
				margin-bottom: 20px;
			}
			#button:hover{
				border: 2px solid salmon;
				background-color: lightblue;
				color: black;
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
		<div class="for_about"><span>Related Jobs</span></div>
		<hr class="between">
		<br><br>
		<div class="main">
			<h2>Increasing options is important to find the perfect job!</h2>
			<br><br>
			<div class="container">
				<span class="jobs_title"><i>Here is a list of jobs related to being a(n) <b><?php echo $v_jobs[0];?></b> and <b><?php echo $v_jobs[1]; ?></b>:</i></span>
			</div>
			<div class="container">
				<div class="jobs">
					<ol class="jobs_ol">
						<?php 
							foreach ($related_list as $value) {
								$number = array_search($value, $related_list) + 1;
								echo "<li><mark>".$number.". ".$value."</mark></li>";
							}
						?>
					</ol>
				</div>
			</div>
			<br>
			<div class="container">
				<div class="re-rate">
					<form class="rate_again" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
						<div class="submit_again">
							<div id="submit1">
								<input type="submit" id="button" name="submit" value="Rate Jobs Again">
							</div>
						</div>
					</form>
				</div>
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
