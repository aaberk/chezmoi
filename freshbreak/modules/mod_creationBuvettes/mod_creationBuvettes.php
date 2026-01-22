<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('cont_creationBuvettes.php');
class ModCreationBuvettes {
    private $controleur;

    public function __construct() {
        $this->controleur = new ContCreationBuvettes();
    }

    public function exec() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'show_form';
        switch ($action) {
            case 'show_form':
                $this->controleur->display_form();
                break;
            case 'create_request':
                $this->controleur->create_request();
                break;
            case 'request_handling':
                if ($_SESSION['admin'])
                    $this->controleur->handle_request();
                else
                    echo "<h1>NUH HUH</h1>";
                break;
            case 'accept_bar_creation':
                if ($_SESSION['admin'])
                    $this->controleur->accept_bar_creation();
                else
                    echo "<h1>NUH HUH</h1>";
                break;
            case 'decline_bar_creation':
                if ($_SESSION['admin'])
                    $this->controleur->decline_bar_creation();
                else
                    echo "<h1>NUH HUH</h1>";
                break;

            default:
                echo "<p>Action inconnue.</p>";
                break;
        }
    }

    public function print_content() {
        return $this->controleur->print_content();
    }
}