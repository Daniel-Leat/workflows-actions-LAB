-- Add more sample users and test data
-- This migration can be added later for testing purposes

-- Add more users
INSERT INTO users (name, email) VALUES
    ('Katarzyna Lewandowska', 'katarzyna.lewandowska@example.com'),
    ('Michał Zieliński', 'michal.zielinski@example.com'),
    ('Magdalena Szymańska', 'magdalena.szymanska@example.com'),
    ('Paweł Dąbrowski', 'pawel.dabrowski@example.com'),
    ('Agnieszka Kozłowska', 'agnieszka.kozlowska@example.com'),
    ('Krzysztof Jankowski', 'krzysztof.jankowski@example.com'),
    ('Joanna Mazur', 'joanna.mazur@example.com'),
    ('Marcin Krawczyk', 'marcin.krawczyk@example.com'),
    ('Ewa Piotrowska', 'ewa.piotrowska@example.com'),
    ('Adam Grabowski', 'adam.grabowski@example.com')
ON DUPLICATE KEY UPDATE name=name;

-- Update statistics (optional)
-- You can add more complex data manipulation here
