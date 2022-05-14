<?php session_start();?>
<?php 
	include 'fonctions.php';
	include 'formulaires.php';
?>
<!DOCTYPE html>
<html lang="fr" >
	<head>
		<meta charset="utf-8">
		<link href="style.css" rel="stylesheet" type="text/css" />
		<title>WEB2 TD56 TP45 PHP Une Application BDD BDD NOM_ETU 2021-2022</title>
	</head>
	<body>
		<header>
			<h1>WEB2 TD56 TP45 PHP Une Application BDD NOM_ETU 2021-2022</h1>
		</header>
		<nav>
			<?php
				// affichage du formulaire de connexion ou le menu avec le nom de la personne
				if (empty($_SESSION)) {
					afficheFormulaireConnexion();
				} 
				else {
					afficheMenu();
				}
				// test de la connexion
				// Point d'entrée ou Route pour traiter le formulaire
				if (empty($_SESSION) && !empty($_POST) && isset($_POST['login'])  && isset($_POST['pass']) ){
					if(compteExiste($_POST['login'],$_POST['pass'])){
						//echo 'OK';
						$_SESSION['login']=$_POST['login'];
						$_SESSION['statut']=isAdmin($_POST['login']) ;
						redirect("index.php",1);
					}
					else {
						echo "<p>Errreur d'authentification</p>";
					}
				}
				
				// Point d'entrée ou Route pour Destruction de la session 
				
				if (!empty($_SESSION) && !empty($_GET) && isset($_GET['action'])  && $_GET['action']=='logout' ){
					$_SESSION=array();
					session_destroy();
					redirect("index.php",1);
				}
				
				
			?>
		</nav>
		<article>
			
			<?php
				// Affichage du message accueil en fonction de la connexion
				if (empty($_SESSION)) 
				echo '<h1>Vous êtes déconnectés</h1>';
				else 
				echo '<h1>Vous êtes connectés comme '.$_SESSION['login'].'</h1>';				
				
				// Route de traitement de la zone centrale de la page en fonction des liens GET du menu s'il y a une session
				if (!empty($_SESSION) && !empty($_GET) && isset($_GET["action"]) ){
					switch($_GET["action"])
					{
						case "liste_utilisateur":	
						echo '<h1>Liste des utilisateurs</h1>';
							$membres = listeCompte();
							if($membres) afficheTableau($membres);
						break;
						case "liste_utilisateur_ville":	
						echo '<h1>Lister les utilisateurs par villle</h1>';
						afficheFormulaireUtilisateurParVille();
						
						
						break;
					}
				}
				// traitement du premier formulaire interne de recherche par ville	
				// Route pour le traitement du formualire par ville
				if (!empty($_SESSION) && !empty($_POST)  &&  isset($_POST['ville'])){
							afficheFormulaireUtilisateurParVille();
							$tab=listeUtilisateurParVille($_POST['ville']);
							if($tab) afficheTableau($tab);
						}
				
				
			?>
		</article>
		<footer>
			<p>Pied de la page <?php echo $_SERVER['PHP_SELF']; ?></p>
			<a href="javascript:history.back()">Retour à la page précédente</a>
		</footer>
	</body>
</html>


