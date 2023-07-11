
<?php
require_once("include/fct.inc.php");
require_once ("include/class.pdogsb.inc.php");
session_start();



date_default_timezone_set('Europe/Paris');



$pdo = PdoGsb::getPdoGsb();
$estConnecte = estConnecte();

if(!isset($_GET['uc'])){
	$_GET['uc'] = 'connexion';
}
else {
	if($_GET['uc'] =="connexion" && !estConnecte()){
		$_GET['uc'] = 'connexion';
	}	   
}



$uc = $_GET['uc'];
switch($uc){
	case 'connexion':{
		include("controleurs/c_connexion.php");break;
	}
    case 'creation':{
		include("controleurs/c_creation.php");break;
	}
	
	case 'codeconnexion':{
		include("controleurs/c_verifcode.php");break;
	}
        
    case 'validation':{
		include("controleurs/c_validation.php");break;
	}

	case'maintenance':{
		include("controleurs/c_maintenance.php");break;
	}

	case 'Inscriptionvisio':{
		include("controleurs/c_inscriptionVisios.php");break;
	}
	
	case'consulteOperation':{
		include("vues/v_consulteOperation.php");break;
	}
	
	case 'produit':{
		include("controleurs/c_produits.php");break;
	}
	case 'statistique':{
		include("controleurs/c_statistiques.php");break;
	}

}
?>







