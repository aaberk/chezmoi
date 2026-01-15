<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('vue_composant.php');

class Cont_composant {
    private $vue;

    public function __construct() {
        $this->vue = new Vue_composant();
    }

    public function getAffichage() {
        return $this->vue->getAffichage();
    }
}
?>