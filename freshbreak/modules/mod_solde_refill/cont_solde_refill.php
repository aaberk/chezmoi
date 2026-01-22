<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('vue_solde_refill.php');
require_once('modele_solde_refill.php');

class ContSoldeRefill {
    private $modele;
    private $vue;

    public function __construct() {
        $this->modele = new ModeleSoldeRefill();
        $this->vue = new VueSoldeRefill();
    }

    public function form() {
        $this->vue->form($_SESSION['solde']);
    }

    public function add_money_to_account() {
        if (isset($_POST['value']) && $_POST['value'] > 0) {
            $refillement = $_POST['value'];
            $this->modele->addMoney($_SESSION['login'],$refillement);
            $_SESSION['solde'] += $refillement;
            header('Location: index.php?module=buvettes&action=liste');
        }
        else {
            $this->form();
            $this->vue->error();
        }
    }

    public function print_content() {
        return $this->vue->close_buffer();
    }
}
?>