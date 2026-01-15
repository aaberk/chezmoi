<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once("./vue_generique.php");

class VueBuvettes extends Vue_generique {
    public function __construct() {
        parent::__construct();
    }

    public function choice(){
       echo '<a href="index.php?module=buvettes&action=liste">Buvettes</a>
|
<a href="index.php?module=creationBuvettes&action=ajout">Créer buvette</a>';
    }
    public function afficher_buvette(array $tab) {

        if (empty($tab)) {
            echo '<p>Aucune buvette disponible.</p>';
            return;
        }

        echo '<form method="get" action="index.php">';
        echo '<input type="hidden" name="module" value="buvettes">';
        echo '<input type="hidden" name="action" value="liste">';


        echo '<label for="bar_id">Choisir une buvette :</label><br>';

        echo '<select name="bar_id" id="bar_id" required>';
        echo '<option value="">-- Sélectionner --</option>';
        foreach ($tab as $item) {
            echo '<option value="' . htmlspecialchars($item['id_bar']) . '">';
            echo htmlspecialchars($item['nom']);
            echo '</option>';
        }

        echo '</select> ';

        echo '<button type="submit">Valider</button>';
        echo '</form>';
    }

    public function menu() {
        echo '<ul>
            <li><a href="index.php?module=stock&action=getStocks">stock</a></li>
            <li><a href="index.php?module=buvettes&action=bilan">bilan</a></li>
            <li><a href="index.php?module=buvettes&action=payer">payer</a></li>
            <li><a href="index.php?module=gestion_profils&action=ajoututilisateur">ajout utilisateur</a></li>
        </ul>';
    }


    public function afficher($buvettes) {
        echo "<h2>Liste des buvettes</h2>";

        if (empty($buvettes)) {
            echo "<p>Aucune buvette enregistrée.</p>";
            return;
        }

        echo "<ul>";
        foreach ($buvettes as $b) {
            echo "<li>" . htmlspecialchars($b['nom']) . "</li>";
        }
        echo "</ul>";
    }
}
