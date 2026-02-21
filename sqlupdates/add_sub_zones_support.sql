-- Add Sub Zones Support to Zones Table
-- This SQL file adds support for sub-zones and area-based zone assignments

-- Step 1: Add parent_zone_id column to zones table
ALTER TABLE `zones` 
ADD COLUMN `parent_zone_id` INT(11) NULL DEFAULT NULL AFTER `status`;

-- Step 2: Add foreign key constraint for parent_zone_id
ALTER TABLE `zones`
ADD CONSTRAINT `zones_parent_zone_id_foreign`
FOREIGN KEY (`parent_zone_id`) REFERENCES `zones`(`id`) ON DELETE CASCADE;

-- Step 3: Add index for better performance
ALTER TABLE `zones`
ADD INDEX `idx_parent_zone_id` (`parent_zone_id`);

-- Step 4: Create zone_area pivot table
CREATE TABLE IF NOT EXISTS `zone_area` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `zone_id` INT(11) NOT NULL,
  `area_id` BIGINT(20) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `zone_area_unique` (`zone_id`, `area_id`),
  KEY `zone_area_zone_id_index` (`zone_id`),
  KEY `zone_area_area_id_index` (`area_id`),
  CONSTRAINT `zone_area_zone_id_foreign` 
    FOREIGN KEY (`zone_id`) REFERENCES `zones`(`id`) ON DELETE CASCADE,
  CONSTRAINT `zone_area_area_id_foreign` 
    FOREIGN KEY (`area_id`) REFERENCES `areas`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

