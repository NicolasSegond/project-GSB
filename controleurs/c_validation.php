<?php 

if(!isset($_GET['action'])){
	$_GET['action'] = 'validerInscription';
}
$action = $_GET['action'];
switch($action){
    case 'validerInscription':{
        $lesMedecins = $pdo->recupererMedecinNonValider();
        include("vues/v_validationComptes.php");
		break;
    }
    case 'validerMedecin':{
        if(isset($_GET['idMedecin'])){
            $id = $_GET['idMedecin'];
        }
        $unUser = $pdo->donneinfosmedecin($id);
        if(isset($_POST['valider'])){
            $pdo->validerMedecin($id);
            echo 'Médecin valider'."<br>";
            $pdo->envoyerMessage("Vous avez été validé par un validateur !","Félicitation, vous avez été validé vous pouvez maintenant vous connectez et profiter des fonctionnalités du site",$unUser['mail']);
        }
        else if(isset($_POST['refuser'])){
            $pdo->refuserMedecin($id);
            echo 'Médecin refuser'."<br>";
            $pdo->envoyerMessage("Vous avez été réfusé par un validateur !","Nous sommes désolé votre inscription au site internet a été réfusé",$unUser['mail']);
        }
        header('Location: index.php?uc=validation&action=validerInscription');
    }
}
?>