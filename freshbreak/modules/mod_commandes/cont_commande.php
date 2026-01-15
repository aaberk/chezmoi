<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('vue_commande.php');
require_once('modele_commande.php');

class ContCommande {
    private $vue;
    private $modele;

    public function __construct() {
        $this->vue = new VueCommande();
        $this->modele = new ModeleCommande();
    }

    private function estBarman() {
        if (!isset($_SESSION['login']) || !isset($_SESSION['bar_id'])) {
            return false;
        }
        return $this->modele->verifierRoleBarman($_SESSION['login'], $_SESSION['bar_id']);
    }

    public function selectionClient() {
        if (!$this->estBarman()) {
            $this->vue->message("Accès refusé : vous devez être barman.");
            return;
        }

        $clients = $this->modele->getClientsBuvette($_SESSION['bar_id']);
        $this->vue->formSelectionClient($clients);
    }

    public function validerClient() {
        if (!$this->estBarman()) {
            $this->vue->message("Accès refusé.");
            return;
        }

        if (isset($_POST['login_client'])) {
            $_SESSION['commande_client'] = $_POST['login_client'];
            $_SESSION['panier'] = [];
            header('Location: index.php?module=commande&action=produits');
            exit;
        } else {
            $this->vue->message("❌ Aucun client sélectionné.");
            $this->selectionClient();
        }
    }

    public function afficherProduits() {
        if (!$this->estBarman() || !isset($_SESSION['commande_client'])) {
            $this->vue->message("Erreur : commande non initialisée.");
            return;
        }

        $produits = $this->modele->getProduitsDispo($_SESSION['bar_id']);
        $panier = $_SESSION['panier'] ?? [];
        $total = $this->calculerTotal($panier);

        $solde_client = $this->modele->getSoldeClient($_SESSION['commande_client']);

        $this->vue->afficherProduits($produits, $panier, $total, $_SESSION['commande_client'], $solde_client);
    }

    public function ajouterAuPanier() {
        if (!$this->estBarman() || !isset($_SESSION['commande_client'])) {
            header('Location: index.php?module=commande');
            exit;
        }

        if (isset($_POST['id_produit']) && isset($_POST['quantite'])) {
            $id_produit = intval($_POST['id_produit']);
            $quantite = intval($_POST['quantite']);

            if ($quantite <= 0) {
                header('Location: index.php?module=commande&action=produits');
                exit;
            }

            $stock = $this->modele->getStockProduit($id_produit, $_SESSION['bar_id']);
            if ($quantite > $stock) {
                $this->vue->message("❌ Stock insuffisant (disponible : $stock)");
                $this->afficherProduits();
                return;
            }

            $produit = $this->modele->getInfosProduit($id_produit);

            $existe = false;
            foreach ($_SESSION['panier'] as &$item) {
                if ($item['id_produit'] == $id_produit) {
                    $item['quantite'] += $quantite;
                    $existe = true;
                    break;
                }
            }

            if (!$existe) {
                $_SESSION['panier'][] = [
                    'id_produit' => $id_produit,
                    'nom' => $produit['nom_produit'],
                    'prix' => $produit['prix_vente'],
                    'quantite' => $quantite
                ];
            }
        }

        header('Location: index.php?module=commande&action=produits');
        exit;
    }

    public function retirerDuPanier() {
        if (!$this->estBarman()) {
            header('Location: index.php?module=commande');
            exit;
        }

        if (isset($_GET['id_produit'])) {
            $id_produit = intval($_GET['id_produit']);

            foreach ($_SESSION['panier'] as $key => $item) {
                if ($item['id_produit'] == $id_produit) {
                    unset($_SESSION['panier'][$key]);
                    $_SESSION['panier'] = array_values($_SESSION['panier']); // Réindexer
                    break;
                }
            }
        }

        header('Location: index.php?module=commande&action=produits');
        exit;
    }

    public function recapitulatif() {
        if (!$this->estBarman() || !isset($_SESSION['commande_client']) || empty($_SESSION['panier'])) {
            $this->vue->message("Panier vide ou commande non initialisée.");
            return;
        }

        $panier = $_SESSION['panier'];
        $total = $this->calculerTotal($panier);
        $client = $_SESSION['commande_client'];
        $solde = $this->modele->getSoldeClient($client);

        $this->vue->recapitulatif($client, $panier, $total, $solde);
    }

    public function validerCommande() {
        if (!$this->estBarman() || !isset($_SESSION['commande_client']) || empty($_SESSION['panier'])) {
            $this->vue->message("❌ Impossible de valider la commande.");
            return;
        }

        $client = $_SESSION['commande_client'];
        $panier = $_SESSION['panier'];
        $total = $this->calculerTotal($panier);

        $solde = $this->modele->getSoldeClient($client);
        if ($solde < $total) {
            $this->vue->message("❌ Solde insuffisant. Solde actuel : {$solde}€, Total : {$total}€");
            $this->recapitulatif();
            return;
        }

        $id_commande = $this->modele->creerCommande($client, $_SESSION['bar_id']);

        foreach ($panier as $item) {
            $this->modele->ajouterProduitCommande(
                $id_commande,
                $item['id_produit'],
                $item['quantite'],
                $item['prix']
            );

            $this->modele->diminuerStock($item['id_produit'], $_SESSION['bar_id'], $item['quantite']);
        }

        $this->modele->debiterClient($client, $total);

        unset($_SESSION['commande_client']);
        unset($_SESSION['panier']);

        $this->vue->confirmation("Commande validée avec succès ! Total débité : {$total}€");
    }

    public function annulerCommande() {
        unset($_SESSION['commande_client']);
        unset($_SESSION['panier']);
        header('Location: index.php?module=buvettes');
        exit;
    }

    private function calculerTotal($panier) {
        $total = 0;
        foreach ($panier as $item) {
            $total += $item['prix'] * $item['quantite'];
        }
        return round($total, 2);
    }

    public function print_content() {
        return $this->vue->close_buffer();
    }
}
?>