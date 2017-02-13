<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170209205300 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `match`
            (
                id INT UNSIGNED AUTO_INCREMENT,
                homeTeamId INT UNSIGNED DEFAULT NULL,
                homeTeamScore INT DEFAULT NULL,
                awayTeamId INT UNSIGNED DEFAULT NULL,
                awayTeamScore INT DEFAULT NULL,
                dateTimeOfMatch DATETIME DEFAULT NULL,
                createdAt DATETIME NOT NULL,
                updatedAt DATETIME DEFAULT NULL,
                PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8
            COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
        $this->addSql('CREATE INDEX idx_7a5bc5055e3c9f6b ON `match` (homeTeamId)');
        $this->addSql('CREATE INDEX idx_7a5bc50542e0b73d ON `match` (awayTeamId)');

        $this->addSql('
            CREATE TABLE team (
              id INT UNSIGNED AUTO_INCREMENT,
              name VARCHAR(250) NOT NULL,
              createdAt DATETIME NOT NULL,
              updatedAt DATETIME DEFAULT NULL,
              PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8
            COLLATE utf8_unicode_ci ENGINE = InnoDB
        ');
        $this->addSql('CREATE INDEX name ON team (name)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE `match`');
        $this->addSql('DROP TABLE team');
    }
}
