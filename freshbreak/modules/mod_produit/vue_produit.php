<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once("vue_generique.php");

class Vue_produit extends Vue_generique {

    public function __construct() {
        parent::__construct();
    }

    public function form_produit($fournisseurs) {
        echo '
    <h2>Ajout produit</h2>
    <form method="post" action="index.php?module=produit">
        <input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">
        <label>Nom produit :</label>
        <input type="text" name="nom_produit" required><br>

        <label>Prix achat :</label>
        <input type="number" step="0.01" name="prix_achat" required><br>

        <label>Prix vente :</label>
        <input type="number" step="0.01" name="prix_vente" required><br>';
        
        if (!isset($_SESSION['tmp_save_inventory'])) {
            echo '<label>Quantité initiale en stock :</label>
        <input type="number" name="quantite" min="0" value="0" required><br><br>';
        }

        echo '<label>Fournisseur existant :</label>
        <select name="fournisseur_id">
            <option value="">-- Aucun --</option>';

        foreach ($fournisseurs as $f) {
            echo '<option value="'.$f['id_fournisseur'].'">'
                .htmlspecialchars($f['nom'])
                .'</option>';
        }

        echo '
        </select><br><br>

        <label>Nouveau fournisseur :</label><br>
        <input type="text" name="fournisseur_nom" placeholder="Nom"><br>
        <input type="email" name="fournisseur_email" placeholder="Email"><br>
        <input type="text" name="fournisseur_tel" placeholder="Téléphone"><br><br>

        <button type="submit">Ajouter</button>
    </form>';
    }

    public function liste_produits($produits) {
        echo '<h2>Produits disponibles</h2><ul>';
        foreach ($produits as $p) {
            echo "<li>{$p['nom_produit']} – {$p['prix_vente']} €</li>";
        }
        echo '</ul>';
    }

    public function message($msg) {
        echo "<p>$msg</p>";
    }
}