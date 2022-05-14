<?php session_start();?>
<?php 
	include 'fonctions.php';
?>
<!DOCTYPE html>
<html lang="fr" >
	<head>
		<meta charset="utf-8">
		<link href="style.css" rel="stylesheet" type="text/css" />
		<title>Page d'acceuil</title>
	</head>
	<body>
		<header>
			<h1>Page d'acceuil</h1>
		</header>
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
					session_destroy();
					$_SESSION=array();
					// On recharge la page pour rafraichir les données
					redirect("connexion.php",0.1);
				}				
			?>
		</nav>
		<article>			
			<?php
				// Affichage du message accueil en fonction de la connexion
				if (empty($_SESSION)){
					redirect("connexion.php",0.1);
				}
				else {
					echo '<h1>Vous êtes connectés comme '.$_SESSION['login'].'</h1>';
				}
							
				
			?>
		</article>
		<footer>
			<p>Pied de la page <?php echo $_SERVER['PHP_SELF']; ?></p>
			<a href="javascript:history.back()">Retour à la page précédente</a>
		</footer>
	</body>
</html>


