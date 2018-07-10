<?php
	require_once 'class.user.php';
	$user_login = new USER();
	$ret_val="";
	$ttmt = $user_login->runQuery("SELECT * FROM candidates");
	$ttmt->execute();
	$rows = $ttmt->fetchAll(PDO::FETCH_ASSOC);

	if(isset($_POST['go_vote']))
	{
		header('Location: '.'vote.php?uidai='.$_POST['uidai']);
	}
	session_start();
	foreach ($rows as $row) {
		 $ret_val.="Name:".$row['first_name']." ".$row['last_name']."#"	."Party:".$row['party_name']."#"."Vote:".$row['vote_count']."#<br>";
	}
	$ret_val.="&ROWCOUNT:".$ttmt->rowCount()."&";
	echo $ret_val;
?>