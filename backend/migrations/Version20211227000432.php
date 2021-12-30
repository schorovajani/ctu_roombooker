<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211227000432 extends AbstractMigration
{
	public function getDescription(): string
	{
		return 'Add account entity';
	}

	public function up(Schema $schema): void
	{
		// this up() migration is auto-generated, please modify it to your needs
		$this->addSql('CREATE SEQUENCE account_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
		$this->addSql('CREATE TABLE account (id INT NOT NULL, owner_id INT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
		$this->addSql('CREATE UNIQUE INDEX UNIQ_ACCOUNT_USERNAME ON account (username)');
		$this->addSql('CREATE INDEX IDX_ACCOUNT_OWNER_ID ON account (owner_id)');
		$this->addSql('ALTER TABLE account ADD CONSTRAINT FK_ACCOUNT_OWNER_ID FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
	}

	public function down(Schema $schema): void
	{
		// this down() migration is auto-generated, please modify it to your needs
		$this->addSql('DROP SEQUENCE account_id_seq CASCADE');
		$this->addSql('DROP TABLE account');
	}
}
