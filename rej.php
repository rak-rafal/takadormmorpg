<?php

	session_start();
	
	if((isset($_SESSION['log']))&&($_SESSION['log']==true)) {
	
		header('Location: glowna2.php');
		exit();
	} else if(isset($_POST['mail'])) {
		
		$flaga = true;
		
		$login = $_POST['login'];
		if((strlen($login)<3) || (strlen($login)>15)) {
		// sprawdzamy długość
			$flaga=false;
			$_SESSION['e_l']="Login długości od 4 do 15 znaków!";
		}
		if(ctype_alnum($login)==false) {
		// sprawdzenie czy nick składa się ze znaków alfanumerycznych
			$flaga=false;
			$_SESSION['e_l']="Login może składać się tylko z liter i cyfr!";
		}
		
		$mail = $_POST['mail'];
		// ochrona przed zabronionymi znakami
		$bmail = filter_var($mail, FILTER_SANITIZE_EMAIL);
		//spr poprawności adresu oraz długości z wejściowym
		if((filter_var($bmail,FILTER_VALIDATE_EMAIL)==false) || ($bmail!=$mail)) {
			
			$flaga=false;
			$_SESSION['e_m']="Niepoprawny adres e-mail!";
		}
		
		$haslo = $_POST['haslo'];
		$haslo2 = $_POST['haslo2'];
		if((strlen($haslo)<8) || (strlen($haslo)>20)) {
		// spr długosć hasła
			$flaga=false;
			$_SESSION['e_h']="Hasło musi posiadać od 8 do 20 znaków";
		}
		if($haslo!=$haslo2) {
		// porównanie haseł
			$flaga=false;
			$_SESSION['e_h']="Podane hasła różnią się od siebie.";
		}
		//hashujemy hasło
		$haslo_h = password_hash($haslo, PASSWORD_DEFAULT);
		
		$wiek = $_POST['wiek'];
		
		
		require_once "connect.php";
		
		try {
			
			$polaczenie = new mysqli($host,$db_login,$db_pass,$db_name);
			$polaczenie->query("SET NAMES 'utf8'");
			
			if($polaczenie->connect_errno!=0) {
		
				throw new Exception(mysqli_connect_errno());
			} else {
				// czy e-mail w bazie?
				$rezultat = $polaczenie->query("SELECT id FROM konto WHERE email='$mail'");
				if(!$rezultat) throw new Exception($polaczenie->error);
				$ile = $rezultat->num_rows;
				if($ile>0) {
					
					$flaga=false;
					$_SESSION['e_m']="Podany e-mail jest już przypisany do czyjegoś konta!";
				}
				// czy podany login w bazie 
				$rezultat = $polaczenie->query("SELECT id FROM konto WHERE login='$login'");
				if(!$rezultat) throw new Exception($polaczenie->error);
				$ile = $rezultat->num_rows;
				if($ile>0) {
					
					$flaga=false;
					$_SESSION['e_l']="Podany login jest już zajęty!";
				}
				
				if($flaga==true) {
				
					// wszystko działa 
					$data = date("Y-m-d");
					if($polaczenie->query("INSERT INTO konto VALUES(NULL,'$login','$mail','$haslo_h','$wiek','$data','$data',0)")) {
						
						$_SESSION['witam']= "Rejestracja przebiegła pomyślnie, dziękujemy!";
						header("Location: log.php");
					} else {

						throw new Exception($polaczenie->error);
					}
				}
				
				$polaczenie->close();
			}
		} catch(Exception $e) {
			
			echo '<span style="color:white; font-size:25px;"> Błąd przy rejestracji!</span>';
			echo "<br />".$e;
		}
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        << TAKADOR>>
    </title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="style.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
</head>

<body class="container-fluid">
    <header>
        <div class="container">
            <a class="navbar-brand" href="glowna.php">
                <img src="logo.png" height="50" class="d-inline-block align-bottom" alt="">
            </a>

            <nav class="row navbar navbar-expand-lg">
                <button class="navbar-toggler" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent15" aria-controls="navbarSupportedContent15"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent15">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item mp-2">
                            <a class="nav-link" href="glowna.php">Nowości</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link" href="ranking.php">Ranking</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link" href="sklep.php">Sklep</a>
                        </li>
                        <li class="nav-item ml-2 mp-5">
                            <a class="nav-link" href="World.php">Świat gry</a>
                        </li>
                        <li class="lr nav-item ml-5 mp-1">
                            <a class="nav-link" href="log.php">Zaloguj</a>
                        </li>
                        <li class="lr nav-item ml-1">
                            <a class="nav-link" href="rej.php">Rejestracja</a>
                        </li>
                    </ul>
                </div>

            </nav>
        </div>
    </header>

    <section id="section" class="row">
        <div id="rej">
            <form method="post">
                <label>Login</label>
                <input type="text" name="login" placeholder="Twój login..">
				<?php
				
					if(isset($_SESSION['e_l'])) {
					
						echo '<div class="error">'.$_SESSION['e_l'].'</div>';
						unset($_SESSION['e_l']);
					}
				?>

                <label>Hasło</label>
                <input type="password" name="haslo" placeholder="Twoje hasło..">
				<?php
				
					if(isset($_SESSION['e_h'])) {
					
						echo '<div class="error">'.$_SESSION['e_h'].'</div>';
						unset($_SESSION['e_h']);
					}
				?>

                <label>Powtórz hasło</label>
                <input type="password" name="haslo2" placeholder="Powtórz hasło..">

                <label>Adres e-mail</label>
                <input type="text" name="mail" placeholder="Twój e-mail..">
				<?php
				
					if(isset($_SESSION['e_m'])) {
					
						echo '<div class="error">'.$_SESSION['e_m'].'</div>';
						unset($_SESSION['e_m']);
					}
				?>
				
				<label>Wiek (min. 13 lat)</label>
				<input type="number" name="wiek">

                <label>Skąd wiesz o grze?</label>
                <select name="info">
                    <option value="1">Reklama w internecie</option>
                    <option value="2">Od znajomego</option>
                    <option value="3">Inne źródło</option>
                </select>
				
                <input type="submit" value="Zarejestruj">
            </form>
        </div>
    </section>

    <footer class="row-fluid">
        Rafał Rak
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>

</html>