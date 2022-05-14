<?php session_start();?>
<?php 
	include 'fonctions.php';
?>
<!DOCTYPE html>
<html lang="fr" >
	<head>
		<meta charset="utf-8">
		<link href="style.css" rel="stylesheet" type="text/css" />
		<title>Connexion</title>
	</head>
	<body>
		<header>
			<h1>Aranguren Yves-Mayeul et Desbonnets Franck SAE23 WEB2 2021-2022</h1>
            <h2>Connexion</h2>
		</header>
		<nav>
			<?php
				// affichage du formulaire de connexion ou le menu avec le nom de la personne
				if (empty($_SESSION)) {
					afficheFormulaireConnexion();
				}
                
				// test de la connexion
				// Point d'entrée ou Route pour traiter le formulaire
				if (empty($_SESSION) && !empty($_POST) && isset($_POST['login'])  && isset($_POST['password']) ){
					if(compteExiste($_POST['login'],$_POST['password'])){
						echo 'OK';
						$_SESSION['login']=$_POST['login'];
						$_SESSION['statut']=isAdmin($_POST['login']) ;
						redirect("index.php",1);
					}
					else {
						redirect("connexion.php",1);
					}
				}
						
				
			?>
		</nav>
		<article>

		</article>
		<footer>
			<p>Pied de la page <?php echo $_SERVER['PHP_SELF']; ?></p>
			<a href="javascript:history.back()">Retour à la page précédente</a>
		</footer>
	</body>
</html>


