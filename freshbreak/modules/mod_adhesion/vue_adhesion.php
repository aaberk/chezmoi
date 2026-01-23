<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('./vue_generique.php');

class VueAdhesion extends Vue_generique {
    public function __construct() {
        parent::__construct();
    }

    public function confirmation($bar_id) {
        echo '<h2>Rejoindre une buvette</h2>';
        echo '<p>Souhaitez-vous envoyer une demande pour rejoindre cette buvette ?</p>';

        echo '
        <form method="post" action="index.php?module=rejoindreBuvette&action=create_request">

            <input type="hidden" name="csrf_token"
                   value="' . htmlspecialchars($_SESSION['csrf_token']) . '">

            <input type="hidden" name="bar_id"
                   value="' . intval($bar_id) . '">

            <button type="submit">Accepter</button>
            <button type="submit" formaction="index.php?module=rejoindreBuvette&action=menu">Annuler</button>
 
        </form>';
    }

    public function display_request($request_user, $bar_name, $request_id) {
        echo '<h2>Requête d adhesion au bar</h2>
                  <p>' . htmlspecialchars($request_user) . ' souhaite rejoindre le bar ' . htmlspecialchars($bar_name) . '.</p>
                  <a href="index.php?module=rejoindreBuvette&action=accept_bar_creation&request_id=' . htmlspecialchars($request_id) . '">Accepter</a> 
                  <a href="index.php?module=rejoindreBuvette&action=decline_bar_creation&request_id=' . htmlspecialchars($request_id) . '">Refuser</a>';
    }

    public function send_notice() {
        echo '<p>Votre demande a bien été envoyé.</p>
                <a href="index.php?module=buvettes">Retour</a>';
    }

    public function message($texte) {
        echo '<p>' . htmlspecialchars($texte) . '</p>';
    }

    public function request_submit_failure_duplicate() {
        echo '<p>Vous avez déjà fait une demande de création pour ce bar. Vous ne pouvez pas effectuer plusieurs demandes à la fois pour la création d\'un bar donné</p>
              <a href="index.php?module=creationBuvettes&action=show_form">Retour</a>';
    }
}
?>
