-- Таблица для хранения сессий Symfony в MariaDB/MySQL
CREATE TABLE IF NOT EXISTS sessions (
    sess_id VARBINARY(128) NOT NULL PRIMARY KEY,
    sess_data BLOB NOT NULL,
    sess_lifetime INTEGER UNSIGNED NOT NULL,
    sess_time INTEGER UNSIGNED NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;