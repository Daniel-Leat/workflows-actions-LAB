-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    description VARCHAR(100)
);

-- Insert sample data
INSERT INTO users (username, email, description) VALUES
    ('jan_kowalski', 'jan.kowalski@example.com', 'Administrator systemu'),
    ('anna_nowak', 'anna.nowak@example.com', 'Programista backend'),
    ('piotr_wisniewski', 'piotr.wisniewski@example.com', 'Frontend developer'),
    ('maria_wojcik', 'maria.wojcik@example.com', 'UX Designer'),
    ('tomasz_kaminski', 'tomasz.kaminski@example.com', 'DevOps Engineer');
