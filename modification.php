<?php session_start(); 
	include 'fonctions.php';
	include 'formulaires.php';
	
?>


<!DOCTYPE html>
<html lang="fr" >
	<head>
		<meta charset="utf-8">
		<link href="style.css" rel="stylesheet" type="text/css" />
		<title>WEB2 TD56 TP45 PHP Une Application BDD NOM_ETU 2021-2022 : Modification</title>
	</head>
	<body>
		<header>
			<h1>WEB2 TD56 TP45 PHP Une Application BDD BDD NOM_ETU 2021-2022 : Modification</h1>
		</header>
		<nav>
			<?php
				if (!empty($_SESSION) && isset($_SESSION['statut']) && $_SESSION['statut']=='Prof'){
					// on a les droits
					afficheMenu();
				}
				else {
					echo '<p>Non autorisé!!!!</p>';
					redirect("index.php",1);
				}
			?>
		</nav>
		<article>
			<?php
				// traitement de la zone centrale de la page en fonction des liens GET du menu s'il y a une session
				if (!empty($_SESSION) && !empty($_GET) && isset($_GET["action"]) ){
					switch($_GET["action"])	{
						case "supprimer_utilisateur":	
						echo '<h1>Suppression d\'un utilisateur</h1>';
						if(empty($_POST))  afficheFormulaireChoixUtilisateur("Supprimer");
						
						break;
						
					}
				}
				// route pour supprimer un utilisateur
				if(!empty($_SESSION) && !empty($_POST && isset($_POST['mail']) && isset($_POST['Supprimer'])  )){
					$res=supprimerUtilisateur($_POST['mail']);
					afficheFormulaireChoixUtilisateur("Supprimer");
					if ($res!=0) 
					echo '<p>Suppression de '.$_POST['mail'].' réussie</p>';
					else 
					echo '<p>Eche de la suppression de '.$_POST['mail'].'</p>';
					$tabUtilisateurs = listeCompte();
					if ($tabUtilisateurs)  afficheTableau($tabUtilisateurs);
				}
				
			?>	
		</article>
		<footer>
			<p>Pied de la page <?php echo $_SERVER['PHP_SELF']; ?> </p>
			<a href="javascript:history.back()">Retour à la page précédente</a>
		</footer>
	</body>
</html>	

