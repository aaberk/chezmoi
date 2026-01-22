<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('cont_stock.php');

class ModStock {
    private $controleur;
    private $content;

    public function __construct() {
        $this->controleur = new ContStock();
    }

    public function exec() {
        $barId = $_SESSION['bar_id'] ?? null;
        $this->controleur->gestion_stock($barId);
        $this->content = $this->controleur->getContent();
    }

    public function print_content() {
        return $this->content;
    }
}
