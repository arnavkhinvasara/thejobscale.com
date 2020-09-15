<?php 
session_start();

include "hidden";

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

//used for addressing page
$this_job = $jobs[0];

//getting session variables
$city = $_SESSION["city"];
$state = $_SESSION["state"];

//function to actually scrape the data wanted
function data_scrape($html){
	//web scraping adding what i want to in array
	$opportunities = array();
	foreach($html->find("div.sjcl") as $div){
		foreach ($div->find("span.company") as $span) {
			array_push($opportunities, $span->plaintext);
		}
	}
	return $opportunities;
}

//function to scrape Indeed
function web_scraper($job, $city, $state, $r_job){
	//web_scraping set-up
	$html = file_get_html("https://www.indeed.com/jobs?q=".$job."&l=".$city."%2C+".$state."&radius=25&from=sug");

	$opportunities = data_scrape($html);
	//checking if page is incorrect
	if(count($opportunities)>0){
		return $opportunities;
	}
	//function to see if words for links on page are similar to job name
	$link_href = "";
	foreach ($html->find("div#suggested_queries") as $div) {
		foreach ($div->find("a") as $a) {
			if(array_search($a, $div->find("a"))==0){
				$link_href = "https://www.indeed.com".$a->href;
			}
		}
	}
	//checking if links not on page, then return that no job opportunities near
	if($link_href==""){
		return array("No Opportunities Found", "No Opportunities Found");
	}
	//scraping new link
	$html_2 = file_get_html($link_href);

	//checking if data found, if not, return same array from above
	$opportunities_2 = data_scrape($html_2);
	if(count($opportunities_2)>0){
		return $opportunities_2;
	}
	return array("No Opportunities Found", "No Opportunities Found");
}

$job_2 = str_replace(" ", "+", $this_job);
if($job_2==$this_job){
	$job_2 = lcfirst($job_2);
}
else{
	$job_list = explode("+", $job_2);
	$the_job_list = array();
	foreach ($job_list as $value) {
		array_push($the_job_list, lcfirst($value));
	}
	$job_2 = implode("+", $the_job_list);
}

$draft_opp = web_scraper($job_2, $city, $state, $this_job);

//function to remove any unnecessary companies
function checker($opps, $job){
	//cleaning initial array haha
	$cleaned = array();
	foreach ($opps as $opp) {
		//checking if element is equal to job, if yes, then delete 
		if(in_array($opp, $cleaned)){
			continue;
		}
		array_push($cleaned, $opp);
	}

	return $cleaned;
}

$final_opp = checker($draft_opp, $this_job);

//function to format job opportunity for link to indeed
function for_apply_link($x){
	//make spaces dashes
	$new_x = str_replace(" ", "-", $x);
	//formatting $new_x even more so that there are no dashes at the beginning
	$to_list = explode("-", $new_x);
	$new_list = array();
	foreach ($to_list as $value) {
		if($value==""){
			continue;
		}
		elseif($value=="Inc."){
			continue;
		}
		elseif($value=="LLC"){
			continue;
		}
		array_push($new_list, $value);
	}
	$newer = array();
	foreach ($new_list as $value) {
		array_push($newer, rtrim($value, ","));
	}
	$imploded_ver = implode("-", $newer);

	$new_link = "https://www.indeed.com/cmp/".$imploded_ver."/jobs";
	return $new_link;
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="shortcut icon" type="image/x-icon" href="http://www.thejobscale.com/favicon.ico">
		<link rel="icon" type="image/x-icon" href="http://www.thejobscale.com/favicon.ico">
		<title>The Job Scale | <?php echo $this_job;?></title>
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
			.wrapper{
				width: 50%;
				justify-content: center;
				font-family: 'Montserrat', sans-serif;
				background-color: salmon;
				border-radius: 10%;
				border: 4px solid lightblue;
				padding: 20px;
			}
			.section_title{
				text-align: center;
				margin-bottom: 30px;
				font-size: 40px;
			}
			.about_ul{
				margin-bottom: 40px;
				font-size: 20px;
			}
			.about_ul li a{
				border-bottom: 1px solid black;
				color: white;
			}
			.about_ul li a:hover{
				border-bottom: 2px solid white;
				color: lightblue;
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
		<div class="for_about"><span><?php echo $this_job; ?></span></div>
		<hr class="between">
		<br><br>
		<div class="main">
			<h2><?php echo $this_job;?> Opportunities</h2>
			<br><br>
			<div class="container">
				<div class="wrapper">
					<div class="section_title">Opportunities Near You</div>
					<ul class="about_ul">
						<?php 
							foreach ($final_opp as $opp) {
								$linker = for_apply_link($opp);
								$number = array_search($opp, $final_opp) + 1;
								echo "<li>— Company/Business ".$number.": ".$opp." (Click <a href='$linker' target='_blank'>Here</a>)</li>";
								echo "<br/>";
							}
						?>
					</ul>
				</div>
			</div>
			<br><br><br>
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
