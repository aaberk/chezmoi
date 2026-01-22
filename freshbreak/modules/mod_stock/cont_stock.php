<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('vue_stock.php');
require_once('modele_stock.php');
require_once('./modules/mod_produit/modele_produit.php');

class ContStock {

    private $vue;
    private $modeleStock;
    private $modeleProduit;

    public function __construct() {
        $this->vue = new VueStock();
        $this->modeleStock = new ModeleStock();
        $this->modeleProduit = new Modele_produit();
    }

    public function gestion_stock($barId) {

        if ($barId === null) {
            $this->vue->message("Aucune buvette sélectionnée.");
            return;
        }

        // Si on est en mode réapprovisionnement
        if (isset($_GET['action']) && $_GET['action'] === 'reapprovisionnement') {
            $this->reapprovisionnement($barId);
            return;
        }

        // Sinon : affichage normal du stock
        $nomBar = $this->modeleStock->getNomBar($barId);
        $stocks = $this->modeleStock->getStockParBar($barId);
        $this->vue->afficher($stocks, $nomBar);
    }

    private function reapprovisionnement($barId) {

        $role = $this->modeleStock->getRoleParBar($_SESSION['login'], $_SESSION['bar_id']);

        if ($role !== 'gérant' && !$_SESSION['admin']) {
            echo "Accès interdit";
            return;
        }

        $produits = $this->modeleProduit->getProduitsComplets();
        $message = null;
        $prixTotal = null;

        if (isset($_POST['id_produit'], $_POST['quantite'])) {

            $idProduit = (int) $_POST['id_produit'];
            $quantite = (int) $_POST['quantite'];

            if ($idProduit > 0 && $quantite > 0) {

                $produit = $this->modeleProduit->getProduitById($idProduit);

                if ($produit) {
                    $this->modeleStock->ajouterOuIncrementerStock(
                        $barId,
                        $idProduit,
                        $quantite
                    );

                    $prixTotal = $produit['prix_achat'] * $quantite;
                    $message = "Réapprovisionnement réussi";
                } else {
                    $message = "Produit introuvable";
                }
            }
        }

        $this->vue->formReapprovisionnement(
            $produits,
            $message,
            $prixTotal
        );
    }

    public function getContent() {
        return $this->vue->close_buffer();
    }
}
