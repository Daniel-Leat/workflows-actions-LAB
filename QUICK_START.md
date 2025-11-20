# 🚀 QUICK START - Szybkie wdrożenie aplikacji

## Opcja 1: Automatyczne wdrożenie przez GitHub Actions (ZALECANE)

### 1. Skonfiguruj GitHub Secrets
Zobacz plik `SETUP_SECRETS.md` dla szczegółowych instrukcji

### 2. Push do repozytorium
```bash
git push origin main
```

### 3. Obserwuj wdrożenie
Przejdź do: https://github.com/Daniel-Leat/workflows-actions-LAB/actions

---

## Opcja 2: Ręczne wdrożenie na serwer

### Przygotowanie serwera (jednorazowo)

```bash
# 1. Zainstaluj wymagane pakiety
sudo apt update
sudo apt install -y apache2 php php-mysql php-cli libapache2-mod-php mysql-client git

# 2. Włącz moduły Apache
sudo a2enmod rewrite php
sudo systemctl restart apache2

# 3. Utwórz użytkownika dla deploymentu (opcjonalnie)
sudo useradd -m -s /bin/bash github-actions
sudo usermod -a -G www-data github-actions
```

### Wdrożenie aplikacji

```bash
# 1. Sklonuj repozytorium (lub skopiuj pliki)
cd /tmp
git clone https://github.com/Daniel-Leat/workflows-actions-LAB.git lab5app
cd lab5app

# 2. Uruchom skrypt wdrożeniowy
chmod +x deploy_app.sh
sudo ./deploy_app.sh lab5app 80

# 3. Skopiuj pliki aplikacji
sudo cp -r index.php db.php migrations.php sql /var/www/lab5app/
sudo chown -R www-data:www-data /var/www/lab5app
sudo chmod -R 755 /var/www/lab5app

# 4. Ustaw zmienne środowiskowe (ZMIEŃ WARTOŚCI!)
export DB_HOST="136.114.93.122"
export DB_NAME="student_123456"  # ZMIEŃ NA SWÓJ!
export DB_USER="stud"
export DB_PASSWORD="Uwb123!!"

# 5. Uruchom migracje bazy danych
cd /var/www/lab5app
php migrations.php

# 6. Testuj połączenie z bazą
php db.php
```

---

## Opcja 3: Lokalne testowanie (Windows + XAMPP/WAMP)

### 1. Zainstaluj XAMPP
Pobierz z: https://www.apachefriends.org/

### 2. Skopiuj pliki
```bash
# Skopiuj całą zawartość LAB5 do:
C:\xampp\htdocs\lab5app\
```

### 3. Skonfiguruj bazę danych

Otwórz phpMyAdmin: http://localhost/phpmyadmin

```sql
CREATE DATABASE lab5_test;
USE lab5_test;

-- Baza zostanie utworzona przez migracje
```

### 4. Ustaw zmienne środowiskowe

W pliku `C:\xampp\htdocs\lab5app\index.php` zmień:
```php
$host = 'localhost';
$db   = 'lab5_test';
$user = 'root';
$pass = ''; // puste hasło dla XAMPP
```

### 5. Uruchom migracje
```bash
cd C:\xampp\htdocs\lab5app
php migrations.php
```

### 6. Otwórz w przeglądarce
http://localhost/lab5app/

---

## 🧪 Testowanie aplikacji

### Test 1: Sprawdź czy Apache działa
```bash
curl http://localhost/
# lub otwórz w przeglądarce IP serwera
```

### Test 2: Sprawdź połączenie z bazą
```bash
php db.php
```

### Test 3: Sprawdź logi Apache
```bash
sudo tail -f /var/log/apache2/lab5app_error.log
sudo tail -f /var/log/apache2/lab5app_access.log
```

### Test 4: Sprawdź czy pliki są na miejscu
```bash
ls -la /var/www/lab5app/
```

---

## 📊 Monitorowanie

### Zobacz status Apache
```bash
sudo systemctl status apache2
```

### Zobacz aktywne virtual hosty
```bash
sudo apache2ctl -S
```

### Testuj konfigurację Apache
```bash
sudo apache2ctl configtest
```

---

## 🔄 Aktualizacja aplikacji

### Automatyczna (przez GitHub Actions)
```bash
git add .
git commit -m "Update application"
git push origin main
```

### Ręczna
```bash
# Na serwerze
cd /tmp
git clone https://github.com/Daniel-Leat/workflows-actions-LAB.git lab5app_new
cd lab5app_new
sudo cp -r index.php db.php migrations.php sql /var/www/lab5app/
sudo systemctl reload apache2
```

---

## 🆘 Szybkie naprawy błędów

### Apache nie startuje
```bash
sudo systemctl restart apache2
sudo apache2ctl configtest
```

### Błąd 403 Forbidden
```bash
sudo chmod -R 755 /var/www/lab5app
sudo chown -R www-data:www-data /var/www/lab5app
```

### Błąd połączenia z bazą danych
```bash
# Testuj połączenie
mysql -h 136.114.93.122 -u stud -p

# Sprawdź czy użytkownik ma uprawnienia
SHOW GRANTS FOR 'stud'@'%';
```

### Blank page (biała strona)
```bash
# Włącz wyświetlanie błędów PHP
sudo nano /etc/php/8.1/apache2/php.ini
# Znajdź i zmień:
display_errors = On
error_reporting = E_ALL

# Zrestartuj Apache
sudo systemctl restart apache2
```

---

## 📞 Pomocne komendy

```bash
# Sprawdź wersję PHP
php -v

# Sprawdź zainstalowane moduły PHP
php -m

# Sprawdź logi Apache w czasie rzeczywistym
sudo tail -f /var/log/apache2/error.log

# Restart Apache
sudo systemctl restart apache2

# Sprawdź konfigurację Apache
sudo apache2ctl -t

# Zobacz procesy Apache
ps aux | grep apache2
```

---

**Powodzenia z wdrożeniem! 🎉**
