<?php
//on insère le fichier qui contient les fonctions
require_once ("../include/class.pdogsb.inc.php");

//appel de la fonction qui permet de se connecter à la base de données

/*$lePdo = PdoGsb::getPdoGsb();
var_dump($lePdo->afficheOpeMedecin());*/
if(mime_content_type("image.jpg") == "image/jpg"){
    echo "Bien joué";
}
else{
    echo "Pas le bon type";
}


