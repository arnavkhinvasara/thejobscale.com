<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="shortcut icon" type="image/x-icon" href="http://www.thejobscale.com/favicon.ico">
		<link rel="icon" type="image/x-icon" href="http://www.thejobscale.com/favicon.ico">
		<title>The Job Scale | Contact</title>
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans+Condensed:wght@300&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap" rel="stylesheet">
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
			.for_contact{
				padding-top: 40px;
				padding-bottom: 40px;
				width: 100%;
				height: 180px;
				background-color: lightblue;
				font-family: 'Open Sans Condensed', sans-serif;
				letter-spacing: 1px;
				font-size: 110px;
			}
			.for_contact span{
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
				font-size: 30px;
			}
			.container{
				width: 100%;
				display: flex;
				justify-content: center;
				align-items: center;
			}
			.contact_info{
				padding-top: 40px;
				border: 3px solid salmon;
				background-color: lightblue;
				width: 700px;
				border-radius: 10%;
				font-weight: bold;
				font-family: 'Montserrat', sans-serif;

			}
			.email{
				position: relative;
				right: 150px;
				text-align: center;
			}
			.phone{
				position: relative;
				bottom: 60px;
				left: 150px;
				text-align: center;
			}
			.info{
				margin-top: 20px;
			}
			.info_title{
				margin-bottom: 20px;
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
		<div class="for_contact"><span>Contact Us</span></div>
		<hr class="between">
		<br><br>
		<div class="main">
			<h2>Tell Us About Your Dream!!</h2>
			<br><br>
			<div class="container">
				<div class="contact_info">
					<div class="email">
						<div class="info_title">Email Address:</div>
						<div class="info">thejobscale@gmail.com</div>
					</div>
					<div class="phone">
						<div class="info_title">Phone Number:</div>
						<div class="info">(408)-750-0016</div>
					</div>
				</div>
			</div>
			<br>
			<br>
			<br>
			<div class="footer">
				<div class="footer_links">
					<span class="about"><a href="../about/">About</a></span>
					<span class="contact"><a href="">Contact Us</a></span>
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