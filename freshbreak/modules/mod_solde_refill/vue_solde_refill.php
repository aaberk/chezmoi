<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once ('./vue_generique.php');

class VueSoldeRefill extends Vue_generique {
    public function __construct() {
        parent::__construct();
    }

    public function form($solde) {
        echo '
        <h2>Rechargement du compte</h2>
        <form method="post" action="index.php?module=solde_refill&action=refill">
            <label>Solde actuel : </label>' . "$solde" . '€<br><label>Argent injecté : </label>
            <input type="number" name="value" step="any" min="0.01"required><label>€</label><br>
            <button type="submit">Recharger</button>
        </form>';
    }

    public function error() {
        echo '<br>error';
    }
}
?>