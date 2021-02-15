<?php

	session_start();
	
	if(!isset($_SESSION['log'])) {
		
		header("Location: glowna.php");
		exit();
	}
	
	require_once "connect.php";
	
	$polaczenie = @new mysqli($host,$db_login,$db_pass,$db_name);
	$polaczenie->query("SET NAMES 'utf8'");	
		
	if($polaczenie->connect_errno!=0) {
		
		echo "Error: ".$polaczenie->connect_errno;
	} else {
		
		$name = $_POST['npostaci'];
		$name= htmlentities($name, ENT_QUOTES, "UTF-8"); // zamiana na encje
		$name = mysqli_real_escape_string($polaczenie,$name);

		if((strlen($name)<4) || (strlen($name)>15)) {
		// sprawdzamy długość
			$_SESSION['erroradd1'] = true;
			header("Location: konto.php");
			exit();
		}
		
		if(ctype_alnum($name)==false) {
		// sprawdzenie czy nick składa się ze znaków alfanumerycznych
			$_SESSION['erroradd2'] = true;
			header("Location: konto.php");
			exit();
		}
		
		$sprawdz = @$polaczenie->query("SELECT id FROM postac WHERE nazwa='$name'");
		$s = $sprawdz->num_rows;
		if($s > 0) {
			
			$_SESSION['erroradd3'] = true;
			header("Location: konto.php");
			exit();
		}
		
		$class = $_POST['klasa'];
		$id = $_SESSION['id'];
		$ile = ((@$polaczenie->query("SELECT COUNT(*) AS ile FROM postac"))->fetch_assoc())['ile'];
		$ile = $ile + 1;
		
		@$polaczenie->query("INSERT INTO postac VALUES(NULL,'$id','$name','1','$class','1000','1000','$ile')");
		
		
		header("Location: konto.php");
		$sprawdz->close();
		$polaczenie->close();
	}
?>