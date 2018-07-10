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
	
	$statusY = "Y";
	$statusN = "N";
	
	$stmt = $user->runQuery("SELECT cid,verified FROM candidates WHERE code=:code AND cid=:id LIMIT 1");
	$stmt->execute(array(":id"=>$id,":code"=>$code));
	$row=$stmt->fetch(PDO::FETCH_ASSOC);
	if($stmt->rowCount() > 0)
	{
		if($row['verified']==$statusN)
		{
			$stmt = $user->runQuery("UPDATE candidates SET verified=:stat WHERE cid=:uID");
			$stmt->bindparam(":uID",$id);
			$stmt->bindparam(":stat",$statusY);
			$stmt->execute();	
			
			$msg = "
		           <h4 class='white-text alert alert-success'>
					  <strong> Your Account is Now Activated : <a href='index.php'>Login here</a>
			       </h4>
			       ";	
		}
		else
		{
			$msg = "
		           <h4 class='white-text alert alert-error'>
					  <strong>Sorry !</strong>  Your Account is already Activated : <a href='index.php'>Login here</a>
			       </h4>
			       ";
		}
	}
	else
	{
		$msg = "
		       <h4 class='white-text alert alert-error'>
			   <strong>Sorry !</strong>  No Account Found : <a href='signup.php'>Signup here</a>
			   </h4>
			   ";
	}	
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Confirm Registration</title>
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
	<body id="login" class="cyan darken-4">
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
			<div class="container">
				<?php if(isset($msg)) { echo $msg; } ?>
			</div> <!-- /container -->
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