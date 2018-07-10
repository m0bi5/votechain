<?php
session_start();
require_once 'class.user.php';

$reg_user = new USER();

if($reg_user->is_logged_in()!="")
{
	$reg_user->redirect('home.php');
}


if(isset($_POST['btn-signup']))
{
	$aadhar= trim($_POST['uidai']);
	$email = trim($_POST['email']);
	$pname= trim($_POST['party_name']);
	$password = trim($_POST['password']);
	$first_name= trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	$phone_number = trim($_POST['phone_number']);
	$code = md5(uniqid(rand()));	
	$stmt = $reg_user->runQuery("SELECT * FROM candidates WHERE uidai=:aadhar");
	$stmt->execute(array(":aadhar"=>$aadhar));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$rtmt = $reg_user->runQuery("SELECT * FROM candidates WHERE email=:em");
	$rtmt->execute(array(":em"=>$email));
	$row = $rtmt->fetch(PDO::FETCH_ASSOC);
	$ttmt = $reg_user->runQuery("SELECT * FROM candidates WHERE phone_number=:ph");
	$ttmt->execute(array(":ph"=>$phone_number));
	$row = $ttmt->fetch(PDO::FETCH_ASSOC);
	$msg="";
	if($stmt->rowCount() > 0)
	{
		$msg = "
		      <div class='white-text alert alert-error'>
					<strong>Sorry !</strong>  your aadhar is already in use. You cannot sign up once again
			  </div>
			  ";
	}
	if($rtmt->rowCount() > 0)
	{
		$msg = "
		      <div class='white-text alert alert-error'>
					<strong>Sorry !</strong>  your email is already in use. You cannot sign up once again
			  </div>
			  ";
	}
	if($ttmt->rowCount() > 0)
	{
		$msg = "
		      <div class='white-text alert alert-error'>
					<strong>Sorry !</strong>  your phone is already in use. You cannot sign up once again
			  </div>
			  ";
	}
	if ($ttmt->rowCount() <= 0 && $rtmt->rowCount() <= 0 && $stmt->rowCount() <= 0)
	{

		if($reg_user->register($first_name,$last_name,$aadhar,$email,$password,$phone_number,$code,"N",$pname))
		{			
			$id = $reg_user->lasdID();		
			$key = base64_encode($id);
			$id = $key;
			
			$message = "					
						Hello $first_name,
						<br /><br />
						Welcome to VoteBlocks!<br/>
						To complete your registration  please , just click following link<br/>
						<br /><br />
						<a href='http://localhost/voteblocks/verify.php?id=$id&code=$code'>Click HERE to Activate :)</a>
						<br /><br />
						Thanks,";
						
			$subject = "Confirm Registration";
						
			$reg_user->send_mail($email,$message,$subject);	
			$msg = "<div class='white-text alert alert-success'>Success! We've sent an email to $email. Please click on the confirmation link in the email to create your account.</div>";
		}
		else
		{
			echo "sorry , Query could no execute...";
		}		
	}
}
?>
<html>
	<head>
		<!--Import Google Icon Font-->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!--Import materialize.css-->
		<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
		<!--Let browser know website is optimized for mobile-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

		<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>

		<style type="text/css">
		body 
		{
			display: flex;
			min-height: 100vh;
			flex-direction: column;
 		}
 		main
 		{
 			flex: 1 0 auto;
 		}
		</style>
		<script type="text/javascript">
			function validate()
			{
				if(document.getElementById('phone').value.length!=10)
				{
					alert("Enter a valid phone number")
					return false
				}
				if(document.getElementById('aadhar').value	.length!=12)
				{
					alert("Enter a valid aadhar number")
					return false
				}
				var day=document.getElementById('dob').value.split(' ')[0]
				var month=document.getElementById('dob').value.split(' ')[1]
				month=month.split(',')[0]
				var year=document.getElementById('dob').value.split(' ')[2]
				monthhash={"January":1,"February":2,"March":3,"April":4,"May":5,"June":6,"July":7,"August":8,"September":9,"October":10,"November":11,"December":12}
				month=monthhash[month]
				day=parseInt(day)
				year=parseInt(year)
				var oneDay=24*60*60*1000
				var secondDate=new Date(year,month,day)
				var date= new Date()
				var age = Math.round(Math.abs((date.getTime() - secondDate.getTime())/(oneDay*360)))
				if(age<18)
				{
					alert("You are not of legal voting age")
					return false
				}
				return true
			}
			function clear()
			{
				var i=document.getElementsByTagName("form");
				for(var k in i)
					i[k].reset();
			}
		</script>
	</head>
	<body onload="clear()" class="cyan darken-4">
		<!--Import jQuery before materialize.js-->
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/materialize.min.js"></script>

		<nav class='green accent-3'>
			<div class="nav-wrapper">

				<a href="#" style="margin-left: 10px;" class="brand-logo">
					VoteBlocks <i class="material-icons fas fa-cubes"></i>
				</a>
				<ul id="nav-mobile" class="right hide-on-med-and-down">
					<li><a href="index.php">Login</a></li>
					<li><a href="signup.php">Sign Up</a></li>
					<li><a href="index.php">View Results</a></li>
					<li><a href="vote.php">Vote</a></li>
				</ul>
			</div>
		</nav>
		<main>
			<div id="login_form">
				<h1 class="center white-text">
					Sign Up
				</h1>
				<div style='margin: 30px;margin-left: 34%' class="row">
					
					<form enctype="multipart/form-data" onsubmit="return validate();redirect();" method="post" class="form-signin hoverable center-align col s12 m6 card-panel black darken-4">	
						<br> 
						<?php 
						if(isset($msg))
						{
								echo $msg;
						}
						?>		
						<br>
						<div class="row">
							<div class="input-field col s6">
								<i class="white-text material-icons prefix">person</i>
								<input required class="input-block-level white-text" id="fName" name="first_name"  type="text" class="validate">
								<label for="icon_prefix">First Name</label>
							</div>
							<div class="input-field col s6">
								<input required class="input-block-level white-text" id="lName" name="last_name" type="text" class="validate">
								<label for="icon_prefix">Last Name</label>
							</div>

							<div class="input-field col s12">
								<i class="white-text material-icons prefix">people</i>
								<input required class="input-block-level white-text" id="party_name" name="party_name" type="text" class="validate">
								<label for="icon_prefix">Party Name</label>

							</div>

							<div class="input-field col s12">
								<i class="white-text material-icons prefix">fingerprint</i>
								<input required class="input-block-level white-text" id="aadhar" name="uidai" type="text" class="validate">
								<label for="icon_prefix">Aadhar Number (UIDAI)</label>

							</div>

							<div class="input-field col s12">
								<i class="white-text material-icons prefix">email</i>
								<input required class="input-block-level white-text" id="icon_prefix" name="email" type="email" class="validate">
								<label for="icon_prefix">Email</label>
							</div>

							<div class="input-field col s12">
								<i class="white-text material-icons prefix">lock</i>
								<input required class="input-block-level white-text" id="password" name="password" type="password" class="validate">
								<label for="icon_prefix">Password</label>
							</div>

							<div class="input-field col s12">
								<i class="white-text material-icons prefix">local_phone</i>
								<input required class="input-block-level white-text" id="phone" name="phone_number" type="text" class="validate">
								<label for="icon_prefix">Phone Number</label>
							</div>


							<div class="input-field col s12">
								<i class="white-text material-icons prefix">date_range</i>
								<input id='dob' type="text" class="white-text datepicker">
								<label for="icon_prefix">Date of Birth</label>
								<script type="text/javascript">
									$('.datepicker').pickadate
									({
										selectMonths: true, // Creates a dropdown to control month
										selectYears: 200, // Creates a dropdown of 15 years to control year,
										today: 'Today',
										clear: 'Clear',
										close: 'Ok',
										closeOnSelect: true // Close upon selecting a date,
									});
								</script>

							</div>
							<p class="col s12 center">
								<input type="checkbox" required id="test5" />
								<label for="test5">I agree to the terms and conditions</label>
								<br>
							</p>
						</div>
				 		<button id="reg" type="submit" name="btn-signup" class="btn waves-effect waves-teal">
				 			Register
				 		</button>
				 		<br>
				 		<br>
					</form>
				</div>
			</div>
		</main>
		<footer class="page-footer">
			<div class="container">
				<div class="row">
					<div class="col l6 s12">
						<h5 class="white-text">VoteBlocks Inc.</h5>
						<p class="grey-text text-lighten-4">You can use rows and columns here to organize your footer content.</p>
					</div>
					<div class="col l4 offset-l2 s12">
						<h5 class="white-text">Sitemap</h5>
						<ul>
							<li><a class="grey-text text-lighten-3" href="index.php">Login</a></li>
							<li><a class="grey-text text-lighten-3" href="fpass.php">Forgot Password</a></li>
							<li><a class="grey-text text-lighten-3" href="signup.php">Register</a></li>
							<li><a class="grey-text text-lighten-3" href="index.php">View Results</a></li>
							<li><a class="grey-text text-lighten-3" href="vote.php">Cast vote</a></li>
						</ul>
					</div>
				</div>
			</div>
		  <div class="footer-copyright">
			<div class="container">
				2018 Copyright
			</div>
		  </div>
		</footer>
	</body>
</html>