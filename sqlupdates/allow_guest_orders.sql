-- Allow Guest Orders Without User Creation
-- This SQL makes user_id nullable in orders and combined_orders tables

-- Make user_id nullable in combined_orders table
ALTER TABLE `combined_orders` 
MODIFY COLUMN `user_id` INT(11) NULL DEFAULT NULL;

-- Make user_id nullable in orders table  
ALTER TABLE `orders` 
MODIFY COLUMN `user_id` INT(11) NULL DEFAULT NULL;

-- Make user_id nullable in coupon_usages table (if exists)
-- ALTER TABLE `coupon_usages` 
-- MODIFY COLUMN `user_id` INT(11) NULL DEFAULT NULL;

