<?php session_start();?>
<?php 
	include 'fonctions.php';
?>
<!DOCTYPE html>
<html lang="fr" >
	<head>
		<meta charset="utf-8">
		<link href="style.css" rel="stylesheet" type="text/css" />
		<title>Insertion</title>
	</head>
	<body>
		<header>
			<h1>Insertion</h1>
		</header>
		<nav>
			<?php
				if(empty($_SESSION) || (isset ($_SESSION["statut"])  && $_SESSION["statut"]!="administrateur" )) {		
					echo "<p>Vous n'êtes pas connectés ou pas admin</p>";
					redirect("connexion.php",0.1);				
				}
				else {
					afficheMenu();
				}
				
			?>
		</nav>
		<article>
			<?php
				afficheFormulaireAjoutJoueur();
				if(!empty($_POST)) {
				ajoutUtilisateur($_POST['mail'],$_POST['pass'],$_POST['rue'],$_POST['ville_etu'],$_POST['status']);
				}
				else {
				echo 'Veuillez renseigner les champs';
				}
				
				
			?>	
		</article>
		<footer>
			<p>Pied de la page <?php echo $_SERVER['PHP_SELF']; ?> </p>
			<a href="javascript:history.back()">Retour à la page précédente</a>
		</footer>
	</body>
</html>	

