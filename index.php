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
		<style>
			.error {color: #FF0000;}
		</style>
		<title>Page d'acceuil</title>
	</head>
	<body>
	<div class="container">
		<header>
			<h1>Page d'acceuil</h1>
		</header>
		<div class="row mb-3">
				<div class="col-md-4">
		<nav>
			<?php		
				// affichage du formulaire de connexion ou le menu avec le nom de la personne
				if (!empty($_SESSION)) {
					afficheMenu();
				} 

				// test de la connexion
				if (empty($_SESSION) && !empty($_POST) && isset($_POST["login"]) && isset($_POST["pass"])){
					if (compteExiste($_POST["login"],$_POST["pass"])){	
						//echo "AUTH OK";
						$_SESSION['login']=$_POST["login"];
						$_SESSION['statut']=isAdmin($_POST["login"]);
						
						// On recharge la page pour rafraichir les données
						redirect("index.php",0.1);
					}
					else{						
						redirect("connexion.php",0.1);			
					}
				}
				
				// Destruction de la session 
				if(!empty($_SESSION)  && !empty($_GET) && isset($_GET["action"]) && $_GET["action"]=="logout" ){
					
					
					//Creation fichier log
					// 1 : on ouvre le fichier
					$monfichier = fopen('acces.log', 'a+');
					// 2 : on fera ici nos opérations sur le fichier...
					fputs($monfichier,"\nDeconnexion depuis ".$_SERVER['REMOTE_ADDR']. " avec l'identifiant " .$_SESSION['login']. " en tant que " .$_SESSION['statut']. " le " .date('l jS \of F Y h:i:s A'));
					fputs($monfichier, "\n");
					// 3 : quand on a fini de l'utiliser, on ferme le fichier
					fclose($monfichier);
					session_destroy();
					$_SESSION=array();
					// On recharge la page pour rafraichir les données
					redirect("connexion.php",0.1);
				}				
			?>
		</nav>
		</div>
				<div class="col-md-8">
		<section>
			<?php
		// Affichage du message accueil en fonction de la connexion
				if (empty($_SESSION)){
					redirect("connexion.php",0.1);
				}
				else {
					echo '<h2>Vous êtes connectés comme</h2> <h2 class="statut">'.$_SESSION['statut'].'.</h2>';
				}
				?>
			</section>
		<article>			
			<?php
				
				
				if (!empty($_SESSION) && !empty($_GET) && isset($_GET["action"]) ){
					switch($_GET["action"])
					{
						case "liste_joueur":	
						echo '<h1>Liste des joueurs</h1>';
						$membres = listeJoueur();
						if($membres) afficheTableau($membres);
						
						break;
						
						case "liste_joueur_team":	
						echo '<h1>Lister les joueurs par team</h1>';
						afficheFormulaireJoueurParTeam();
						break;
					}
				}
				
				// traitement du premier formulaire interne de recherche par team	
				// Route pour le traitement du formualire par team
				if (!empty($_SESSION) && !empty($_POST)  &&  isset($_POST['team'])){
					afficheFormulaireJoueurParTeam();
					$tab=listeJoueurParTeam($_POST['team']);
					if($tab) afficheTableau($tab);
				}
				
			?>
		</article>
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
			<a href="./index.php">Retour à la page d'acceuil</a>
		</footer>
	</body>
</html>


