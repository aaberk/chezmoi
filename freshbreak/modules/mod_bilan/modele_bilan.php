<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('connexion.php');

class Modele_bilan extends connexion {

    public function date_min()
    {
        $sql = 'SELECT MIN(date_commande) from commande ';
        $stmt = self::$bdd->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();

    }
    public function chiffre_affaire($date_debut, $date_fin, $id_bar) {
        $sql = 'SELECT sum(quantite*prix) from produit_commande inner join commande on produit_commande.id_commande = commande.id_commande WHERE bar_associe = :bar_associe AND date_commande >= :date_debut AND date_commande <= :date_fin;';
        $sql = self::$bdd->prepare($sql);
        $sql->execute(['bar_associe' => $id_bar, 'date_debut' => $date_debut, 'date_fin' => $date_fin]);
        return $sql->fetchColumn();

    }

    public function get_achat_produit($id_bar,$date_debut,$date_fin) {
        $req = self::$bdd->prepare("SELECT id_produit,quantite FROM achat_produit WHERE bar_associe = :bar_associe AND date_achat_produit >= :date_debut AND date_achat_produit <= :date_fin;");
        $req->execute(['bar_associe' => $id_bar, 'date_debut' => $date_debut, 'date_fin' => $date_fin]);
        return $req->fetchAll();
    }

    public function get_produit_prix($id_produit) {
        $req = self::$bdd->prepare("SELECT prix_achat FROM produit WHERE id_produit = :id_produit;");
        $req->execute(['id_produit' => $id_produit]);
        return $req->fetchColumn();
    }

    public function get_reapprovisionnement() {
        $req = self::$bdd->prepare("SELECT produit.nom_produit,SUM(quantite) FROM achat_produit NATURAL JOIN produit GROUP BY produit.nom_produit;");
        $req->execute();
        $result = $req->fetchAll();
        //var_dump($result);
        return $result;
    }

    public function get_diff_produit($id_bar,$date_debut,$date_fin,$produit){
        $sql = 'SELECT sum(quantite)
                    FROM produit_commande
                        INNER JOIN commande ON produit_commande.id_commande = commande.id_commande
                        INNER JOIN produit ON produit.id_produit = produit_commande.id_produit
                            WHERE bar_associe = :bar_associe
                                AND date_commande >= :date_debut
                                AND date_commande <= :date_fin
                                AND produit_commande.id_produit =:produit;';
        $sql = self::$bdd->prepare($sql);
        $sql->execute(['bar_associe' => $id_bar, 'date_debut' => $date_debut, 'date_fin' => $date_fin, 'produit'=>$produit]);
        return $sql->fetchAll();
    }

    public function get_dispo($id_bar){
        $req = self::$bdd->prepare("SELECT id_produit FROM disponibilite WHERE bar_associe = :id_bar;");
        $req->execute(['id_bar' => $id_bar]);
        return $req->fetchall();

    }

    public function get_produit($id_produit){
        $req = self::$bdd->prepare("SELECT nom_produit FROM produit WHERE id_produit = :id_produit;");
        $req->execute(['id_produit' => $id_produit]);
        return $req->fetchColumn();

    }
}
?>