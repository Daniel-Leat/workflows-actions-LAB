-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data
INSERT INTO users (name, email) VALUES
    ('Jan Kowalski', 'jan.kowalski@example.com'),
    ('Anna Nowak', 'anna.nowak@example.com'),
    ('Piotr Wiśniewski', 'piotr.wisniewski@example.com'),
    ('Maria Wójcik', 'maria.wojcik@example.com'),
    ('Tomasz Kamiński', 'tomasz.kaminski@example.com')
ON DUPLICATE KEY UPDATE name=name;
