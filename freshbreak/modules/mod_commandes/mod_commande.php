<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('cont_commande.php');

class ModCommande {
    private $controleur;

    public function __construct() {
        $this->controleur = new ContCommande();
    }

    public function exec() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'client';

        switch ($action) {
            case 'client':
                $this->controleur->formSelectionClient();
                break;

            case 'valider_client':
                $this->controleur->validerClient();
                break;

            case 'panier':
                $this->controleur->afficherPanier();
                break;

            case 'ajouter':
                $this->controleur->ajouterProduit();
                break;

            case 'retirer':
                $this->controleur->retirerProduit();
                break;

            case 'valider':
                $this->controleur->validerCommande();
                break;

            case 'annuler':
                $this->controleur->annuler();
                break;
        }
    }

    public function print_content() {
        return $this->controleur->print_content();
    }
}
?>