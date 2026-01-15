<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('connexion.php');

class ModeleStock extends connexion {

    public static function getStocks() {
        $req = self::$bdd->prepare("SELECT * FROM stock");
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}
