<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250929144825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // новая таблица — ок
        $this->addSql("
        CREATE TABLE activity (
            id INT AUTO_INCREMENT NOT NULL,
            url VARCHAR(255) NOT NULL,
            user_id INT DEFAULT NULL,
            agent VARCHAR(255) DEFAULT NULL,
            query LONGTEXT NOT NULL,
            ip_addr VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
    ");

        // 1) заполняем NULL перед ужесточением:
        //    выберите логику, подходящую под ваш проект!
        $this->addSql("UPDATE user SET login = CONCAT('user_', id) WHERE login IS NULL");
        $this->addSql("UPDATE user SET password = '' WHERE password IS NULL");
        // ^ здесь лучше поставить какой-нибудь «невалидный» плейсхолдер,
        //   чтобы при первом логине заставить сменить пароль

        // 2) теперь ужесточаем
        $this->addSql('ALTER TABLE user MODIFY login VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user MODIFY password VARCHAR(255) NOT NULL');
    }
}
