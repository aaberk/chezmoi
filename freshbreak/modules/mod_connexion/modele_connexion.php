<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('connexion.php');

class ModeleConnexion extends connexion {

    public function loginExiste($login) {
        $req = self::$bdd->prepare("SELECT COUNT(*) FROM utilisateur WHERE login = :login");
        $req->bindParam(':login', $login);
        $req->execute();
        return $req->fetchColumn() > 0;
    }


public function ajouterUtilisateur($login, $password) {
   
    $mdp_hash = password_hash($password, PASSWORD_DEFAULT);
    
    $req = self::$bdd->prepare("INSERT INTO utilisateur (login, password, solde) VALUES (:login, :password, 0)");
    $req->bindParam(':login', $login);
    $req->bindParam(':password', $mdp_hash);
    $req->execute();
}
    public function verifierConnexion($login, $password) {
        $req = self::$bdd->prepare("SELECT password FROM utilisateur WHERE login = :login");
        $req->bindParam(':login', $login);
        $req->execute();
        $resultat = $req->fetch(PDO::FETCH_ASSOC);

        if ($resultat && password_verify($password, $resultat['password'])) {
            return true;
            $_SESSION['login'] = $login;
        }
        return false;
    }

    public function getSolde($login) {
        $req = self::$bdd->prepare("SELECT solde FROM utilisateur WHERE login = :login");
        $req->bindParam(':login', $login);
        $req->execute();
        return $req->fetch()[0];
    }

    public function getAdmin() {
        $req = self::$bdd->prepare("SELECT login FROM admin;");
        $req->execute();
        return $req->fetch()[0];
    }
}
?>

