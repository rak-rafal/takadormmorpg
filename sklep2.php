<?php

	session_start();
	
	if(!isset($_SESSION['log'])) {
		
		header("Location: glowna.php");
		exit();
	}
	
	require_once "connect.php";
	
	$polaczenie = @new mysqli($host,$db_login,$db_pass,$db_name);
	$polaczenie->query("SET NAMES 'utf8'");
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
        <aside class="col-2" id="side1">
            <?php
			
			echo "<br><h1>Magazyn konta:</h1><br>";
			
			$id_gracza = $_SESSION['id'];
			$dane2 = @$polaczenie->query("SELECT nazwa, ilosc FROM przedmioty JOIN sklep ON przedmioty.idprzedmiotu=sklep.id WHERE idkonta='$id_gracza'");
			
			if($dane2->num_rows > 0) {

				while($ro = $dane2->fetch_assoc()) {
					
					echo $ro['nazwa']." x".$ro['ilosc']."<br>";
				}
			}				
			
			?>
        </aside>
        <div class="col-1"></div>
        <article class="col-6">
			<table>
                <tr>
                    <th>Nazwa przedmiotu</th>
                    <th>Cena</th>
					<th>Opis</th>
					<th>Zakup</th>
                </tr>	
			<?php
				
				$dane1 = @$polaczenie->query("SELECT * FROM sklep");
				$ile_danych = $dane1->num_rows;
				
				if($ile_danych > 0) {
					
					while($r = $dane1->fetch_assoc()) {
						
						$id = $r['id'];
						$cena = $r['cena'];
						
						echo "<tr>";
						echo "<td>".$r['nazwa']."</td>";
						echo "<td>".$r['cena']." monet</td>";
						echo "<td>".$r['opis']."</td>";
						echo '<td><form method="post" action="zakup.php?id='.$id.'&cena='.$cena.'">'
								.'<input type="number" name="ilosc" min="1" max="100">
								<input type="submit" value="KUP">
							</form></td>';
						echo "</tr>";
					}
					echo "</table>";
				} 
				
				if(isset($_SESSION['biedak'])) {
					
					$wiadomosc = "Niewystarczająca ilość monet";
					echo "<script type='text/javascript'>alert('$wiadomosc');</script>";
					unset($_SESSION['biedak']);
				}
				
				$dane1->close();
			?>
        </article>
        <div class="col-1"></div>
        <aside class="col-2" id="side2">
            <?php
				
				echo "<br><h1>Gracz: ".$_SESSION['login']."</h1><br>";
				
				$id = $_SESSION['id'];
				
				$dane = @$polaczenie->query("SELECT monety FROM konto WHERE id='$id'");
				$r = $dane->fetch_assoc();
				$_SESSION['monety'] = $r['monety'];
				
				echo "Stan monet = ".$r['monety'];
				
				$dane->close();
				$polaczenie->close();
			?>
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