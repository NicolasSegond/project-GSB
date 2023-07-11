<?php

/** 
 * Classe d'accÃ¨s aux donnÃ©es. 
 
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 
 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb
{
    private static $serveur = 'mysql:host=localhost';
    private static $bdd = 'dbname=gsbextranet1';
    private static $user = 'root';
    private static $mdp = '';
    private static $monPdo;
    private static $monPdoGsb = null;

    /**
     * Constructeur privÃ©, crÃ©e l'instance de PDO qui sera sollicitÃ©e
     * pour toutes les mÃ©thodes de la classe
     */
    private function __construct()
    {

        PdoGsb::$monPdo = new PDO(PdoGsb::$serveur . ';' . PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp);
        PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
    }
    public function _destruct()
    {
        PdoGsb::$monPdo = null;
    }
    /**
     * Fonction statique qui crÃ©e l'unique instance de la classe
 
     * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
 
     * @return l'unique objet de la classe PdoGsb
     */
    public  static function getPdoGsb()
    {
        if (PdoGsb::$monPdoGsb == null) {
            PdoGsb::$monPdoGsb = new PdoGsb();
        }
        return PdoGsb::$monPdoGsb;
    }
    /**
     * vÃ©rifie si le login et le mot de passe sont corrects
     * renvoie true si les 2 sont corrects
     * @param type $login
     * @param type $pwd
     * @return bool
     * @throws Exception
     */
    function checkUser($login, $pwd): bool
    {
        //AJOUTER TEST SUR TOKEN POUR ACTIVATION DU COMPTE
        $user = false;
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT motDePasse, RppsValide FROM medecin WHERE mail= :login AND token IS NULL");
        $bvc1 = $monObjPdoStatement->bindValue(':login', $login, PDO::PARAM_STR);
        if ($monObjPdoStatement->execute()) {
            $unUser = $monObjPdoStatement->fetch();
            if (is_array($unUser)) {
                if ($pwd == $unUser['motDePasse'] && $unUser['RppsValide'] == 1)
                    $user = true;
            }
        } else
            throw new Exception("erreur dans la requÃªte");
        return $user;
    }



    /**
     * Selectionne les informations du médecin en fonction de l'email
     * Retourne un objet $unUser correspondant à un médecin
     * @param type $login
     * @return User
     * @throws Exception
     */
    function donneLeMedecinByMail($login)
    {

        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT id,nom,prenom,rpps,mail FROM medecin WHERE mail= :login");
        $bvc1 = $monObjPdoStatement->bindValue(':login', $login, PDO::PARAM_STR);
        if ($monObjPdoStatement->execute()) {
            $unUser = $monObjPdoStatement->fetch();
        } else
            throw new Exception("erreur dans la requÃªte");
        return $unUser;
    }

    /**
     * Calcule la taille du champ mail de la table medecin
     * Retourne un tableau avec la longueur du champ
     */
    public function tailleChampsMail()
    {
        $pdoStatement = PdoGsb::$monPdo->prepare("SELECT CHARACTER_MAXIMUM_LENGTH FROM INFORMATION_SCHEMA.COLUMNS
WHERE table_name = 'medecin' AND COLUMN_NAME = 'mail'");
        $execution = $pdoStatement->execute();
        $leResultat = $pdoStatement->fetch();

        return $leResultat[0];
    }

    /**
     * Fonction qui crée un médecin avec les informations saisies
     * retourne un booleen confirmant ou non l'execution
     * @param type $email
     * @param type $mdp
     */
    function creeMedecin($nom, $prenom, $email, $mdp, $typeUtilisateur, $rpps)
    {
        $pdoStatement = PdoGsb::$monPdo->prepare("INSERT INTO medecin(id, nom, prenom, mail, motDePasse, dateCreation, rpps, dateConsentement, typeUtilisateur) VALUES (null, :leNom, :lePrenom, :leMail, :leMdp, now(), :rpps, now(), :typeUtilisateur)");
        $bv1 = $pdoStatement->bindValue(':leNom', $nom);
        $bv1 = $pdoStatement->bindValue(':lePrenom', $prenom);
        $bv1 = $pdoStatement->bindValue(':leMail', $email);
        $bv2 = $pdoStatement->bindValue(':leMdp', $mdp);
        $bv5 = $pdoStatement->bindValue(':typeUtilisateur', $typeUtilisateur);
        $bv6 = $pdoStatement->bindValue(':rpps', $rpps);
        $execution = $pdoStatement->execute();
        return $execution;
    }

    /**
     * Fonction pour tester l'existence du mail dans la BDD
     * Retourne un booléen true en cas de réussite de la recherche, sinon un booléen false
     * @param type $email
     * @return bool
     */
    function testMail($email)
    {
        $pdo = PdoGsb::$monPdo;
        $pdoStatement = $pdo->prepare("SELECT count(*) as nbMail FROM medecin WHERE mail = :leMail");
        $bv1 = $pdoStatement->bindValue(':leMail', $email);
        $execution = $pdoStatement->execute();
        $resultatRequete = $pdoStatement->fetch();
        if ($resultatRequete['nbMail'] == 0)
            $mailTrouve = false;
        else
            $mailTrouve = true;

        return $mailTrouve;
    }

    /**
     * Fonction pour Récupérer l'id d'un médecin et l'envoyer vers une autre fonction d'ajout à la bd
     * @param type $mail
     * 
     */
    function connexionInitiale($mail)
    {
        $pdo = PdoGsb::$monPdo;
        $medecin = $this->donneLeMedecinByMail($mail);
        $id = $medecin['id'];
        $this->ajouteConnexionInitiale($id);
    }
    /** 
     *Fonction qui stocke les informations du médecin dans l'historique de connexion
     *Retourne un booléen attestant de l'éxecution de la requête.
     *@param type $id
     *@return bool
     */
    function ajouteConnexionInitiale($id)
    {
        $pdoStatement = PdoGsb::$monPdo->prepare("INSERT INTO historiqueconnexion "
            . "VALUES (:leMedecin, now(), now())");
        $bv1 = $pdoStatement->bindValue(':leMedecin', $id);
        $execution = $pdoStatement->execute();
        return $execution;
    }

    /**
     * Fonction pour récupérer l'id, le nom et prénom d'un médecin en fonction de son id
     * Retourne un objet Medecin
     * @param type $id
     */
    function donneinfosmedecin($id)
    {
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT id,mail,nom,prenom,typeUtilisateur FROM medecin WHERE id= :lId");
        $bvc1 = $monObjPdoStatement->bindValue(':lId', $id, PDO::PARAM_INT);
        if ($monObjPdoStatement->execute()) {
            $unUser = $monObjPdoStatement->fetch();
        } else {
            throw new Exception("erreur");
        }
        return $unUser;
    }

    /**
     * Fonction pour récupérer le type d'utilisateur en fonction de son id
     * Retourne un objet Utilisateur
     * @param type $id
     */
    function retourneTypeUtilisateur($id)
    {
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT typeutilisateur.TypeUtilisateur, typeutilisateur.nomType FROM medecin INNER JOIN typeutilisateur ON medecin.typeUtilisateur = typeutilisateur.typeUtilisateur WHERE id = :id");
        $bvc1 = $monObjPdoStatement->bindValue(':id', $id, PDO::PARAM_INT);
        if ($monObjPdoStatement->execute()) {
            $unUser = $monObjPdoStatement->fetch();
        } else {
            throw new Exception("erreur");
        }
        return $unUser;
    }

    
    /**
     * Procédure pour envoyer le message en fonction du sujet, du contenue du message et du mail de l'utilisateur
     *
     * @param String $sujet
     * @param String $message
     * @param String $email
     */
    function envoyerMessage($sujet, $message, $email)
    {
        if (mail($email, $sujet, $message)) {
            echo "Email envoyé avec succès";
        } else {
            echo "Échec de l'envoi de l'email...";
        }
    }

    /**
     * Procédure qui modifie le code de verification dans la base de donnée en fonction de l'id et du code généré
     *
     * @param type $id
     * @param type $code
     */
    function ModifCodeVerif($id, $code)
    {
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("UPDATE medecin SET CodeVerif = :lecode WHERE id= :lId");
        $bvc1 = $monObjPdoStatement->bindValue(':lId', $id);
        $bvc2 = $monObjPdoStatement->bindValue(':lecode', $code);
        $monObjPdoStatement->execute();
    }

    /**
     * Procédure qui envoie le code de vérification pour la connexion en fonction de l'id
     *
     * @param type $id
     */
    function EnvoieCodeVerif($id)
    {
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT CodeVerif FROM medecin WHERE id= :lId");
        $bvc1 = $monObjPdoStatement->bindValue(':lId', $id);
        $monObjPdoStatement->execute();
        $leResultat = $monObjPdoStatement->fetch();

        $sujet = "Votre code de vérification";
        $message = "Veuillez entrer ce code pour vérifier votre connexion : " . $leResultat[0];
        $email = $_SESSION['login'];
        $this->envoyerMessage($sujet, $message, $email);
    }
    
    /**
     * Procédure qui retourne le code pour la connexion en fonction de l'id
     * Retourne un codeVerif
     * @param type $id
     */
    function verificationcodeconnexion($id)
    {
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT CodeVerif FROM medecin WHERE id= :lId");
        $bvc1 = $monObjPdoStatement->bindValue(':lId', $id);
        $monObjPdoStatement->execute();
        $leResultat = $monObjPdoStatement->fetch();
        return $leResultat[0];
    }

    /**
     * Procédure qui envoie un mail au validateur contenant l'id, le nom, le prénom et le numéro de rpps de l'utilisateur qui dois se faire valider
     *
     * @param type $id
     * @param String $nom
     * @param String $prenom
     * @param type $rpps
     */
    function envoieMailValidateur($id, $nom, $prenom, $rpps)
    {
        $pdo = PdoGsb::$monPdo;
        $email = 'contactgsb9@gmail.com';
        $sujet = 'Information du client';
        $message = "le médecin est : " . $nom . " " . $prenom . " dont l'id est : " . $id . " son numéro de rpps est : " . $rpps . "\n http://localhost/B3-AP-GSB/";
        $this->envoyerMessage($sujet, $message, $email);
    }
    /**
     * Procédure qui valide le médecin dans la base de données (met le booléen a 1) en fonction de l'id de l'utilisateur entré
     *
     * @param type $id
     */
    function validerMedecin($id)
    {
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("UPDATE medecin SET RppsValide = 1 WHERE id = :id");
        $monObjPdoStatement->bindValue(':id', $id, PDO::PARAM_INT);
        $monObjPdoStatement->execute();
    }

    /**
     * Procédure qui refuse en médecin en fonction de son id (le supprime de la base de donnée en même temps)
     *
     * @param type $id
     */
    function refuserMedecin($id)
    {
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("DELETE FROM medecin WHERE id = :id");
        $monObjPdoStatement->bindValue(':id', $id, PDO::PARAM_INT);
        $monObjPdoStatement->execute();
    }
 
    /**
     * Fonction qui retourne une requête sql qui sera utiliser dans un tableau ensuite
     * Retourne la requête sql 
     */
    function recupererProduits(){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT * FROM produit");
        if ($monObjPdoStatement->execute()) {
            $produits = $monObjPdoStatement;
        } else {
            throw new Exception("erreur");
        }
        return $produits;
    }

    /**
     * Procédure qui crée un produit en fonction des éléments entrés en paramètres et qui ajoute une opération à la création d'un produit a partir de son id recupérant avant
     * @param type $nom
     * @param type $image
     * @param type $objectif
     * @param type $informations
     * @param type $effets
     * @param type $idCompte
     */
    function creeProduit($nom,$image,$objectif,$informations,$effets,$idCompte){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("INSERT INTO produit(nom,image,objectif, information,effetIndesirable,vuesProduits) VALUES(:nom,:image,:objectif,:information,:effets, 0)");
        $bv1 = $monObjPdoStatement->bindValue(':nom', $nom);
        $bv2 = $monObjPdoStatement->bindValue(':image', $image);
        $bv3 = $monObjPdoStatement->bindValue(':objectif', $objectif);
        $bv5 = $monObjPdoStatement->bindValue(':information', $informations);
        $bv6 = $monObjPdoStatement->bindValue(':effets', $effets);
        $execution = $monObjPdoStatement->execute();


        $requete = $pdo->prepare("SELECT id FROM produit where nom = :nom");
        $requete->bindValue(':nom', $nom);
        $requete->execute();
        $resultat = $requete->fetch();
        $this->ajoutOpeProd($resultat['id'], 1, $idCompte);
        return $execution;
    }

    /**
     * Procédure qui supprimer le produit en fonction de son id entrée en paramètre et qui ajoute une opération de suppression
     * @param type $id
     * @param type $idMedecin
     */
    function supprimerProduit($id, $idMedecin){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("DELETE FROM produit WHERE id = :id");
        $bv6 = $monObjPdoStatement->bindValue(':id', $id);
        $execution = $monObjPdoStatement->execute();
        $this->ajoutOpeProd($id, 3, $idMedecin);
    }

    /**
     * Procédure qui modifie un produit en fonction des éléments entrées en paramètre et qui ajoute une opération de modification
     * @param type $id
     * @param type $nom
     * @param type $image
     * @param type $objectif
     * @param type $information
     * @param type $effetsIndesirable
     * @param type $idCompte
     */
    function modificationProduit($id,$nom,$image,$objectif,$information,$effetsIndesirable,$idCompte){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("UPDATE produit SET nom=:nom, image=:image, objectif=:objectif, information=:information, effetIndesirable=:effetIndesirable WHERE id=:id");
        $monObjPdoStatement->bindValue(':nom', $nom);
        $monObjPdoStatement->bindValue(':image', $image);
        $monObjPdoStatement->bindValue(':objectif', $objectif);
        $monObjPdoStatement->bindValue(':information', $information);
        $monObjPdoStatement->bindValue(':effetIndesirable', $effetsIndesirable);
        $monObjPdoStatement->bindValue(':id', $id);
        $execution = $monObjPdoStatement->execute();
        $this->ajoutOpeProd($id, 2, $idCompte);
        return $execution;
    }

    /**
     * Fonction qui retourne les informations du produit dont l'id est entré en paramètre
     * Retourne un tableau d'information du produit
     * @param type $id
     */
    function infosProduit($id){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT * FROM produit WHERE id=:id");
        $monObjPdoStatement->bindValue(':id', $id);
        if ($monObjPdoStatement->execute()) {
            $produit = $monObjPdoStatement->fetch();
        } else {
            throw new Exception("erreur");
        }
        return $produit;
    }
    /**
     * Fonction qui ajoute une vue à tous les produits
     */
    function ajouteVueProduit(){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo-> prepare("UPDATE produit set vuesProduits = vuesProduits+1");
        $monObjPdoStatement->execute();
    }

    /**
     * Fonction qui recupère l'ip de l'utilisateur connecté
     * Retourne l'ip de l'utilisateur
     */
    function recupererIP(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
          $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
          $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
          $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
        echo $ip;
    }

    /**
    * Procédure qui modifie l'ip de l'utilisateur dont l'id est entré en paramètre
    * @param type $id
    */
    function updateIP($id){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("UPDATE medecin SET ip=:ip WHERE id=:id");
        $monObjPdoStatement->bindValue(':ip', $this->recupererIP());
        $monObjPdoStatement->bindValue(':id', $id);
        $execution = $monObjPdoStatement->execute();
        return $execution;
    }

    /**
    * Procédure qui affiche les opérations faites sur les produits
    */
    function afficheOpeProduit(){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT produit.nom, medecin.mail, medecin.ip, typeoperationproduit.typeOperation FROM medecin
        INNER JOIN listeoperationproduit
        ON listeoperationproduit.idMedecin = medecin.id 
        INNER JOIN produit 
        ON listeoperationproduit.idProd = produit.id
        INNER JOIN typeoperationproduit
        ON listeoperationproduit.idTypeOpe = typeoperationproduit.idTypeOpe");
        $execution = $monObjPdoStatement->execute();
        if ($execution = true) {
            $resultat = $monObjPdoStatement;
        }
        else {
            throw new Exception("erreur");
        }
        return $resultat;
    }

    /**
    * Procédure qui modifie la valeur de la maintenance a true dans la base de donnée afin de mettre le site en maintenance
    */
    function activerMaintenance(){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("UPDATE maintenance SET siMaintenance = 1");
        $monObjPdoStatement->execute();
    }

    /**
    * Procédure qui modifie la valeur de la maintenance a false dans la base de donnée afin de désactiver le site en maintenance
    */
    function desactiverMaintenance(){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("UPDATE maintenance SET siMaintenance = 0");
        $monObjPdoStatement->execute();
    }

    /*
    * Fonction qui test si le boléeen est true ou false, c'est-à-dire si le site est en maintenance ou non
    * Retourne un booléen indiquant si le site est en maintenance ou non
    */
    function testMaintenance(){
        $reponse = false;
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT siMaintenance FROM maintenance");
        $execution = $monObjPdoStatement->execute();
        $resultatRequete = $monObjPdoStatement->fetch();
        if($resultatRequete[0] == 1){
            //il y a une maintenance
            $reponse = false;
        }
        else{
            //Pas de maintenance
            $reponse = true;
        }
        return $reponse;
    }

    /**
     * Procédure qui ajoute une opération sur les produits en fonction du produit, du type d'opération et de l'id de l'utilisateur connécté
     * @param type $nomProd
     * @param type $typeOpe
     * @param type $idCompte
     */
    function ajoutOpeProd($nomProd, $typeOpe, $idCompte){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("INSERT INTO listeoperationproduit VALUES(:idProd, :typeOpe, :idMedecin)");
        $monObjPdoStatement->bindValue(':idProd', $nomProd);
        $monObjPdoStatement->bindValue(':typeOpe', $typeOpe);
        $monObjPdoStatement->bindValue(':idMedecin', $idCompte);
        $monObjPdoStatement->execute();
    }

     /**
     * Fonction qui recuperer l'id de la conférence entré en paramètre
     * Retourne l'id de la conférence
     * @param type $nomConference
     */
    function getIdVisio($nomConference){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT id FROM visioconference where nomVisio == :nomConference");
        $monObjPdoStatement->bindValue(':nomVisio',$nomConference);
        $monObjPdoStatement->execute();
        $leResultat = $monObjPdoStatement->fetch();
        return $leResultat[0]; 
    }

     /**
     * Procédure qui retourne la requete récupérant toutes les visio-conférences utilisé ensuite en tant que tableau
     * Retourne la requête
     */
    function recupererVisio(){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT * FROM visioconference");
        $monObjPdoStatement->execute();
        $visios= $monObjPdoStatement;
        return $visios;  
    }

    /**
     * Procédure qui ajoute une inscription au médecin don't l'id est entré en paramètre sur la visio dont l'id est également entré en paramètre
     * @param type $id
     * @param type $laVisio
     */
    function InscriptionVisio($id, $laVisio){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("INSERT INTO medecinvisio Values (:leMedecin, :lavisio, now()) ");
        $monObjPdoStatement->bindValue(':leMedecin', $id, PDO::PARAM_INT);
        $monObjPdoStatement->bindValue(':lavisio', $laVisio, PDO::PARAM_INT);
        $monObjPdoStatement->execute();
    }

    /**
     * Procédure supprimer la visio dont l'id est entré en paramètre
     * @param type $laVisio
     */
    function SupprimerVisio($idVisio){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("DELETE FROM visioconference WHERE id = :id");
        $monObjPdoStatement->bindValue(':id', $idVisio, PDO::PARAM_INT);
        $monObjPdoStatement->execute();

    }

    /**
     * Procédure qui crée une visio-conférence selon les éléments entrés en paramètre
     * @param type $nom
     * @param type $objectif
     * @param type $url
     * @param type $dateVisio
     */
    function creeVisio($nom,$objectif,$url,$dateVisio){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("INSERT INTO visioconference(nomVisio,objectif,url,dateVisio) VALUES(:nomVisio,:objectif,:url,:dateVisio)");
        $bv1 = $monObjPdoStatement->bindValue(':nomVisio', $nom);
        $bv3 = $monObjPdoStatement->bindValue(':objectif', $objectif);
        $bv5 = $monObjPdoStatement->bindValue(':url', $url);
        $bv6 = $monObjPdoStatement->bindValue(':dateVisio', $dateVisio);
        $monObjPdoStatement->execute();

    }

    /**
     * Procédure qui modifie une visio-conférence en fonction des éléments entrés en paramètre
     * @param type $id
     * @param type $nom
     * @param type $objectif
     * @param type $url
     * @param type $dateVisio
     */
    function modificationVisio($id,$nom,$objectif,$url,$dateVisio){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("UPDATE visioconference SET nomVisio=:nom, objectif=:objectif, url=:url, dateVisio=:dateVisio WHERE id=:id");
        $monObjPdoStatement->bindValue(':nom', $nom);
        $monObjPdoStatement->bindValue(':objectif', $objectif);
        $monObjPdoStatement->bindValue(':url', $url);
        $monObjPdoStatement->bindValue(':dateVisio', $dateVisio);
        $monObjPdoStatement->bindValue(':id', $id);
        $monObjPdoStatement->execute();
    }

    /**
     * Fonction retourne les informations sur la visio-conférence dont l'id est entré en paramètre
     * Retourne les information
     * @param type $id
     */
    function infosVisio($id){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT * FROM visioconference WHERE id=:id");
        $monObjPdoStatement->bindValue(':id', $id);
        if ($monObjPdoStatement->execute()) {
            $produit = $monObjPdoStatement->fetch();
        } else {
            throw new Exception("erreur");
        }
        return $produit;
    }

    /**
     * Fonction qui retourne les visios-conférence dont le médecin (avec l'id entré en paramètre) à participer
     * Retourne un tableau de toute les visios-conférence
     * @param type $id
     */
    function recupereToutLesVisiosInscrits($id){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT id,nomVisio,objectif,url,dateVisio,medecinvisio.idMedecin FROM visioconference
         INNER JOIN medecinvisio ON visioconference.id = medecinvisio.idVisio WHERE medecinvisio.idMedecin =:leMedecin
         AND medecinvisio.dateInscription<now()");
        $monObjPdoStatement->bindValue(':leMedecin', $id, PDO::PARAM_INT);
        if ($monObjPdoStatement->execute()) {
            $lesVisios = $monObjPdoStatement->fetchAll(PDO::FETCH_ASSOC);
        } else
            throw new Exception("erreur dans la requÃªte");
        return $lesVisios;
    }

    /**
     * Procédure ajoute un avis dans la table avisconference en fonction de l'id du médecin, de l'id de la visio, et de l'avis entrés en paramètre
     * @param type $idMedecin
     * @param type $idVisio
     * @param type $avis
     */
    function ajouterUnAvis($idMedecin, $idVisio, $avis){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("INSERT INTO avisconference(idMedecin,idVisio,avis,validationAvis) VALUES(:idMedecin,:idVisio,:avis,:validationAvis)");
        $bv1 = $monObjPdoStatement->bindValue(':idMedecin', $idMedecin);
        $bv3 = $monObjPdoStatement->bindValue(':idVisio', $idVisio);
        $bv5 = $monObjPdoStatement->bindValue(':avis', $avis);
        $bv6 = $monObjPdoStatement->bindValue(':validationAvis', 0);
        $execution = $monObjPdoStatement->execute();
        return $execution;
    }

    /**
     * Fonction qui récupère les avis de la visio dont l'id est entré en paramètre
     * Retourne la requete sql qui sera utiliser ensuite en tant que tableau pour les afficher
     * @param type $id
     */
    function recupererAvis($id){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT nom, prenom, avis, validationAvis FROM avisconference 
        INNER JOIN medecin ON avisconference.idMedecin = medecin.id WHERE idVisio = :id AND validationAvis = 1");
        $bv1 = $monObjPdoStatement->bindValue(':id', $id);
        if ($monObjPdoStatement->execute()) {
            $lesAvis = $monObjPdoStatement;
        } else {
            throw new Exception("erreur");
        }
        return $lesAvis;
    }

    /**
     * Fonction qui récupère les avis non vérifiés dans la base de données c'est-à-dire celle ou la validationAvis est a false (le modo ne la pas encore valider)
     * Retourne la requete sql qui sera utiliser ensuite en tant que tableau pour les afficher
     */
    function recupererAvisNonVerifies(){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT avis, medecin.nom, medecin.prenom, visioconference.nomVisio, avisconference.idMedecin, avisconference.idVisio FROM avisconference 
        INNER JOIN medecin ON avisconference.idMedecin = medecin.id 
        INNER JOIN visioconference ON avisconference.idVisio = visioconference.id
        WHERE validationAvis = 0");
        if ($monObjPdoStatement->execute()) {
            $lesAvis = $monObjPdoStatement;
        } else {
            throw new Exception("erreur");
        }
        return $lesAvis;
    }

    /**
     * Procédure qui modifie la valeur de validationAvis à true c'est-à-dire accepter l'avis
     */
    function accepterAvis($idMedecin,$idVisio){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("UPDATE avisconference SET validationAvis = :validationAvis WHERE idMedecin=:idMedecin AND idVisio=:idVisio");
        $monObjPdoStatement->bindValue(':validationAvis', 1);
        $monObjPdoStatement->bindValue(':idMedecin', $idMedecin);
        $monObjPdoStatement->bindValue(':idVisio', $idVisio);
        $execution = $monObjPdoStatement->execute();
        return $execution;
    }

    /**
     * Procédure qui modifie la valeur de validationAvis à false c'est-à-dire refuser l'avis
     */
    function refuserAvis($idMedecin,$idVisio){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("DELETE FROM avisconference WHERE idMedecin=:idMedecin AND idVisio=:idVisio");
        $monObjPdoStatement->bindValue(':idMedecin', $idMedecin);
        $monObjPdoStatement->bindValue(':idVisio', $idVisio);
        $execution = $monObjPdoStatement->execute();
        return $execution;
    }

    /**
     * Function qui recupere les medecin non valider
     * Retourne la requete sql qui sera utiliser ensuite comme tableau pour afficher les médecins non valider
     * @return $lesMedecins
     */
    function recupererMedecinNonValider(){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT * FROM medecin WHERE RppsValide = 0");
        if ($monObjPdoStatement->execute()) {
            $lesMedecins = $monObjPdoStatement;
        } else {
            throw new Exception("erreur");
        }
        return $lesMedecins;
    }

    function recupererDureeMaintenance(){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT duréeMaintenance, dateFinMaintenance FROM historiquemaintenance ORDER BY id DESC LIMIT 1;");
        if ($monObjPdoStatement->execute()) {
            $duree = $monObjPdoStatement->fetch();
        } else {
            throw new Exception("erreur");
        }
        return $duree;
    }

    function MedecinsConnecterMoins4Semaines(){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT DISTINCT(idMedecin), mail FROM historiqueconnexion INNER JOIN medecin ON historiqueconnexion.idMedecin = medecin.id WHERE DATEDIFF(CURDATE(), dateFinLog) <= 28;");
        if ($monObjPdoStatement->execute()) {
            $medecins = $monObjPdoStatement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new Exception("erreur");
        }
        return $medecins;
    }

    function recupererDureeTotaleInterruptionParMois(){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT MONTH(dateDebutMaintenance) AS mois, YEAR(dateDebutMaintenance) AS année, SUM(duréeMaintenance) AS duree_maintenance 
        FROM historiquemaintenance 
        WHERE dateDebutMaintenance >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) 
        GROUP BY YEAR(dateDebutMaintenance), MONTH(dateDebutMaintenance);");
        if ($monObjPdoStatement->execute()) {
            $duree = $monObjPdoStatement;
        } else {
            throw new Exception("erreur");
        }
        return $duree;
    }

    function recupererDureeTotaleInterruptionParAnnee(){
        $pdo = PdoGsb::$monPdo;
        $monObjPdoStatement = $pdo->prepare("SELECT YEAR(dateDebutMaintenance) AS année, SUM(duréeMaintenance) AS duree_maintenance 
        FROM historiquemaintenance 
        WHERE dateDebutMaintenance >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) 
        GROUP BY YEAR(dateDebutMaintenance);");
        if ($monObjPdoStatement->execute()) {
            $duree = $monObjPdoStatement;
        } else {
            throw new Exception("erreur");
        }
        return $duree;
    }
    
}

?>
