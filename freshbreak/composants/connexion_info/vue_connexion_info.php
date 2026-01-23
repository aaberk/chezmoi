<?php
if (!defined('APP_SECURE')) die('Accès interdit.');

class Vue_connexion_info {
    private $affichage;

    public function __construct() {
        if (isset($_SESSION['login'])) {
            $this->affichage = '<div class="user-info">';
            $this->affichage .= '<span class="user-name">' . htmlspecialchars($_SESSION['login']) . '</span>';
            $this->affichage .= '<span class="user-solde">' . $_SESSION['solde'] . '€</span>';
            $this->affichage .= '<a href="index.php?module=inbox&action=show_inbox" class="header-link">Boîte de réception</a>';
            $this->affichage .= '<a href="index.php?module=connexion&action=deconnexion" class="header-link logout">Déconnexion</a>';
            $this->affichage .= '</div>';
        } else {
            $this->affichage = '';
        }
    }

    public function getAffichage() {
        return $this->affichage;
    }
}
?>