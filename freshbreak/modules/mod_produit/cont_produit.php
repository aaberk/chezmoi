<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('vue_produit.php');
require_once('modele_produit.php');

class Cont_produit {
    private $vue;
    private $modele;

    public function __construct() {
        $this->vue = new Vue_produit();
        $this->modele = new Modele_produit();
    }

    public function gestion_produit() {

        if (isset($_POST['nom_produit'])) {

            $nom = trim($_POST['nom_produit']);
            $prixAchat = $_POST['prix_achat'];
            $prixVente = $_POST['prix_vente'];
            $fournisseur = $_POST['fournisseur'];

            if ($nom === '') {
                $this->vue->message("Nom du produit obligatoire.");
                return;
            }

            if ($prixAchat <= 0 || $prixVente <= 0) {
                $this->vue->message("Les prix doivent être positifs.");
                return;
            }

            if ($this->modele->produitExiste($nom)) {
                $this->vue->message("Ce produit existe déjà.");
                return;
            }

            $this->modele->ajouterProduit(
                $nom,
                $prixAchat,
                $prixVente,
                $fournisseur
            );

            $this->vue->message("Produit ajouté.");
        }

        $produits = $this->modele->getProduits();
        $this->vue->form_produit();
        $this->vue->liste_produits($produits);
    }

    public function print_content() {
        return $this->vue->close_buffer();
    }
}
