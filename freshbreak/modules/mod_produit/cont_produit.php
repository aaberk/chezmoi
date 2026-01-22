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

        $erreur = false;

        if (isset($_POST['nom_produit'])) {

            $nom = trim($_POST['nom_produit']);
            $prixAchat = (float) $_POST['prix_achat'];
            $prixVente = (float) $_POST['prix_vente'];
            $quantite  = (int) ($_POST['quantite'] ?? 0);

            $fournisseurId = $_POST['fournisseur_id'] ?? null;
            $fNom   = trim($_POST['fournisseur_nom'] ?? '');
            $fEmail = trim($_POST['fournisseur_email'] ?? '');
            $fTel   = trim($_POST['fournisseur_tel'] ?? '');

            if ($nom === '') {
                $this->vue->message("Nom du produit obligatoire.");
                $erreur = true;
            }

            if ($prixAchat <= 0 || $prixVente <= 0) {
                $this->vue->message("Les prix doivent être strictement positifs.");
                $erreur = true;
            }

            if ($quantite <= 0) {
                $this->vue->message("La quantité doit être strictement supérieure à 0.");
                $erreur = true;
            }

            if (!$erreur && $this->modele->produitExiste($nom)) {
                $this->vue->message("Ce produit existe déjà.");
                $erreur = true;
            }

            if (!$erreur) {
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
                        $erreur = true;
                    }
                }
            }

            if (!$erreur) {

                $idProduit = $this->modele->ajouterProduit(
                    $nom,
                    $prixAchat,
                    $prixVente,
                    $idFournisseurFinal
                );

                $idBar = $_SESSION['bar_id'] ?? null;

                if ($idBar === null) {
                    $this->vue->message("Aucune buvette sélectionnée.");
                    $erreur = true;
                } else {
                    $this->modele->ajouterStock(
                        $idBar,
                        $idProduit,
                        $quantite
                    );

                    $this->modele->enregistrerAchatProduit(
                        $idProduit,
                        $idBar,
                        $quantite
                    );

                    if (isset($_SESSION['tmp_save_inventory'])) {
                        $_SESSION['tmp_save_inventory'][$nom] = 0;
                        header("Location: index.php?module=inventaire_manuel&action=display_all_products");
                    }

                    $this->vue->message("Produit ajouté avec succès.");
                }
            }
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
