<?php
if (!defined('APP_SECURE')) die('Accès interdit.');

class Vue_composant {
    private $affichage;

    public function __construct() {
        $this->affichage .= '';
    }

    public function getAffichage() {
        return $this->affichage;
    }
}
?>