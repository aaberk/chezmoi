<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('cont_inventaire_manuel.php');

class Mod_inventaire_manuel {
    private $controller;

    public function __construct() {
        $this->controller = new Cont_inventaire_manuel();
    }

    public function exec() {
        $action = $_GET['action'] ?? 'display_all_products';
        switch ($action) {
            case 'display_all_products':
                $this->controller->show_products();
                break;

            case 'submit_inventory':
                $this->controller->submit_request();
                break;

            case 'save_tmp_inventory':
                $this->controller->save_temp_inventory();
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