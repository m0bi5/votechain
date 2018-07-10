<?php

require_once 'dbconfig.php';

class USER
{	

	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function runQuery($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	
	public function lasdID()
	{
		$stmt = $this->conn->lastInsertId();
		return $stmt;
	}
	
	public function register($first_name,$last_name,$aadhar,$email,$password,$phone_number,$code,$status,$party_name)
	{
		try
		{							
			$password = md5($password);
			$zero="0";
			$stmt = $this->conn->prepare("INSERT INTO candidates(first_name,last_name,email,uidai,password,phone_number,code,verified,party_name,vote_count) 
			                                             VALUES(:f_name, :l_name, :Email,:aadhar, :password, :phone_num,:Authcode,:stat,:pname,:z)");
			$stmt->bindparam(":f_name",$first_name);
			$stmt->bindparam(":l_name",$last_name);
			$stmt->bindparam(":aadhar",$aadhar);
			$stmt->bindparam(":Email",$email);
			$stmt->bindparam(":password",$password);
			$stmt->bindparam(":phone_num",$phone_number);
			$stmt->bindparam(":Authcode",$code);
			$stmt->bindparam(":stat",$status);
			$stmt->bindparam(":pname",$party_name);
			$stmt->bindparam(":z",$zero);
			$stmt->execute();	
			return $stmt;
		}
		catch(PDOException $ex)
		{
			return $ex->getMessage();
		}
		
	}
	public function vote($aadhar,$phone_number)
	{
		try
		{							
			$stmt = $this->conn->prepare("INSERT INTO voted (uidai,phone_number) VALUES (:aadhar,:phone_num)");
			$stmt->bindparam(":aadhar",$aadhar);
			$stmt->bindparam(":phone_num",$phone_number);
			$stmt->execute();	
			return $stmt;
		}
		catch(PDOException $ex)
		{
			return $ex->getMessage();
		}
		
	}
	public function login($email,$upass)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT * FROM candidates WHERE email=:email");
			$stmt->execute(array(":email"=>$email));
			$userRow=$stmt->fetch(PDO::FETCH_ASSOC);
			
			if($stmt->rowCount() == 1)
			{
				if($userRow['verified']=="Y")
				{
					if($userRow['password']==md5($upass))
					{
						$_SESSION['userSession'] = $userRow['cid'];
						return true;
					}
					else
					{
						header("Location: index.php?error");
						exit;
					}
				}
				else
				{
					header("Location: index.php?inactive");
					exit;
				}	
			}
			else
			{
				header("Location: index.php?error");
				exit;
			}		
		}
		catch(PDOException $ex)
		{
			echo $ex->getMessage();
		}
	}
	
	
	public function is_logged_in()
	{
		if(isset($_SESSION['userSession']))
		{
			return true;
		}
	}
	
	public function redirect($url)
	{
		header("Location: $url");
	}
	
	public function logout()
	{
		session_destroy();
		$_SESSION['userSession'] = false;
	}
	
	function send_mail($email,$message,$subject)
	{						
		require_once('mailer/class.phpmailer.php');
		$mail = new PHPMailer();
		$mail->IsSMTP(); 
		$mail->SMTPDebug  = 0;                     
		$mail->SMTPAuth   = true;                  
		$mail->SMTPSecure = "ssl";                 
		$mail->Host       = "smtp.gmail.com";      
		$mail->Port       = 465;             
		$mail->AddAddress($email);
		$mail->Username="voteblocks@gmail.com";  
		$mail->Password="archpndym0bi5";            
		$mail->SetFrom('voteblocks@gmail.com','VoteBlocks Inc.');
		$mail->AddReplyTo("voteblocks@gmail.com","VoteBlocks Inc.");
		$mail->Subject    = $subject;
		$mail->MsgHTML($message);
		$mail->Send();
	}	
}