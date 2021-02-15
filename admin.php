<?php

	session_start();
	
	if(!isset($_SESSION['log'])) {
		
		header("Location: glowna.php");
		exit();
	}
	
	if($_SESSION['id'] != 10) {
			
		header("Location: glowna2.php");
		exit();
	}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        << TAKADOR >>
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
            <a class="navbar-brand" href="glowna.html">
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
                            <a class="nav-link" href="glowna2.php">Nowości</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link" href="ranking2.php">Ranking</a>
                        </li>
                        <li class="nav-item mx-2">
                            <a class="nav-link" href="sklep2.php">Sklep</a>
                        </li>
                        <li class="nav-item ml-2 mp-5">
                            <a class="nav-link" href="World2.php">Świat gry</a>
                        </li>
                        <li class="lr nav-item ml-5 mp-1">
                            <a class="nav-link" href="konto.php">Konto</a>
                        </li>
                        <li class="lr nav-item ml-1">
                            <a class="nav-link" href="logout.php">Wyloguj</a>
                        </li>
                    </ul>
                </div>

            </nav>
        </div>
    </header>

    <section id="section" class="row">
        <aside class="col-2" id="side1n">

        </aside>
        <article class="col-8">
			<?php
			
			require_once "connect.php";
		
			$polaczenie = @new mysqli($host,$db_login,$db_pass,$db_name);
			$polaczenie->query("SET NAMES 'utf8'");
			
			if(isset($_POST['tnews'])) {
							
				$temat = $_POST['tnews'];
				$tresc = $_POST['tresc'];
				$data = date("Y-m-d");
				$wiadomosc = "Pomyślnie dodano!";
				echo "<script type='text/javascript'>alert('$wiadomosc');</script>";
				$polaczenie->query("INSERT INTO news VALUES(NULL,'$temat','$tresc','$data')");							
			}
			
			if(isset($_GET['monety'])) {
				
				$ilosc = $_POST['ilosc'];
				$obecnie = $_GET['monety'];
				$id = $_GET['id'];
				
				$zmiana = $obecnie + $ilosc;
				
				$polaczenie->query("UPDATE konto SET monety='$zmiana' WHERE id='$id'");
				
				$wiadomosc = "Pomyślnie wykonano operacje na monetach!";
				echo "<script type='text/javascript'>alert('$wiadomosc');</script>";
			}
			
			if(isset($_POST['info']) || isset($wybor)) {
				
				$wybor = $_POST['info'];
					
				if($wybor==1) {
						
						$dane1 = @$polaczenie->query("SELECT * FROM konto ");
						$ile_danych = $dane1->num_rows;
							
						if($ile_danych > 0) {
								
							echo "<table>";
								while($r = $dane1->fetch_assoc()) {
									
									echo "<tr>";
									echo '<td>'.$r['id']."</td>";
									echo "<td>".$r['login']."</td>";
									echo "<td>".$r['email']."</td>";
									echo "<td>".$r['wiek']."</td>";
									echo "<td>".$r['datutwk']."</td>";
									echo "<td>".$r['datostlog']."</td>";
									echo "</tr>";
								}
							echo "</table>";
						}
						
						$dane1->close();
				}
					
					
				if($wybor==2) {
							
						echo '<form method="post">
									<select name="tnews">
										<option value="1">Aktualizacja</option>
										<option value="2">Prace serwisowe</option>
										<option value="3">Event</option>
										<option value="4">Inne</option>
									</select><br><br>
									<textarea name="tresc" rows="5" cols="50">Treść...</textarea><br>
									<input type="submit" value="Dodaj">
								</form>';						
				}	
					
				if($wybor==3) {
						
						$dane3 = $polaczenie->query("SELECT id, login, monety FROM konto");
						echo "<br><table>";
						while($r = $dane3->fetch_assoc()) {
							
							echo "<tr>";
							echo "<td>".$r['login']." - ".$r['monety']."</td>";
							echo '<td><form method="post" action="admin.php?id='.$r['id'].'&monety='.$r['monety'].'">
								<input type="number" name="ilosc">
								<input type="submit" value="ZMIEŃ">
							</form></td></tr>';
						}
						echo "</table>";
						$dane3->close();
				}
			} else {
				
				echo '<br><br><br>
				<form method="post">
					<label><h2>Jakie dane mają być zmodyfikowane?</h2></label>
					<select name="info">
						<option value="1">Wyświetl użytkowników</option>
						<option value="2">Dodaj news</option>
						<option value="3">Dodaj/Odejmij monety z konta</option>
					</select>
					<input type="submit" value="WYBIERZ">
				</form>';
			}
			
						
			$polaczenie->close();
			?>
        </article>
        <aside class="col-2" id="side2b">
        </aside>
    </section>

    <footer class="row-fluid stopka">
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