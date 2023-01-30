<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230122094410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE messenger DROP FOREIGN KEY messenger_ibfk_1');
        $this->addSql('ALTER TABLE messenger CHANGE id_users id_users INT DEFAULT NULL');
        $this->addSql('ALTER TABLE messenger ADD CONSTRAINT FK_E22A4301FA06E4D9 FOREIGN KEY (id_users) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE messenger DROP FOREIGN KEY FK_E22A4301FA06E4D9');
        $this->addSql('ALTER TABLE messenger CHANGE id_users id_users INT NOT NULL');
        $this->addSql('ALTER TABLE messenger ADD CONSTRAINT messenger_ibfk_1 FOREIGN KEY (id_users) REFERENCES user (id) ON UPDATE CASCADE');
    }
}
