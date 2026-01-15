<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('connexion.php');

class ModeleSoldeRefill extends connexion {
    public function addMoney($login,$value) {
        $req = self::$bdd->prepare("UPDATE utilisateur SET solde=solde+:value WHERE login=:login;");
        $req->bindParam(':login', $login);
        $req->bindParam(':value', $value);
        $req->execute();
    }
}
?>