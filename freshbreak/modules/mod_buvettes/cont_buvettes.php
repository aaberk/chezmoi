<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('vue_buvettes.php');
require_once('modele_buvette.php');

class ContBuvettes {
    private $vue;
    private $modele;
    public function __construct() {
        $this->vue = new VueBuvettes();
        $this->modele = new ModeleBuvette();


    }


        public function menu($login)
        {
            $role =$this->modele->getRole($login);
            $method = 'menu_' . $role;
            $this->vue->$method();
        }

    public function liste($login){
        $this-> vue -> afficher_buvette($this->modele-> getListe($login));
    }
    public function afficherStock(){}

    public function afficherBilan(){}

    public function payer(){
    }

    public function choix(){
        $this->vue->choice();
    }

    public function print_content() {
        return $this->vue->close_buffer();
    }

    public function retour(){}


}