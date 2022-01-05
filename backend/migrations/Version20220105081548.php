<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220105081548 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Add rooms, add CardReader account, change requests description';
	}

	public function up(Schema $schema): void
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->addSql("INSERT INTO room (id, building_id, team_id, name, is_public, is_locked) VALUES (nextval('room_id_seq'), 1, 1, 'A-1324', true, true)");
		$this->addSql("INSERT INTO room (id, building_id, team_id, name, is_public, is_locked) VALUES (nextval('room_id_seq'), 1, 1, 'A-1236', false, true)");
		$this->addSql("INSERT INTO room (id, building_id, team_id, name, is_public, is_locked) VALUES (nextval('room_id_seq'), 1, 2, 'A-1124', false, true)");
		$this->addSql("INSERT INTO room (id, building_id, team_id, name, is_public, is_locked) VALUES (nextval('room_id_seq'), 1, 2, 'A-1222', true, true)");

		$this->addSql("INSERT INTO account (id, owner_id, username, roles, password) VALUES (nextval('account_id_seq'), 1, 'CardReader', '[]', '$2y$13\$wcBGrh4P6ougxiFdwoLApO82V5hLwCnTERiA1bC6XLb2RJBOvlFTK')");

		$this->addSql("UPDATE request SET description = 'Konzultace BI-TWA' WHERE id = 1");
		$this->addSql("UPDATE request SET description = 'Konzultace BI-BIG' WHERE id = 2");
		$this->addSql("UPDATE request SET description = 'Konzultace BI-OOP' WHERE id = 3");
		$this->addSql("UPDATE request SET description = 'Konzultace BI-PA1' WHERE id = 4");
		$this->addSql("UPDATE request SET description = 'Konzultace BI-VZD' WHERE id = 5");
		$this->addSql("UPDATE request SET description = 'Konzultace BI-LLA' WHERE id = 6");
		$this->addSql("UPDATE request SET description = 'Konzultace BI-TWA' WHERE id = 7");
		$this->addSql("UPDATE request SET description = 'Konzultace BI-MA1' WHERE id = 8");
		$this->addSql("UPDATE request SET description = 'Konzultace BI-MA2' WHERE id = 9");
		$this->addSql("UPDATE request SET description = 'Konzultace BI-DBS' WHERE id = 10");
		$this->addSql("UPDATE request SET description = 'Konzultace BI-PA1' WHERE id = 11");
		$this->addSql("UPDATE request SET description = 'Konzultace BI-ULI' WHERE id = 12");
	}

	public function down(Schema $schema): void
	{
		// this down() migration is auto-generated, please modify it to your needs
	}
}
