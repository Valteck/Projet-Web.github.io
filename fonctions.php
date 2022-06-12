<?php
	//****************Fonctions utilisées**************************************
	function compteExiste($login,$password){
		$retour = false ;
		$madb = new PDO('sqlite:bdd/TeamPlayerPays2.sqlite'); 
		$login= $madb->quote($login);
		$password = $madb->quote($password);
		$requete = "SELECT login,password FROM comptes WHERE login = ".$login." AND password = ".$password ;
		//var_dump($requete);echo "<br/>";  	
		$resultat = $madb->query($requete);
		$tableau_assoc = $resultat->fetchAll(PDO::FETCH_ASSOC);
		if (sizeof($tableau_assoc)!=0) $retour = true;	
		return $retour;
	}
	//********************************************************************************
	function isAdmin($login){ 
		$retour = false ;
		$madb = new PDO('sqlite:bdd/TeamPlayerPays2.sqlite'); 
		$login= $madb->quote($login);
		
		$requete = "SELECT Statut FROM comptes WHERE login = $login;";
		//var_dump($requete);echo "<br/>";  	
		$resultat = $madb->query($requete);
		if($resultat){
			$res = $resultat->fetch(PDO::FETCH_ASSOC);
			$retour = $res['statut'];
		}
		return $retour;		
	}
    //******************************************************************************
	function afficheFormulaireConnexion(){
	?>
	<form id="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<fieldset>
			<legend>Connexion</legend>	
			<label class="log" for="id_login">Nom d'utilisateur : </label><input type="login" name="login" id="id_login" placeholder="ex : Valteck" required size="20"/><br />
			<label class="log" for="id_password">Mot de passe : </label><input type="password" name="password" id="id_password" required size="10" /><br />
			<input type="submit" name="connect" value="Connexion" class="btn btn-success"/>
		</fieldset>
	</form>
	<?php
	}
	//******************************************************************************
	function afficheMenu(){	
		echo '<p class="user">Bonjour '.$_SESSION['login'].' !</p>';
	?>
	<ul>
		<li><a href="index.php?action=liste_joueur" title="Lister les joueurs">Lister les joueurs</a></li>
		<li><a href="index.php?action=liste_joueur_team" title="Lister les joueurs par team">Lister les joueurs par team</a></li>	
		<?php 
			if($_SESSION['statut']=="administrateur"){
			?>
			<li><a href="insertion.php?action=inserer_joueur" title="Insérer un joueur">Insérer un joueur</a></li>	
			<li><a href="modification.php?action=modifier_joueur" title="Modifier un joueur">Modifier un joueur</a></li>	
			<li><a href="modification.php?action=supprimer_joueur" title="Supprimer un joueur">Supprimer un joueur</a></li>	
			
			<?php 
			}
		?>	
	</ul>				
	<p><a href="index.php?action=logout" title="Déconnexion">Se déconnecter</a></p>
	
	<?php
	}
	//******************************************************************************
	function afficheFormulaireAjoutJoueur() {
		// connexion BDD et récupération des pays
		$madb = new PDO('sqlite:bdd/TeamPlayerPays2.sqlite'); 
		$requete = "SELECT cid, name FROM country;";
		$resultat = $madb->query($requete);//var_dump($resultat);echo "<br/>";  
		
		
		if($resultat){
			$pays = $resultat->fetchAll(PDO::FETCH_ASSOC);			
		}
		
		
		$requete_2 = "SELECT tid, name FROM team;";
		$resultat_2 = $madb->query($requete_2);//var_dump($resultat);echo "<br/>";  
		
		
		if($resultat_2){
			$team = $resultat_2->fetchAll(PDO::FETCH_ASSOC);			
		}
		
	?>
	<form  action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onSubmit="return Verification()" >
		<fieldset> 
			<label for="idNom">Nom de famille : </label><input type="text" name="nom" id="idNom" placeholder="Dupont" size="20" /> * Obligatoire<br />
			<label for="idPrenom">Prénom : </label><input type="text" name="prenom" id="idPrenom" placeholder="Jean" size="20" /> * Obligatoire<br />
			<label for="id_pays">Pays : </label> 
			<select id="cid" name="cid" size="1">
				<?php 				
					foreach($pays as $countryyy){					
						echo '<option value="'.$countryyy["cid"].'">'.$countryyy["name"].'</option>';
					}					
				?>
			</select>
			<br />
			
			<label for="itd">Team :</label> 
			
			<select id="tid" name="tid" size="1">
				<?php 				
					foreach($team as $teammm){					
						echo '<option value="'.$teammm["tid"].'">'.$teammm["name"].'</option>';
					}					
				?>
			</select>
			
				</br>




			<input type="text" name="captcha"/>
			<img src="capcha/image.php" onclick="this.src='capcha/image.php?' + Math.random();" alt="captcha" style="cursor:pointer;">

			<input type="submit" value="Valider le CAPTCHA et insérer"/>


		</fieldset>
	</form>




	<?php
		echo "<br/>";
	}// fin afficheFormulaireAjoutJoueur
	//*******************************************************************************************
	function ajoutJoueur($cid,$tid,$family_name,$first_name){
        /* on récupère directement le code de la ville qui a été transmis dans l'attribut value de la balise <option> du formulaire
        Il n'est donc pas nécessaire de rechercher le code INSEE de la ville*/
        $retour=0;
        $madb = new PDO('sqlite:bdd/TeamPlayerPays2.sqlite');
        // filtrer les paramètres
        $cid = $madb->quote($cid);

        $tid = $madb->quote($tid);
        $family_name = $madb->quote($family_name);
        $first_name = $madb->quote($first_name);
        // requête
        $requete = "INSERT INTO player (cid,tid,family_name,first_name) VALUES ($cid, $tid, $family_name, $first_name);";
        $resultat = $madb->exec($requete);
        if ($resultat == false ) 
            $retour = 0;
        else 
            $retour = $resultat;
        return $retour;
    }
	//********************************************************************************
	function listeJoueur()	{ 
		
		$retour = false ;
		$madb = new PDO('sqlite:bdd/TeamPlayerPays2.sqlite'); 
		$requete = "SELECT country.name as 'Pays', team.name as 'Team', family_name as 'Nom de famille', first_name as 'Prénom' from player inner join country on player.cid = country.cid inner join team on player.tid = team.tid;";
		$resultat = $madb->query($requete);
		if($resultat){
			$retour = $resultat->fetchAll(PDO::FETCH_ASSOC);			
		}		
		return $retour;
	}		
	//*******************************************************************************************
	function afficheTableau($tab){
		echo '<table>';	
		echo '<tr>';// les entetes des colonnes qu'on lit dans le premier tableau par exemple
		foreach($tab[0] as $colonne=>$valeur){		echo "<th>$colonne</th>";		}
		echo "</tr>\n";
		// le corps de la table
		foreach($tab as $ligne){
			echo '<tr>';
			foreach($ligne as $cellule)		{		echo "<td>$cellule</td>";		}
			echo "</tr>\n";
		}
		echo '</table>';
	}
	//*******************************************************************************************
	function listeJoueurParTeam($tid){
		$retour = false ;
		$madb = new PDO('sqlite:bdd/TeamPlayerPays2.sqlite');
		
		$tid = $madb->quote($tid);
		$requete = "SELECT country.name as 'Pays', family_name as 'Nom de famille', first_name as 'Prénom' from player inner join team on (player.tid = team.tid) inner join country on player.cid = country.cid where team.tid = $tid;";
		$resultat = $madb->query($requete);
		if($resultat){
			$retour = $resultat->fetchAll(PDO::FETCH_ASSOC);			
		}
		return $retour;
	}
	
	
	function afficheFormulaireJoueurParTeam(){
		echo "<br/>";
		// CNX BDD + REQUETE pour obtenir les Pays correspondantes à des Joueurs
		$madb = new PDO('sqlite:bdd/TeamPlayerPays2.sqlite'); 
		$requete = "SELECT DISTINCT tid, name FROM team;";
		$resultat = $madb->query($requete);
		if($resultat){
			$team = $resultat->fetchAll(PDO::FETCH_ASSOC);			
		}
		//	var_dump($pays);
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<fieldset> 
			<label for="id_team">Team :</label> 
			<select id="id_team" name="team" size="1">
				<?php 
					foreach($team as $teammm){					
						echo '<option value="'.$teammm["tid"].'">'.$teammm["name"].'</option>';
					}
				?>
			</select>
			<input type="submit" value="Rechercher Joueur par team" class="btn btn-primary"/>
		</fieldset>
	</form>
	<?php
		echo "<br/>";
	}// fin afficheFormulaireJoueurParPays
	
	//*******************************************************************************************
	function afficheFormulaireChoixJoueur($choix){
		echo "<br/>";
		$madb = new PDO('sqlite:bdd/TeamPlayerPays2.sqlite'); 
		$requete = "SELECT DISTINCT pid, first_name, family_name FROM player;";
		$resultat = $madb->query($requete);
		if($resultat){
			$joueur = $resultat->fetchAll(PDO::FETCH_ASSOC);			
		}
	?>	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<fieldset> 
			<label for="id_joueur">Joueur :</label> 
			<select id="id_joueur" name="joueur" size="1">
				<?php 
					foreach($joueur as $joueurrrrr){					
						echo '<option value="'.$joueurrrrr["pid"].'">'.$joueurrrrr["family_name"].' '.$joueurrrrr["first_name"].'</option>';
					}
				?>
			</select>
			<input type="submit" name="<?php echo $choix; ?>" value="<?php echo $choix; ?>"/>
		</fieldset>
	</form>
	<?php
		echo "<br/>";
	}// fin afficheFormulaireChoixJoueur
	
	
	
	
	//*******************************************************************************************
	
	function supprimerJoueur($pid){
		$retour=0;
		$madb = new PDO('sqlite:bdd/TeamPlayerPays2.sqlite'); 
		// filtrer le paramètre	
		$pid = $madb->quote($pid);
		$requete = "DELETE FROM player WHERE pid = $pid;";
		$retour = $madb->exec($requete);
		
		return $retour;
	}
	//*******************************************************************************************
	function modifierJoueur($cid,$pid,$tid,$family_name,$first_name){
		$retour=0;
		$madb = new PDO('sqlite:bdd/TeamPlayerPays2.sqlite'); 
		// filtrer les paramètres	
		
		$cid = $madb->quote($cid);
		$pid = $madb->quote($pid);
		$tid = $madb->quote($tid);
		$family_name = $madb->quote($family_name);
		$first_name = $madb->quote($first_name);
		var_dump('dsfoijgbhvzeiruhbesrzoqifuhihbul');

		$sql = "UPDATE joueur set cid=$cid,pid=$pid,tid=$tid,family_name=$family_name, first_name=$first_name where pid=$pid";
		//var_dump($sql);
		try{
			$resultat = $madb->exec($sql);
			} catch(exception $e) {
			//C'est ici que nous allons gérer l'erreur obtenue
		}
		if($resultat != false) {
			$retour = 1;
		}
		else {
			$retour = 0;
		}
		
		return $retour;
	}
