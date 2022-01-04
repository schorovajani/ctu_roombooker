<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220104100722 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Add testing data';
	}

	public function up(Schema $schema): void
	{
		$this->addSql("DELETE FROM attendee");
		$this->addSql("DELETE FROM request");
		$this->addSql("DELETE FROM room_role");
		$this->addSql("DELETE FROM team_role");
		$this->addSql("DELETE FROM account");
		$this->addSql("DELETE FROM \"user\"");

		$this->addSql("ALTER SEQUENCE request_id_seq RESTART WITH 1");
		$this->addSql("ALTER SEQUENCE account_id_seq RESTART WITH 1");
		$this->addSql("ALTER SEQUENCE user_id_seq RESTART WITH 1");

		$this->addSql("INSERT INTO \"user\" (id, first_name, last_name) VALUES (nextval('user_id_seq'), 'Tomáš', 'Dvořák')");
		$this->addSql("INSERT INTO \"user\" (id, first_name, last_name) VALUES (nextval('user_id_seq'), 'Jirka', 'Skalický')");
		$this->addSql("INSERT INTO \"user\" (id, first_name, last_name) VALUES (nextval('user_id_seq'), 'Iva', 'Nováková')");
		$this->addSql("INSERT INTO \"user\" (id, first_name, last_name) VALUES (nextval('user_id_seq'), 'Lucka', 'Štěpánová')");
		$this->addSql("INSERT INTO \"user\" (id, first_name, last_name) VALUES (nextval('user_id_seq'), 'Matěj', 'Motyčka')");

		$this->addSql("INSERT INTO account (id, owner_id, username, roles, password) VALUES (nextval('account_id_seq'), 1, 'Admin', '[\"ROLE_ADMIN\"]', '$2y$13\$v/WSC.cPFS.f0mgPJEJSy.bEu5zBwn0q8Qb6STXEprNDChqyIEBTy')");
		$this->addSql("INSERT INTO account (id, owner_id, username, roles, password) VALUES (nextval('account_id_seq'), 2, 'TeamManager', '[]', '$2y$13\$xoPXpYeD6fha7zUM3km3Uu38fvaKpXL9ZO8lpXtXSvwQXtW1ZYfki')");
		$this->addSql("INSERT INTO account (id, owner_id, username, roles, password) VALUES (nextval('account_id_seq'), 3, 'RoomManager', '[]', '$2y$13$19DGDmJEwxG4LuP4ZUa.uueIEPHxXGsSDn4hQ2LgS2tt5Ip2Tz.Wq')");
		$this->addSql("INSERT INTO account (id, owner_id, username, roles, password) VALUES (nextval('account_id_seq'), 4, 'TeamMember', '[]', '$2y$13$8JPAfYIsOzKyW90svROfFOMbk7L0BuAsMLCHPzfexUZFPPbtTP20y')");
		$this->addSql("INSERT INTO account (id, owner_id, username, roles, password) VALUES (nextval('account_id_seq'), 5, 'RoomMember', '[]', '$2y$13\$kN6wuC1J2CktjZPnm88xt.Gv5m5HsD5zh3NDrGTgC7yTZ.kgW.lWK')");

		$this->addSql("INSERT INTO team_role (user_id, team_id, role_type_id) VALUES (2, 1, 1)");
		$this->addSql("INSERT INTO team_role (user_id, team_id, role_type_id) VALUES (2, 4, 2)");
		$this->addSql("INSERT INTO team_role (user_id, team_id, role_type_id) VALUES (3, 2, 2)");
		$this->addSql("INSERT INTO team_role (user_id, team_id, role_type_id) VALUES (3, 4, 2)");
		$this->addSql("INSERT INTO team_role (user_id, team_id, role_type_id) VALUES (4, 2, 2)");
		$this->addSql("INSERT INTO team_role (user_id, team_id, role_type_id) VALUES (4, 3, 2)");
		$this->addSql("INSERT INTO team_role (user_id, team_id, role_type_id) VALUES (4, 4, 2)");

		$this->addSql("INSERT INTO room_role (user_id, room_id, role_type_id) VALUES (3, 3, 1)");
		$this->addSql("INSERT INTO room_role (user_id, room_id, role_type_id) VALUES (3, 7, 1)");
		$this->addSql("INSERT INTO room_role (user_id, room_id, role_type_id) VALUES (5, 3, 2)");
		$this->addSql("INSERT INTO room_role (user_id, room_id, role_type_id) VALUES (5, 4, 2)");
		$this->addSql("INSERT INTO room_role (user_id, room_id, role_type_id) VALUES (5, 5, 2)");
		$this->addSql("INSERT INTO room_role (user_id, room_id, role_type_id) VALUES (5, 6, 2)");
		$this->addSql("INSERT INTO room_role (user_id, room_id, role_type_id) VALUES (5, 7, 2)");

		$this->addSql("INSERT INTO request (id, room_id, user_id, status_id, description, event_start, event_end) VALUES (nextval('request_id_seq'), 3, 5, 1, 'Consultation BI-TWA', '2022-01-05 10:00', '2022-01-05 11:30')");
		$this->addSql("INSERT INTO request (id, room_id, user_id, status_id, description, event_start, event_end) VALUES (nextval('request_id_seq'), 3, 3, 2, 'Consultation BI-BIG', '2022-01-05 12:00', '2022-01-05 14:30')");
		$this->addSql("INSERT INTO request (id, room_id, user_id, status_id, description, event_start, event_end) VALUES (nextval('request_id_seq'), 4, 3, 2, 'Consultation BI-OOP', '2022-01-05 9:00', '2022-01-05 10:30')");
		$this->addSql("INSERT INTO request (id, room_id, user_id, status_id, description, event_start, event_end) VALUES (nextval('request_id_seq'), 4, 2, 1, 'Consultation BI-PA1', '2022-01-05 11:30', '2022-01-05 13:00')");
		$this->addSql("INSERT INTO request (id, room_id, user_id, status_id, description, event_start, event_end) VALUES (nextval('request_id_seq'), 4, 2, 2, 'Consultation BI-VZD', '2022-01-05 14:00', '2022-01-05 15:30')");
		$this->addSql("INSERT INTO request (id, room_id, user_id, status_id, description, event_start, event_end) VALUES (nextval('request_id_seq'), 2, 2, 1, 'Consultation BI-LLA', '2022-01-05 13:30', '2022-01-05 16:00')");
		$this->addSql("INSERT INTO request (id, room_id, user_id, status_id, description, event_start, event_end) VALUES (nextval('request_id_seq'), 3, 5, 1, 'Consultation BI-TWA', '2022-01-06 9:30', '2022-01-06 10:30')");
		$this->addSql("INSERT INTO request (id, room_id, user_id, status_id, description, event_start, event_end) VALUES (nextval('request_id_seq'), 3, 4, 2, 'Consultation BI-MA1', '2022-01-06 12:00', '2022-01-06 14:00')");
		$this->addSql("INSERT INTO request (id, room_id, user_id, status_id, description, event_start, event_end) VALUES (nextval('request_id_seq'), 3, 5, 2, 'Consultation BI-MA2', '2022-01-06 16:00', '2022-01-06 17:30')");
		$this->addSql("INSERT INTO request (id, room_id, user_id, status_id, description, event_start, event_end) VALUES (nextval('request_id_seq'), 4, 4, 2, 'Consultation BI-DBS', '2022-01-06 9:30', '2022-01-06 12:00')");
		$this->addSql("INSERT INTO request (id, room_id, user_id, status_id, description, event_start, event_end) VALUES (nextval('request_id_seq'), 4, 4, 1, 'Consultation BI-PA1', '2022-01-06 14:00', '2022-01-06 15:30')");
		$this->addSql("INSERT INTO request (id, room_id, user_id, status_id, description, event_start, event_end) VALUES (nextval('request_id_seq'), 1, 2, 1, 'Consultation BI-ULI', '2022-01-06 13:30', '2022-01-06 15:30')");

		$this->addSql("INSERT INTO attendee (request_id, user_id) VALUES (1, 1)");
		$this->addSql("INSERT INTO attendee (request_id, user_id) VALUES (1, 2)");
		$this->addSql("INSERT INTO attendee (request_id, user_id) VALUES (2, 1)");
		$this->addSql("INSERT INTO attendee (request_id, user_id) VALUES (2, 2)");
		$this->addSql("INSERT INTO attendee (request_id, user_id) VALUES (2, 4)");
		$this->addSql("INSERT INTO attendee (request_id, user_id) VALUES (3, 5)");
		$this->addSql("INSERT INTO attendee (request_id, user_id) VALUES (4, 3)");
		$this->addSql("INSERT INTO attendee (request_id, user_id) VALUES (5, 1)");
		$this->addSql("INSERT INTO attendee (request_id, user_id) VALUES (5, 4)");
		$this->addSql("INSERT INTO attendee (request_id, user_id) VALUES (6, 5)");
		$this->addSql("INSERT INTO attendee (request_id, user_id) VALUES (7, 1)");
		$this->addSql("INSERT INTO attendee (request_id, user_id) VALUES (7, 2)");
		$this->addSql("INSERT INTO attendee (request_id, user_id) VALUES (8, 3)");
		$this->addSql("INSERT INTO attendee (request_id, user_id) VALUES (10, 2)");
		$this->addSql("INSERT INTO attendee (request_id, user_id) VALUES (10, 3)");
		$this->addSql("INSERT INTO attendee (request_id, user_id) VALUES (11, 1)");
		$this->addSql("INSERT INTO attendee (request_id, user_id) VALUES (11, 2)");
		$this->addSql("INSERT INTO attendee (request_id, user_id) VALUES (11, 3)");
		$this->addSql("INSERT INTO attendee (request_id, user_id) VALUES (12, 5)");
	}

	public function down(Schema $schema): void
	{
		// this down() migration is auto-generated, please modify it to your needs
	}
}
