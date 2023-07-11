<?php

$token = "";

if(!isset($_GET['action'])){
	$_GET['action'] = 'demandeCreation';
}
$action = $_GET['action'];
switch($action){
	
	case 'demandeCreation':{
		include("vues/v_creation.php");
		break;
	}
	case 'valideCreation':{
		$leLogin = htmlspecialchars($_POST['login']);
        $lePassword = htmlspecialchars($_POST['mdp']);
        $typeUtilisateur = 1;
        $leNom = htmlspecialchars($_POST['nom']);
        $lePrenom = htmlspecialchars($_POST['prenom']);
        $rpps = htmlspecialchars($_POST['rpps']);
        
        if ($leLogin == $_POST['login'])
        {
             $loginOk = true;
             $passwordOk=true;
        }
        else{
            echo 'tentative d\'injection javascript - login refusé';
             $loginOk = false;
             $passwordOk=false;
        }
        //test récup données
        //echo $leLogin.' '.$lePassword;
        $rempli=false;
        if ($loginOk && $passwordOk){
        //obliger l'utilisateur à saisir login/mdp
        $rempli=true; 
        if (empty($leLogin)==true) {
            echo 'Le login n\'a pas été saisi<br/>';
            $rempli=false;
        }
        if (empty($lePassword)==true){
            echo 'Le mot de passe n\'a pas été saisi<br/>';
            $rempli=false; 
        }
        
        
        //si le login et le mdp contiennent quelque chose
        // on continue les vérifications
        if ($rempli){
            //supprimer les espaces avant/après saisie
            $leLogin = trim($leLogin);
            $lePassword = trim($lePassword);

            

            //vérification de la taille du champs
            
            $nbCarMaxLogin = $pdo->tailleChampsMail();
            if(strlen($leLogin)>$nbCarMaxLogin){
                 echo 'Le login ne peut contenir plus de '.$nbCarMaxLogin.'<br/>';
                $loginOk=false;
                
            }
            
            //vérification du format du login
           if (!filter_var($leLogin, FILTER_VALIDATE_EMAIL)) {
                echo 'le mail n\'a pas un format correct<br/>';
                $loginOk=false;
            }

            if($pdo->testMail($leLogin)){
                echo 'le mail existe déjà dans la base de données';
                $loginOk = false;
            }
            
          
            $patternPassword='#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W){12,}#';
            if (preg_match($patternPassword, $lePassword)==false){
                echo 'Le mot de passe doit contenir au moins 12 caractères, une majuscule,'
                . ' une minuscule et un caractère spécial<br/>';
                $passwordOk=false;
            }
            
            
                 
        }
        }
        if($rempli && $loginOk && $passwordOk){
                echo 'tout est ok, nous allons pouvoir créer votre compte...<br/>';
                $executionOK = $pdo->creeMedecin($leNom,$lePrenom,$leLogin,$lePassword,$typeUtilisateur,$rpps);     
               
                if ($executionOK==true){
                    echo "c'est bon, votre compte a bien été créé ;-)";
                    $pdo->connexionInitiale($leLogin);

                    $token = generateCode();
                    $Message = "Voici votre code de validation: " . $token;
                    $sujet = "Validation de mail";

                    $User = $pdo->donneLeMedecinByMail($leLogin);
                    $id = $User['id'];
                    
                    $pdo->envoyerMessage($sujet, $Message, $leLogin);
                    $pdo->envoieMailValidateur($id,$leNom,$lePrenom,$rpps);

                    $pdo->ModifCodeVerif($id, $token);
			        $_SESSION['starttime']= microtime(true);

                    include("vues/v_verificationMail.php");
                }   
                else
                     echo "ce login existe déjà, veuillez en choisir un autre";
        }

        
        break;	
    }
    case 'validerMail':{
        $end_time=microtime(true);
        $total_time = $end_time - $_SESSION['starttime'];
        if($total_time > 86400){
            ajouterErreur("Temps écoulé, veuillez recommencer");
        }
        else{
            if($tokenEntrer == $token){
                connecter($id,$nom,$prenom);
                include("vues/v_sommaire.php");
            }
            else
            {
                echo "Vous n'avez pas entré le bon code";
            }
            
        }
        $tokenEntrer = htmlspecialchars($_POST['token']);
        
		break;
    }
	default :{
		include("vues/v_connexion.php");
		break;
	}
}
