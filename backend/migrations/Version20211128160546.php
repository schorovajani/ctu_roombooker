<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211128160546 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Add testing data';
	}

	public function up(Schema $schema): void
	{
		$this->addSql("INSERT INTO \"user\" (id, first_name, last_name, is_admin) VALUES (nextval('user_id_seq'), 'Tomáš', 'Dvořák', true)");
		$this->addSql("INSERT INTO \"user\" (id, first_name, last_name, is_admin) VALUES (nextval('user_id_seq'), 'Eliška', 'Procházková', false)");
		$this->addSql("INSERT INTO \"user\" (id, first_name, last_name, is_admin) VALUES (nextval('user_id_seq'), 'Johan', 'Valenta', false)");
		$this->addSql("INSERT INTO \"user\" (id, first_name, last_name, is_admin) VALUES (nextval('user_id_seq'), 'Lucka', 'Šťastná', false)");

		$this->addSql("INSERT INTO team (id, parent_id, name) VALUES (nextval('team_id_seq'), null, 'Katedra softwarového inženýrství')");
		$this->addSql("INSERT INTO team (id, parent_id, name) VALUES (nextval('team_id_seq'), 1, 'Softwarové inženýrství ')");
		$this->addSql("INSERT INTO team (id, parent_id, name) VALUES (nextval('team_id_seq'), 1, 'Webové inženýrství ')");
		$this->addSql("INSERT INTO team (id, parent_id, name) VALUES (nextval('team_id_seq'), null, 'Katedra teoretické informatiky')");
		$this->addSql("INSERT INTO team (id, parent_id, name) VALUES (nextval('team_id_seq'), 3, 'Tvorba webových aplikací')");

		$this->addSql("INSERT INTO building (id, name) VALUES (nextval('building_id_seq'), 'TH')");
		$this->addSql("INSERT INTO building (id, name) VALUES (nextval('building_id_seq'), 'T9')");

		$this->addSql("INSERT INTO room (id, building_id, team_id, name, is_public, is_locked) VALUES (nextval('room_id_seq'), 1, 1, 'Laboratoř otevřených dat', true, true)");
		$this->addSql("INSERT INTO room (id, building_id, team_id, name, is_public, is_locked) VALUES (nextval('room_id_seq'), 1, 1, 'Laboratoř 3D tisku', false, true)");
		$this->addSql("INSERT INTO room (id, building_id, team_id, name, is_public, is_locked) VALUES (nextval('room_id_seq'), 2, 2, '349', true, true)");
		$this->addSql("INSERT INTO room (id, building_id, team_id, name, is_public, is_locked) VALUES (nextval('room_id_seq'), 2, 2, '350', true, true)");
		$this->addSql("INSERT INTO room (id, building_id, team_id, name, is_public, is_locked) VALUES (nextval('room_id_seq'), 2, 3, '351', true, true)");
		$this->addSql("INSERT INTO room (id, building_id, team_id, name, is_public, is_locked) VALUES (nextval('room_id_seq'), 2, 3, '352', true, true)");
		$this->addSql("INSERT INTO room (id, building_id, team_id, name, is_public, is_locked) VALUES (nextval('room_id_seq'), 2, 5, '353', true, true)");

		$this->addSql("INSERT INTO role_type (id, name) VALUES (nextval('role_type_id_seq'), 'Manager')");
		$this->addSql("INSERT INTO role_type (id, name) VALUES (nextval('role_type_id_seq'), 'Member')");

		$this->addSql("INSERT INTO team_role (user_id, team_id, role_type_id) VALUES (2, 1, 1)");
		$this->addSql("INSERT INTO team_role (user_id, team_id, role_type_id) VALUES (3, 5, 1)");
		$this->addSql("INSERT INTO team_role (user_id, team_id, role_type_id) VALUES (3, 2, 2)");
		$this->addSql("INSERT INTO team_role (user_id, team_id, role_type_id) VALUES (3, 3, 2)");

		$this->addSql("INSERT INTO room_role (user_id, room_id, role_type_id) VALUES (3, 1, 1)");
		$this->addSql("INSERT INTO room_role (user_id, room_id, role_type_id) VALUES (3, 2, 2)");
		$this->addSql("INSERT INTO room_role (user_id, room_id, role_type_id) VALUES (4, 4, 1)");
		$this->addSql("INSERT INTO room_role (user_id, room_id, role_type_id) VALUES (4, 5, 2)");

		$this->addSql("INSERT INTO status (id, name) VALUES (nextval('status_id_seq'), 'Pending')");
		$this->addSql("INSERT INTO status (id, name) VALUES (nextval('status_id_seq'), 'Approved')");
		$this->addSql("INSERT INTO status (id, name) VALUES (nextval('status_id_seq'), 'Rejected')");

		$this->addSql("INSERT INTO request (id, room_id, user_id, status_id, description, event_start, event_end) VALUES (nextval('request_id_seq'), 3, 3, 1, 'Consultation BI-TWA', '2021-11-29 14:00', '2021-11-29 15:30')");
		$this->addSql("INSERT INTO request (id, room_id, user_id, status_id, description, event_start, event_end) VALUES (nextval('request_id_seq'), 5, 4, 1, 'Consultation', '2021-12-05 9:15', '2021-12-05 11:15')");

		$this->addSql("INSERT INTO attendee (request_id, user_id) VALUES (1, 1)");
		$this->addSql("INSERT INTO attendee (request_id, user_id) VALUES (1, 4)");
	}

	public function down(Schema $schema): void
	{
		$this->addSql('TRUNCATE TABLE attendee');
		$this->addSql('TRUNCATE TABLE request');
		$this->addSql('TRUNCATE TABLE status');
		$this->addSql('TRUNCATE TABLE room_role');
		$this->addSql('TRUNCATE TABLE team_role');
		$this->addSql('TRUNCATE TABLE role_type');
		$this->addSql('TRUNCATE TABLE room');
		$this->addSql('TRUNCATE TABLE building');
		$this->addSql('TRUNCATE TABLE team');
		$this->addSql('TRUNCATE TABLE user');
	}
}
