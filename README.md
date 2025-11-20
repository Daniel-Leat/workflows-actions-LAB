# LAB5 - PHP Web Application with MySQL

Prosta aplikacja webowa w PHP z bazą danych MySQL, automatycznie wdrażana na maszynie wirtualnej przy użyciu GitHub Actions.

## 📋 Struktura projektu

```
LAB5/
├── .github/
│   └── workflows/
│       └── deploy.yml          # GitHub Actions workflow
├── sql/
│   └── 001_create_users_table.sql  # Migracje bazy danych
├── index.php                   # Główna strona aplikacji
├── db.php                      # Test połączenia z bazą danych
├── migrations.php              # Skrypt migracji
├── deploy_app.sh              # Skrypt wdrożeniowy
└── README.md                  # Dokumentacja
```

## 🚀 Funkcjonalności

- ✅ Wyświetlanie informacji o serwerze i PHP
- ✅ Połączenie z bazą danych MySQL
- ✅ Wyświetlanie listy użytkowników z bazy danych
- ✅ Automatyczne wdrożenie przez GitHub Actions
- ✅ System migracji bazy danych
- ✅ Responsywny design

## 📦 Wymagania

- Serwer z Apache 2.4+
- PHP 7.4+ z rozszerzeniem PDO MySQL
- MySQL/MariaDB 5.7+
- Dostęp SSH do serwera
- Git

## ⚙️ Konfiguracja GitHub Secrets

W repozytorium GitHub ustaw następujące sekrety (Settings → Secrets and variables → Actions):

### Wymagane sekrety:

| Nazwa | Przykład | Opis |
|-------|----------|------|
| `SSH_PRIVATE_KEY` | `-----BEGIN RSA PRIVATE KEY-----...` | Klucz prywatny SSH (bez hasła) |
| `VM_HOST` | `136.116.111.59` | Adres IP lub domena serwera |
| `VM_USER` | `github-actions` | Użytkownik SSH |
| `APP_NAME` | `lab5app` | Nazwa aplikacji (będzie użyta w `/var/www/`) |
| `DB_HOST` | `136.114.93.122` | Host bazy danych |
| `DB_NAME` | `student_123456` | Nazwa bazy danych (np. numer albumu) |
| `DB_USER` | `stud` | Użytkownik bazy danych |
| `DB_PASSWORD` | `Uwb123!!` | Hasło do bazy danych |

## 🔐 Konfiguracja SSH

### 1. Wygeneruj parę kluczy SSH (jeśli nie masz):

```bash
ssh-keygen -t rsa -b 4096 -C "github-actions" -f ~/.ssh/github_actions_key -N ""
```

### 2. Dodaj klucz publiczny do serwera:

```bash
ssh-copy-id -i ~/.ssh/github_actions_key.pub user@your-server
```

Lub ręcznie:
```bash
cat ~/.ssh/github_actions_key.pub | ssh user@your-server "mkdir -p ~/.ssh && cat >> ~/.ssh/authorized_keys"
```

### 3. Dodaj klucz prywatny do GitHub Secrets:

```bash
cat ~/.ssh/github_actions_key
```

Skopiuj całą zawartość (włącznie z `-----BEGIN RSA PRIVATE KEY-----` i `-----END RSA PRIVATE KEY-----`) i wklej jako `SSH_PRIVATE_KEY` w GitHub Secrets.

## 🖥️ Przygotowanie serwera

### Zainstaluj wymagane oprogramowanie:

```bash
# Aktualizuj system
sudo apt update && sudo apt upgrade -y

# Zainstaluj Apache, PHP i MySQL client
sudo apt install -y apache2 php php-mysql php-cli libapache2-mod-php mysql-client

# Włącz moduły Apache
sudo a2enmod rewrite
sudo a2enmod php

# Uruchom Apache
sudo systemctl start apache2
sudo systemctl enable apache2
```

### Skonfiguruj użytkownika dla GitHub Actions:

