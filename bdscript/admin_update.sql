SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `admin` (
	`login` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `request_tasks` (
	`id_request` bigint UNSIGNED NOT NULL,
	`login_tasked_user` varchar(30) NOT NULL,
	`login_request_user` varchar(30) NOT NULL,
	`request_content` varchar(215) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `inbox` (
	`id_message` bigint UNSIGNED NOT NULL,
	`user_login` varchar(30) NOT NULL,
	`message_type` smallint NOT NULL,
	`msg_arguments` varchar(225) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `admin`
	ADD PRIMARY KEY (`login`);

ALTER TABLE `request_tasks`
	ADD PRIMARY KEY (`id_request`),
	ADD UNIQUE KEY `id_request` (`id_request`);

ALTER TABLE `inbox`
	ADD PRIMARY KEY (`id_message`),
	ADD UNIQUE KEY `id_message` (`id_message`);

ALTER TABLE `request_tasks`
	MODIFY `id_request` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `inbox`
	MODIFY `id_message` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `admin`
        ADD CONSTRAINT `fk_admin_utilisateur` FOREIGN KEY (`login`) REFERENCES `utilisateur` (`login`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `request_tasks`
        ADD CONSTRAINT `fk_request_tasks_tasked_user_utilisateur` FOREIGN KEY (`login_tasked_user`) REFERENCES `utilisateur` (`login`) ON DELETE RESTRICT ON UPDATE RESTRICT,
	ADD CONSTRAINT `fk_request_tasks_utilisateur` FOREIGN KEY (`login_request_user`) REFERENCES `utilisateur` (`login`) ON DELETE RESTRICT ON UPDATE RESTRICT;

ALTER TABLE `inbox`
        ADD CONSTRAINT `fk_inbox_utilisateur` FOREIGN KEY (`user_login`) REFERENCES `utilisateur` (`login`) ON DELETE RESTRICT ON UPDATE RESTRICT;

RENAME TABLE `stock` TO `disponibilite`;

--à insérer un fois que vous avez créé le compte admin manuellement
--INSERT INTO `admin` (`login`) VALUES ('admin');

COMMIT;
