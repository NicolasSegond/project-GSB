<?php

$unUser = $pdo->donneinfosmedecin($_SESSION['id']);

if (!isset($_GET['action'])) {
	$_GET['action'] = 'demandeCreation';
}
$action = $_GET['action'];
switch ($action) {

	case 'consultationProduits': {
			$unUser = $pdo->donneinfosmedecin($_SESSION['id']);
			$prods = $pdo->recupererProduits();
			include("vues/v_CRUDProduits.php");
			break;
		}

	case 'creationProduits': {
			include("vues/v_creationProduit.php");
			break;
		}

	case 'updateProduit': {
			if(isset($_GET['id'])){
				$unProduit = $pdo->infosProduit($_GET['id']);
			}
			include("vues/v_updateProduit.php");
			break;
		}

	case 'validerCreation': {
			$nom = $_POST['nom'];
			$objectif = $_POST['objectif'];
			$informations = $_POST['informations'];
			$effets = $_POST['effetsIndesirables'];
			$images = "";

			if (isset($_POST['creer'])) {
				if (isset($_FILES['image']) and !empty($_FILES['image']['name'])) {
					$tailleMax = 2097152;
					if ($_FILES['image']['size'] <= $tailleMax) {
						$extensionUpload = strtolower(substr(strrchr($_FILES['image']['name'], '.'), 1));
						if (mime_content_type($_FILES['image']['tmp_name']) == "image/png" || mime_content_type($_FILES['image']['tmp_name']) =="image/jpeg" || 
						mime_content_type($_FILES['image']['tmp_name']) =="image/jpg" || mime_content_type($_FILES['image']['tmp_name']) == "image/gif") {
							$chemin = "images/" . $nom . "." . $extensionUpload;
							$resultat = move_uploaded_file($_FILES['image']['tmp_name'], $chemin);
							if ($resultat) {
								$images = $nom . "." . $extensionUpload;
								$pdo->creeProduit($nom, $images, $objectif, $informations, $effets, $_SESSION['id']);
								echo 'produit crée';
								include('vues/v_creationProduit.php');
							} else {
								echo "Erreur durant l'importation de votre produit";
							}
						} else {
							echo "La photo du produit doit être au format jpg, jpeg, gif ou png";
						}
					} else {
						echo "Votre photo de profil ne doit pas dépasser 2Mo";
					}
				}
			}
			include('vues/v_creationProduit.php');
			break;
		}

	case 'validerSuppression': {
			if (isset($_GET['id'])) {
				$id = $_GET['id'];
			}

			if (isset($_POST['submit']) and isset($_GET['id'])) {
				$pdo->supprimerProduit($id, $_SESSION['id']);
				header('Location: index.php?uc=produit&action=consultationProduits');
			}
			break;
		}

	case 'validerUpdate': {
			if (isset($_GET['id'])) {
				$id = $_GET['id'];
				$unProduit = $pdo->infosProduit($id);
			}
			$nom = $_POST['nom'];
			if ($_FILES['image'] == null) {
				$image = $unProduit['image'];
			} else {
				$image = $nom . '.jpg';
				if (isset($_FILES['image']) and !empty($_FILES['image']['name'])) {
					$tailleMax = 2097152;
					$extensionsValides = array('jpg', 'jpeg', 'gif', 'png');
					if ($_FILES['image']['size'] <= $tailleMax) {
						$extensionUpload = strtolower(substr(strrchr($_FILES['image']['name'], '.'), 1));
						if (in_array($extensionUpload, $extensionsValides)) {
							$chemin = "images/" . $nom . "." . $extensionUpload;
							$resultat = move_uploaded_file($_FILES['image']['tmp_name'], $chemin);
							if ($resultat) {
								$images = $nom . "." . $extensionUpload;
							} else {
								$msg = "Erreur durant l'importation de votre produit";
							}
						} else {
							$msg = "Votre photo de profil doit être au format jpg, jpeg, gif ou png";
						}
					} else {
						$msg = "Votre photo de profil ne doit pas dépasser 2Mo";
					}
				}
			}
			$objectif = $_POST['objectif'];
			$information = $_POST['informations'];
			$effets = $_POST['effetsIndesirables'];

			if (isset($_POST['update'])) {
				if ($pdo->modificationProduit($id, $nom, $image, $objectif, $information, $effets, $_SESSION['id'])) {
					echo 'modification apporté';
					header('Location: index.php?uc=produit&action=consultationProduits');
				}
				$image = $unProduit['image'];
	
				$CheminFichier = "images/" . $unProduit['image'];
				$CheminCible = "images/" . $nom . '.jpg';
	
				if (file_exists($CheminFichier)) {
					rename($CheminFichier, $CheminCible);
				} 
			}
			break;
		}
}
