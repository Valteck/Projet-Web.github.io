<?php
	//****************Fonctions utilisées**************************************
	function compteExiste($login,$password){
		$retour = false ;
		$madb = new PDO('sqlite:bdd/test.sqlite'); 
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
		$madb = new PDO('sqlite:bdd/test.sqlite'); 
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
                <legend>Formulaire de connexion</legend>	
                <label for="id_login">Nom d'utilisateur : </label><input type="login" name="login" id="id_login" placeholder="ex : Valteck" required size="20" /><br />
                <label for="id_password">Mot de passe : </label><input type="password" name="password" id="id_password" required size="10" /><br />
                <input type="submit" name="connect" value="Connexion" />
            </fieldset>
        </form>
        <?php
        }
//******************************************************************************
	function afficheMenu(){	
		echo '<p>votre login est '.$_SESSION['login'].'</p>';
	?>
	<ul>
		<li><a href="index.php?action=liste_joueur" title="Lister les joueurs">Lister les joueurs</a></li>
		<li><a href="index.php?action=liste_joueur_team" title="Lister les joueurs par team">Lister les joueurs par team</a></li>	
	<?php 
		if($_SESSION['statut']=="administrateur"){
	?>
		<li><a href="insertion.php?action=inserer_joueur" title="Insérer un joueur">Insérer un utilisateur</a></li>	
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
		$madb = new PDO('sqlite:bdd/test.sqlite'); 
		$requete = "SELECT id, name FROM country;";
		$resultat = $madb->query($requete);//var_dump($resultat);echo "<br/>";  
		

		if($resultat){
			$pays = $resultat->fetchAll(PDO::FETCH_ASSOC);			
		}


		$requete_2 = "SELECT id, name FROM team;";
		$resultat_2 = $madb->query($requete_2);//var_dump($resultat);echo "<br/>";  
		

		if($resultat_2){
			$team = $resultat_2->fetchAll(PDO::FETCH_ASSOC);			
		}
		
	?>
	<form  action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >
		<fieldset> 
			<label for="id_mail">Nom de famille</label><input type="text" name="nom" id="id_nom" placeholder="Dupont" required size="20" /><br />
			<label for="id_mail">Prénom</label><input type="text" name="prenom" id="id_prenom" placeholder="Jean" required size="20" /><br />
			<label for="id_pays">Pays :</label> 
			<select id="id_pays" name="pays_joueur" size="1">
				<?php 				
					foreach($pays as $countryyy){					
						echo '<option value="'.$countryyy["id"].'">'.$countryyy["name"].'</option>';
					}					
				?>
			</select>
			<br />

			<label for="id_team">Team :</label> 

			<select id="id_team" name="team_joueur" size="1">
				<?php 				
					foreach($team as $teammm){					
						echo '<option value="'.$teammm["id"].'">'.$teammm["name"].'</option>';
					}					
				?>
			</select>


			<input type="submit" value="Insérer"/>
		</fieldset>
	</form>
	<?php
		echo "<br/>";
	}// fin afficheFormulaireAjoutJoueur
	//*******************************************************************************************
	function ajoutJoueur($id_country,$team_id,$family_name,$first_name){
		/* on récupère directement le code de la ville qui a été transmis dans l'attribut value de la balise <option> du formulaire
		Il n'est donc pas nécessaire de rechercher le code INSEE de la ville*/
		$retour=0;
		$madb = new PDO('sqlite:bdd/test.sqlite'); 	
		// filtrer les paramètres		
		$id_country = $madb->quote($id_country);
		$id = "SELECT max(id) FROM PLAYER;";
		$id = $madb->exec($id);	
		$id = $id + 1;
		$team_id = $madb->quote($team_id);
		$family_name = $madb->quote($family_name);
		$first_name = $madb->quote($first_name);
		// requête
		$requete = "INSERT INTO player VALUES ($id_country, $id, $team_id, $family_name, $first_name);";
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
		$madb = new PDO('sqlite:bdd/test.sqlite'); 
		$requete = "SELECT country.name, team.name, family_name, first_name from player inner join country on (player.country_id = country.id) inner join team on (player.team_id = team.id);";
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
	function listeJoueurParTeam($id_team){
		$retour = false ;
		$madb = new PDO('sqlite:bdd/test.sqlite');
		
		$id_team = $madb->quote($id_team);
		$requete = "SELECT * from player inner join team on (player.team_id = team.id) where team.id = $id_team;";
		$resultat = $madb->query($requete);
		if($resultat){
			$retour = $resultat->fetchAll(PDO::FETCH_ASSOC);			
		}
		return $retour;
	}


	function afficheFormulaireJoueurParTeam(){
		echo "<br/>";
		// CNX BDD + REQUETE pour obtenir les Pays correspondantes à des Joueurs
		$madb = new PDO('sqlite:bdd/test.sqlite'); 
		$requete = "SELECT id, name FROM team;";
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
						echo '<option value="'.$teammm["id"].'">'.$teammm["name"].'</option>';
					}
				?>
			</select>
			<input type="submit" value="Rechercher Utilisateur par team"/>
		</fieldset>
	</form>
	<?php
		echo "<br/>";
	}// fin afficheFormulaireJoueurParPays

	//*******************************************************************************************
	function supprimerUtilisateur($login){
		$retour=0;
		$madb = new PDO('sqlite:bdd/test.sqlite'); 
		// filtrer le paramètre	
		$login = $madb->quote($login);
		$requete = "DELETE FROM utilisateurs WHERE login = $login;";
		$retour = $madb->exec($requete);
		
		return $retour;
	}
	//*******************************************************************************************
	function modifierUtilisateur($login,$password,$rue,$insee,$status){
		$retour=0;
		$madb = new PDO('sqlite:bdd/test.sqlite'); 
		// filtrer les paramètres	
		
		
		
		
		
		
		
		
		return $retour;
	}
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
