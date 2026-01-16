<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('cont_produit.php');

class Modproduit {
    private $controleur;

    public function __construct() {
        $this->controleur = new Cont_produit();
    }

    public function exec() {
        $this->controleur->gestion_produit();
    }

    public function print_content() {
        return $this->controleur->print_content();
    }
}
