<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('vue_creationBuvettes.php');
require_once('modele_creationBuvettes.php');

class ContCreationBuvettes {
    private $vue;
    private $modele;
    public function __construct()
    {
        $this->vue = new VueCreationBuvettes();
        $this->modele = new ModeleCreationBuvettes();
    }

    public function create_request() {
        if (
            empty($_POST['csrf_token']) ||
            empty($_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {

            return;
        }
        $nom = trim($_POST['nom']);
        if ($nom == '') {
            $this->vue->message("❌ Le nom de la buvette est obligatoire.");
            return;
        }
        if ($this->modele->nomexist($nom)) {
            $this->vue->message("❌ Cette buvette existe déjà.");
            return;
        }
        if ($this->modele->check_request_exists($nom)) {
            $this->vue->request_submit_failure_duplicate();
            return;
        }
        $file_name1 = $this->generate_file_name($nom,'doc1');
        $file_name2 = $this->generate_file_name($nom,'doc2');
        $file_name3 = $this->generate_file_name($nom,'doc3');

        if (!$this->check_files()) {
    //        move_uploaded_file($_FILES['doc1']['tmp_name'],'./dossiers_creation_bar/' . $file_name1);
    //        move_uploaded_file($_FILES['doc2']['tmp_name'],'./dossiers_creation_bar/' . $file_name2);
    //        move_uploaded_file($_FILES['doc3']['tmp_name'],'./dossiers_creation_bar/' . $file_name3);
            $this->create_request_message_to_inbox($this->modele->new_request($_SESSION['login'], $nom));
            $this->vue->send_notice();
        }
        else {
            $this->vue->request_submit_failure_format();
        }
    }

    public function check_files(): bool {
        return mime_content_type($_FILES['doc1']['tmp_name']) == "application/pdf" && mime_content_type($_FILES['doc2']['tmp_name']) == "application/pdf" && mime_content_type($_FILES['doc3']['tmp_name']) == "application/pdf";
    }

    public function generate_file_name($bar_name, $doc): string {
        return $_SESSION['login'] . '_request_'.$bar_name.'_'.$doc.'.'.pathinfo($_FILES[$doc]['name'],PATHINFO_EXTENSION);
    }

    public function display_form() {
        $this->vue->form_inscription();
    }

    public function create_request_message_to_inbox($request_id) {
        $this->modele->new_message('admin',1,$request_id);
    }

    public function handle_request() {
        if (isset($_GET['request_id'])) {
            $request_id = $_GET['request_id'];
            $request = $this->modele->get_request($request_id);
            $this->vue->display_request($request['login_request_user'],$request['request_content'],$request_id);
        }
    }

    public function accept_bar_creation() {
        if (isset($_GET['request_id'])) {
            $request_id = $_GET['request_id'];
            $request = $this->modele->get_request($request_id);
            $request_user = $request['login_request_user'];
            $bar_name = $request['request_content'];

            $this->modele->ajouterBar($bar_name,$request_user);
            $this->modele->new_message($request_user,2,'1|'.$bar_name);
            $this->finish_tasks($request_id,$request_user,$bar_name);
        }
    }

    public function decline_bar_creation() {
        if (isset($_GET['request_id'])) {
            $request_id = $_GET['request_id'];
            $request = $this->modele->get_request($request_id);
            $request_user = $request['login_request_user'];
            $bar_name = $request['request_content'];

            $this->modele->new_message($request_user,2,'0|'.$bar_name);
            $this->finish_tasks($request_id,$request_user,$bar_name);
        }
    }

    public function finish_tasks($request_id,$request_user,$bar_name) {
        $this->modele->delete_request($request_id);
        $this->modele->delete_msg($request_id);
//        unlink('./dossiers_creation_bar/'.$request_user.'_request_'.$bar_name.'_doc1.pdf');
//        unlink('./dossiers_creation_bar/'.$request_user.'_request_'.$bar_name.'_doc2.pdf');
//        unlink('./dossiers_creation_bar/'.$request_user.'_request_'.$bar_name.'_doc3.pdf');
        header('Location: index.php?module=inbox&action=show_inbox');
    }

    public function print_content() {
        return $this->vue->close_buffer();
    }
}
