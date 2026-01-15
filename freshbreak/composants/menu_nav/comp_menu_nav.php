<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('cont_menu_nav.php');

class Comp_menu_nav {
    private $controller;

    public function __construct() {
        $this->controller = new Cont_menu_nav();
    }

    public function affiche() {
        return $this->controller->getAffichage();
    }
}
?>