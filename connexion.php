<?php session_start();?>
<?php 
	include 'fonctions.php';
?>
<!DOCTYPE html>
<html lang="fr" >
	<head>
		<meta charset="utf-8">
		<link href="style.css" rel="stylesheet" type="text/css" />
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
		<title>Connexion</title>
	</head>
	<body>
	<div class="container">
		<header>
            <h1>Connexion</h1>
		</header>
		<div class="row mb-3">
				<div class="col-md-5">
		<nav>
			<?php
				// affichage du formulaire de connexion ou le menu avec le nom de la personne
				if (empty($_SESSION)) {
					afficheFormulaireConnexion();
				}
                
				// test de la connexion
				// Point d'entrée ou Route pour traiter le formulaire
				if (empty($_SESSION) && !empty($_POST) && isset($_POST['login'])  && isset($_POST['password']) ){
					//Creation fichier log
					// 1 : on ouvre le fichier
					$monfichier = fopen('acces.log', 'a+');
					// 2 : on fera ici nos opérations sur le fichier...
					fputs($monfichier,"\nLa personne avec cet adresse IP: ".$_SERVER['REMOTE_ADDR']. " a tente de se connecte avec l'identifiant " .$_POST['login']. " le ".date('l jS \of F Y h:i:s A'));
					fputs($monfichier, "\n");
					// 3 : quand on a fini de l'utiliser, on ferme le fichier
					fclose($monfichier);
					if(compteExiste($_POST['login'],$_POST['password'])){
						echo '<p>Connexion en cours...</p>';
						$_SESSION['login']=$_POST['login'];
						$_SESSION['statut']=isAdmin($_POST['login']) ;
						// 1 : on ouvre le fichier
						$monfichier = fopen('acces.log', 'a+');
						// 2 : on fera ici nos opérations sur le fichier...
						fputs($monfichier,"\tL'utilisateur ".$_POST['login']." a reussis a se connecter en tant que ".$_SESSION['statut'] );
						fputs($monfichier, "\n");
						// 3 : quand on a fini de l'utiliser, on ferme le fichier
						fclose($monfichier);
						redirect("index.php",1);
						
					}
					else {
						// 1 : on ouvre le fichier
						$monfichier = fopen('acces.log', 'a+');
						// 2 : on fera ici nos opérations sur le fichier...
						fputs($monfichier,"\tConnexion refuse");
						fputs($monfichier, "\n");
						// 3 : quand on a fini de l'utiliser, on ferme le fichier
						fclose($monfichier);
						redirect("connexion.php",1);
					}
				}
			?>
		</nav>
		</div>
				<div class="col-md-7">
				<section>
			<?php
		// Affichage du message accueil en fonction de la connexion

					echo '<h2>Veuillez vous</h2> <h2 class="statut">connecter.</h2>';
				
				?>
			</section>
			</div>
			</div>
		<footer>
			<p class="copy">
				Yves-Mayeul ARANGUREN - Franck DESBONNETS © 2022
			</p>
			<p class="iut">
				IUT de Lannion
			</p>
			
			<p> Emplacement: <?php echo $_SERVER['PHP_SELF']; ?> </p>
		</footer>
	</body>
</html>