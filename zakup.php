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
		
		$cena = $_GET['cena']; // cena zakupionego przedmiotu
		$ilosc = $_POST['ilosc']; // ilość przedmiotów kupionych
		$id_przedmiotu = $_GET['id']; // id kupionego przedmiotu
		$id_gracza = $_SESSION['id']; // id gracza który jest obecnie zalogowany i kupił przedmiot
		
		$suma = $ilosc * $cena; // należność
		
		// sprawdzamy czy starczy monet
		if($suma > $_SESSION['monety']) {
			
			$_SESSION['biedak'] = true;
		} else { // odjęcie z konta gracza monet 
			
			$pobierz = $_SESSION['monety'] - $suma;
			@$polaczenie->query("UPDATE konto SET monety='$pobierz' WHERE id='$id_gracza'");
		}

		// w tabeli PRZEDMIOTY dodajemy kolejny zakup/transakcje czyli id_konta id_przedmiotu ilosc (jeśli gracz ma już taki przedmiot to dodajemy tylko ilosc)
		$dane1 = @$polaczenie->query("SELECT * FROM przedmioty WHERE idkonta='$id_gracza' AND idprzedmiotu='$id_przedmiotu'");
		if($r=$dane1->fetch_assoc()) {

				$id_transakcji = $r['id'];
				$obecna = $r['ilosc'];
				$nowa = $obecna + $ilosc;
				
				@$polaczenie->query("UPDATE przedmioty SET ilosc='$nowa' WHERE id='$id_transakcji'");
		} else {
			
			@$polaczenie->query("INSERT INTO przedmioty VALUES(NULL,'$id_gracza','$id','$ilosc')");
		}
		
		$dane1->close();
		$polaczenie->close();
		header('Location: sklep2.php');
	}
?>