
create  table country (
   id    int  primary  key, 

   name  varchar(32)  unique  not  null
) ;


/*Cette première table permet d'enregistrer des pays. Chaque pays possède un ID, qui constitue sa clé primaire. Il est décrit par un nom, qui doit être unique, et non nul.
*/


create  table team (
   country_id  int  not  null  references country(id) , 
   id  int  not  null, 
    
   name  varchar(32)  unique  not  null, 

    constraint pk_team  primary  key (id, country_id)
) ;


/*Notre deuxième table permet de stocker des équipes. Chaque équipe appartient à un pays, représenté par la colonne country_id, déclaré comme clé étrangère sur la colonne id de la table country.

Il possède une déclaration de contrainte sur une table : une clé primaire composite, sur les colonnes id et country_id. Une clé primaire déclarée sur plusieurs colonnes est appelée clé primaire composite .*/


create  table player (
   country_id           int  not  null  references country(id) , 
   id                   int  not  null, 
   team_id              int  references team(id), 
   family_name  varchar(32)  not  null, 
   first_name   varchar(32)  not  null, 
   constraint pk_player  primary  key (id, country_id)
) ;