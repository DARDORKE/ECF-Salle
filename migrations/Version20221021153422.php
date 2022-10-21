<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221021153422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6492534008B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499393F8FE');
        $this->addSql('DROP INDEX UNIQ_8D93D6499393F8FE ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D6492534008B ON user');
        $this->addSql('ALTER TABLE user ADD partner INT DEFAULT NULL, ADD structure INT DEFAULT NULL, DROP partner_id, DROP structure_id');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649312B3E16 FOREIGN KEY (partner) REFERENCES partner (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496F0137EA FOREIGN KEY (structure) REFERENCES structure (id) ON DELETE SET NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649312B3E16 ON user (partner)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6496F0137EA ON user (structure)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649312B3E16');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496F0137EA');
        $this->addSql('DROP INDEX UNIQ_8D93D649312B3E16 ON user');
        $this->addSql('DROP INDEX UNIQ_8D93D6496F0137EA ON user');
        $this->addSql('ALTER TABLE user ADD partner_id INT DEFAULT NULL, ADD structure_id INT DEFAULT NULL, DROP partner, DROP structure');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6492534008B FOREIGN KEY (structure_id) REFERENCES structure (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499393F8FE FOREIGN KEY (partner_id) REFERENCES partner (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6499393F8FE ON user (partner_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6492534008B ON user (structure_id)');
    }
}
