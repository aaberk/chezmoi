ALTER TABLE achat_produit
DROP FOREIGN KEY fk_achat_produit_produit,
DROP FOREIGN KEY fk_achat_produit_bar;
ALTER TABLE achat_produit
DROP PRIMARY KEY;
ALTER TABLE achat_produit
    ADD PRIMARY KEY (id_produit, bar_associe, date_achat_produit);
ALTER TABLE achat_produit
    ADD CONSTRAINT fk_achat_produit_produit
        FOREIGN KEY (id_produit) REFERENCES produit(id_produit)
            ON DELETE RESTRICT ON UPDATE RESTRICT,
ADD CONSTRAINT fk_achat_produit_bar
    FOREIGN KEY (bar_associe) REFERENCES bar(id_bar)
    ON DELETE RESTRICT ON UPDATE RESTRICT;
