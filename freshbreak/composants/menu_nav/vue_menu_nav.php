<?php
if (!defined('APP_SECURE')) die('Accès interdit.');

class Vue_menu_nav {
    private $affichage;

    public function __construct() {
        if (isset($_SESSION['login']))
            $this->affichage .= '<a href="index.php?module=buvettes">Buvettes</a>'.'|<a href="index.php?module=solde_refill&action=form">Recharger le solde</a>'.'|<a href="index.php?module=produit&action=form_produit">Ajouter un produit</a>';
        else
            $this->affichage = '';
    }

    public function getAffichage() {
        return $this->affichage;
    }
}
?>