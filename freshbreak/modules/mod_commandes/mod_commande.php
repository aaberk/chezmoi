<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('cont_commande.php');

class ModCommande {
    private $controleur;

    public function __construct() {
        $this->controleur = new ContCommande();
    }

    public function exec() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'selection_client';

        switch ($action) {
            case 'selection_client':
                $this->controleur->selectionClient();
                break;

            case 'valider_client':
                $this->controleur->validerClient();
                break;

            case 'produits':
                $this->controleur->afficherProduits();
                break;

            case 'ajouter_panier':
                $this->controleur->ajouterAuPanier();
                break;

            case 'retirer_panier':
                $this->controleur->retirerDuPanier();
                break;

            case 'recapitulatif':
                $this->controleur->recapitulatif();
                break;

            case 'valider_commande':
                $this->controleur->validerCommande();
                break;

            case 'annuler':
                $this->controleur->annulerCommande();
                break;

            default:
                echo "<p>Action inconnue.</p>";
                $this->controleur->selectionClient();
                break;
        }
    }

    public function print_content() {
        return $this->controleur->print_content();
    }
}
?>