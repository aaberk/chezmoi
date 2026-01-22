<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once("./vue_generique.php");

class VueBuvettes extends Vue_generique {
    public function __construct() {
        parent::__construct();
    }

    public function choice(){
       echo '<a href="index.php?module=buvettes&action=liste">Choisir Bar</a>
            |
            <a href="index.php?module=creationBuvettes&action=show_form">Créer buvette</a>';
    }
    public function afficher_buvette(array $tab) {

        if (empty($tab)) {
            echo '<p>Aucune buvette disponible.</p>';
            return;
        }

        echo '<a href="index.php?module=creationBuvettes&action=show_form">Créer buvette</a>';

        echo '<form method="get" action="index.php">';
        echo '<input type="hidden" name="csrf_token"
                   value="' . htmlspecialchars($_SESSION['csrf_token']) . '">';
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

    private function headerBuvette($nomBar) {
        echo '<h3>Buvette : ' . htmlspecialchars($nomBar) . '</h3>';
    }

    private function changerBuvette(array $buvettes) {
        echo '<hr>';
        echo '<h4>Changer de buvette</h4>';

        echo '<form method="get" action="index.php">
        <input type="hidden" name="module" value="buvettes">
        <input type="hidden" name="action" value="liste">

        <select name="bar_id" required>
            <option value="">-- Sélectionner --</option>';

        foreach ($buvettes as $b) {
            echo '<option value="'.$b['id_bar'].'">'
                . htmlspecialchars($b['nom']) .
                '</option>';
        }

        echo '</select>
        <button type="submit">Valider</button>
    </form>';
    }

    public function menu_client($nomBar, $buvettes) {
        echo '<h3>Buvette : ' . htmlspecialchars($nomBar) . '</h3>';

        echo '<ul>
            <li><a href="index.php?module=buvettes&action=voir_produits">Voir les produits</a></li>
            <li><a href="index.php?module=buvettes&action=payer">payer</a></li>
        </ul>';
    }

    public function menu_gérant($nomBar, $buvettes) {
        $this->headerBuvette($nomBar);

        echo '<ul>
            <li><a href="index.php?module=stock">Gérer le stock</a></li>
            <li><a href="index.php?module=buvettes&action=bilan">Bilan</a></li>
            <li><a href="index.php?module=gestion_profils&action=ajoututilisateur">Ajouter un utilisateur</a></li>
            <li><a href="index.php?module=inventaire_manuel&action=display_all_products">Faire un inventaire manuel</a></li>
        </ul>';

        $this->changerBuvette($buvettes);
    }
    public function menu_barman($nomBar, $buvettes) {
        $this->headerBuvette($nomBar);

        echo '<ul>
            <li><a href="index.php?module=commande&action=client">Nouvelle commande</a></li>
            <li><a href="index.php?module=stock&action=getStocks">stock</a></li>
            
        </ul>';
    }

    public function menu_admin(){
        echo '<ul>
            <li><a href="index.php?module=commande&action=client">Nouvelle commande</a></li>
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

    public function afficher_produits_disponibles($produits) {
        echo '<h2>Produits disponibles</h2>';

        if (empty($produits)) {
            echo '<p>Aucun produit disponible actuellement.</p>';
            return;
        }


        echo '<ul>';
        foreach ($produits as $p) {
            echo '<li>'
                . htmlspecialchars($p['nom_produit'])
                . ' - '
                . htmlspecialchars($p['prix_vente'])
                . ' €</li>';
        }
        echo '</ul>';
    }

    public function message($texte) {
        echo "<p>$texte</p>";
    }
}