/*****************************************************************/
function listeFiltreUtilisateurs(ville)	{
var req_AJAX = null;// Objet qui sera crée
if (window.XMLHttpRequest) 	{	// Mozilla, Safari
	req_AJAX= new XMLHttpRequest();
	} 
if (req_AJAX) 	{

	var url = "listeUtilisateurs_ETU.php";

	
	// on définit la méthode TraiteReponse(req_AJAX) qui se sera exécutée lors de chaque cycle de la requête
	req_AJAX.onreadystatechange = function() {
		TraiteListeFiltreUtilisateurs(req_AJAX);
	}	
	// on spécifie l'action que l'on demande au serveur avec la méthode HTTP souhaitée
	req_AJAX.open("POST", url, true);
	req_AJAX.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	
	//req_AJAX.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");  
	// l'envoi de la requête par la méthode POST se fait avec ce Content-Type
	// on envoie la requête : pas de paramètre avec GET
	if(ville.value != 0){
	req_AJAX.send("choix="+ville.value);
	} 
}
else 	{ 	
	alert("EnvoiRequete: pas de XMLHTTP !");	
	}
} // fin fonction listeUtilisateurs()

function TraiteListeFiltreUtilisateurs(requete)	{
	// test si la requête est en cours de chargement et affichage "En cours"
	if(requete.readyState == 1) {
		document.getElementById("tableau").innerHTML = "En cours...";
	}
	// test si la requête est terminée (état 4) et correcte (code HTTP 200)
	if(requete.readyState == 4 && requete.status == 200) {
		var id_ville = requete.responseText;
		console.log(id_ville)
		document.getElementById("tableau").innerHTML=requete.responseText;
	}	
} // fin TraiteListeFiltreUtilisateurs


