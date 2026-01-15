<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('cont_creationBuvettes.php');



class ModCreationBuvettes {
    private $controleur;

    public function __construct() {
        $this->controleur = new ContCreationBuvettes();
    }

    public function exec()
    {

        $this->controleur->form_ajout_bar();
    }

    public function print_content() {
        return $this->controleur->print_content();
    }
}