<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180807124834 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('INSERT INTO user (name, email, password, api_key, roles) (SELECT voter.voting_name, voter.voting_email, voter.voting_email, voter.voting_email, \'a:1:{i:0;s:9:"ROLE_USER";}\' FROM voter where voter.voting_email not in (SELECT email FROM user))');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622EBB4B8AD');
        $this->addSql('CREATE TABLE vote (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, voting_name VARCHAR(255) NOT NULL, voting_email VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5A1085642BF46331 (voting_email), INDEX IDX_5A108564A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A108564A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('INSERT INTO vote (id, user_id, voting_name, voting_email) (SELECT voter.id, user.id, voter.voting_name, voter.voting_email FROM voter JOIN user on user.email = voter.voting_email)');
        $this->addSql('DROP TABLE voter');
        $this->addSql('DROP INDEX IDX_D8892622EBB4B8AD ON rating');
        $this->addSql('ALTER TABLE rating CHANGE voter_id vote_id INT NOT NULL');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262272DCDAFC FOREIGN KEY (vote_id) REFERENCES vote (id)');
        $this->addSql('CREATE INDEX IDX_D889262272DCDAFC ON rating (vote_id)');
        $this->addSql('DROP INDEX UNIQ_5A1085642BF46331 ON vote');
        $this->addSql('ALTER TABLE vote DROP voting_name, DROP voting_email');
        $this->addSql('ALTER TABLE vote ADD challenge_id INT, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('UPDATE vote set challenge_id = (SELECT min(id) FROM challenge)');
        $this->addSql('ALTER TABLE vote CHANGE challenge_id challenge_id INT NOT NULL');
        $this->addSql('ALTER TABLE vote ADD CONSTRAINT FK_5A10856498A21AC6 FOREIGN KEY (challenge_id) REFERENCES challenge (id)');
        $this->addSql('CREATE INDEX IDX_5A10856498A21AC6 ON vote (challenge_id)');
        $this->addSql('ALTER TABLE vote ADD date DATETIME');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D889262272DCDAFC');
        $this->addSql('CREATE TABLE voter (id INT AUTO_INCREMENT NOT NULL, voting_name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, voting_email VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_268C4A592BF46331 (voting_email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE vote');
        $this->addSql('DROP INDEX IDX_D889262272DCDAFC ON rating');
        $this->addSql('ALTER TABLE rating CHANGE vote_id voter_id INT NOT NULL');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622EBB4B8AD FOREIGN KEY (voter_id) REFERENCES voter (id)');
        $this->addSql('CREATE INDEX IDX_D8892622EBB4B8AD ON rating (voter_id)');
        $this->addSql('ALTER TABLE vote ADD voting_name VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD voting_email VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A1085642BF46331 ON vote (voting_email)');
        $this->addSql('ALTER TABLE vote DROP FOREIGN KEY FK_5A10856498A21AC6');
        $this->addSql('DROP INDEX IDX_5A10856498A21AC6 ON vote');
        $this->addSql('ALTER TABLE vote DROP challenge_id, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vote DROP date');
    }
}
