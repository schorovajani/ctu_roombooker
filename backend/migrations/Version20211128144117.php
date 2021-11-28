<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211128144117 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Initial migrations';
	}

	public function up(Schema $schema): void
	{
		$this->addSql('CREATE SEQUENCE building_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
		$this->addSql('CREATE SEQUENCE request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
		$this->addSql('CREATE SEQUENCE role_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
		$this->addSql('CREATE SEQUENCE room_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
		$this->addSql('CREATE SEQUENCE status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
		$this->addSql('CREATE SEQUENCE team_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
		$this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
		$this->addSql('CREATE TABLE building (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
		$this->addSql('CREATE TABLE request (id INT NOT NULL, room_id INT NOT NULL, user_id INT NOT NULL, status_id INT NOT NULL, description VARCHAR(500) NOT NULL, event_start TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, event_end TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
		$this->addSql('CREATE INDEX IDX_REQUEST_ROOM_ID ON request (room_id)');
		$this->addSql('CREATE INDEX IDX_REQUEST_USER_ID ON request (user_id)');
		$this->addSql('CREATE INDEX IDX_REQUEST_STATUS_ID ON request (status_id)');
		$this->addSql('CREATE TABLE attendee (request_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(request_id, user_id))');
		$this->addSql('CREATE INDEX IDX_ATTENDEE_REQUEST_ID ON attendee (request_id)');
		$this->addSql('CREATE INDEX IDX_ATTENDEE_USER_ID ON attendee (user_id)');
		$this->addSql('CREATE TABLE role_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
		$this->addSql('CREATE TABLE room (id INT NOT NULL, building_id INT NOT NULL, team_id INT NOT NULL, name VARCHAR(255) NOT NULL, is_public BOOLEAN NOT NULL, is_locked BOOLEAN NOT NULL, PRIMARY KEY(id))');
		$this->addSql('CREATE INDEX IDX_ROOM_BUILDING_ID ON room (building_id)');
		$this->addSql('CREATE INDEX IDX_ROOM_TEAM_ID ON room (team_id)');
		$this->addSql('CREATE TABLE room_role (user_id INT NOT NULL, room_id INT NOT NULL, role_type_id INT NOT NULL, PRIMARY KEY(user_id, room_id))');
		$this->addSql('CREATE INDEX IDX_ROOM_ROLE_USER_ID ON room_role (user_id)');
		$this->addSql('CREATE INDEX IDX_ROOM_ROLE_ROOM_ID ON room_role (room_id)');
		$this->addSql('CREATE INDEX IDX_ROOM_ROLE_ROLE_TYPE_ID ON room_role (role_type_id)');
		$this->addSql('CREATE TABLE status (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
		$this->addSql('CREATE TABLE team (id INT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
		$this->addSql('CREATE INDEX IDX_TEAM_PARENT_ID ON team (parent_id)');
		$this->addSql('CREATE TABLE team_role (user_id INT NOT NULL, team_id INT NOT NULL, role_type_id INT NOT NULL, PRIMARY KEY(user_id, team_id))');
		$this->addSql('CREATE INDEX IDX_TEAM_ROLE_USER_ID ON team_role (user_id)');
		$this->addSql('CREATE INDEX IDX_TEAM_ROLE_TEAM_ID ON team_role (team_id)');
		$this->addSql('CREATE INDEX IDX_TEAM_ROLE_ROLE_TYPE_ID ON team_role (role_type_id)');
		$this->addSql('CREATE TABLE "user" (id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, is_admin BOOLEAN NOT NULL, PRIMARY KEY(id))');
		$this->addSql('ALTER TABLE request ADD CONSTRAINT FK_REQUEST_ROOM_ID FOREIGN KEY (room_id) REFERENCES room (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE request ADD CONSTRAINT FK_REQUEST_USER_ID FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE request ADD CONSTRAINT FK_REQUEST_STATUS_ID FOREIGN KEY (status_id) REFERENCES status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE attendee ADD CONSTRAINT FK_ATTENDEE_REQUEST_ID FOREIGN KEY (request_id) REFERENCES request (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE attendee ADD CONSTRAINT FK_ATTENDEE_USER_ID FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE room ADD CONSTRAINT FK_ROOM_BUILDING_ID FOREIGN KEY (building_id) REFERENCES building (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE room ADD CONSTRAINT FK_ROOM_TEAM_ID FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE room_role ADD CONSTRAINT FK_ROOM_ROLE_USER_ID FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE room_role ADD CONSTRAINT FK_ROOM_ROLE_ROOM_ID FOREIGN KEY (room_id) REFERENCES room (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE room_role ADD CONSTRAINT FK_ROOM_ROLE_ROLE_TYPE_ID FOREIGN KEY (role_type_id) REFERENCES role_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE team ADD CONSTRAINT FK_TEAM_PARENT_ID FOREIGN KEY (parent_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE team_role ADD CONSTRAINT FK_TEAM_ROLE_USER_ID FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE team_role ADD CONSTRAINT FK_TEAM_ROLE_TEAM_ID FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE team_role ADD CONSTRAINT FK_TEAM_ROLE_ROLE_TYPE_ID FOREIGN KEY (role_type_id) REFERENCES role_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
	}

	public function down(Schema $schema): void
	{
		$this->addSql('CREATE SCHEMA public');
		$this->addSql('ALTER TABLE room DROP CONSTRAINT FK_ROOM_BUILDING_ID');
		$this->addSql('ALTER TABLE attendee DROP CONSTRAINT FK_ATTENDEE_REQUEST_ID');
		$this->addSql('ALTER TABLE room_role DROP CONSTRAINT FK_ROOM_ROLE_ROLE_TYPE_ID');
		$this->addSql('ALTER TABLE team_role DROP CONSTRAINT FK_TEAM_ROLE_ROLE_TYPE_ID');
		$this->addSql('ALTER TABLE request DROP CONSTRAINT FK_REQUEST_ROOM_ID');
		$this->addSql('ALTER TABLE room_role DROP CONSTRAINT FK_ROOM_ROLE_ROOM_ID');
		$this->addSql('ALTER TABLE request DROP CONSTRAINT FK_REQUEST_STATUS_ID');
		$this->addSql('ALTER TABLE room DROP CONSTRAINT FK_ROOM_TEAM_ID');
		$this->addSql('ALTER TABLE team DROP CONSTRAINT FK_TEAM_PARENT_ID');
		$this->addSql('ALTER TABLE team_role DROP CONSTRAINT FK_TEAM_ROLE_TEAM_ID');
		$this->addSql('ALTER TABLE request DROP CONSTRAINT FK_REQUEST_USER_ID');
		$this->addSql('ALTER TABLE attendee DROP CONSTRAINT FK_ATTENDEE_USER_ID');
		$this->addSql('ALTER TABLE room_role DROP CONSTRAINT FK_ROOM_ROLE_USER_ID');
		$this->addSql('ALTER TABLE team_role DROP CONSTRAINT FK_TEAM_ROLE_USER_ID');
		$this->addSql('DROP SEQUENCE building_id_seq CASCADE');
		$this->addSql('DROP SEQUENCE request_id_seq CASCADE');
		$this->addSql('DROP SEQUENCE role_type_id_seq CASCADE');
		$this->addSql('DROP SEQUENCE room_id_seq CASCADE');
		$this->addSql('DROP SEQUENCE status_id_seq CASCADE');
		$this->addSql('DROP SEQUENCE team_id_seq CASCADE');
		$this->addSql('DROP SEQUENCE user_id_seq CASCADE');
		$this->addSql('DROP TABLE building');
		$this->addSql('DROP TABLE request');
		$this->addSql('DROP TABLE attendee');
		$this->addSql('DROP TABLE role_type');
		$this->addSql('DROP TABLE room');
		$this->addSql('DROP TABLE room_role');
		$this->addSql('DROP TABLE status');
		$this->addSql('DROP TABLE team');
		$this->addSql('DROP TABLE team_role');
		$this->addSql('DROP TABLE "user"');
	}
}
