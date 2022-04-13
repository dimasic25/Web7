<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220410220256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C5FB1909');
        $this->addSql('DROP INDEX IDX_9474526C5FB1909 ON comment');
        $this->addSql('ALTER TABLE comment CHANGE news_id_id news_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CB5A459A0 FOREIGN KEY (news_id) REFERENCES news (id)');
        $this->addSql('CREATE INDEX IDX_9474526CB5A459A0 ON comment (news_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CB5A459A0');
        $this->addSql('DROP INDEX IDX_9474526CB5A459A0 ON comment');
        $this->addSql('ALTER TABLE comment CHANGE news_id news_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C5FB1909 FOREIGN KEY (news_id_id) REFERENCES news (id)');
        $this->addSql('CREATE INDEX IDX_9474526C5FB1909 ON comment (news_id_id)');
    }
}
