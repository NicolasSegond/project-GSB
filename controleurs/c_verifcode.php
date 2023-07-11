<?php
$unUser = $pdo->donneinfosmedecin($_SESSION['id']);
$typeUtilisateur = $pdo->retourneTypeUtilisateur($_SESSION['id']);

if(!isset($_GET['action'])){
	$_GET['action'] = 'demandeConnexion';
}
$action = $_GET['action'];
switch($action){
	
    case 'verifcodeconnexion':{
        $connexionOk=false;
	    $id = $_SESSION['id'];
        $codedb = $pdo->verificationcodeconnexion($id);
        if($codedb == $_POST['codeconnexion'] && $codedb != NULL)
        {
            $connexionOk=true;
        }
        if(!$connexionOk){
            ajouterErreur("Les codes ne correspondent pas");
			include("vues/v_erreurs.php");
			include("vues/v_connexion.php");
        }
        else{
            $infosMedecin = $pdo->donneLeMedecinByMail($_SESSION['login']);
            $id = $infosMedecin['id'];
            $nom =  $infosMedecin['nom'];
            $prenom = $infosMedecin['prenom'];
            $end_time=microtime(true);
            $total_time = $end_time - $_SESSION['starttime'];
            if($total_time > 60){
                ajouterErreur("Temps écoulé, veuillez recommencer");
                include("vues/v_erreurs.php");
                include("vues/v_connexion.php");
            }
            else{
                
                connecter($id,$nom,$prenom);
                $estEnMaintenance = $pdo->testMaintenance();
		        $typeUtilisateur = $pdo->retourneTypeUtilisateur($_SESSION['id']);
                $dureeMaintenance = $pdo->recupererDureeMaintenance();
		        if($estEnMaintenance == true){	
			        include("vues/v_sommaire.php");
		        }
		        else{
                    switch($typeUtilisateur['TypeUtilisateur']){
                        case 1:{
                            include("vues/v_maintenance.php");
                            break;}
                        case 2:{
                            include("vues/v_maintenance.php");
                            break;}
                        case 3:{
                            include("vues/v_maintenance.php");
                            break;}
                        case 4:{
                            include("vues/v_maintenance.php");
                            break;}
                        case 5:{
                            include("vues/v_sommaire.php");
                            break;}
                    }	
                } 
            }
        }
    }
}
?>