<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('cont_inbox.php');

class Mod_inbox {
    private $controller;

    public function __construct() {
        $this->controller = new Cont_inbox();
    }

    public function exec() {

        $action = isset($_GET['action']) ? $_GET['action'] : 'show_inbox';

        switch ($action) {
            case 'show_inbox':
                $this->controller->show_inbox();
                break;
            case 'delete_all_notif':
                $this->controller->delete_all_notification();
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