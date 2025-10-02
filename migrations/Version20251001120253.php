<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251001120253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user
        ADD COLUMN login_cnt INT UNSIGNED NOT NULL DEFAULT 0,
        ADD COLUMN login_at DATETIME
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user
        DROP COLUMN login_cnt,
        DROP COLUMN login_at
        ');
    }
}
