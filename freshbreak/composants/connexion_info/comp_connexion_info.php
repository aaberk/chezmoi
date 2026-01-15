<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('cont_connexion_info.php');

class Comp_connexion_info {
    private $controller;

    public function __construct() {
        $this->controller = new Cont_connexion_info();
    }

    public function affiche() {
        return $this->controller->getAffichage();
    }
}
?>