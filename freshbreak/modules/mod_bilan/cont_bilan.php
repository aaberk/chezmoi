<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('vue_bilan.php');
require_once('modele_bilan.php');

class Cont_bilan {
    private $modele;
    private $vue;

    public function __construct() {
        $this->modele = new Modele_bilan();
        $this->vue = new Vue_bilan();
    }

    public function show_date_selection(){
        $this->vue->show_date_selection($this->modele->date_min(),date('Y-m-d'));
    }

    public function calculate_summary() {
        if (
            empty($_POST['csrf_token']) ||
            empty($_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            return;
        }

        if (isset($_POST['date_debut']) && isset($_POST['date_fin'])) {
            $date_debut = $_POST['date_debut'];
            $date_fin = $_POST['date_fin'];
            if ($date_debut > $date_fin)
                $this->vue->date_failure($this->modele->date_min(),date('Y-m-d'));

            $bilan =
                [
                    'chiffre_affaire' => $this->modele->chiffre_affaire($date_debut,$date_fin,$_SESSION['bar_id']),
                    'depense' => $this->get_depense($date_debut,$date_fin),
                    'consommation' => $this->getStock($date_debut,$date_fin),
                    'reapprovisionnement' => $this->modele->get_reapprovisionnement(),
                ];
            $this->vue->display_summary($bilan,$date_debut,$date_fin);
        }
    }

    public function get_depense($date_debut,$date_fin) {
        $achats = $this->modele->get_achat_produit($_SESSION['bar_id'],$date_debut,$date_fin);
        $total = 0;
        foreach ($achats as $achat) {
            $total += $this->modele->get_produit_prix($achat['id_produit'])*$achat['quantite'];
        }
        return $total;
    }

    public function print_content() {
        return $this->vue->close_buffer();
    }
    public function getStock($date_debut,$date_fin): array {
        $id_bar=$_SESSION['bar_id'];
        $dispo= $this->modele->get_dispo($id_bar);
        $stock = [];

        foreach ($dispo as $id_produit) {
            $stock[] = [
                'id' => $id_produit,
                'nom' => $this->modele->get_produit($id_produit['id_produit']),
                'vendu' => $this->modele->get_diff_produit(
                    $id_bar,
                    $date_debut,
                    $date_fin,
                    $id_produit['id_produit']
                )
            ];
        }
        return $stock;

    }
}
?>