<?php
if (!defined('APP_SECURE')) die('Accès interdit.');
require_once('connexion.php');

class Modele_inventaire_manuel extends connexion {
    public function new_inventaire($id_bar,$date) {
        $req = self::$bdd->prepare("INSERT INTO inventaire_manuel (date_inventaire,bar_associe) VALUES 
                                                                              (:date_inventaire,:bar_associe);");
        $req->execute(['date_inventaire' => $date, 'bar_associe' => $id_bar]);
        return self::$bdd->lastInsertId();
    }

    public function get_nom_produits_from_availability($id_bar) {
        $req = self::$bdd->prepare("SELECT nom_produit,produit.id_produit FROM produit NATURAL JOIN disponibilite WHERE bar_associe = :bar_associe;");
        $req->execute(['bar_associe' => $id_bar]);
        return $req->fetchAll();
    }

    public function add_product_inventory($id_produit,$id_inventaire,$quantite) {
        $req = self::$bdd->prepare("INSERT INTO produit_inventaire (id_produit_inventaire,id_inventaire,quantite) VALUES
                                                                                  (:id_produit,:id_inventaire,:quantite);");
        $req->execute(['id_produit' => $id_produit, 'id_inventaire' => $id_inventaire, 'quantite' => $quantite]);
    }

    public function get_stock_from_availability($id_bar) {
        $req = self::$bdd->prepare("SELECT quantite,produit.nom_produit FROM disponibilite NATURAL JOIN produit WHERE bar_associe = :bar_associe;");
        $req->execute(['bar_associe' => $id_bar]);
        return $req->fetchAll();
    }

    public function get_stock_from_inventory($id_inventaire) {
        $req = self::$bdd->prepare("SELECT id_produit_inventaire,quantite FROM produit_inventaire WHERE id_inventaire = :id_inventaire;");
        $req->execute(['id_inventaire' => $id_inventaire]);
        return $req->fetchAll();
    }

    public function update_availability($id_produit,$id_bar,$new_quantite) {
        $req = self::$bdd->prepare("UPDATE disponibilite SET quantite = :new_quantite WHERE bar_associe = :bar_associe AND id_produit = :id_produit;");
        $req->execute(['new_quantite' => $new_quantite, 'bar_associe' => $id_bar, 'id_produit' => $id_produit]);
    }
}
?>