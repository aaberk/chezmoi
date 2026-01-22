<?php
if (!defined('APP_SECURE')) die('Accès interdit.');

class Vue_connexion_info {
    private $affichage;

    public function __construct() {
        if (isset($_SESSION['login']))
            $this->affichage .= '<p>Connecté sous <b>' . htmlspecialchars($_SESSION['login']) . '</b> | Solde : ' . $_SESSION['solde'] . '€ | ' . '<a href=index.php?module=inbox&action=show_inbox>Boite de réception</a> | ' . '<a href="index.php?module=connexion&action=deconnexion">Se déconnecter</a></p>';
        else
            $this->affichage = '';
    }

    public function getAffichage() {
        return $this->affichage;
    }
}
?>