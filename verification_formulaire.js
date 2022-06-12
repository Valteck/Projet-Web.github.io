function Verification() {
    // Récupérer lavaleur des champs nom et email
    var Nom = document.getElementById('idNom').value;
    var Prenom = document.getElementById('idPrenom').value;

    
    // Contrôle sur le nom
    if(Nom==''){
    alert('Vous devez compléter votre nom !');
    document.getElementById('idNom').style.backgroundColor="red";
    document.getElementById('idNom').style.color="#FFF";
   
    // Permet de bloquer l'envoi du formulaire
    return false;
    }
    else{
    document.getElementById('idNom').style.backgroundColor="#9C6";
    }

    // Contrôle sur le prenom
    if(Prenom==''){
        alert('Vous devez compléter votre prenom !');
        document.getElementById('idPrenom').style.backgroundColor="red";
        document.getElementById('idPrenom').style.color="#FFF";
        
        // Permet de bloquer l'envoi du formulaire
        return false;
        }
        else{
        document.getElementById('idPrenom').style.backgroundColor="#9C6";
        }
   
    }
