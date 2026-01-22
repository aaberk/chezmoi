<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('connexion.php');


class ModeleCreationBuvettes extends connexion {
    public function nomexist($nom) {
        $req = self::$bdd->prepare("SELECT COUNT(*) FROM bar WHERE nom = :nom");
        $req->bindParam(':nom', $nom);
        $req->execute();
        return $req->fetchColumn() > 0;
    }
    public function ajouterBar($nom,$gerant) {
        $req = self::$bdd->prepare("INSERT INTO bar (nom) VALUES (:nom)");
        $req->bindParam(':nom', $nom);
        $req->execute();

        $idBar = self::$bdd->lastInsertId();

        $req = self::$bdd->prepare(" INSERT INTO role (login_utilisateur, bar_associe, role_bar) 
                                    VALUES (:login_utilisateur, :bar_associe, 'gérant')");
        $req->bindParam(':login_utilisateur', $gerant);
        $req->bindParam(':bar_associe', $idBar);
        $req->execute();
    }

    public function new_request($login_request_user,$request_content) {
        $req_insert = self::$bdd->prepare("INSERT INTO request_tasks (login_tasked_user,login_request_user,request_content) VALUES
                                                                                                  (:login_tasked_user,:login_request_user,:request_content);");
        $req_insert->execute(['login_tasked_user' => 'admin','login_request_user' => $login_request_user,'request_content' => $request_content]);
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
        $req = self::$bdd->prepare("DELETE FROM inbox WHERE message_type = 1 AND msg_arguments = :msg_arguments");
        $req->execute(['msg_arguments' => $request_id]);
    }

    public function check_request_exists($nom) {
        $req = self::$bdd->prepare("SELECT COUNT(*) FROM request_tasks WHERE request_content = :nom;");
        $req->execute(['nom' => $nom]);
        return $req->fetchColumn() > 0;
    }
}
