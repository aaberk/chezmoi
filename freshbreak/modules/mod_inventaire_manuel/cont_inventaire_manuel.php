<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('vue_inventaire_manuel.php');
require_once('modele_inventaire_manuel.php');

class Cont_inventaire_manuel {
    private $modele;
    private $vue;

    public function __construct() {
        $this->modele = new Modele_inventaire_manuel();
        $this->vue = new Vue_inventaire_manuel();
    }

    public function print_content() {
        return $this->vue->close_buffer();
    }

    public function show_products() {
        if (!isset($_SESSION['bar_id'])) {
            $this->vue->no_bar_selected_error();
        }
        else {
            $produits = $this->modele->get_nom_produits_from_availability($_SESSION['bar_id']);
            if (isset($_SESSION['tmp_save_inventory'])) {
                foreach ($produits as &$produit) {
                    $produit['quantite'] = $_SESSION['tmp_save_inventory'][$produit['nom_produit']];
                }
                unset($produit);
                unset($_SESSION['tmp_save_inventory']);
            }
            else {
                foreach ($produits as &$prod) {
                    $prod['quantite'] = 0;
                }
            }
            $_SESSION['produits'] = $produits;
            $this->vue->show_products_inventory_form($produits);
        }
    }

    public function submit_request() {
        if (
            empty($_POST['csrf_token']) ||
            empty($_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            return;
        }
        $inventaire_id = $this->modele->new_inventaire($_SESSION['bar_id'],date('Y-m-d'));
        $this->add_all_products_to_bd($inventaire_id);
        $this->stock_difference_evaluation($inventaire_id);
    }

    public function stock_difference_evaluation($id_inventaire) {
        $stock_availability = $this->modele->get_stock_from_availability($_SESSION['bar_id']);
        $stock_inventory = $this->modele->get_stock_from_inventory($id_inventaire);
        $difference_evaluation = [];
        for ($i = 0; $i < count($stock_availability); $i++) {
            if ($stock_availability[$i]['quantite'] != $stock_inventory[$i]['quantite']) {
                $difference_evaluation[$stock_inventory[$i]['id_produit_inventaire']] = [
                    "quantite" => $stock_availability[$i]['quantite'] - $stock_inventory[$i]['quantite'],
                    "nom_produit" => $stock_availability[$i]['nom_produit'],
                ];
            }
        }
        $this->vue->title_stock_analyse();
        if ($difference_evaluation == [])
            $this->vue->no_difference_stock_notice();
        else {
            $this->vue->display_stock_differences($difference_evaluation);
            $this->update_bd($stock_inventory);
        }
    }

    public function update_bd($stock_inventory) {
        foreach ($stock_inventory as $produit) {
            $this->modele->update_availability($produit['id_produit_inventaire'],$_SESSION['bar_id'],$produit['quantite']);
        }
    }

    public function add_all_products_to_bd($id_inventaire) {
        $produits = $_SESSION['produits'];
        unset($_SESSION['produits']);
        foreach ($produits as $prod) {
            $this->modele->add_product_inventory($prod['id_produit'],$id_inventaire,$_POST[$this->char_replace($prod['nom_produit']," ","_")]);
        }
    }

    public function save_temp_inventory() {
        if (
            empty($_POST['csrf_token']) ||
            empty($_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            return;
        }
        $save_tmp_inventory = [];
        foreach ($_POST as $nom_produit=>$quantite) {
            if ($nom_produit != 'csrf_token') {
                $save_tmp_inventory[$this->char_replace($nom_produit,"_"," ")] = $quantite;
            }
        }
        $_SESSION['tmp_save_inventory'] = $save_tmp_inventory;
        header("Location: index.php?module=produit&action=form_produit");
    }

    public function char_replace($string,$pattern,$replace): string {
        $returned_string = "";
        foreach (explode($pattern,$string) as $s) {
            $returned_string .= $s.$replace;
        }
        return trim($returned_string,$replace);
    }
}
?>