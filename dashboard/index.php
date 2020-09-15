<?php
session_start();

if(!isset($_SESSION["dashboard_username"])){

	header("location: ../login/");
	echo '<meta http-equiv="refresh" content="0;url=http://www.google.com/" />';
}

$userbase_file = fopen("hidden", "r") or die("Unable to open file.");

$city = "";
$state = "";
$dashboard_name = "";
$skills = "";
$dashboard_username = $_SESSION["dashboard_username"];
while(($line = fgets($userbase_file))!== false){
	$splitter = explode("=", $line);
	$first_username = $splitter[0];
	if($dashboard_username==$first_username){
		$second = $splitter[1];
		$difference = explode("---", $second);
		$second_one = $difference[0];
		$for_first_name = explode(",", $second_one);
		$city = ucfirst(trim($for_first_name[3]));
		$state = strtoupper(trim($for_first_name[4]));
		$first_name_address = ltrim($for_first_name[0], "{");
		$dashboard_name = $first_name_address;
		//getting string of skills
		$second_two = $difference[1];
		$skills = $second_two;
		break;
	}
}
fclose($userbase_file);

function jobs(){
	$userbase_file = fopen("hidden", "r") or die("Unable to open file.");
	//associative array for job title and associated list
	$jobs_and_skills = array();
	//while loop to add elements to array
	while(($line = fgets($userbase_file))!== false){
		$splitter = explode("=", $line);
		$job_title_and_more = $splitter[0];
		$for_just_job_title = explode("(", $job_title_and_more);
		$job_title = $for_just_job_title[0];
		$job_skills = $splitter[1];
		$jobs_and_skills[$job_title] = $job_skills;
	}
	fclose($userbase_file);
	return $jobs_and_skills;
}

//function to determine jobs relating to user skills
function skill_to_job($x, $y){
	$common_skills = array("listening", "adapt", "communication", "creativity", "critical", "customer service", "decision", "interpersonal", "time management", "organisation", "problem solving", "team", "analytical", "technical", "patience", "research", "attention to detail", "physical", "enthusiasm" ,"numeracy");
	$not_common = array("leadership", "public speaking", "administrative", "language", "marketing", "IT skills", "writing", "math", "financial management", "empathy", "scientific", "ability to work under pressure", "dexterity", "alone", "business a", "artist", "coordina");
	//getting list of skills, each element for skills of each job
	$specific_skills = array();
	foreach ($y as $key => $value) {
		array_push($specific_skills, $value);
	}
	//starting the meat of the project--themeat
	$chosen_skills = array();
	foreach ($specific_skills as $value) {
		$count = 0;
		foreach ($x as $value_2) {
			if(in_array($value_2, $not_common)){
				if(strpos($value, $value_2)){
					if(!in_array($value, $chosen_skills)){
						array_push($chosen_skills, $value);
					}
					$count++;
					continue;
				}
			}
			else{
				if(strpos($value, $value_2)){
					$count++;
					continue;
				}
			}
		}
		if(in_array($value, $chosen_skills)){
			continue;
		}
		else{
			if(count($x)<=5){
				if($count>=2){
					array_push($chosen_skills, $value);
				}
			}
			else{
				if($count>=4){
					array_push($chosen_skills, $value);
				}
			}
		}
	}
	return $chosen_skills;
}

//function to split array into one where keys=job and values=skills and another where  key=jobs and values=salary
function array_splitter ($x){
	
	$job_with_skills = array();
	$job_with_salaries = array();
	foreach ($x as $key => $value) {
		$split_key = explode("(", $key);
		$just_job = $split_key[0];
		//for 1st array
		$job_with_skills[$just_job] = $value;
		//for 2nd array
		$job_with_salaries[$just_job] = rtrim($split_key[1], ")");
	}

	return array($job_with_skills, $job_with_salaries);

}

//function to get array of jobs/salary as keys and skills as values
function jobs_arrays ($x){
	//open file
	$userbase_file = fopen("hidden", "r") or die("Unable to open file.");

	//function to get array with keys as job titles and values as skills associated with job
	$jobs_skills_salary = array();
	while(($line = fgets($userbase_file))!== false){
		foreach ($x as $value) {
			if(strpos($line, $value)){
				$splitter = explode("=", $line);
				$job_title_and_more = $splitter[0];
				$for_just_job_title = explode("(", $job_title_and_more);
				$job_title = $for_just_job_title[0];
				$job_skills = $splitter[1];
				$jobs_skills_salary[$job_title_and_more] = $job_skills;
			}
		}
	}
	fclose($userbase_file);

	return array_splitter($jobs_skills_salary);

}

