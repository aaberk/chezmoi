<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('connexion.php');

class ModeleGestionProfils extends connexion {

    public function utilisateurExiste($login) {
        $req = self::$bdd->prepare("SELECT COUNT(*) FROM utilisateur WHERE login = :login");
        $req->bindParam(':login', $login);
        $req->execute();
        return $req->fetchColumn() > 0;
    }

    public function estDejaMembreBuvette($login, $bar_id) {
        $req = self::$bdd->prepare("SELECT COUNT(*) FROM role WHERE login_utilisateur = :login AND bar_associe = :bar_id");
        $req->bindParam(':login', $login);
        $req->bindParam(':bar_id', $bar_id);
        $req->execute();
        return $req->fetchColumn() > 0;
    }

    public function ajouterMembreBuvette($login, $bar_id, $role) {
        $req = self::$bdd->prepare("INSERT INTO role (login_utilisateur, bar_associe, role_bar) VALUES (:login, :bar_id, :role)");
        $req->bindParam(':login', $login);
        $req->bindParam(':bar_id', $bar_id);
        $req->bindParam(':role', $role);
        $req->execute();
    }
}
?>