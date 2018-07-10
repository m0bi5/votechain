<?php
require_once 'class.user.php';
$user = new USER();

if(empty($_GET['id']) && empty($_GET['code']))
{
	$user->redirect('index.php');
}

if(isset($_GET['id']) && isset($_GET['code']))
{
	$id = base64_decode($_GET['id']);
	$code = $_GET['code'];
	
	$stmt = $user->runQuery("SELECT * FROM candidates WHERE cid=:uid AND code=:token");
	$stmt->execute(array(":uid"=>$id,":token"=>$code));
	$rows = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if($stmt->rowCount() == 1)
	{
		if(isset($_POST['btn-reset-pass']))
		{
			$pass = $_POST['pass'];
			$cpass = $_POST['confirm-pass'];
			
			if($cpass!==$pass)
			{
				$msg = "<div class='white-text alert alert-block'>
						<strong>Sorry!</strong>  Password Doesn't match. 
						</div>";
			}
			else
			{
				$password = md5($cpass);
				$stmt = $user->runQuery("UPDATE candidates SET password=:upass WHERE cid=:uid");
				$stmt->execute(array(":upass"=>$password,":uid"=>$rows['userId']));
				
				$msg = "<div class='white-text alert alert-success'>
						Password Changed.
						</div>";
				header("refresh:5;index.php");
			}
		}	
	}
	else
	{
		$msg = "<div class='white-text alert alert-success'>
				<button class='close' data-dismiss='alert'>&times;</button>
				No Account Found, Try again
				</div>";
				
	}
	
	
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Password Reset</title>
		<!--Import Google Icon Font-->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!--Import materialize.css-->
		<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
		<!--Let browser know website is optimized for mobile-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

		<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
  </head>

  <body id="login" class="teal darken-4">
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
		<div id="login_form">
			<h1 class="center white-text">
				Reset Password
			</h1>
			<div style='margin: 30px;margin-left: 34%' class="row">

				<form method="post" class="white-text form-signin hoverable center-align col s12 m6 card-panel black darken-4">
					<br>
					<strong>Hello !</strong>  <?php echo $rows['fName'] ?> you are here to reset your forgetton password. 		
					<br>
					<?php if(isset($msg)) { echo $msg; } ?>
					<br>
					<div class="row">
						<div class="input-field col s12">
							<i class="white-text material-icons prefix">lock</i>
							<input class="input-block-level white-text" id="icon_prefix" name="pass" type="password" class="validate">
							<label for="icon_prefix">Password</label>
						</div>
						<div class="input-field col s12">
							<i class="white-text material-icons prefix">lock</i>
							<input name="confirm-pass" class="input-block-level white-text" id="icon_telephone" type="password" class="validate">
							<label for="icon_telephone">Confirm Password</label>
						</div>
					</div>
			 		<button type="submit" name="btn-reset-pass" class="btn waves-effect waves-teal">Reset Password</button>
			 		<br>
			 		<br>
				</form>
			</div>
		</div>
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