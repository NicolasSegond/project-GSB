<?php

$lesMedecins = $pdo->MedecinsConnecterMoins4Semaines();

if($pdo->testMaintenance() == true){
    $sujet = "Maintenance du site";
    $message = "votre site est en maintenance";
    foreach($lesMedecins as $medecin){
        $mail = $medecin['mail'];
        $pdo->envoyerMessage($sujet, $message, $mail);
    }
    $pdo->activerMaintenance();
}
else{
    $pdo->desactiverMaintenance();
}
//include("vues/v_sommaire.php");
header('Location: index.php?uc=connexion&action=connecter');