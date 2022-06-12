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

		<title>Modification</title>
	</head>
	<body>
		<div class="container">
		<header>
			<h1>Modification</h1>
			<?php
			var_dump($_POST);
			?>
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
				
				// traitement de la zone centrale de la page en fonction des liens GET du menu s'il y a une session
				if (!empty($_SESSION) && !empty($_GET) && isset($_GET["action"]) ){
					switch($_GET["action"])	{
						case "modifier_joueur":	
						echo '<h1>Modification d\'un joueur</h1>';
						if(empty($_POST))  afficheFormulaireChoixJoueur("modification");
						
						break;
						
					}
				}
				
				
                
				//route pour modifier un joueur
			// 	if(!empty($_SESSION) && !empty($_POST) && isset($_POST['joueur']) && isset($_POST['Modifier'])  ){
			// 		var_dump("test");

			// 		afficheFormulaireChoixJoueur("Modifier");
			// 		// if (!empty($_POST) && isset($_POST['joueur'])){
			// 		// 	$pid = $_POST['joueur'];
			// 		// 	var_dump($pid);
			// 		// }
			// 		afficheFormulaireModificationJoueur($_POST['joueur']);
			// 		if (1==1){
			// 			var_dump("teszrtyhzryeheryytrhztterhnzt'rnehtegzgetregrztt");
			// 			modifierJoueur($_POST['cid'], $_POST['joueur'], $_POST['tid'], $_POST['nom'], $_POST['prenom']);
			// 			echo '<p>Modification de '.$_POST['joueur'].' réussie</p>';
			// 		}
			// 		else 
			// 		echo '<p>Echec de la modification de '.$_POST['joueur'].'</p>';
					

			// }
				
            // if(isset($_POST['modification'])){
            //     afficheFormulaireModificationJoueur(intval($_POST['joueur']));
            //     intval($_POST['joueur']);
            //     if(isset($_POST["joueur"]) && isset($_POST["pid"]) && isset($_POST["tid"]) && isset($_POST["nom"]) && isset($_POST["prenom"])){
            //         intval($_POST['joueur']);

            //         modifierJoueur($_POST['cid'], intval($_POST['joueur']), $_POST['tid'], $_POST['nom'], $_POST['prenom']);
            //         afficheTableau(listeJoueur());
            //     }
            // }

				
// route pour modifier un joueur
if(!empty($_SESSION) && !empty($_POST) && isset($_POST['joueur']) && isset($_POST['modification'])  ){
					
	afficheFormulaireChoixJoueur("modification");
	if (!empty($_POST) && isset($_POST['joueur'])){
		$pid = $_POST['joueur'];
		var_dump($pid);
	}
	afficheFormulaireModificationJoueur($pid);
		if (!empty($_POST)){
			var_dump('AAAAAAAAAAAAAAAAAAAAAAAAAAAaaa');
			modifierJoueur($_POST['cid'], $pid, $_POST['tid'], $_POST['nom'], $_POST['prenom']);
			echo '<p>Modification de '.$_POST['joueur'].' réussie</p>';
		}
		else 
		echo '<p>Echec de la modification de '.$_POST['joueur'].'</p>';
		// $tabJoueurs = listeCompte();
		// if ($tabJoueurs)  afficheTableau($tabJoueurs);
		
}





				


				

				
                if (!empty($_SESSION) && !empty($_GET) && isset($_GET["action"]) ){
					switch($_GET["action"])	{
                        case "supprimer_joueur":	
						echo '<h1>Suppression d\'un joueur</h1>';
						if(empty($_POST))  afficheFormulaireChoixJoueur("Supprimer");
						break;
					}
				}
                // route pour supprimer un joueur
                if(!empty($_SESSION) && !empty($_POST && isset($_POST['joueur']) && isset($_POST['Supprimer']) )){
					
                    $res=supprimerJoueur($_POST['joueur']);
                    afficheFormulaireChoixJoueur("Supprimer");
                    if ($res!=0)
                    echo '<p>Suppression <mark class="vrai"> réussie</mark></p>';
                    else 
                    echo '<p>Suppression <mark class="faux"> loupé</mark></p>';
                    $tabJoueur = listeJoueur();
                    if ($tabJoueur)  afficheTableau($tabJoueur);
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