<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once ("./vue_generique.php");

class VueCreationBuvettes extends Vue_generique {
    public function __construct() {
        parent::__construct();
    }

    public function form_inscription() {
        echo '
        <h2>Ajout Buvette</h2>
        <form method="post" action="index.php?module=creationBuvettes&action=create_request" enctype="multipart/form-data">
         <input type="hidden" name="csrf_token"
                   value="' . htmlspecialchars($_SESSION['csrf_token']) . '">
            <label>Nom Buvette :</label>
            <input type="text" name="nom" required><br>
            <label>Documents justificatifs :</label><br>
            <input type="file" name="doc1" required accept="application/pdf,.pdf"><br>
            <input type="file" name="doc2" required accept="application/pdf,.pdf"><br>
            <input type="file" name="doc3" required accept="application/pdf,.pdf"><br>
            <button type="submit">S’inscrire</button>
        </form>';
    }

    public function message($message) {
        echo "<p>$message</p>";
    }

    public function display_request($request_user,$bar_name,$request_id) {
        echo '<h2>Requête de création de bar</h2>
                  <p>'.$request_user.' souhaite créer le bar '.$bar_name.'.</p>
                  <h3>Pièces justificatives :</h3>
                      <a href="./dossiers_creation_bar/'.$request_user.'_request_'.$bar_name.'_doc1.pdf">Document 1</a><br>
                      <a href="./dossiers_creation_bar/'.$request_user.'_request_'.$bar_name.'_doc2.pdf">Document 2</a><br>
                      <a href="./dossiers_creation_bar/'.$request_user.'_request_'.$bar_name.'_doc3.pdf">Document 3</a><br>
                  <a href="index.php?module=creationBuvettes&action=accept_bar_creation&request_id='.$request_id.'">Accepter</a> 
                  <a href="index.php?module=creationBuvettes&action=decline_bar_creation&request_id='.$request_id.'">Refuser</a>';
    }

    public function request_submit_failure_format() {
        echo '<p>Un ou plusieurs fichier enregistré ne sont pas du bon format. Il est nécessaire que les fichiers soient de format pdf.</p>
              <a href="index.php?module=creationBuvettes&action=show_form">Réessayer</a>';
    }

    public function request_submit_failure_duplicate() {
        echo '<p>Vous avez déjà fait une demande de création pour ce bar. Vous ne pouvez pas effectuer plusieurs demandes à la fois pour la création d\'un bar donné</p>
              <a href="index.php?module=creationBuvettes&action=show_form">Retour</a>';
    }

    public function send_notice() {
        echo '<p>Votre demande a bien été envoyé.</p>
                <a href="index.php?module=buvettes">Retour</a>';
    }
}