<?php
session_start();
require_once 'class.user.php';
$user_login = new USER();
$ttmt = $user_login->runQuery("SELECT * FROM candidates");
$ttmt->execute();
$rows = $ttmt->fetchAll(PDO::FETCH_ASSOC);
if(isset($_POST['btn-vote']))
{
	$aadhar= trim($_POST['uidai']);
	$phone_number = trim($_POST['phone']);
	$stmt = $user_login->runQuery("SELECT * FROM voted WHERE uidai=:aadhar");
	$stmt->execute(array(":aadhar"=>$aadhar));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	$ttmt = $user_login->runQuery("SELECT * FROM voted WHERE phone_number=:ph");
	$ttmt->execute(array(":ph"=>$phone_number));
	$row = $ttmt->fetch(PDO::FETCH_ASSOC);
	$msg="";
	if($stmt->rowCount() > 0)
	{
		$msg = "
		      <div class='white-text alert alert-error'>
					<strong>Sorry !</strong>  your aadhar has previously voted
			  </div>
			  ";
	}
	else if($ttmt->rowCount() > 0)
	{
		$msg = "
		      <div class='white-text alert alert-error'>
					<strong>Sorry !</strong>  your mobile number has previously voted
			  </div>
			  ";

	}
	else
	{

		$user_login->vote($aadhar,$phone_number);
				$msg = "
		      <div class='white-text alert alert-error'>
					Success! You have voted
			  </div>
			  ";
	}
}
?>
<!DOCTYPE html>
<html class="no-js">
    
    <head>
        <title>Cast your Vote</title>
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!--Import materialize.css-->
		<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
		<!--Let browser know website is optimized for mobile-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript">
			function find_vote()
			{
				var radio=document.getElementsByTagName('input');
				var i=0;
				for(i in radio)
				{
					if(radio[i].checked==true)
						return radio[i].value;
				}
			}
			function verify_otp()
			{
				return true
			}
			function cast_vote()
			{
				var isnum1 = /^\d+$/.test(document.getElementById("uidai").value);
				var isnum2 = /^\d+$/.test(document.getElementById("mobile").value);

				if(isnum1 && isnum2 && document.getElementById("uidai").value.length==12 && document.getElementById("mobile").value.length==10 && verify_otp())
				{
					$.post( "send.php", { "voter_name": "Anonymous", "candidate":find_vote(),"aadhar":document.getElementById("uidai").value } );
					alert(1)
				}
				else
					alert("Invalid Details!")
			}
		</script>

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
	 		.carousel .carousel-item {width:400px !important;}
		</style>
		<script type="text/javascript">
			$(document).ready(
				function()
				{
					var no_of_candidates=document.getElementById('candidate_db').textContent.split('&')[1].split(':')[1].split('&')[0];
					var names=document.getElementById('candidate_db').innerHTML.split('<br>')
					var i
					var d,k=[]
					for (i in names)
					{
						k.push(names[i].split("Name:")[1])
						try
						{
							names[i]=k[i].split("#")[0]
						}
						catch
						{
							d=0
						}
					}					

					var i=0;
					for(;i<parseInt(no_of_candidates);i+=1)
						document.getElementById('candidates').innerHTML+='<p><input name="cdates" class="with-gap" value="'+names[i]+'" type="radio" id="'+i+'"/><label class="white-text" for="'+i+'">'+names[i]+'</label></p>'
				}
			);

		</script>
    </head>
    
    <body class="teal darken-4">
    			<!--Import jQuery before materialize.js-->
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/materialize.min.js"></script>

		<nav class='green accent-3'>
			<div class="nav-wrapper">

				<a href="#" style="margin-left: 10px;" class="brand-logo">
					VoteBlocks <i class="material-icons fas fa-cubes"></i>
				</a>
				<ul id="nav-mobile" class="right hide-on-med-and-down">
					<li><a href="logout.php">Login</a></li>
					<li><a href="index.php">View Results</a></li>
					<li><a href="vote.php">Vote</a></li>
				</ul>
			</div>
		</nav>
		<main class="row center-align">

				<div id="candidate_db" style="display: none;">
							<?php
			foreach ($rows as $row) {
				echo "Name:".$row['first_name']." ".$row['last_name']."#"	."Party:".$row['party_name']."#"."Vote:".$row['vote_count']."#<br>";
			}
			echo "&ROWCOUNT:".$ttmt->rowCount()."&";
			?>
				</div>
				<div  id ="cast_vote">
					<h1 class="center white-text">
						Cast Vote
					</h1>

					<div >

						<form enctype="multipart/form-data" method="post" onsubmit="cast_vote()" id="voter_form" style="margin:10%" class="offset-s4 form-signin hoverable center-align card-panel black darken-4">	 		
							<br>						
							<?php 
							if(isset($msg))
							{
								echo $msg;
							}
							?>	
							<br>
							<div class="row">
								<div class="input-field col s12">
									<i class="white-text material-icons prefix">fingerprint</i>
									<input class="input-block-level white-text" id="uidai" name="uidai" type="text" >
									<label for="icon_prefix">UIDAI</label>
								</div>
								<div class="input-field col s7">
									<i class="white-text material-icons prefix">phone</i>
									<input class="input-block-level white-text" id="mobile" name="phone" type="text" >
									<label for="icon_prefix">Phone Number</label>
								</div>

								<div class="input-field col s5">
									<i class="white-text material-icons prefix">message</i>
									<input class="input-block-level white-text" id="otp" type="password">
									<label for="icon_lock">OTP</label>
								</div>
							</div>
							<div class="row">
								<div class="input-field col s12">
								</div>
							</div>
							<div id="candidates"></div>
					 		<button name ="btn-vote" class="btn waves-effect waves-teal">Vote</button>
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