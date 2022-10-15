<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221014231825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partner DROP FOREIGN KEY FK_312B3E1634F43E12');
        $this->addSql('ALTER TABLE structure DROP FOREIGN KEY FK_6F0137EAA5F87C89');
        $this->addSql('CREATE TABLE module_user (module_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_37AF9345AFC2B591 (module_id), INDEX IDX_37AF9345A76ED395 (user_id), PRIMARY KEY(module_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE module_user ADD CONSTRAINT FK_37AF9345AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_user ADD CONSTRAINT FK_37AF9345A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_module DROP FOREIGN KEY FK_69763D15A76ED395');
        $this->addSql('ALTER TABLE user_module DROP FOREIGN KEY FK_69763D15AFC2B591');
        $this->addSql('ALTER TABLE user_partner DROP FOREIGN KEY FK_6926201CBF396750');
        $this->addSql('ALTER TABLE user_structure DROP FOREIGN KEY FK_6FE1BA0EBF396750');
        $this->addSql('DROP TABLE user_module');
        $this->addSql('DROP TABLE user_partner');
        $this->addSql('DROP TABLE user_structure');
        $this->addSql('DROP INDEX UNIQ_312B3E1634F43E12 ON partner');
        $this->addSql('ALTER TABLE partner CHANGE user_partner_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE partner ADD CONSTRAINT FK_312B3E16A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_312B3E16A76ED395 ON partner (user_id)');
        $this->addSql('DROP INDEX UNIQ_6F0137EAA5F87C89 ON structure');
        $this->addSql('ALTER TABLE structure CHANGE user_structure_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE structure ADD CONSTRAINT FK_6F0137EAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6F0137EAA76ED395 ON structure (user_id)');
        $this->addSql('ALTER TABLE user DROP type');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_module (user_id INT NOT NULL, module_id INT NOT NULL, INDEX IDX_69763D15A76ED395 (user_id), INDEX IDX_69763D15AFC2B591 (module_id), PRIMARY KEY(user_id, module_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_partner (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_structure (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_module ADD CONSTRAINT FK_69763D15A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_module ADD CONSTRAINT FK_69763D15AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_partner ADD CONSTRAINT FK_6926201CBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_structure ADD CONSTRAINT FK_6FE1BA0EBF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_user DROP FOREIGN KEY FK_37AF9345AFC2B591');
        $this->addSql('ALTER TABLE module_user DROP FOREIGN KEY FK_37AF9345A76ED395');
        $this->addSql('DROP TABLE module_user');
        $this->addSql('ALTER TABLE partner DROP FOREIGN KEY FK_312B3E16A76ED395');
        $this->addSql('DROP INDEX IDX_312B3E16A76ED395 ON partner');
        $this->addSql('ALTER TABLE partner CHANGE user_id user_partner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE partner ADD CONSTRAINT FK_312B3E1634F43E12 FOREIGN KEY (user_partner_id) REFERENCES user_partner (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_312B3E1634F43E12 ON partner (user_partner_id)');
        $this->addSql('ALTER TABLE structure DROP FOREIGN KEY FK_6F0137EAA76ED395');
        $this->addSql('DROP INDEX IDX_6F0137EAA76ED395 ON structure');
        $this->addSql('ALTER TABLE structure CHANGE user_id user_structure_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE structure ADD CONSTRAINT FK_6F0137EAA5F87C89 FOREIGN KEY (user_structure_id) REFERENCES user_structure (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6F0137EAA5F87C89 ON structure (user_structure_id)');
        $this->addSql('ALTER TABLE user ADD type VARCHAR(255) NOT NULL');
    }
}
