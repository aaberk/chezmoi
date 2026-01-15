<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('cont_composant.php');

class Comp_composant {
    private $controller;

    public function __construct() {
        $this->controller = new Cont_composant();
    }

    public function affiche() {
        return $this->controller->getAffichage();
    }
}
?>