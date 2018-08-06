<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180802142955 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE participant ADD finish_date DATETIME DEFAULT NULL');
        $this->addSql('UPDATE user, participant SET participant.finish_date = DATE_FORMAT(FROM_UNIXTIME(user.finish_date), \'%Y-%m-%d  %H:%i:%s\') WHERE user.id = participant.user_id');
        $this->addSql('ALTER TABLE user DROP finish_date');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE participant DROP finish_date');
        $this->addSql('ALTER TABLE user ADD finish_date INT DEFAULT NULL');
    }
}