// 	function modifierJoueur($cid, $pid, $tid, $family_name, $first_name){
//         $db = new PDO('sqlite:bdd/TeamPlayerPays2.sqlite');

//         $cid = htmlspecialchar($cid);
//         $pid = htmlspecialchar($pid);
//         $tid = htmlspecialchar($tid);
//         $family_name = htmlspecialchar($family_name);
//         $first_name = htmlspecialchar($first_name);

//         $req = $db->prepare("UPDATE joueur SET cid = ?, pid = ?, tid = ?, family_name = ?, first_name = ? WHERE pid = ?");
//         $req->execute(array($cid, $pid, $tid, $family_name, $first_name, $pid));

// }
	
	
	//*******************************************************************************************
	
	function afficheFormulaireModificationJoueur($joueur) {
		// connexion BDD et récupération des pays
		$madb = new PDO('sqlite:bdd/TeamPlayerPays2.sqlite'); 
		$requete = "SELECT cid, name FROM country;";
		$resultat = $madb->query($requete);//var_dump($resultat);echo "<br/>";  
		
		
		if($resultat){
			$pays = $resultat->fetchAll(PDO::FETCH_ASSOC);			
		}
		// var_dump($pays);
		// var_dump($pays[0]["name"]);

		$requete_2_2 = "SELECT tid, name FROM team;";
		$resultat_2_2 = $madb->query($requete_2_2);//var_dump($resultat);echo "<br/>";  
		if($resultat_2_2){
			$team = $resultat_2_2->fetchAll(PDO::FETCH_ASSOC);			
		}



		$madb1 = new PDO('sqlite:bdd/TeamPlayerPays2.sqlite'); 
		$requete_2 = "SELECT team.name FROM player inner join team on player.tid = team.tid where player.pid = $joueur;";
		$resultat_2 = $madb1->query($requete_2);//var_dump($resultat);echo "<br/>";  
		
		
		if($resultat_2){
			$teamJ = $resultat_2->fetchAll(PDO::FETCH_ASSOC);			
		}
		var_dump($teamJ[0]["name"]);

	
	
		$requete_3 = "SELECT first_name, family_name FROM player where pid = $joueur;";
		$resultat_3 = $madb->query($requete_3);
		
		if($resultat_3){
			$joueurNeP = $resultat_3->fetchAll(PDO::FETCH_ASSOC);			
		}
	
	
		$madb2 = new PDO('sqlite:bdd/TeamPlayerPays2.sqlite'); 
		//$requete_4 = "SELECT name FROM country inner join player on country.cid = player.cid where player.pid = $joueur;";
		$requete_4 = "SELECT country.name FROM player inner join country on country.cid = player.cid where  player.pid = $joueur;";
		$resultat_4 = $madb2->query($requete_4);//var_dump($resultat);echo "<br/>";  
		
		
		if($resultat_4){
			$paysJoueur = $resultat_4->fetchAll(PDO::FETCH_ASSOC);			
		}
		var_dump($paysJoueur[0]["name"]);
		// var_dump($paysJoueur["name"]);

	?>
	<form  action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >
		<fieldset> 
			<label for="id_player">Nom de famille</label><input value="<?php echo $joueurNeP[0]["family_name"];?>" type="text" name="nom" id="id_nom" required size="20" /><br />
			<label for="id_player">Prénom</label><input value="<?php echo $joueurNeP[0]["first_name"];?>" type="text" name="prenom" id="id_prenom" required size="20" /><br />
			<label for="id_pays">Pays :</label> 
			<select id="id_pays" name="cid" size="1">
				<?php // on se sert de value directement pour l'insertion
				
				echo '<option value = "'.$paysJoueur[0]['cid'].'">'.$paysJoueur[0]["name"].' (Original)</option>';
					foreach($pays as $countryyy) {
						// var_dump($countryyy);
						echo '<option ';
						
						if($paysJoueur[0]["name"] != $countryyy['name']){
							echo ' value = "'.$countryyy['cid'].'">'.$countryyy['name'].'</option>';
						}
					}
				?>


			</select>
			<br />
			
			<label for="itd">Team :</label> 
			<select id="tid" name="tid" size="1">
				<?php // on se sert de value directement pour l'insertion

				echo '<option value = "'.$teamJ[0]['tid'].'">'.$teamJ[0]["name"].' (Original)</option>';
				foreach($team as $teammm) {
					echo '<option ';
					
					if($teamJ[0]["name"] != $teammm['name']){
						echo ' value = "'.$teammm['tid'].'">'.$teammm['name'].'</option>';
					}
					 
					
				}
				?>
			</select>
	
			
			
			<input type="submit" value="modification" name="modification"/>
	
	
		</fieldset>
	</form>
	<?php
	}// fin afficheFormulaireModificationJoueur
	
	//*******************************************************************************************
