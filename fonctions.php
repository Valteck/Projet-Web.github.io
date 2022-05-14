<?php
	//****************Fonctions utilisées**************************************
	function compteExiste($login,$password){
		$retour = false ;
		$madb = new PDO('sqlite:bdd/TeamPlayerPays.db'); 
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
		$madb = new PDO('sqlite:bdd/TeamPlayerPays.db'); 
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
	function afficheMenu(){	// à compléter
		echo '<p>votre login est '.$_SESSION['login'].'</p>';
	?>
	<ul>
		<li><a href="index.php?action=liste_utilisateur" title="Lister les utilisateurs">Lister les utilisateurs</a></li>
		<li><a href="index.php?action=liste_utilisateur_ville" title="Lister les utilisateurs par villle">Lister les utilisateurs par ville</a></li>	
	<?php 
		if($_SESSION['statut']=="administrateur"){
	?>
		<li><a href="insertion.php?action=inserer_utilisateur" title="Insérer un utilisateur">Insérer un utilisateur</a></li>	
	<?php 
		}
	?>	
	</ul>				
	<p><a href="index.php?action=logout" title="Déconnexion">Se déconnecter</a></p>
	
	<?php
	}


	//******************************************************************************
	function afficheFormulaireAjoutJoueur() {
		// connexion BDD et récupération des villes
		$madb = new PDO('sqlite:bdd/IUT.sqlite'); 
		$requete = "SELECT insee, CP, Commune FROM villes;";
		$resultat = $madb->query($requete);//var_dump($resultat);echo "<br/>";  
		$resultat = $madb->query($requete);
		if($resultat){
			$villes = $resultat->fetchAll(PDO::FETCH_ASSOC);			
		}
	?>
	<form  action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" >
		<fieldset> 
			<label for="id_mail">Adresse Mail : </label><input type="email" name="mail" id="id_mail" placeholder="@mail" required size="20" /><br />
			<label for="id_pass">Mot de passe : </label><input type="password" name="pass" required id="id_pass" size="10" /><br />
			<label for="id_status">Status :</label> 
			<input type="radio" name="status" value="etudiant" checked> Etudiant 
			<input type="radio" name="status" value="prof" > Prof<br/>
			<label for="id_rue">Rue : </label><input type="text" name="rue" id="id_rue" placeholder="adresse" required size="20" /><br />
			<label for="id_ville">Ville :</label> 
			<select id="id_ville" name="ville_etu" size="1">
				<?php // on se sert de value directement pour l'insertion					
				// <option value="22013">22300 Lannion</option>
					foreach($villes as $ville){					
						echo '<option value="'.$ville["Insee"].'">'.$ville["CP"].' '.$ville["Commune"].'</option>';
					}					
				?>
			</select>
			<input type="submit" value="Insérer"/>
		</fieldset>
	</form>
	<?php
		echo "<br/>";
	}// fin afficheFormulaireAjoutUtilisateur


	//*******************************************************************************************
	function ajoutUtilisateur($login,$password,$rue,$insee,$status){
		/* on récupère directement le code de la ville qui a été transmis dans l'attribut value de la balise <option> du formulaire
		Il n'est donc pas nécessaire de rechercher le code INSEE de la ville*/
		$retour=0;
		$madb = new PDO('sqlite:bdd/IUT.sqlite'); 	
		// filtrer les paramètres		
		$login = $madb->quote($login);
		$password = $madb->quote($password);
		$rue = $madb->quote($rue);
		$insee = $madb->quote($insee);
		$status = $madb->quote($status);
		// requête
		//INSERT INTO utilisateurs VALUES ('test@test', 'password', 'rue dskjfsdj', '22113' , 'Prof' );
		$requete = " INSERT INTO utilisateurs VALUES ($login, $password, $rue, $insee , $status );  ";
		$resultat = $madb->exec($requete);	
		if ($resultat == false ) 
			$retour = 0;
		else 
			$retour = $resultat;
		return $retour;
	}

	
	//********************************************************************************
	function listeCompte()	{ // A faire
		
		$retour = false ;
		$madb = new PDO('sqlite:bdd/IUT.sqlite'); 
		$requete = "SELECT login, Statut, Adresse, CP, Commune FROM utilisateurs u INNER JOIN villes v ON u.insee = v.insee;";
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
	function listeUtilisateurParVille($insee){
		$retour = false ;
		$madb = new PDO('sqlite:bdd/IUT.sqlite');
		
		$insee = $madb->quote($insee);
		$requete = "SELECT login, Statut, Adresse, CP, Commune FROM utilisateurs u INNER JOIN villes v ON u.insee = v.insee WHERE u.insee=$insee;";
		$resultat = $madb->query($requete);
		if($resultat){
			$retour = $resultat->fetchAll(PDO::FETCH_ASSOC);			
		}
		return $retour;
	}

	//*******************************************************************************************
	function supprimerUtilisateur($login){
		$retour=0;
		$madb = new PDO('sqlite:bdd/IUT.sqlite'); 
		// filtrer le paramètre	
		$login = $madb->quote($login);
		$requete = "DELETE FROM utilisateurs WHERE login = $login;";
		$retour = $madb->exec($requete);
		
		return $retour;
	}
	//*******************************************************************************************
	function modifierUtilisateur($login,$password,$rue,$insee,$status){
		$retour=0;
		$madb = new PDO('sqlite:bdd/IUT.sqlite'); 
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
