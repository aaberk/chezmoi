<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('vue_menu_nav.php');

class Cont_menu_nav {
    private $vue;

    public function __construct() {
        $this->vue = new Vue_menu_nav();
    }

    public function getAffichage() {
        return $this->vue->getAffichage();
    }
}
?>