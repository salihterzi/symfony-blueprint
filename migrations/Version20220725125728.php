<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220725125728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE permission_groups (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, display_name VARCHAR(100) NOT NULL)');
        $this->addSql('CREATE TABLE permissions (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, permission_group_id INTEGER DEFAULT NULL, name VARCHAR(100) NOT NULL, display_name VARCHAR(100) NOT NULL)');
        $this->addSql('CREATE INDEX IDX_2DEDCC6FB6C0CF1 ON permissions (permission_group_id)');
        $this->addSql('CREATE TABLE roles (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, display_name VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE role_permissions (role_id INTEGER NOT NULL, permission_id INTEGER NOT NULL, PRIMARY KEY(role_id, permission_id))');
        $this->addSql('CREATE INDEX IDX_1FBA94E6D60322AC ON role_permissions (role_id)');
        $this->addSql('CREATE INDEX IDX_1FBA94E6FED90CCA ON role_permissions (permission_id)');
        $this->addSql('CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE user_roles (user_id INTEGER NOT NULL, role_id INTEGER NOT NULL, PRIMARY KEY(user_id, role_id))');
        $this->addSql('CREATE INDEX IDX_54FCD59FA76ED395 ON user_roles (user_id)');
        $this->addSql('CREATE INDEX IDX_54FCD59FD60322AC ON user_roles (role_id)');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE permission_group');
        $this->addSql('DROP TABLE user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE permission (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, permission_group_id INTEGER DEFAULT NULL, name VARCHAR(100) NOT NULL COLLATE BINARY, display_name VARCHAR(100) NOT NULL COLLATE BINARY)');
        $this->addSql('CREATE INDEX IDX_E04992AAB6C0CF1 ON permission (permission_group_id)');
        $this->addSql('CREATE TABLE permission_group (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL COLLATE BINARY, display_name VARCHAR(100) NOT NULL COLLATE BINARY)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL COLLATE BINARY, last_name VARCHAR(255) NOT NULL COLLATE BINARY, email VARCHAR(255) NOT NULL COLLATE BINARY, password VARCHAR(255) NOT NULL COLLATE BINARY)');
        $this->addSql('DROP TABLE permission_groups');
        $this->addSql('DROP TABLE permissions');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE role_permissions');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE user_roles');
    }
}
