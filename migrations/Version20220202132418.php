<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220202132418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO product (name, category_id, description, size, quantity, created_at, price, picture) VALUES ("Ferocactus latispinus", 1, "Lorem Ipsum is simply dummy text of the printing and typesetting industry.", "5 cm", 10, NOW(), 499, "415898.jpg")');
        $this->addSql('INSERT INTO product (name, category_id, description, size, quantity, created_at, price, picture) VALUES ("Opuntia", 1, "Lorem Ipsum is simply dummy text of the printing and typesetting industry.", "10 cm", 15, NOW(), 999, "415899.jpg")');
        $this->addSql('INSERT INTO product (name, category_id, description, size, quantity, created_at, price, picture) VALUES ("Opuntia", 1, "Lorem Ipsum is simply dummy text of the printing and typesetting industry.", "9 cm", 5, NOW(), 4999, "415900.jpg")');
        $this->addSql('INSERT INTO product (name, category_id, description, size, quantity, created_at, price, picture) VALUES ("Euphorbe", 1, "Lorem Ipsum is simply dummy text of the printing and typesetting industry.", "10 cm", 14, NOW(), 999, "415713.jpg")');
        $this->addSql('INSERT INTO product (name, category_id, description, size, quantity, created_at, price, picture) VALUES ("Opuntia", 1, "Lorem Ipsum is simply dummy text of the printing and typesetting industry.", "15 cm", 6, NOW(), 1499, "1748454000.jpg")');
        $this->addSql('INSERT INTO product (name, category_id, description, size, quantity, created_at, price, picture) VALUES ("Opuntia", 1, "Lorem Ipsum is simply dummy text of the printing and typesetting industry.", "10 cm", 12, NOW(), 990, "415696.jpg")');
        $this->addSql('INSERT INTO product (name, category_id, description, size, quantity, created_at, price, picture) VALUES ("Ferocactus latispinus", 1, "Lorem Ipsum is simply dummy text of the printing and typesetting industry.", "10 cm", 12, NOW(), 990, "415666.jpg")');
        $this->addSql('INSERT INTO product (name, category_id, description, size, quantity, created_at, price, picture) VALUES ("Thelocactus", 1, "Lorem Ipsum is simply dummy text of the printing and typesetting industry.", "9 cm", 50, NOW(), 990, "440140_1.jpg")');
        $this->addSql('INSERT INTO product (name, category_id, description, size, quantity, created_at, price, picture) VALUES ("Euphorbe", 1, "Lorem Ipsum is simply dummy text of the printing and typesetting industry.", "20 cm", 22, NOW(), 1490, "415691.jpg")');
        $this->addSql('INSERT INTO product (name, category_id, description, size, quantity, created_at, price, picture) VALUES ("Gymnocalycium", 1, "Lorem Ipsum is simply dummy text of the printing and typesetting industry.", "6 cm", 32, NOW(), 790, "683969.jpg")');
        $this->addSql('INSERT INTO product (name, category_id, description, size, quantity, created_at, price, picture) VALUES ("Thelocactus", 1, "Lorem Ipsum is simply dummy text of the printing and typesetting industry.", "11 cm", 17, NOW(), 1290, "683965.jpg")');
        $this->addSql('INSERT INTO product (name, category_id, description, size, quantity, created_at, price, picture) VALUES ("Ferocactus", 1, "Lorem Ipsum is simply dummy text of the printing and typesetting industry.", "17 cm", 27, NOW(), 1590, "683970.jpg")');
        $this->addSql('INSERT INTO product (name, category_id, description, size, quantity, created_at, price, picture) VALUES ("Ferocactus", 1, "Lorem Ipsum is simply dummy text of the printing and typesetting industry.", "13 cm", 15, NOW(), 890, "415662.jpg")');


    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
