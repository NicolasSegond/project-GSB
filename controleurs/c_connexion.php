<?php


if(!isset($_GET['action'])){
	$_GET['action'] = 'demandeConnexion';
}
$action = $_GET['action'];
switch($action){
	
	case 'demandeConnexion':{
		include("vues/v_connexion.php");
		break;
	}
	case 'connecter':{
		$typeUtilisateur = $pdo->retourneTypeUtilisateur($_SESSION['id']);
		$unUser = $pdo->donneinfosmedecin($_SESSION['id']);
		include("vues/v_sommaire.php");
		break;
	}
	case 'valideConnexion':{
		
		$login = $_POST['login'];
		$mdp = $_POST['mdp'];
		$connexionOk = $pdo->checkUser($login,$mdp);
		if(!$connexionOk){
			ajouterErreur("Login ou mot de passe incorrect, le compte n'a peut être pas encore été validé, veuillez rééssayer ultérieurement");
			include("vues/v_erreurs.php");
			include("vues/v_connexion.php");
		}
		else { 
			$infosMedecin = $pdo->donneLeMedecinByMail($login);
			$id = $infosMedecin['id'];
			$_SESSION['id'] = $id;
			$_SESSION['login'] = $login;
			$pdo->ModifCodeVerif($id, generateCode());
			$pdo->EnvoieCodeVerif($id);
			$pdo->updateIP($id);
			$_SESSION['starttime']= microtime(true);
			include('vues/v_verifcode.php');
				
					
					}

					break;	
	}   

	default :{
		include("vues/v_connexion.php");
		break;
	}
}
?>