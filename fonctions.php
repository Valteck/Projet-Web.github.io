<?php
	//****************Fonctions utilisées**************************************
	function compteExiste($mail,$pass){
		$retour = false ;
		$madb = new PDO('sqlite:bdd/IUT.sqlite'); 
		$mail= $madb->quote($mail);
		$pass = $madb->quote($pass);
		$requete = "SELECT Email,Pass FROM utilisateurs WHERE Email = ".$mail." AND Pass = ".$pass ;
		//var_dump($requete);echo "<br/>";  	
		$resultat = $madb->query($requete);
		$tableau_assoc = $resultat->fetchAll(PDO::FETCH_ASSOC);
		if (sizeof($tableau_assoc)!=0) $retour = true;	
		return $retour;
	}
	//********************************************************************************
	function isAdmin($mail){ // Retourne la valeur du statut. Changement de sujet!!!!!
		$retour = false ;
		// A faire
		$madb = new PDO('sqlite:bdd/IUT.sqlite'); 
		$mail= $madb->quote($mail);
		//SELECT Statut FROM utilisateurs WHERE Email = 'etu@etu.fr'
		$requete = "SELECT Statut FROM utilisateurs WHERE Email = $mail;";
		//var_dump($requete);echo "<br/>";  	
		$resultat = $madb->query($requete);
		if($resultat){
			$res = $resultat->fetch(PDO::FETCH_ASSOC);
			$retour = $res['Statut'];
		}
		return $retour;		
	}
	//********************************************************************************
	function listeCompte()	{ // A faire
		
		$retour = false ;
		$madb = new PDO('sqlite:bdd/IUT.sqlite'); 
		$requete = "SELECT Email, Statut, Adresse, CP, Commune FROM utilisateurs u INNER JOIN villes v ON u.insee = v.insee;";
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
		$requete = "SELECT Email, Statut, Adresse, CP, Commune FROM utilisateurs u INNER JOIN villes v ON u.insee = v.insee WHERE u.insee=$insee;";
		$resultat = $madb->query($requete);
		if($resultat){
			$retour = $resultat->fetchAll(PDO::FETCH_ASSOC);			
		}
		return $retour;
	}
	//*******************************************************************************************
	function ajoutUtilisateur($mail,$pass,$rue,$insee,$status){
		/* on récupère directement le code de la ville qui a été transmis dans l'attribut value de la balise <option> du formulaire
		Il n'est donc pas nécessaire de rechercher le code INSEE de la ville*/
		$retour=0;
		$madb = new PDO('sqlite:bdd/IUT.sqlite'); 	
		// filtrer les paramètres		
		$mail = $madb->quote($mail);
		$pass = $madb->quote($pass);
		$rue = $madb->quote($rue);
		$insee = $madb->quote($insee);
		$status = $madb->quote($status);
		// requête
		//INSERT INTO utilisateurs VALUES ('test@test', 'pass', 'rue dskjfsdj', '22113' , 'Prof' );
		$requete = " INSERT INTO utilisateurs VALUES ($mail, $pass, $rue, $insee , $status );  ";
		$resultat = $madb->exec($requete);	
		if ($resultat == false ) 
			$retour = 0;
		else 
			$retour = $resultat;
		return $retour;
	}
	//*******************************************************************************************
	function supprimerUtilisateur($mail){
		$retour=0;
		$madb = new PDO('sqlite:bdd/IUT.sqlite'); 
		// filtrer le paramètre	
		$mail = $madb->quote($mail);
		$requete = "DELETE FROM utilisateurs WHERE Email = $mail;";
		$retour = $madb->exec($requete);
		
		return $retour;
	}
	//*******************************************************************************************
	function modifierUtilisateur($mail,$pass,$rue,$insee,$status){
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
