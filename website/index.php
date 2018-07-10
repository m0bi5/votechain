<?php
session_start();
require_once 'class.user.php';
$user_login = new USER();

if($user_login->is_logged_in()!="")
{
	$user_login->redirect('home.php');
}

if(isset($_POST['btn-login']))
{
	$email = trim($_POST['txtemail']);
	$upass = trim($_POST['txtupass']);
	
	if($user_login->login($email,$upass))
	{
		$user_login->redirect('home.php');
	}
}
$ttmt = $user_login->runQuery("SELECT * FROM candidates");
$ttmt->execute();
$rows = $ttmt->fetchAll(PDO::FETCH_ASSOC);

if(isset($_POST['go_vote']))
{
	header('Location: '.'vote.php?uidai='.$_POST['uidai']);
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
		<script type="text/javascript">
				function load_votes()
				{

					$.ajax({
					  type: "GET",
					  url: "retreive_votes.php",
					  datatype: "html",
					  success: function(data) {
					    document.getElementById("candidate_db").innerHTML=data;
					    vcs=document.getElementsByClassName("vote_count");
					    var no_of_candidates=document.getElementById('candidate_db').textContent.split('&')[1].split(':')[1].split('&')[0];
						var names=document.getElementById('candidate_db').innerHTML.split('<br>')
						
						var vc=document.getElementById('candidate_db').innerHTML.split('<br>')
						k=[]
						for (i in vc)
						{
							k.push(vc[i].split("Vote:")[1])
							try
							{
								vc[i]=k[i].split("#")[0]
							}
							catch(e)
							{
								d=0
							}
						}
						var i=0;
					    for (i in vcs)
					    	vcs[i].innerHTML=vc[i]
							
					    }

					});
				}		
	
function pollFunc(fn, timeout, interval) {
    var startTime = (new Date()).getTime();
    interval = interval || 100,
    canPoll = true;

    (function p() {
        canPoll = ((new Date).getTime() - startTime ) <= timeout;
        if (!fn() && canPoll)  { // ensures the function exucutes
            setTimeout(p, interval);
        }
    })();
}
pollFunc(load_votes, 6000000000, 1000);
    
		</script>
		<style type="text/css">
			.carousel .carousel-item {width:400px !important;}
			/* Flipclock Skeleton */

		</style>
		<script type="text/javascript">
			
			function clear()
			{
				var i=document.getElementsByTagName("form");
				for(var k in i)
					i[k].reset();
			}
			var data={
			  "count":"00"
			};

	</script>

	</head>
	<body onload="clear();" class="cyan darken-4">
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
		<div style="display:none;" id="candidate_db">

<script type="text/javascript">
$( document ).ready(function() {
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
		catch(e)
		{
			d=0
		}
	}					
	var party=document.getElementById('candidate_db').innerHTML.split('<br>')
	k=[]
	for (i in party)
	{
		k.push(party[i].split("Party:")[1])
		try
		{
			party[i]=k[i].split("#")[0]
		}
		catch(e)
		{
			d=0
		}
	}
	var vc=document.getElementById('candidate_db').innerHTML.split('<br>')
	k=[]
	for (i in vc)
	{
		k.push(vc[i].split("Vote:")[1])
		try
		{
			vc[i]=k[i].split("#")[0]
		}
		catch(e)
		{
			d=0
		}
	}
	var i=0;
	for(;i<parseInt(no_of_candidates);i+=1)
	{
		document.getElementsByClassName('carousel')[0].innerHTML+='<a class="carousel-item" href="#one!"><div class="center-align col s12"><div class="card-panel hoverable amber darken-4"><img style="margin-top: -100px" class="responsive-img circle center" src="img/1.png"><br><br><span class="white-text">Candidate Name:<span class="candidate_name">'+names[i]+'</span><br>Vote Count:<span id=vc"'+i+'" class="vote_count">'+vc[i]+'</span><br>Party:<span class="party">'+party[i]+'</span><br></span></div></div></a>'

	}
	$('.carousel').carousel();
});

</script>

		</div>
		<div class="row">
			<div class="col s12">
				<h1 class="center white-text">
					Current Results
				</h1>
				<div id="political_cards">
					<div class="carousel">
						
					</div>
				</div>				
			</div>
		</div>
		<br>
		<div id="login_form">
			<h1 class="center white-text">
				Candidate Login
			</h1>
			<div style='margin: 30px;margin-left: 34%' class="row">

				<form method="post" class="form-signin hoverable center-align col s12 m6 card-panel black darken-4">
										<?php
				        if(isset($_GET['error']))
						{
							?>
				            <div class='white-text alert alert-success'>
				            	<br>
								<strong>Wrong Details!</strong> 
								<br>
								<a href="fpass.php" class="waves-effect">Forgot Password?</a>
							</div>
				            <?php
						}
					?>
					<?php 
						if(isset($_GET['inactive']))
						{
							?>
							<br>	
				            <div class='white-text alert alert-error'>
								This Account is not Activated Go to your Inbox and Activate it. 
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
						<div class="input-field col s12">
							<i class="white-text material-icons prefix">lock</i>
							<input name="txtupass" class="input-block-level white-text" id="icon_telephone" type="password" class="validate">
							<label for="icon_telephone">Password</label>
						</div>
					</div>
			 		<button type="submit" name="btn-login" class="btn waves-effect waves-teal">Login</button>
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