<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180802120104 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // Add new statuses
        $this->addSql('INSERT INTO status (name) VALUE (\'Pending\')');
        $this->addSql('INSERT INTO status (name) VALUE (\'Open\')');
        $this->addSql('INSERT INTO status (name) VALUE (\'Closed\')');

        // Create first marathon. Add Association Mapping ManyToMany between Challenge and User
        $this->addSql('INSERT INTO challenge (name, author, description, alias, start_date, end_date, daily_goal, challenge_goal, status_id) VALUES ("Push Up Challenge 2018", "Bronislav Gromm", "Push up marathon", "push_up", "2018-07-12 00:00:00", "2018-08-10 00:00:00", 100, 3000, (select id from status where name = "Open"))');
        $this->addSql('INSERT INTO participant (challenge_id, user_id) SELECT (select min(id) from challenge), id  FROM user where roles LIKE \'%ROLE_USER%\'');

        // Bind Day and Participant
        $this->addSql('ALTER TABLE day DROP FOREIGN KEY FK_E5A02990A76ED395');
        $this->addSql('DROP INDEX IDX_E5A02990A76ED395 ON day');
        $this->addSql('UPDATE day, participant SET day.user_id = participant.id WHERE day.user_id = participant.user_id');
        $this->addSql('ALTER TABLE day CHANGE user_id participant_id INT NOT NULL');
        $this->addSql('ALTER TABLE day ADD CONSTRAINT FK_E5A029909D1C3019 FOREIGN KEY (participant_id) REFERENCES participant (id)');
        $this->addSql('CREATE INDEX IDX_E5A029909D1C3019 ON day (participant_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
