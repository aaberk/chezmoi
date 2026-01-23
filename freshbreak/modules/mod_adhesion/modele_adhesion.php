<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('connexion.php');

class ModeleAdhesion extends connexion {

    public function estDejaMembreBuvette($login, $bar_id) {
        $req = self::$bdd->prepare("SELECT COUNT(*) FROM role WHERE login_utilisateur = :login AND bar_associe = :bar_id");
        $req->bindParam(':login', $login);
        $req->bindParam(':bar_id', $bar_id);
        $req->execute();
        return $req->fetchColumn() > 0;
    }

    public function ajouterMembreBuvette($login, $bar_id, $role) {
        $req = self::$bdd->prepare("INSERT INTO role (login_utilisateur, bar_associe, role_bar) VALUES (:login, :bar_id, :role)");
        $req->bindParam(':login', $login);
        $req->bindParam(':bar_id', $bar_id);
        $req->bindParam(':role', $role);
        $req->execute();
    }

    public function new_request($bar,$login_request_user,$gestionnaire) {
        $req_insert = self::$bdd->prepare("INSERT INTO request_tasks (login_tasked_user,login_request_user,request_content) VALUES
                                                                                                  (:login_tasked_user,:login_request_user,:request_content);");
        $req_insert->execute(['login_tasked_user' => $gestionnaire,'login_request_user' => $login_request_user,'request_content' => $bar]);
        return self::$bdd->lastInsertId();
    }


    public function new_message($user_login,$msg_type,$msg_arguments) {
        $req = self::$bdd->prepare("INSERT INTO inbox (user_login,message_type,msg_arguments) VALUES (:user_login,:msg_type,:msg_arguments);");
        $req->execute(['user_login' => $user_login, 'msg_type' => $msg_type, 'msg_arguments' => $msg_arguments]);
    }

    public function get_request($id_request) {
        $req = self::$bdd->prepare("SELECT login_request_user,request_content FROM request_tasks WHERE id_request = :id_request;");
        $req->execute(['id_request' => $id_request]);
        return $req->fetchAll()[0];
    }

    public function delete_request($request_id) {
        $req = self::$bdd->prepare("DELETE FROM request_tasks WHERE id_request = :id_request;");
        $req->execute(['id_request' => $request_id]);
    }



    public function delete_msg($request_id) {
        $req = self::$bdd->prepare("DELETE FROM inbox WHERE message_type = 3 AND msg_arguments = :msg_arguments");
        $req->execute(['msg_arguments' => $request_id]);
    }

    public function check_request_exists($nom) {
        $req = self::$bdd->prepare("SELECT COUNT(*) FROM request_tasks WHERE request_content = :nom;");
        $req->execute(['nom' => $nom]);
        return $req->fetchColumn() > 0;
    }

    public function getGestionnaire($bar){
        $req = self::$bdd->prepare("Select login_utilisateur from role where bar_associe = :bar and role_bar ='gérant' ");
        $req->execute(['bar' => $bar]);
        return $req->fetchColumn();

    }

    public function getLoginRequest($idrequest) {
        $req =self::$bdd->prepare("Select login_request_user from request_tasks where id_request = :id_request;");
        $req->execute(['id_request' => $idrequest]);
        return $req->fetchColumn();
    }

    public function getBarRequest($idrequest) {
        $req = self::$bdd->prepare("Select request_content from request_tasks where id_request = :id_request;");
        $req->execute(['id_request' => $idrequest]);
        return $req->fetchColumn();
    }

    public function get_bar_name($id_bar) {
        $req = self::$bdd->prepare("SELECT nom FROM bar WHERE id_bar = :id_bar;");
        $req->execute(['id_bar' => $id_bar]);
        return $req->fetchColumn();
    }
}
?>