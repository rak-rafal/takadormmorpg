<?php

	session_start();
	
	if((!isset($_POST['login'])) || (!isset($_POST['haslo']))) {
		
		header("Location: log.php");
		exit();
	}
	
	require_once "connect.php";
	
	$polaczenie = @new mysqli($host,$db_login,$db_pass,$db_name);
	$polaczenie->query("SET NAMES 'utf8'");	
		
	if($polaczenie->connect_errno!=0) {
		
		echo "Error: ".$polaczenie->connect_errno;
	} else {
		
		$login = $_POST['login'];
		$haslo = $_POST['haslo'];
		$login = htmlentities($login, ENT_QUOTES, "UTF-8");
		
		if($dane = @$polaczenie->query(
		sprintf("SELECT * FROM konto WHERE login='%s'",
		mysqli_real_escape_string($polaczenie,$login)
		))){
			
			$ilu_userow = $dane->num_rows;
			
			if($ilu_userow>0) {
				
				$r = $dane->fetch_assoc();
				if(password_verify($haslo,$r['haslo'])) {
					
					$_SESSION['log'] = true; // czy zalogowany
					
					$id = $_SESSION['id'] = $r['id'];
					$_SESSION['login'] = $r['login'];
					$_SESSION['duk'] = $r['datutwk'];
					$_SESSION['dol'] = $r['datostlog'];
					$_SESSION['monety'] = $r['monety'];
				
					unset($_SESSION['blad']);
					
					$data = date("Y-m-d");
					@$polaczenie->query("UPDATE konto SET datostlog='$data' WHERE id='$id'");
					
					$dane->close();
					header("Location: glowna2.php");
				} else {
				
					$_SESSION['blad'] = '<span style="color:white"><br> Nieprawidłowy login lub hasło!</span>';
				
					header("Location: log.php");
				}
			} else {
				
				$_SESSION['blad'] = '<span style="color:white"><br> Nieprawidłowy login lub hasło!</span>';
				
				header("Location: log.php");
			}
		}
		
		$polaczenie->close();
	}
?>