```bash
# Dodaj użytkownika
sudo useradd -m -s /bin/bash github-actions

# Dodaj do grupy www-data
sudo usermod -a -G www-data github-actions

# Pozwól na sudo bez hasła dla potrzebnych komend
sudo visudo
```

Dodaj na końcu pliku:
```
github-actions ALL=(ALL) NOPASSWD: /usr/bin/mv, /usr/bin/mkdir, /usr/bin/chown, /usr/bin/chmod, /usr/sbin/a2ensite, /usr/sbin/a2enmod, /usr/sbin/apache2ctl, /bin/systemctl reload apache2, /bin/bash
```

## 🗄️ Konfiguracja bazy danych

### Utwórz bazę danych (na serwerze MySQL):

```sql
CREATE DATABASE IF NOT EXISTS student_123456;
GRANT ALL PRIVILEGES ON student_123456.* TO 'stud'@'%';
FLUSH PRIVILEGES;
```

Migracje zostaną wykonane automatycznie podczas pierwszego wdrożenia.

## 🚢 Wdrożenie

### Automatyczne wdrożenie:

1. **Push do brancha main:**
   ```bash
   git add .
   git commit -m "Deploy application"
   git push origin main
   ```

2. **Ręczne uruchomienie workflow:**
   - Przejdź do zakładki "Actions" w repozytorium GitHub
   - Wybierz "Deploy PHP App to VM"
   - Kliknij "Run workflow"

### Ręczne wdrożenie (bez GitHub Actions):

```bash
# Skopiuj pliki na serwer
scp -r * user@server:/tmp/lab5app/

# Połącz się z serwerem
ssh user@server

# Uruchom skrypt wdrożeniowy
cd /tmp/lab5app
chmod +x deploy_app.sh
sudo ./deploy_app.sh lab5app 80

# Uruchom migracje
export DB_HOST="136.114.93.122"
export DB_NAME="student_123456"
export DB_USER="stud"
export DB_PASSWORD="Uwb123!!"
cd /var/www/lab5app
php migrations.php
```

## 🧪 Testowanie

### Test połączenia z bazą danych:

```bash
ssh user@server
cd /var/www/lab5app
export DB_HOST="136.114.93.122"
export DB_NAME="student_123456"
export DB_USER="stud"
export DB_PASSWORD="Uwb123!!"
php db.php
```

### Test aplikacji w przeglądarce:

```
http://your-server-ip/
```

## 📊 Workflow GitHub Actions

Workflow automatycznie:

1. ✅ Pobiera kod z repozytorium
2. ✅ Konfiguruje połączenie SSH
3. ✅ Kopiuje pliki aplikacji na serwer
4. ✅ Uruchamia skrypt wdrożeniowy
5. ✅ Konfiguruje Apache
6. ✅ Ustawia zmienne środowiskowe
7. ✅ Wykonuje migracje bazy danych
8. ✅ Testuje wdrożenie

## 📝 Rozwiązywanie problemów

### Błąd: "Permission denied" podczas SSH
```bash
# Sprawdź uprawnienia klucza
chmod 600 ~/.ssh/github_actions_key

# Sprawdź czy klucz publiczny jest na serwerze
cat ~/.ssh/authorized_keys
```

### Błąd: "Connection refused" do bazy danych
```bash
# Sprawdź czy MySQL nasłuchuje na zewnętrznym interfejsie
mysql -h 136.114.93.122 -u stud -p

# Sprawdź firewall
sudo ufw status
```

### Błąd: "Could not find driver" (PDO)
```bash
# Zainstaluj rozszerzenie MySQL dla PHP
sudo apt install php-mysql
sudo systemctl restart apache2
```

## 📚 Referencje

- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [PHP PDO Documentation](https://www.php.net/manual/en/book.pdo.php)
- [Apache Virtual Hosts](https://httpd.apache.org/docs/2.4/vhosts/)

## 👤 Autor

LAB5 - Usługi w Chmurze
