<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('vue_adhesion.php');
require_once('modele_adhesion.php');

class ContAdhesion {
    private $modele;
    private $vue;

    public function __construct() {
        $this->modele = new ModeleAdhesion();
        $this->vue = new VueAdhesion();
    }

    public function create_request() {
        if (
            empty($_POST['csrf_token']) ||
            empty($_SESSION['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {

            return;
        }

        $login = $_SESSION['login'] ?? null;
        $bar_id = $_POST['bar_id'] ?? null;

        if (!$login || !$bar_id) {
            $this->vue->message("Données invalides.");
            return;
        }

        if ($this->modele->estDejaMembreBuvette($login, $bar_id)) {
            $this->vue->message("Vous êtes déjà membre. ");
            return;
        }

        $gestionnaire = $this->modele->getGestionnaire($bar_id);
        $request_id = $this->modele->new_request($bar_id,$_SESSION['login'],$gestionnaire);

        $this->modele->new_message($gestionnaire, 3, $request_id);
        $this->vue->send_notice();
        unset($_SESSION['bar_id']);
    }
    public function getGestionnaire($bar_id)
    {
     return   $this->modele->getGestionnaire($bar_id);

    }




    public function display_form() {
        $this->vue->confirmation($_SESSION['bar_id'] ?? null);
    }



    public function handle_request() {
        if (isset($_GET['request_id'])) {
            $request_id = $_GET['request_id'];
            $request = $this->modele->get_request($request_id);
            var_dump($request_id);
            var_dump($request);
            $this->vue->display_request($request['login_request_user'],$this->modele->get_bar_name($request['request_content']),$request_id);
        }
    }

    public function accept_adhesion() {
        $request_id = $_GET['request_id'] ?? null;
        if (!$request_id) return;

        $request = $this->modele->get_request($request_id);

        $this->modele->ajouterMembreBuvette(
            $request['login_request_user'],
            $request['request_content'],
            'client'
        );

        $this->modele->new_message($request['login_request_user'],4,'1|'.$this->modele->get_bar_name($request['request_content']));

        $this->finish_tasks($request_id);
    }

    public function nuhuh(){
        $this->vue->message("nuh uh");
    }

    public function decline_adhesion() {
        if (isset($_GET['request_id'])) {
            $request_id = $_GET['request_id'];
            $request = $this->modele->get_request($request_id);
            $request_user = $request['login_request_user'];
            $bar_name = $this->modele->get_bar_name($request['request_content']);

            $this->modele->new_message($request_user,4,'0|'.$bar_name);
            $this->finish_tasks($request_id);
        }
    }

    public function finish_tasks($request_id) {
        $this->modele->delete_request($request_id);
        $this->modele->delete_msg($request_id);
        header('Location: index.php?module=inbox&action=show_inbox');

    }

    public function print_content() {
        return $this->vue->close_buffer();
    }
}
?>