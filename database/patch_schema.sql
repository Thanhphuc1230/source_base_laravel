-- Patch Schema SQL for Incremental Updates
-- Rule 12 & Rule 17 Compliance

-- Add currency_unit column to product_settings
ALTER TABLE `product_settings` ADD COLUMN IF NOT EXISTS `currency_unit` VARCHAR(50) DEFAULT 'đ' AFTER `is_active`;
