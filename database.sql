-- Password reset OTP table
-- Assumes an existing `users` table with primary key `user_id`

CREATE TABLE IF NOT EXISTS `password_resets` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT(20) NOT NULL,
  `otp_hash` VARCHAR(255) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `used_at` DATETIME NULL,
  `attempts` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  `request_ip` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_expires_at` (`expires_at`),
  CONSTRAINT `fk_password_resets_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
