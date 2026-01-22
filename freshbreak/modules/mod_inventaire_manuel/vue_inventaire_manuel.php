<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('./vue_generique.php');
class Vue_inventaire_manuel extends Vue_Generique {
    public function __construct() {
        parent::__construct();
    }

    public function no_bar_selected_error() {
        echo '<p>Aucun bar n\'est sélectionné. Veuillez en <a href="index.php?module=buvettes&action=liste">sélectionner un</a> afin d\'effectuer l\'inventaire manuel</p>';
    }

    public function show_products_inventory_form($products) {
        echo '<form method="post" action="index.php?module=inventaire_manuel&action=submit_inventory">
                  <input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">';
        foreach ($products as $product) {
            $nom_produit = $product['nom_produit'];
            echo '<div>
                      <p>'.htmlspecialchars($nom_produit).' quantité : </p><input type="number" name="'.htmlspecialchars($nom_produit).'" value="'.htmlspecialchars($product['quantite']).'" required>
                  </div>';
        }
        echo '    <button type="submit"> Valider l\'inventaire</button>
                  <p>Il manque un produit ? <button type="submit" formaction="index.php?module=inventaire_manuel&action=save_tmp_inventory">Ajoutez-le</button></p>
              </form>';
    }

    public function title_stock_analyse() {
        echo '<h4>Bilan de l\'analyse des stocks et de l\'inventaire</h4>';
    }

    public function no_difference_stock_notice() {
        echo '<p>Il n\'y a aucun écart entre l\'inventaire et les stocks du bar.</p>';
    }

    public function display_stock_differences($stock_differences) {
        echo '<p>Écarts de stocks :</p>';
        foreach ($stock_differences as $info) {
            $quantite = $info['quantite'];
            if ($quantite < 0)
                $signe = 'de moins';
            else
                $signe = 'de plus';
            echo '<div>
                      <p>'.abs($quantite).' de '.$info['nom_produit'].' '.$signe.' dans les stocks par rapport à l\'inventaire.</p>
                  </div>';
        }
    }
}
?>