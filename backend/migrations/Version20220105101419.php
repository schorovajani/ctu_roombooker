<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220105101419 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Add more rooms, admin owns one request';
	}

	public function up(Schema $schema): void
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->addSql("INSERT INTO room (id, building_id, team_id, name, is_public, is_locked) VALUES (nextval('room_id_seq'), 1, 1, 'A-1177', true, true)");
		$this->addSql("INSERT INTO room (id, building_id, team_id, name, is_public, is_locked) VALUES (nextval('room_id_seq'), 1, 1, 'A-1333', false, true)");

		$this->addSql("UPDATE request SET user_id = 1 WHERE id = 3");
	}

	public function down(Schema $schema): void
	{
		// this down() migration is auto-generated, please modify it to your needs
	}
}
