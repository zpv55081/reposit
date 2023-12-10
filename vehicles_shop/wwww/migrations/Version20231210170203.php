<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231210170203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creating tables for showcase';
    }


    public function up(Schema $schema): void
    {
        $this->addSql('CREATE
            TABLE vehshop.vehicle_brand (
                id INT auto_increment NOT NULL,
                name varchar(100) NULL,
                CONSTRAINT vehicle_brand_PK PRIMARY KEY (id)
            )
            ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_general_ci;
        ');

        $this->addSql('INSERT INTO vehshop.vehicle_brand (id,`name`) VALUES (1,"BMW");');
        $this->addSql('INSERT INTO vehshop.vehicle_brand (id,`name`) VALUES (2,"Toyota");');


        $this->addSql('CREATE 
            TABLE vehshop.vehicle_model (
            id INT auto_increment NOT NULL,
            vehicle_brand_id INT NULL,
            name varchar(100) NULL,
            CONSTRAINT vehicle_model_PK PRIMARY KEY (id),
            CONSTRAINT vehicle_model_FK FOREIGN KEY (vehicle_brand_id) REFERENCES vehshop.vehicle_brand(id) ON DELETE CASCADE
            )
            ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_general_ci;
        ');

        $this->addSql('INSERT INTO vehshop.vehicle_model (id,vehicle_brand_id,`name`) VALUES (1,1,"1  Серии");');
        $this->addSql('INSERT INTO vehshop.vehicle_model (id,vehicle_brand_id,`name`) VALUES (3,1,"3  Серии");');
        $this->addSql('INSERT INTO vehshop.vehicle_model (id,vehicle_brand_id,`name`) VALUES (4,1,"X6");');
        $this->addSql('INSERT INTO vehshop.vehicle_model (id,vehicle_brand_id,`name`) VALUES (5,2,"Land Cruiser Prado");');


        $this->addSql('CREATE 
        TABLE vehshop.showcase (
            id INT auto_increment NOT NULL,
            vehicle_model_id INT NULL,
            image varchar(100) NULL,
            price FLOAT NULL,
            CONSTRAINT showcase_PK PRIMARY KEY (id),
            CONSTRAINT showcase_FK FOREIGN KEY (vehicle_model_id) REFERENCES vehshop.vehicle_model(id) ON DELETE CASCADE
            )
            ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_general_ci
            COMMENT="Витрина";
        ');

        $this->addSql('INSERT INTO vehshop.showcase (vehicle_model_id,image,price) VALUES (5,"toyota/pradoSilv.jpg",5200000);');
        $this->addSql('INSERT INTO vehshop.showcase (vehicle_model_id,image,price) VALUES (1,"bmw/s1white.jpg",1100000);');
        $this->addSql('INSERT INTO vehshop.showcase (vehicle_model_id,image,price) VALUES (4,"bmw/x6red.jpg",6500000);');
        $this->addSql('INSERT INTO vehshop.showcase (vehicle_model_id,image,price) VALUES (3,"bmw/s3white.jpg",3100000);');
    }


    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE showcase');
        $this->addSql('DROP TABLE vehicle_model');
        $this->addSql('DROP TABLE vehicle_brand');
    }
}
