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

    public function formSelectionClient() {
        $clients = $this->modele->getClients($_SESSION['bar_id']);
        $this->vue->formClient($clients);
    }

    public function validerClient() {
        if (
            empty($_POST['csrf_token']) ||
            empty($_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {

            return;
        }
        if (isset($_POST['mdp'])) {
            foreach ($this->modele->get_users() as $user) {
                if (password_verify($_POST['mdp'],$user['password'])) {
                    $_SESSION['client_commande'] = $user['login'];
                }
            }
            if (isset($_SESSION['client_commande'])) {
                $this->vue->notice_non_exisant_mdp();
            }
            $_SESSION['panier'] = [];
            header('Location: index.php?module=commande&action=panier');
            exit;
        }
        $this->vue->message("Aucun client sélectionné");
    }

    public function afficherPanier() {
        $produits = $this->modele->getProduits($_SESSION['bar_id']);
        $panier = $_SESSION['panier'] ?? [];
        $client = $_SESSION['client_commande'];
        $solde = $this->modele->getSolde($client);
        $this->vue->afficherPanier($produits, $panier, $client, $solde);
    }

    public function ajouterProduit() {
        if (
            empty($_POST['csrf_token']) ||
            empty($_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {

            return;
        }
        if (isset($_POST['id_produit']) && isset($_POST['quantite'])) {
            $id = intval($_POST['id_produit']);
            $qte = intval($_POST['quantite']);

            $produit = $this->modele->getProduit($id);

            $existe = false;
            foreach ($_SESSION['panier'] as &$p) {
                if ($p['id'] == $id) {
                    $p['qte'] += $qte;
                    $existe = true;
                    break;
                }
            }

            if (!$existe) {
                $_SESSION['panier'][] = [
                    'id' => $id,
                    'nom' => $produit['nom_produit'],
                    'prix' => $produit['prix_vente'],
                    'qte' => $qte
                ];
            }
        }
        header('Location: index.php?module=commande&action=panier');
        exit;
    }

    public function retirerProduit() {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            foreach ($_SESSION['panier'] as $key => $p) {
                if ($p['id'] == $id) {
                    unset($_SESSION['panier'][$key]);
                    break;
                }
            }
            $_SESSION['panier'] = array_values($_SESSION['panier']);
        }
        header('Location: index.php?module=commande&action=panier');
        exit;
    }

    public function validerCommande() {
        if (empty($_SESSION['panier'])) {
            $this->vue->message("Panier vide");
            return;
        }

        $client = $_SESSION['client_commande'];
        $panier = $_SESSION['panier'];

        $total = 0;
        foreach ($panier as $p) {
            $total += $p['prix'] * $p['qte'];
        }

        $solde = $this->modele->getSolde($client);
        if ($solde < $total) {
            $this->vue->message("Solde insuffisant");
            return;
        }

        $id_commande = $this->modele->creerCommande($client, $_SESSION['bar_id']);

        foreach ($panier as $p) {
            $this->modele->ajouterLigne($id_commande, $p['id'], $p['qte'], $p['prix']);
            $this->modele->diminuerStock($p['id'], $_SESSION['bar_id'], $p['qte']);
        }

        $this->modele->debiterSolde($client, $total);

        unset($_SESSION['client_commande']);
        unset($_SESSION['panier']);

        $this->vue->message("Commande validée");
    }

    public function annuler() {
        unset($_SESSION['client_commande']);
        unset($_SESSION['panier']);
        header('Location: index.php?module=buvettes');
        exit;
    }

    public function print_content() {
        return $this->vue->close_buffer();
    }
}
?>