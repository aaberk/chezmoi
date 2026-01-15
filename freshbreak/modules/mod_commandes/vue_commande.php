<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('./vue_generique.php');

class VueCommande extends Vue_generique {
    public function __construct() {
        parent::__construct();
    }

    public function formSelectionClient($clients) {
        echo '<h2>Nouvelle commande - Sélection du client</h2>';

        if (empty($clients)) {
            echo '<p>Aucun client enregistré pour cette buvette.</p>';
            echo '<a href="index.php?module=buvettes">← Retour</a>';
            return;
        }

        echo '<form method="post" action="index.php?module=commande&action=valider_client">';
        echo '<label for="login_client">Client :</label><br>';
        echo '<select name="login_client" id="login_client" required>';
        echo '<option value="">-- Sélectionner un client --</option>';

        foreach ($clients as $client) {
            echo '<option value="' . htmlspecialchars($client['login']) . '">';
            echo htmlspecialchars($client['login']) . ' (Solde : ' . $client['solde'] . '€)';
            echo '</option>';
        }

        echo '</select><br><br>';
        echo '<button type="submit">Suivant →</button> ';
        echo '<a href="index.php?module=buvettes"><button type="button">Annuler</button></a>';
        echo '</form>';
    }

    public function afficherProduits($produits, $panier, $total, $client, $solde_client) {
        echo '<h2>Commande pour : ' . htmlspecialchars($client) . '</h2>';
        echo '<p><strong>Solde disponible :</strong> ' . $solde_client . '€</p>';

        echo '<h3>Panier actuel</h3>';
        if (empty($panier)) {
            echo '<p>Panier vide</p>';
        } else {
            echo '<table border="1" cellpadding="5">';
            echo '<tr><th>Produit</th><th>Prix unitaire</th><th>Quantité</th><th>Sous-total</th><th>Action</th></tr>';

            foreach ($panier as $item) {
                $sous_total = $item['prix'] * $item['quantite'];
                echo '<tr>';
                echo '<td>' . htmlspecialchars($item['nom']) . '</td>';
                echo '<td>' . $item['prix'] . '€</td>';
                echo '<td>' . $item['quantite'] . '</td>';
                echo '<td>' . $sous_total . '€</td>';
                echo '<td><a href="index.php?module=commande&action=retirer_panier&id_produit=' . $item['id_produit'] . '">❌ Retirer</a></td>';
                echo '</tr>';
            }

            echo '<tr><td colspan="3"><strong>TOTAL</strong></td><td colspan="2"><strong>' . $total . '€</strong></td></tr>';
            echo '</table>';

            echo '<br><a href="index.php?module=commande&action=recapitulatif"><button>✅ Valider la commande</button></a> ';
        }

        echo '<h3>Ajouter des produits</h3>';
        if (empty($produits)) {
            echo '<p>Aucun produit disponible en stock.</p>';
        } else {
            echo '<table border="1" cellpadding="5">';
            echo '<tr><th>Produit</th><th>Prix</th><th>Stock</th><th>Quantité</th><th>Action</th></tr>';

            foreach ($produits as $prod) {
                echo '<tr>';
                echo '<form method="post" action="index.php?module=commande&action=ajouter_panier">';
                echo '<input type="hidden" name="id_produit" value="' . $prod['id_produit'] . '">';
                echo '<td>' . htmlspecialchars($prod['nom_produit']) . '</td>';
                echo '<td>' . $prod['prix_vente'] . '€</td>';
                echo '<td>' . $prod['quantite'] . '</td>';
                echo '<td><input type="number" name="quantite" min="1" max="' . $prod['quantite'] . '" value="1" style="width:60px;"></td>';
                echo '<td><button type="submit">➕ Ajouter</button></td>';
                echo '</form>';
                echo '</tr>';
            }

            echo '</table>';
        }

        echo '<br><a href="index.php?module=commande&action=annuler"><button>❌ Annuler la commande</button></a>';
    }

    // Récapitulatif avant validation
    public function recapitulatif($client, $panier, $total, $solde) {
        echo '<h2> Récapitulatif de la commande</h2>';
        echo '<p><strong>Client :</strong> ' . htmlspecialchars($client) . '</p>';
        echo '<p><strong>Solde actuel :</strong> ' . $solde . '€</p>';

        echo '<table border="1" cellpadding="5">';
        echo '<tr><th>Produit</th><th>Prix unitaire</th><th>Quantité</th><th>Sous-total</th></tr>';

        foreach ($panier as $item) {
            $sous_total = $item['prix'] * $item['quantite'];
            echo '<tr>';
            echo '<td>' . htmlspecialchars($item['nom']) . '</td>';
            echo '<td>' . $item['prix'] . '€</td>';
            echo '<td>' . $item['quantite'] . '</td>';
            echo '<td>' . $sous_total . '€</td>';
            echo '</tr>';
        }

        echo '<tr><td colspan="3"><strong>TOTAL À PAYER</strong></td><td><strong>' . $total . '€</strong></td></tr>';
        echo '</table>';

        if ($solde < $total) {
            echo '<p style="color:red;">❌ <strong>Solde insuffisant !</strong></p>';
            echo '<a href="index.php?module=commande&action=produits"><button>← Retour</button></a>';
        } else {
            echo '<p style="color:green;">✅ Solde suffisant. Nouveau solde après achat : ' . round($solde - $total, 2) . '€</p>';
            echo '<br><a href="index.php?module=commande&action=valider_commande"><button>✅ Confirmer et valider</button></a> ';
            echo '<a href="index.php?module=commande&action=produits"><button>← Retour</button></a>';
        }
    }

    public function confirmation($message) {
        echo '<h2>' . $message . '</h2>';
        echo '<a href="index.php?module=commande"><button>Nouvelle commande</button></a> ';
        echo '<a href="index.php?module=buvettes"><button>Retour aux buvettes</button></a>';
    }

    public function message($texte) {
        echo '<p>' . $texte . '</p>';
    }
}
?>