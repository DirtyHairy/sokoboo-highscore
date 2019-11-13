<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191110195927 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Create table score_entry';
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE score_entry (id INT AUTO_INCREMENT NOT NULL, level INT NOT NULL, moves INT NOT NULL, seconds INT NOT NULL, nick VARCHAR(20) NOT NULL, code VARCHAR(12) NOT NULL, timestamp INT NOT NULL, ip VARCHAR(15) DEFAULT NULL, session VARCHAR(36) DEFAULT NULL, INDEX level_idx (level), INDEX nick_idx (nick), INDEX moves_seconds_timestamp_idx (moves, seconds, timestamp), UNIQUE INDEX code_nick_unqiue (nick, code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     * @throws DBALException
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE score_entry');
    }
}
