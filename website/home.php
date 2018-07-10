<?php
session_start();
require_once 'class.user.php';
$user_home = new USER();

if(!$user_home->is_logged_in())
{
	$user_home->redirect('index.php');
}

$stmt = $user_home->runQuery("SELECT * FROM candidates WHERE cid=:uid");
$stmt->execute(array(":uid"=>$_SESSION['userSession']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$ttmt = $user_home->runQuery("SELECT * FROM candidates");
$ttmt->execute();
$rows = $ttmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html class="no-js">
    
    <head>
        <title><?php echo $_SESSION['userSession']; ?></title>
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
 		.carousel .carousel-item {width:400px !important;}
	</style>
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
					<li>
						<a style="display: inline-flex;" href="dashboard.php">
							<i class="white-text material-icons prefix">person</i>
							Welcome <?php echo $row['first_name']; ?>
						</a>
					</li>
					<li><a href="logout.php">Logout</a></li>
					<li><a href="index.php">View Results</a></li>
					<li><a href="vote.php">Vote</a></li>
				</ul>
			</div>
		</nav>
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
					var party=document.getElementById('candidate_db').innerHTML.split('<br>')
					k=[]
					for (i in party)
					{
						k.push(party[i].split("Party:")[1])
						try
						{
							party[i]=k[i].split("#")[0]
						}
						catch
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
						catch
						{
							d=0
						}
					}
					var i=0;
					for(;i<parseInt(no_of_candidates);i+=1)
						document.getElementsByClassName('carousel')[0].innerHTML+='<a class="carousel-item" href="#one!"><div class="center-align col s12"><div class="card-panel hoverable amber darken-4"><img style="margin-top: -100px" class="responsive-img circle center" src="img/1.png"><br><br><span class="white-text">Candidate Name:<span class="candidate_name">'+names[i]+'</span><br>Vote Count:<span class="vote_count">'+vc[i]+'</span><br>Party:<span class="party">'+party[i]+'</span><br></span></div></div></a>'

					$('.carousel').carousel();
				}
				);
		</script>
		<main>
			<div style="display: none;" id="candidate_db">
				<?php
					foreach ($rows as $row) {
						echo "Name:".$row['first_name']." ".$row['last_name']."#"	."Party:".$row['party_name']."#"."Vote:".$row['vote_count']."#<br>";
					}
					echo "&ROWCOUNT:".$ttmt->rowCount()."&";
				?>
			</div>
			<h1 class="center white-text">
				Current Results
			</h1>
			<div>
			<div id="political_cards">
				<div class="carousel">



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