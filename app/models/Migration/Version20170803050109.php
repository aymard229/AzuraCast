<?php

namespace Migration;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170803050109 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE listener CHANGE listener_user_agent listener_user_agent VARCHAR(191) NOT NULL');
        $this->addSql('ALTER TABLE station CHANGE url url VARCHAR(191) DEFAULT NULL, CHANGE radio_media_dir radio_media_dir VARCHAR(191) DEFAULT NULL, CHANGE radio_base_dir radio_base_dir VARCHAR(191) DEFAULT NULL');
        $this->addSql('ALTER TABLE station_media CHANGE path path VARCHAR(191) DEFAULT NULL');
        $this->addSql('ALTER TABLE station_mounts CHANGE relay_url relay_url VARCHAR(191) DEFAULT NULL, CHANGE authhash authhash VARCHAR(191) DEFAULT NULL');
        $this->addSql('ALTER TABLE users CHANGE auth_password auth_password VARCHAR(191) DEFAULT NULL');
        $this->addSql('ALTER TABLE app_migrations CHANGE version version VARCHAR(191) NOT NULL');

        $this->changeCharset('utf8mb4', 'utf8mb4_unicode_ci');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE listener CHANGE listener_user_agent listener_user_agent VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE station CHANGE url url VARCHAR(255) DEFAULT NULL, CHANGE radio_base_dir radio_base_dir VARCHAR(255) DEFAULT NULL, CHANGE radio_media_dir radio_media_dir VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE station_media CHANGE path path VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE station_mounts CHANGE relay_url relay_url VARCHAR(255) DEFAULT NULL, CHANGE authhash authhash VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE users CHANGE auth_password auth_password VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE app_migrations CHANGE version version VARCHAR(255) NOT NULL');

        $this->changeCharset('utf8', 'utf8_unicode_ci');
    }

    protected function changeCharset($charset, $collate)
    {
        $db_name = $this->connection->getDatabase();

        $this->addSql([
            'ALTER DATABASE '.$this->connection->quoteIdentifier($db_name).' CHARACTER SET = '.$charset.' COLLATE = '.$collate,
            'ALTER TABLE `song_history` DROP FOREIGN KEY FK_2AD16164A0BDB2F3',
            'ALTER TABLE `station_media` DROP FOREIGN KEY FK_32AADE3AA0BDB2F3',
            'ALTER TABLE `analytics` CONVERT TO CHARACTER SET '.$charset.' COLLATE '.$collate,
            'ALTER TABLE `api_keys` CONVERT TO CHARACTER SET '.$charset.' COLLATE '.$collate,
            'ALTER TABLE `app_migrations` CONVERT TO CHARACTER SET '.$charset.' COLLATE '.$collate,
            'ALTER TABLE `listener` CONVERT TO CHARACTER SET '.$charset.' COLLATE '.$collate,
            'ALTER TABLE `role` CONVERT TO CHARACTER SET '.$charset.' COLLATE '.$collate,
            'ALTER TABLE `role_permissions` CONVERT TO CHARACTER SET '.$charset.' COLLATE '.$collate,
            'ALTER TABLE `settings` CONVERT TO CHARACTER SET '.$charset.' COLLATE '.$collate,
            'ALTER TABLE `song_history` CONVERT TO CHARACTER SET '.$charset.' COLLATE '.$collate,
            'ALTER TABLE `songs` CONVERT TO CHARACTER SET '.$charset.' COLLATE '.$collate,
            'ALTER TABLE `station` CONVERT TO CHARACTER SET '.$charset.' COLLATE '.$collate,
            'ALTER TABLE `station_media` CONVERT TO CHARACTER SET '.$charset.' COLLATE '.$collate,
            'ALTER TABLE `station_mounts` CONVERT TO CHARACTER SET '.$charset.' COLLATE '.$collate,
            'ALTER TABLE `station_playlist_has_media` CONVERT TO CHARACTER SET '.$charset.' COLLATE '.$collate,
            'ALTER TABLE `station_playlists` CONVERT TO CHARACTER SET '.$charset.' COLLATE '.$collate,
            'ALTER TABLE `station_requests` CONVERT TO CHARACTER SET '.$charset.' COLLATE '.$collate,
            'ALTER TABLE `station_streamers` CONVERT TO CHARACTER SET '.$charset.' COLLATE '.$collate,
            'ALTER TABLE `user_has_role` CONVERT TO CHARACTER SET '.$charset.' COLLATE '.$collate,
            'ALTER TABLE `users` CONVERT TO CHARACTER SET '.$charset.' COLLATE '.$collate,
            'ALTER TABLE `song_history` ADD CONSTRAINT FK_2AD16164A0BDB2F3 FOREIGN KEY (song_id) REFERENCES songs (id) ON DELETE CASCADE',
            'ALTER TABLE `station_media` ADD CONSTRAINT FK_32AADE3AA0BDB2F3 FOREIGN KEY (song_id) REFERENCES songs (id) ON DELETE SET NULL',
        ]);
    }
}
