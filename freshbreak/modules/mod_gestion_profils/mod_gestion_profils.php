<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('cont_gestion_profils.php');

class ModGestionProfils {
    private $controleur;

    public function __construct() {
        $this->controleur = new ContGestionProfils();
    }

    public function exec() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'form';

        switch ($action) {
            case 'form':
                $this->controleur->formAjoutMembre();
                break;

            case 'ajouter':
                $this->controleur->ajouterMembre();
                break;

            default:
                $this->controleur->formAjoutMembre();
                break;
        }
    }

    public function print_content() {
        return $this->controleur->print_content();
    }
}
?>