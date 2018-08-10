<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180810103900 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_8D93D649C912ED9D ON user');
        $this->addSql('ALTER TABLE user ADD full_name VARCHAR(255) DEFAULT NULL, DROP password, DROP api_key');
        $this->addSql('UPDATE user SET full_name = name');
        $this->addSql('UPDATE user SET name = SUBSTRING_INDEX(email, \'@\', 1)');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD password VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD api_key VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP full_name');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649C912ED9D ON user (api_key)');
    }
}
