<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('./vue_generique.php');

class Vue_bilan extends Vue_Generique {
    public function __construct() {
        parent::__construct();
    }

    public function show_date_selection($date_min,$date_max) {
        echo '
        <h3>Bilan statistique du bar</h3>
            <p>Veuillez sélectionner une date de départ et de fin de l\'analyse statistique :</p>';
        $this->show_form($date_min,$date_max);
        echo '<a href="index.php?module=buvettes&action=liste"><button type="button">← Retour au menu</button></a>';
    }

    public function show_form($date_min,$date_max) {
        echo '
        <form action="index.php?module=bilan&action=summary_calculation" method="post">
                <input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">
                <label>Date début</label>
                <input type="date" min="' . htmlspecialchars($date_min) . '" max="' . htmlspecialchars($date_max) . '" name="date_debut">
                <label>Date fin</label>
                <input type="date" min="' . htmlspecialchars($date_min) . '" max="' . htmlspecialchars($date_max) . '" name="date_fin">
                <button type="submit">Valider</button>
        </form>';
    }

    public function date_failure($date_min,$date_max) {
        echo '
        <p>La date de début est supérieur à la date de fin. La date de fin doit être supérieur à la date de début :</p>';
        $this->show_form($date_min,$date_max);
    }

    public function display_summary($summary,$date_debut,$date_fin) {
        echo '
        <h3>Bilan statistique du bar</h3>
            <p>Voici le bilan de la période démarrant du ' . htmlspecialchars($this->format_date($date_debut)) . ' au ' . htmlspecialchars($this->format_date($date_fin)) . ' :</p>
            <p>Chiffre d\'affaire : ' . htmlspecialchars($summary['chiffre_affaire']) . '€</p>
            <p>Dépenses : ' . htmlspecialchars($summary['depense']) . '€</p>
            <p>Bénéfices : ' . htmlspecialchars($summary['chiffre_affaire'] - $summary['depense']) . '€</p>
        <div>';
        $this->afficher_stock($summary['consommation']);
        echo '
        </div>
        <div>
            <h4>Quantité de produits réapprovisionnés</h4>';
        foreach ($summary['reapprovisionnement'] as $produit) {
            echo '<p>' . htmlspecialchars($produit['nom']) . ' : ' . (int)$produit['quantite'] . '</p>';
        }
        echo '
        </div>';
    }

    public function format_date($date) {
        $decomposed_date = explode("-",$date);
        return $decomposed_date[2]."/".$decomposed_date[1]."/".$decomposed_date[0];
    }

    public function afficher_stock(array $stock)
    {
        if (empty($stock)) {
            echo '<p>Aucune consommation.</p>';
            return;
        }

        echo '<h4>Consommation</h4>';

        foreach ($stock as $produit) {
            echo "<p>"
                . htmlspecialchars($produit['nom'])
                . " → "
                . (int)$produit['vendu'][0][0]
                . " consommé</p>";
        }
    }
}
?>
