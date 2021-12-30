<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211227010128 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Add accounts for testing';
	}

	public function up(Schema $schema): void
	{
		// Password is same as the username
		$this->addSql("INSERT INTO account (id, owner_id, username, roles, password) VALUES (nextval('account_id_seq'), 1, 'tomas', '[\"ROLE_ADMIN\"]', '$2y$13\$i2Ay5DiFsQ6sAyqtO21YM.0LNJJ8qYIrlg49KEvDwkZPTaMe/LHpa')");
		$this->addSql("INSERT INTO account (id, owner_id, username, roles, password) VALUES (nextval('account_id_seq'), 2, 'eliska', '[\"ROLE_ADMIN\"]', '$2y$13\$ivMdw/2z3dg2RbcGV3/EvOAowhHryHKClB3wdwpJIjwQcnYrQbg3e')");
	}

	public function down(Schema $schema): void
	{
		$this->addSql('TRUNCATE TABLE account');
	}
}
