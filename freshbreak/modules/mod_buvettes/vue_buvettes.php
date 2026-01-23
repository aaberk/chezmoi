<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once("./vue_generique.php");

class VueBuvettes extends Vue_generique {
    public function __construct() {
        parent::__construct();
    }

    public function choice(){
        echo '<div class="action-buttons">';
        echo '<a href="index.php?module=buvettes&action=liste" class="btn btn-primary">Choisir Bar</a>';
        echo '<a href="index.php?module=creationBuvettes&action=show_form" class="btn btn-success">Créer buvette</a>';
        echo '</div>';
    }

    public function afficher_buvette(array $tab) {

        echo '<div style="margin-bottom: 30px;">';
        echo '<a href="index.php?module=creationBuvettes&action=show_form" class="btn btn-success">Créer une nouvelle buvette</a>';
        echo '</div>';

        if (empty($tab)) {
            return;
        }


        echo '<h2>Choisir une buvette</h2>';

        echo '<div class="buvettes-grid">';
        foreach ($tab as $item) {
            echo '<a href="index.php?module=buvettes&action=liste&bar_id=' . htmlspecialchars($item['id_bar']) . '" class="buvette-card">';
            echo '<h3>' . htmlspecialchars($item['nom']) . '</h3>';
            echo '</a>';
        }
        echo '</div>';
    }

    public function afficher_buvetteNon(array $tab) {
        if (empty($tab)) {

            return;
        }
        echo '<h2> Autre Bar </h2>';
//        echo '<div class="liste-buvettes">';
//
//        foreach ($tab as $item) {
//
//            echo '<div class="buvette-card">';
//
//            echo '<h3>' . htmlspecialchars($item['nom']) . '</h3>';
//
//            echo '<a href="index.php?module=rejoindreBuvette&action=show_form&bar_id='
//                . (int)$item['id_bar']
//                . '">
//                Rejoindre la buvette
//              </a>';
//
//            echo '</div>';
//        }
//
//        echo '</div>';
        echo '<div class="buvettes-grid">';
        foreach ($tab as $item) {
            echo '<a href="index.php?module=buvettes&action=rejoindre_buvette&bar_id=' . htmlspecialchars($item['id_bar']) . '" class="buvette-card">';
            echo '<h3>' . htmlspecialchars($item['nom']) . '</h3>';
            echo '</a>';
        }
        echo '</div>';
    }

    private function headerBuvette($nomBar) {
        echo '<h3>Buvette : ' . htmlspecialchars($nomBar) . '</h3>';
    }

    private function changerBuvette(array $buvettes_inscrit, $buvettes_non_inscrit) {
        echo '<hr>';
        echo '<h4>Changer de buvette</h4>';

        if (count($buvettes_inscrit) > 0) {
            echo '<h5>Buvettes où vous êtes inscrit</h5>';
            echo '<div class="buvettes-grid">';
            foreach ($buvettes_inscrit as $b) {
                echo '<a href="index.php?module=buvettes&action=liste&bar_id=' . htmlspecialchars($b['id_bar']) . '" class="buvette-card">';
                echo '<h3>' . htmlspecialchars($b['nom']) . '</h3>';
                echo '</a>';
            }
            echo '</div>';
        }
        if (count($buvettes_non_inscrit) > 0) {
            echo '<h5>Autre buvettes</h5>';
            echo '<div class="buvettes-grid">';
            foreach ($buvettes_non_inscrit as $b) {
                echo '<a href="index.php?module=buvettes&action=rejoindre_buvette&bar_id=' . htmlspecialchars($b['id_bar']) . '" class="buvette-card">';
                echo '<h3>' . htmlspecialchars($b['nom']) . '</h3>';
                echo '</a>';
            }
            echo '</div>';
        }
    }

    public function menu_client($nomBar, $buvettes_inscrit, $buvettes_non_inscrit) {
        $this->headerBuvette($nomBar);

        echo '<div class="menu-actions">';
        echo '<a href="index.php?module=buvettes&action=voir_produits" class="menu-btn">Voir les produits</a>';
        echo '</div>';

        $this->changerBuvette($buvettes_inscrit,$buvettes_non_inscrit);
    }

    public function menu_gérant($nomBar, $buvettes_inscrit, $buvettes_non_inscrit) {
        $this->headerBuvette($nomBar);

        echo '
        <div class="menu-actions">
            <a href="index.php?module=stock" class="menu-btn">Gérer le stock</a>
            <a href="index.php?module=bilan&action=display_form" class="menu-btn">Bilan</a>
            <a href="index.php?module=gestion_profils&action=ajoututilisateur" class="menu-btn">Ajouter un utilisateur</a>
            <a href="index.php?module=inventaire_manuel&action=display_all_products" class="menu-btn">Faire un inventaire manuel</a>
        </div>';

        $this->changerBuvette($buvettes_inscrit,$buvettes_non_inscrit);
    }

    public function menu_barman($nomBar, $buvettes_inscrit, $buvettes_non_inscrit) {
        $this->headerBuvette($nomBar);

        echo '<div class="menu-actions">';
        echo '<a href="index.php?module=commande&action=client" class="menu-btn">Nouvelle commande</a>';
        echo '<a href="index.php?module=stock&action=getStocks" class="menu-btn">Stock</a>';
        echo '</div>';

        $this->changerBuvette($buvettes_inscrit,$buvettes_non_inscrit);
    }

    public function menu_admin(){
        echo '<div class="menu-actions">';
        echo '<a href="index.php?module=commande&action=client" class="menu-btn">Nouvelle commande</a>';
        echo '<a href="index.php?module=stock&action=getStocks" class="menu-btn">Stock</a>';
        echo '<a href="index.php?module=buvettes&action=bilan" class="menu-btn">Bilan</a>';
        echo '<a href="index.php?module=gestion_profils&action=ajoututilisateur" class="menu-btn">Ajout utilisateur</a>';
        echo '</div>';
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