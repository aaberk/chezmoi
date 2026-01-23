<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('vue_inbox.php');
require_once('modele_inbox.php');

class Cont_inbox {
    private $modele;
    private $vue;

    public function __construct() {
        $this->modele = new Modele_inbox();
        $this->vue = new Vue_inbox();
    }

    public function show_inbox() {
        $tab_messages = $this->modele->getMessages($_SESSION['login']);
        $nb_msg = count($tab_messages);
        $this->vue->show_number_msg($nb_msg);

        if ($nb_msg > 0) {
            for ($i = $nb_msg - 1; $i >= 0; $i--) {
                $this->show_message($tab_messages[$i]);
            }
        }
        $this->vue->display_supp();
    }

    public function delete_all_notification() {
        $login = $_SESSION['login'];
        $nb_msg_delete = 0;
        foreach ($this->modele->getMessages($login) as $message) {
            if ($message['message_type'] == 2)
                $nb_msg_delete += 1;
        }
        if ($nb_msg_delete > 0) {
            $this->modele->delete_user_msgs($login);
            header('Location: index.php?module=inbox');
        }
        else
            $this->vue->no_msg_to_supp();
    }

    public function show_message($message) {
        $message_type = $message['message_type'];
        $message_content = $message['msg_arguments'];

        switch ($message_type) {
            case 0:
                $this->vue->task_type();
                $this->vue->show_payment_confirmation($message_content);
                break;
            case 1:
                $this->vue->task_type();
                $this->vue->show_admin_bar_creation_request($message_content);
                break;
            case 2:
                $this->vue->notification_type();
                $this->vue->show_bar_creation_msg($message_content);
                break;
            case 3:
                $this->vue->task_type();
                $this->vue->show_gerant_adhesion_request($message_content);
                break;
            case 4:
                $this->vue->notification_type();
                $this->vue->show_adhesion_answer($message_content);
                break;

        }
    }

    public function print_content() {
        return $this->vue->close_buffer();
    }
}
?>