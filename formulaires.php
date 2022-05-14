<?php
	//******************************************************************************
	function afficheFormulaireConnexion(){// fourni
	?>
	<form id="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<fieldset>
			<legend>Formulaire de connexion</legend>	
			<label for="id_mail">Adresse Mail : </label><input type="email" name="login" id="id_mail" placeholder="@mail" required size="20" /><br />
			<label for="id_pass">Mot de passe : </label><input type="password" name="pass" id="id_pass" required size="10" /><br />
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
		if($_SESSION['statut']=="Administrateur"){
	?>
		<li><a href="insertion.php?action=inserer_utilisateur" title="Insérer un utilisateur">Insérer un utilisateur</a></li>	
		<li><a href="update.php?action=supprimer_utilisateur" title="Supprimer un utilisateur">Supprimer un utilisateur</a></li>	
		<li><a href="update.php?action=modifier_utilisateur" title="Modifier un utilisateur">Modifier un utilisateur</a></li>
	<?php 
		}
	?>	
	</ul>				
	<p><a href="index.php?action=logout" title="Déconnexion">Se déconnecter</a></p>
	
	<?php
	}
	
	
	//******************************************************************************
	function afficheFormulaireUtilisateurParVille(){
		echo "<br/>";
		// CNX BDD + REQUETE pour obtenir les villes correspondantes à des utilisateurs
		$madb = new PDO('sqlite:bdd/IUT.sqlite'); 
		// SELECT DISTINCT v.Insee, CP, Commune FROM villes v INNER JOIN utilisateurs u ON v.Insee = u.Insee;
		$requete = "SELECT DISTINCT v.Insee, CP, Commune FROM villes v INNER JOIN utilisateurs u ON v.Insee = u.Insee;";
		$resultat = $madb->query($requete);
		if($resultat){
			$villes = $resultat->fetchAll(PDO::FETCH_ASSOC);			
		}
		//	var_dump($villes);
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<fieldset> 
			<label for="id_ville">Ville :</label> 
			<select id="id_ville" name="ville" size="1">
				<?php // générer la liste des options à partir de $villes
				// <option value="22013">22300 Lannion</option>
					foreach($villes as $ville){					
						echo '<option value="'.$ville["Insee"].'">'.$ville["CP"].' '.$ville["Commune"].'</option>';
					}
				?>
			</select>
			<input type="submit" value="Rechercher Utilisateur par Ville"/>
		</fieldset>
	</form>
	<?php
		echo "<br/>";
	}// fin afficheFormulaireUtilisateurParVille
	
	
	//******************************************************************************
	function afficheFormulaireAjoutUtilisateur() {
		// connexion BDD et récupération des villes
		$madb = new PDO('sqlite:bdd/IUT.sqlite'); 
		$requete = "SELECT insee, CP, Commune FROM villes;";
		$resultat = $madb->query($requete);//var_dump($resultat);echo "<br/>";  
		$resultat = $madb->query($requete);
		if($resultat){
			$villes = $resultat->fetchAll(PDO::FETCH_ASSOC);			
		}
		var_dump($resultat);
		?> </br> <?php
		var_dump($villes);

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
	
	
	//******************************************************************************
	function afficheFormulaireChoixUtilisateur($choix){
		$madb = new PDO('sqlite:bdd/IUT.sqlite'); 
		$requete = "SELECT Email FROM utilisateurs;";
		$resultat = $madb->query($requete);
		if ($resultat) {
			$utilisateurs = $resultat->fetchAll(PDO::FETCH_ASSOC);			
		}
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
		<fieldset> 
			<select id="id_mail" name="mail" size="1">
				<?php // on se sert de value directement 
				// <option value="prof@prof.fr">prof@prof.fr</option>		
				foreach($utilisateurs as $uti){
					echo '<option value="'.$uti['Email'].'">'.$uti['Email'].'</option>';
					}	
				?>
			</select>
			<input type="submit" name="<?php echo $choix; ?>" value="<?php echo $choix; ?>"/>
		</fieldset>
	</form>
	<?php
		echo "<br/>";
	}// fin afficheFormulaireChoixUtilisateur
	
	
	//*******************************************************************************************
	function afficheFormulaireModificationUtilisateur($mail){
		
		
		
	}// fin afficheFormulaireChoixUtilisateur
?>