//function to cut array down to include only 10 highest-paying jobs
function best_jobs($x){
	$job_and_skill = $x[0];
	$job_and_salary = $x[1];

	//sorting salary array based on salary
	arsort($job_and_salary);

	if(count($job_and_salary)>10){
		//function to add top 10 indexes to another array for salary
		$job_salary = array();
		foreach ($job_and_salary as $key => $value) {
			if(array_search($key, array_keys($job_and_salary))<=9){
				$job_salary[$key] = $value;
			}
		}
		//function to add top 10 indexes to another array for skills
		$job_skill = array();
		foreach ($job_salary as $key => $value) {
			$job_skill[$key] = $job_and_skill[$key];
		}
		//2-dimensional array with keys as jobs and values is arrays with salary and skills
		$job_with_skill_and_salary = array();
		foreach ($job_salary as $key => $value) {
			$job_with_skill_and_salary[$key] = array($value, $job_skill[$key]);
		}
		return $job_with_skill_and_salary;
	}
	//function to get array with skills when list of jobs less than 10
	$job_skill = array();
	foreach ($job_and_salary as $key => $value) {
		$job_skill[$key] = $job_and_skill[$key];
	}
	//2-dimensional array with keys as jobs and values is arrays with salary and skills
	$job_with_skill_and_salary = array();
	foreach ($job_and_salary as $key => $value) {
		$job_with_skill_and_salary[$key] = array($value, $job_skill[$key]);
	}

	return $job_with_skill_and_salary;

}

//formatting list of skills
$skills_list = explode(",", $skills);
array_pop($skills_list);

//calling functions
$base_jobs = jobs_arrays(skill_to_job($skills_list, jobs()));
$best_jobs = best_jobs($base_jobs);

$jobs = array_keys($best_jobs);
$specs = array_values($best_jobs);

//making sure that every job has user skills in them
$decider = "";
foreach ($specs as $spec) {
	//making sure there is at least 1 skill for the job
	$mr_count = 0;
	$index = array_search($spec, $specs);
	foreach (explode("---", $specs[$index][1]) as $value) {
		foreach ($skills_list as $value_2) {
			if(strpos($value, $value_2)){
				$mr_count++;
				break;
			}
		}
	}
	//removing from list of all related jobs if not even 1 skill
	if($mr_count==0){
		$skill_itself = $specs[$index][1];
		if($decider=="wrongness"){
			unset($job_skill_list[array_search($skill_itself, $job_skill_list)]);
		}
		else{
			$decider = "wrongness";
			//adding job skills to new list if meet criteria of having skills where user skills are in the skill
			$job_skill_list = skill_to_job($skills_list, jobs());
			unset($job_skill_list[array_search($skill_itself, $job_skill_list)]);
		}
	}
}

//addressing all the other functions if there is a new version of the list of skills for each job
if($decider=="wrongness"){
	//calling functions
	$base_jobs = jobs_arrays($job_skill_list);
	$best_jobs = best_jobs($base_jobs);

	$jobs = array_keys($best_jobs);
	$_SESSION["job_list"] = $jobs;
	$specs = array_values($best_jobs);
}
else{
	$_SESSION["job_list"] = $jobs;
}

