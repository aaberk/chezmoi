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
            $quantite = (int)($_POST['quantite'] ?? 0);

            $fournisseurId = $_POST['fournisseur_id'] ?? null;
            $fNom   = trim($_POST['fournisseur_nom'] ?? '');
            $fEmail = trim($_POST['fournisseur_email'] ?? '');
            $fTel   = trim($_POST['fournisseur_tel'] ?? '');

            if ($nom === '') {
                $this->vue->message("Nom du produit obligatoire.");
                return;
            }

            if ($prixAchat <= 0 || $prixVente <= 0) {
                $this->vue->message("Les prix doivent être positifs.");
                return;
            }

            if ($quantite < 0) {
                $this->vue->message("Quantité invalide.");
                return;
            }

            if ($this->modele->produitExiste($nom)) {
                $this->vue->message("Ce produit existe déjà.");
                return;
            }

            if (!empty($fournisseurId)) {
                $idFournisseurFinal = $fournisseurId;
            } else {
                if ($fNom && $fEmail && $fTel) {
                    $fournisseur = $this->modele->getFournisseurByInfos($fNom, $fEmail, $fTel);

                    if ($fournisseur) {
                        $idFournisseurFinal = $fournisseur['id_fournisseur'];
                    } else {
                        $idFournisseurFinal = $this->modele->ajouterFournisseur($fNom, $fEmail, $fTel);
                    }
                } else {
                    $this->vue->message("Veuillez sélectionner ou renseigner un fournisseur.");
                    return;
                }
            }

            $idProduit = $this->modele->ajouterProduit(
                $nom,
                $prixAchat,
                $prixVente,
                $idFournisseurFinal
            );

            $idBar = $_SESSION['bar_id'] ?? null;

            if ($idBar === null) {
                $this->vue->message("Aucune buvette sélectionnée.");
                return;
            }

            $this->modele->ajouterStock(
                $idBar,
                $idProduit,
                $quantite
            );

            if (isset($_SESSION['tmp_save_inventory'])) {
                $_SESSION['tmp_save_inventory'][$nom] = 0;
                header("Location: index.php?module=inventaire_manuel&action=display_all_products");
            }

            $this->vue->message("Produit ajouté.");
        }

        $fournisseurs = $this->modele->getFournisseurs();
        $produits = $this->modele->getProduits();

        $this->vue->form_produit($fournisseurs);
        $this->vue->liste_produits($produits);
    }

    public function print_content() {
        return $this->vue->close_buffer();
    }
}
