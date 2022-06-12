
create  table country (
   cid    INTEGER PRIMARY KEY AUTOINCREMENT,

   name  varchar(32)  unique  not  null
) ;


/*Cette première table permet d'enregistrer des pays. Chaque pays possède un ID, qui constitue sa clé primaire. Il est décrit par un nom, qui doit être unique, et non nul.
*/


create  table team (
   cid  int  not  null , 
   tid  INTEGER PRIMARY KEY AUTOINCREMENT,     
   name  varchar(32)  unique  not  null,
   CONSTRAINT fk_team FOREIGN KEY (cid) references country(cid) ON DELETE CASCADE ON UPDATE CASCADE
) ;


/*Notre deuxième table permet de stocker des équipes. Chaque équipe appartient à un pays, représenté par la colonne country_id, déclaré comme clé étrangère sur la colonne id de la table country.

Il possède une déclaration de contrainte sur une table : une clé primaire composite, sur les colonnes id et country_id. Une clé primaire déclarée sur plusieurs colonnes est appelée clé primaire composite .*/


create  table player (
   cid           int  not  null, 
   pid                   INTEGER PRIMARY KEY AUTOINCREMENT, 
   tid              int  not null, 
   family_name  varchar(32)  not  null, 
   first_name   varchar(32)  not  null, 
   img_name    varchar(32),
   CONSTRAINT fk1_player FOREIGN KEY (cid) references country(cid) ON DELETE CASCADE ON UPDATE CASCADE,
   CONSTRAINT fk2_player FOREIGN KEY (tid) references team(tid) ON DELETE CASCADE ON UPDATE CASCADE
) ;

