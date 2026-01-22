<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('connexion.php');

class Modele_inbox extends connexion {
    public function getMessages($login) {
        $req = self::$bdd->prepare("SELECT message_type,msg_arguments FROM inbox WHERE user_login = :login");
        $req->bindParam(':login', $login);
        $req->execute();
        return $req->fetchAll();
    }

    public function delete_user_msgs($login) {
        $req = self::$bdd->prepare("DELETE FROM inbox WHERE user_login = :user_login AND (message_type = 2);");
        $req->execute(['user_login' => $login]);
    }
}
?>