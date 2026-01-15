<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('cont_connexion.php');

class ModConnexion {
    private $controleur;

    public function __construct() {
        $this->controleur = new ContConnexion();
    }

    public function exec() {

        $action = isset($_GET['action']) ? $_GET['action'] : 'connexion';

        switch ($action) {
            case 'inscription':
                $this->controleur->form_inscription();
                break;

            case 'connexion':
                $this->controleur->form_connexion();
                break;

            case 'deconnexion':
                $this->controleur->deconnexion();
                break;

            default:
                echo "<p>Action inconnue.</p>";
                $this->controleur->form_connexion();
                break;
        }
    }

    public function print_content() {
        return $this->controleur->print_content();
    }
}
?>

