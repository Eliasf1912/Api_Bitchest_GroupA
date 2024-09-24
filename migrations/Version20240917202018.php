<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240917202018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wallet_user_cryptos DROP FOREIGN KEY FK_91139128712520F3');
        $this->addSql('ALTER TABLE wallet_user_cryptos DROP FOREIGN KEY FK_9113912894441C31');
        $this->addSql('DROP TABLE user_cryptos');
        $this->addSql('DROP TABLE wallet_user_cryptos');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_cryptos (id INT AUTO_INCREMENT NOT NULL, amount NUMERIC(10, 0) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', cotation NUMERIC(10, 0) NOT NULL, crypto VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE wallet_user_cryptos (wallet_id INT NOT NULL, user_cryptos_id INT NOT NULL, INDEX IDX_91139128712520F3 (wallet_id), INDEX IDX_9113912894441C31 (user_cryptos_id), PRIMARY KEY(wallet_id, user_cryptos_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE wallet_user_cryptos ADD CONSTRAINT FK_91139128712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE wallet_user_cryptos ADD CONSTRAINT FK_9113912894441C31 FOREIGN KEY (user_cryptos_id) REFERENCES user_cryptos (id) ON DELETE CASCADE');
    }
}