// function image($name)
// {
// 	$retour = false ;
// 	$madb = new PDO('sqlite:bdd/TeamPlayerPays2.sqlite'); 
// 	$requete = "select image_name from player where image_name=$name;";
// 	$resultat = $madb->query($requete);
// 	if($resultat){
// 		$retour = $resultat->fetchAll(PDO::FETCH_ASSOC);			
// 	}		
// 	return $retour;


// 	$requete = "SELECT * FROM products WHERE id = $id";
// $resultat = $madb->query($requete);
// $result=mysqli_fetch_array($resultat);
// echo '<img src="data:image/jpeg;base64,'.base64_encode( $result['image'] ).'"/>';
// }
	
	
	
	
	
	
	//*******************************************************************************************
	//Nom : redirect()
	//Role : Permet une redirection en javascript
	//Parametre : URL de redirection et Délais avant la redirection
	//Retour : Aucun
	//*******************
	function redirect($url,$tps)
	{
		$temps = $tps * 1000;
		
		echo "<script type=\"text/javascript\">\n"
		. "<!--\n"
		. "\n"
		. "function redirect() {\n"
		. "window.location='" . $url . "'\n"
		. "}\n"
		. "setTimeout('redirect()','" . $temps ."');\n"
		. "\n"
		. "// -->\n"
		. "</script>\n";
		
	}
	
?>