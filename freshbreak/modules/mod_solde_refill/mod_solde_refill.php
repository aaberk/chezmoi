<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('cont_solde_refill.php');

class ModSoldeRefill {
    private $controller;

    public function __construct() {
        $this->controller = new ContSoldeRefill();
    }

    public function exec() {

        $action = isset($_GET['action']) ? $_GET['action'] : 'form';

        switch ($action) {
            case 'form':
                $this->controller->form();
                break;

            case 'refill':
                $this->controller->add_money_to_account();
                break;

            default:
                echo "<p>Action inconnue.</p>";
                break;
        }
    }

    public function print_content() {
        return $this->controller->print_content();
    }
}
?>