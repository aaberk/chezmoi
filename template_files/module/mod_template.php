<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('cont_template.php');

class Mod_template {
    private $controller;

    public function __construct() {
        $this->controller = new Cont_template();
    }

    public function exec() {

        $action = isset($_GET['action']) ? $_GET['action'] : '[default]';

        switch ($action) {
            case '[case]':
                break;

            default:
                echo "<p>Action inconnue.</p>";
                break;
        }
    }
}
?>