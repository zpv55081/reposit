<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220824095925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creating tables of questions, answer variants, and replies';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO question (id, text) VALUES(1, "Вы пользуетесь социальными сетями?")');
        $this->addSql('INSERT INTO question (id, text) VALUES(2, "В какой социальной сети вы зарегистрированы?")');        
       
        $this->addSql('CREATE TABLE variant (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(100) DEFAULT NULL, question_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO btu.variant (id, `text`, question_id) VALUES(1, "Нет", 1)');
        $this->addSql('INSERT INTO btu.variant (id, `text`, question_id) VALUES(2, "Да", 1)');
        $this->addSql('INSERT INTO btu.variant (id, `text`, question_id) VALUES(3, "Вконтакте", 2)');
        $this->addSql('INSERT INTO btu.variant (id, `text`, question_id) VALUES(4, "Одноклассники", 2)');
        $this->addSql('INSERT INTO btu.variant (id, `text`, question_id) VALUES(5, "Facebook", 2)');
        
        $this->addSql('CREATE TABLE reply (id INT AUTO_INCREMENT NOT NULL, user_ipv4 VARCHAR(15) DEFAULT NULL, question_id INT NOT NULL, variant_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE variant');
        $this->addSql('DROP TABLE reply');
    }
}
