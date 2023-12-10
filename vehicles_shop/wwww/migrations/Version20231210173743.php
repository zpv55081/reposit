<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration
 */
final class Version20231210173743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Credit asking';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE asking (id INT AUTO_INCREMENT NOT NULL, showcase_id INT NOT NULL, programm_amount DOUBLE PRECISION NOT NULL, rate DOUBLE PRECISION NOT NULL, vehicle_price DOUBLE PRECISION NOT NULL, initial_payment DOUBLE PRECISION NOT NULL, monthly_payment DOUBLE PRECISION NOT NULL, credit_term INT NOT NULL, created_at INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE asking');
    }
}
