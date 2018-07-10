<?php
session_start();
require_once 'class.user.php';
$user = new USER();

if($user->is_logged_in()!="")
{
	$user->redirect('home.php');
}

if(isset($_POST['btn-submit']))
{
	$email = $_POST['txtemail'];
	
	$stmt = $user->runQuery("SELECT cid FROM candidates WHERE email=:email LIMIT 1");
	$stmt->execute(array(":email"=>$email));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);	
	if($stmt->rowCount() == 1)
	{
		$id = base64_encode($row['cid']);
		$code = md5(uniqid(rand()));
		
		$stmt = $user->runQuery("UPDATE candidates SET code=:token WHERE email=:email");
		$stmt->execute(array(":token"=>$code,"email"=>$email));
		
		$message= "
				   Hello , $email
				   <br /><br />
				   We got requested to reset your password, if you do this then just click the following link to reset your password, if not just ignore                   this email,
				   <br /><br />
				   Click Following Link To Reset Your Password 
				   <br /><br />
				   <a href='http://localhost/voteblocks/resetpass.php?id=$id&code=$code'>click here to reset your password</a>
				   <br /><br />
				   thank you :)
				   ";
		$subject = "Password Reset";
		
		$user->send_mail($email,$message,$subject);
		
		$msg = "<div class='white-text alert alert-success'>
					We've sent an email to $email.
                    Please click on the password reset link in the email to generate new password. 
			  	</div>";
	}
	else
	{
		$msg = "<br><div class='white-text alert alert-danger'>
					This email has not yet registered.<br>
				<a href='signup.php'>Sign Up</a>
			    </div>";
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
	</head>
	<body class="cyan darken-4">
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
					Forgot Password
				</h1>
				<div style='margin: 30px;margin-left: 34%' class="row">
					<form method="post" class="form-signin hoverable center-align col s12 m6 card-panel black darken-4">
	        
			        	<?php
							if(isset($msg))
							{
								echo $msg;
							}
							else
							{
								?>
					              	<div class='white-text alert alert-info'>
									Please enter your email address. You will receive a link to create a new password via email.!
									</div>  
				                <?php
							}
						?>		 		
						<br>
						<div class="row">
							<div class="input-field col s12">
								<i class="white-text material-icons prefix">email</i>
								<input class="input-block-level white-text" id="icon_prefix" name="txtemail" type="email" class="validate">
								<label for="icon_prefix">Email</label>
							</div>
						</div>
				 		<button type="submit" name="btn-submit" href="#!" class="btn waves-effect waves-teal">Generate Password</button>
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