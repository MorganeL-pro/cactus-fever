<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220202200952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO user (email, roles, password, firstname, lastname, phone, street_address, postal_code, city, created_at) VALUES ("admin@cactusfever.com", "[\"ROLE_ADMIN\"]", "$2y$13$4.F2PRNXXQuKAOm1fV0MBOrEws0MlSD..WBFShrZSHFAFU8545PiO","Morgane", "Lahiyé", "0212345678", "2 rue des Belles Plantes", "45000", "Orléans", NOW())');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
