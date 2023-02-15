-- php artisan down --secret="1630542a-246b-4b66-afa1-dd72a4c43515"
-- php artisan create-backup

-- mysql -u root -p1turr1 -n laravel < sql/migration/drive_fields.sql

ALTER TABLE `accounts` CHANGE `drive_file` `e_drive_file` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;
ALTER TABLE `accounts` CHANGE `drive_url` `e_drive_url` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL;

ALTER TABLE `orders` CHANGE `drive_file` `e_drive_file` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL; 
ALTER TABLE `orders` CHANGE `drive_url` `e_drive_url` VARCHAR(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL; 

-- doctrine:schema:update

UPDATE `accounts_drive_files` SET `discr`='estimate' WHERE 1;

-- env
-- route
-- event
