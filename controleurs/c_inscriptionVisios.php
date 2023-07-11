<?php 

if(!isset($_GET['action'])){
	$_GET['action'] = 'Inscriptionvisio';
}
$action = $_GET['action'];
switch($action){

    case "consultation":{
        $unUser = $pdo->donneinfosmedecin($_SESSION['id']);
        $typeUtilisateur = $pdo->retourneTypeUtilisateur($_SESSION['id']);
        $visios = $pdo->recupererVisio();
        $lesVisiosInscrits = $pdo->recupereToutLesVisiosInscrits($_SESSION['id']);
        include("vues/v_visioConferences.php");
        break;        
    }
    
	case 'updateVisio': {
        if(isset($_GET['id'])){
            $lesvisios = $pdo->infosVisio($_GET['id']);
        }
        include("vues/consultationVisios/v_updateVisio.php");
        break;
    }

    case 'Retour':{
        $typeUtilisateur = $pdo->retourneTypeUtilisateur($_SESSION['id']);
		$unUser = $pdo->donneinfosmedecin($_SESSION['id']);
        include("vues/v_sommaire.php");
        break;
    }

    case 'avisVisio':{
        include('vues/v_formulaireAvisConferences.php');
        break;
    }

    case 'consulterAvis':{
        $lesAvisConferences = $pdo->recupererAvis($_GET['id']);
        include("vues/v_avisConference.php");
        break;
    }

    case 'creationVisios': {
        include("vues/consultationVisios/v_creationVisio.php");
        break;
    }

    case "s'inscrire":{
        $prods = $pdo->recupererVisio();
        $typeUtilisateur = $pdo->retourneTypeUtilisateur($_SESSION['id']);
		$unUser = $pdo->donneinfosmedecin($_SESSION['id']);
        include("vues/v_inscriptionVisios.php");
		break;
    }

    case 'verifierAvis':{
        $lesAvisNonVerifies = $pdo->recupererAvisNonVerifies();
        include("vues/v_verifierAvis.php");
        break;
    }

    case "Confirmation":{
        $idVisio = $_POST['IdVisio'];
        $mailMedecin = $_POST['mailMedecin'];
        $User = $pdo->donneLeMedecinByMail($mailMedecin);
        

        $uneVisio = $pdo->infosVisio($idVisio);
        if($uneVisio['dateVisio']> date('Y-m-d')){
            $idMedecin=$User[0];
            $pdo->InscriptionVisio($idMedecin, $idVisio);
            $unUser = $pdo->donneinfosmedecin($_SESSION['id']);

            $typeUtilisateur = $pdo->retourneTypeUtilisateur($_SESSION['id']);
            $unUser = $pdo->donneinfosmedecin($_SESSION['id']);
            include("vues/v_sommaire.php");
        }
        else{
            ajouterErreur("La visio est déjà passée, vous ne pouvez pas vous inscrire");
            include("vues/v_erreurs.php");

            $typeUtilisateur = $pdo->retourneTypeUtilisateur($_SESSION['id']);
            $unUser = $pdo->donneinfosmedecin($_SESSION['id']);
            include("vues/v_sommaire.php");

        }

        break;
    }

    case 'validerCreation':{
        $nom = $_POST['nom'];
		$objectif = $_POST['objectif'];
		$url = $_POST['url'];
		$dateVisio = $_POST['dateVisio'];

        if (isset($_POST['creer'])) {$pdo->creeVisio($nom, $objectif, $url, $dateVisio);};

        include('vues/consultationVisios/v_creationVisio.php');
        break;
    }

    case 'validerSuppression': {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }

        if (isset($_POST['submit']) and isset($_GET['id'])) {
            $pdo->SupprimerVisio($id);
            header('Location: index.php?uc=Inscriptionvisio&action=consultation');
        }
        break;
    }

    case 'validerUpdate': {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $unProduit = $pdo->infosVisio($id);
        }

        if (isset($_POST['update'])) {
            $nomVisio = $_POST['nomVisio'];
            $objectif = $_POST['objectif'];
            $url = $_POST['url'];
            $dateVisio =$_POST['dateVisio'];
            $pdo->modificationVisio($id,$nomVisio,$objectif,$url,$dateVisio);
            header('Location: index.php?uc=Inscriptionvisio&action=consultation');
        }
        break;
    }

    case'donnerAvis':{
        if(isset($_GET['id'])){
            $id=$_GET['id'];
        }
        $avis = $_POST['avis'];
        $idMedecin = $_SESSION['id'];
        $idVisio = $_GET['id'];

        if(isset($_POST['submit'])){
            if($pdo->ajouterUnAvis($idMedecin, $idVisio, $avis) == true){
                echo 'Avis non importer (avis déjà existant)';
                header('Location: index.php?uc=Inscriptionvisio&action=consultation');
            }
            else{
                echo 'Avis non importer (avis déjà existant)';
                header('Location: index.php?uc=Inscriptionvisio&action=consultation');
            }
        }
        break;
    }

    case 'validerAvis':{
        if(isset($_GET['idMedecin'])){
            $idMedecin = $_GET['idMedecin'];
        }
        if(isset($_GET['idMedecin'])){
            $idVisio = $_GET['idVisio'];
        }

        if(isset($_POST['accepter'])){
            if($pdo->accepterAvis($idMedecin,$idVisio)){
                echo 'avis accepter';
                header('Location: index.php?uc=Inscriptionvisio&action=verifierAvis');
            }
        }
        break;
    }
    
    case 'refuserAvis':{
        if(isset($_GET['idMedecin'])){
            $idMedecin = $_GET['idMedecin'];
        }
        if(isset($_GET['idMedecin'])){
            $idVisio = $_GET['idVisio'];
        }

        if(isset($_POST['refuser'])){
            if($pdo->refuserAvis($idMedecin,$idVisio)){
                echo 'avis refuser (suppression avis)';
                header('Location: index.php?uc=Inscriptionvisio&action=verifierAvis');
            }
        }
        break;
    }
}

?>