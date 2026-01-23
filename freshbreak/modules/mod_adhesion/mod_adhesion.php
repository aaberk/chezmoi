<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('cont_adhesion.php');

class ModAdhesion {
    private $controller;

    public function __construct() {
        $this->controller = new ContAdhesion();
    }

    public function exec() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'show_form';

        if (isset($_GET['bar_id'])) {
            $_SESSION['bar_id'] = intval($_GET['bar_id']);

        }
        switch ($action) {
            case 'show_form':
                $this->controller->display_form();
                break;
            case 'create_request':
                $this->controller->create_request();
                break;
            case 'request_handling':

                    $this->controller->handle_request();

                break;
            case 'accept_bar_creation':

                    $this->controller->accept_adhesion();

                break;
            case 'decline_bar_creation':
                    $this->controller->decline_adhesion();
                break;
            case 'menu':
                unset($_SESSION['bar_id']);
                header("Location: index.php?module=buvettes&action=liste");
                break;
          //  default:
            //    echo "<p>Action inconnue.</p>";
              //  break;
        }
    }



    public function print_content() {
        return $this->controller->print_content();
    }
}
?>