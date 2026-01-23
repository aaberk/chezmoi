<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('cont_bilan.php');

class Mod_bilan {
    private $controller;

    public function __construct() {
        $this->controller = new Cont_bilan();
    }

    public function exec() {
        $action = isset($_GET['action']) ? $_GET['action'] : '[default]';
        switch ($action) {
            case 'display_form':
                $this->controller->show_date_selection();
                break;

            case 'summary_calculation':
                $this->controller->calculate_summary();
                break;

            default:
                $this->controller->show_date_selection();
                break;
        }
    }

    public function print_content() {
        return $this->controller->print_content();
    }
}
?>