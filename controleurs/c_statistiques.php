<?php

if (!isset($_GET['action'])) {
	$_GET['action'] = 'demandeCreation';
}
$action = $_GET['action'];
switch ($action) {
    case 'consultationStat':{
        $statParMois = $pdo->recupererDureeTotaleInterruptionParMois();
        $statParAnnee = $pdo->recupererDureeTotaleInterruptionParAnnee();
        include("vues/v_statistiques.php");
        break;
    }
}