//making sure page still loads
try{
	$specs_1 = $specs[0];
	$specs_2 = $specs[1];
	$specs_3 = $specs[2];
	$specs_4 = $specs[3];
	$specs_5 = $specs[4];
	$specs_6 = $specs[5];
	$specs_7 = $specs[6];
	$specs_8 = $specs[7];
	$specs_9 = $specs[8];
	$specs_10 = $specs[9];
}
catch(Exception $e){
	$specs_1 = $specs_2 = $specs_3 = $specs_4 = $specs_5 = $specs_6 = $specs_7 = $specs_8 = $specs_9 = $specs_10 = array("Could Not Find Matches For Your Skills", "Could Not Find Matches For Your Skills");
	$jobs = array("No Job Match", "No Job Match", "No Job Match", "No Job Match", "No Job Match", "No Job Match", "No Job Match", "No Job Match", "No Job Match", "No Job Match");
}
if(!in_array("No Job Match", $jobs)){

	//formatting city and job
	$city = str_replace(" ", "+", $city);

	$_SESSION["city"]  = $city;
	$_SESSION["state"] = $state;

}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="shortcut icon" type="image/x-icon" href="http://www.thejobscale.com/favicon.ico">
		<link rel="icon" type="image/x-icon" href="http://www.thejobscale.com/favicon.ico">
		<title>The Job Scale | Dashboard</title>
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
				padding-bottom: 50px;
				width: 100%;
				background-color: lightblue;
				font-family: 'Open Sans Condensed', sans-serif;
				letter-spacing: 1px;
				font-size: 110px;
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
				font-size: 23px;
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
			.survey_title{
				text-align: center;
				font-family: arial;
				font-size: 50px;
			}
			.jobs_title{
				width: 69%;
				font-size: 30px;
				font-family: 'Montserrat', sans-serif;
			}
			.jobs{
				width: 75%;
			}
			.jobs_ol{
				font-family: 'Open Sans Condensed', sans-serif;
				font-size: 50px;
			}
			mark{
				background-color: salmon;
				color: black;
				padding-left: 5px;
				padding-right: 5px;
				border-radius: 5%;
			}
			.individual_jobs{
				text-indent: 10px;
				font-size: 30px;
				font-family: 'Indie Flower', cursive;
				margin-top: 20px;
				margin-bottom: 35px;
				background-color: lightblue;
				padding: 15px;
				border-radius: 5%;
			}
			.individual_jobs li a{
				color: salmon;
			}
			.individual_jobs li a:hover{
				border-bottom: 2px solid salmon;
			}
			.job_skills li{
				text-indent: 10px;
				font-size: 20px;
			}
			.change_info{
				text-align: center;
				border: 2px solid salmon;
				width: 65%;
				border-radius: 5%;
				font-size: 20px;
				justify-content: center;
				padding: 5px;
				color: black;
				font-family: 'Montserrat', sans-serif;
			}
			.change_info a{
				color: lightblue;
				font-weight: bold;
				border-bottom: 1px solid black;
				margin-top: 5px;
			}
			.change_info a:hover{
				border-bottom: 2px solid black;
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
				<a href="../logout/"><div>Log Out</div></a>
			</div>
		</div>
		<div class="for_perfect"><span>Dashboard</span></div>
		<div class="main">
			<div class="aside">
				<br><br>
				<div class="top">
					<h2>Click <a href="../relatedjobs/">here</a> to rate each job and identify titles relating to your top 2 jobs (titles will be updated every time you open the site).</h2>
				</div>
				<br><br>
			</div>
			<br><br>
			<div class="survey_title">
				<span>Hi <?php echo $dashboard_name ?>!</span>
			</div>
			<br><br>
			<div class="container">
				<div class="jobs_title">Here is a list of jobs that suit you the best based on your skills:</div>
			</div>
			<br><br>
			<div class="container">
				<div class="change_info">Name and Residence Incorrect? (Your job opportunities are based on where you live) <a href="../changeinfo/">Change Information</a></div>
			</div>
			<br><br>
			<div class="container">
				<div class="jobs">
					<ol class="jobs_ol">
						<li><mark>1. <?php echo $jobs[0]; ?></mark></li>
						<ul class="individual_jobs">
							<li>* Salary: $<?php echo $specs_1[0]?></li>
							<li>* Skills Needed That You Have:</li>
							<ol class="job_skills">
								<?php 
									foreach (explode("---", $specs_1[1]) as $value) {
										foreach ($skills_list as $value_2) {
											if(strpos($value, $value_2)){
												echo "<li>- ".$value."</li>";
												break;
											}
										}
									}
								?>
							</ol>
							<li>* Job Opportunities Near You: Click <a href="../job1/" target="_blank">Here</a></li>
						</ul>
						<li><mark>2. <?php echo $jobs[1]; ?></mark></li>
						<ul class="individual_jobs">
							<li>* Salary: $<?php echo $specs_2[0]?></li>
							<li>* Skills Needed That You Have:</li>
							<ol class="job_skills">
								<?php 
									foreach (explode("---", $specs_2[1]) as $value) {
										foreach ($skills_list as $value_2) {
											if(strpos($value, $value_2)){
												echo "<li>- ".$value."</li>";
												break;
											}
										}
									}
								?>
							</ol>
							<li>* Job Opportunities Near You: Click <a href="../job2/" target="_blank">Here</a></li>
						</ul>
						<li><mark>3. <?php echo $jobs[2]; ?></mark></li>
						<ul class="individual_jobs">
							<li>* Salary: $<?php echo $specs_3[0]?></li>
							<li>* Skills Needed That You Have:</li>
							<ol class="job_skills">
								<?php 
									foreach (explode("---", $specs_3[1]) as $value) {
										foreach ($skills_list as $value_2) {
											if(strpos($value, $value_2)){
												echo "<li>- ".$value."</li>";
												break;
											}
										}
									}
								?>
							</ol>
							<li>* Job Opportunities Near You: Click <a href="../job3/" target="_blank">Here</a></li>
						</ul>
						<li><mark>4. <?php echo $jobs[3]; ?></mark></li>
						<ul class="individual_jobs">
							<li>* Salary: $<?php echo $specs_4[0]?></li>
							<li>* Skills Needed That You Have:</li>
							<ol class="job_skills">
								<?php 
									foreach (explode("---", $specs_4[1]) as $value) {
										foreach ($skills_list as $value_2) {
											if(strpos($value, $value_2)){
												echo "<li>- ".$value."</li>";
												break;
											}
										}
									}
								?>
							</ol>
							<li>* Job Opportunities Near You: Click <a href="../job4/" target="_blank">Here</a></li>
						</ul>
						<li><mark>5. <?php echo $jobs[4]; ?></mark></li>
						<ul class="individual_jobs">
							<li>* Salary: $<?php echo $specs_5[0]?></li>
							<li>* Skills Needed That You Have:</li>
							<ol class="job_skills">
								<?php 
									foreach (explode("---", $specs_5[1]) as $value) {
										foreach ($skills_list as $value_2) {
											if(strpos($value, $value_2)){
												echo "<li>- ".$value."</li>";
												break;
											}
										}
									}
								?>
							</ol>
							<li>* Job Opportunities Near You: Click <a href="../job5/" target="_blank">Here</a></li>
						</ul>
						<li><mark>6. <?php echo $jobs[5]; ?></mark></li>
						<ul class="individual_jobs">
							<li>* Salary: $<?php echo $specs_6[0]?></li>
							<li>* Skills Needed That You Have:</li>
							<ol class="job_skills">
								<?php 
									foreach (explode("---", $specs_6[1]) as $value) {
										foreach ($skills_list as $value_2) {
											if(strpos($value, $value_2)){
												echo "<li>- ".$value."</li>";
												break;
											}
										}
									}
								?>
							</ol>
							<li>* Job Opportunities Near You: Click <a href="../job6/" target="_blank">Here</a></li>
						</ul>
						<li><mark>7. <?php echo $jobs[6]; ?></mark></li>
						<ul class="individual_jobs">
							<li>* Salary: $<?php echo $specs_7[0]?></li>
							<li>* Skills Needed That You Have:</li>
							<ol class="job_skills">
								<?php 
									foreach (explode("---", $specs_7[1]) as $value) {
										foreach ($skills_list as $value_2) {
											if(strpos($value, $value_2)){
												echo "<li>- ".$value."</li>";
												break;
											}
										}
									}
								?>
							</ol>
							<li>* Job Opportunities Near You: Click <a href="../job7/" target="_blank">Here</a></li>
						</ul>
						<li><mark>8. <?php echo $jobs[7]; ?></mark></li>
						<ul class="individual_jobs">
							<li>* Salary: $<?php echo $specs_8[0]?></li>
							<li>* Skills Needed That You Have:</li>
							<ol class="job_skills">
								<?php 
									foreach (explode("---", $specs_8[1]) as $value) {
										foreach ($skills_list as $value_2) {
											if(strpos($value, $value_2)){
												echo "<li>- ".$value."</li>";
												break;
											}
										}
									}
								?>
							</ol>
							<li>* Job Opportunities Near You: Click <a href="../job8/" target="_blank">Here</a></li>
						</ul>
						<li><mark>9. <?php echo $jobs[8]; ?></mark></li>
						<ul class="individual_jobs">
							<li>* Salary: $<?php echo $specs_9[0]?></li>
							<li>* Skills Needed That You Have:</li>
							<ol class="job_skills">
								<?php 
									foreach (explode("---", $specs_9[1]) as $value) {
										foreach ($skills_list as $value_2) {
											if(strpos($value, $value_2)){
												echo "<li>- ".$value."</li>";
												break;
											}
										}
									}
								?>
							</ol>
							<li>* Job Opportunities Near You: Click <a href="../job9/" target="_blank">Here</a></li>
						</ul>
						<li><mark>10. <?php echo $jobs[9]; ?></mark></li>
						<ul class="individual_jobs">
							<li>* Salary: $<?php echo $specs_10[0]?></li>
							<li>* Skills Needed That You Have:</li>
							<ol class="job_skills">
								<?php 
									foreach (explode("---", $specs_10[1]) as $value) {
										foreach ($skills_list as $value_2) {
											if(strpos($value, $value_2)){
												echo "<li>- ".$value."</li>";
												break;
											}
										}
									}
								?>
							</ol>
							<li>* Job Opportunities Near You: Click <a href="../job10/" target="_blank">Here</a></li>
						</ul>
					</ol>
				</div>
			</div>
			<br>
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
