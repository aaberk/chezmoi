<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('connexion.php');


class ModeleBuvette extends connexion
{

    public function getListe() {
        $requetes = 'select * from bar;';
        $requetesPrepare = self::$bdd -> prepare($requetes);
        $requetesPrepare ->execute();
        $tables = $requetesPrepare -> fetchAll();

        return $tables;
    }

}
