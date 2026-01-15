<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('vue_connexion_info.php');

class Cont_connexion_info {
    private $vue;

    public function __construct() {
        $this->vue = new Vue_connexion_info();
    }

    public function getAffichage() {
        return $this->vue->getAffichage();
    }
}
?>