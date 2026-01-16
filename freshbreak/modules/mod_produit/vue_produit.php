<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once("vue_generique.php");

class Vue_produit extends Vue_generique {

    public function __construct() {
        parent::__construct();
    }

    public function form_produit() {
        echo '
    <h2>Ajout produit</h2>
    <form method="post" action="index.php?module=produit">

        <label>Nom produit :</label>
        <input type="text" name="nom_produit" required><br>

        <label>Prix achat :</label>
        <input type="number" step="0.01" name="prix_achat" required><br>

        <label>Prix vente :</label>
        <input type="number" step="0.01" name="prix_vente" required><br>

        <label>Fournisseur (id) :</label>
        <input type="number" name="fournisseur" required><br>

        <button type="submit">Ajouter</button>
    </form>
    ';
    }

    public function liste_produits($produits) {
        echo '<h2>Produits disponibles</h2><ul>';
        foreach ($produits as $p) {
            echo "<li>{$p['nom']} – {$p['prix']} €</li>";
        }
        echo '</ul>';
    }

    public function message($msg) {
        echo "<p>$msg</p>";
    }
}
