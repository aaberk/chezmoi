<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('./vue_generique.php');

class VueStock extends Vue_generique {

    public function __construct() {
        parent::__construct();
    }

    public function afficher($stocks) {
        echo "<h2>Stocks</h2>";

        if (empty($stocks)) {
            echo "<p>Aucun stock disponible.</p>";
        } else {
            echo "<ul>";
            foreach ($stocks as $s) {
                echo "<li>"
                        . htmlspecialchars($s['produit'])
                        . " : "
                        . (int)$s['quantite']
                        . "</li>";
            }
            echo "</ul>";
        }
    }
}
