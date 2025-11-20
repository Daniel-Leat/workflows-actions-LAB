# ❓ FAQ - Najczęściej zadawane pytania

## 🚀 Wdrożenie i konfiguracja

### Q: Gdzie znajdę swoje repozytorium?
**A:** Twoje repozytorium znajduje się tutaj: https://github.com/Daniel-Leat/workflows-actions-LAB

### Q: Jak skonfigurować GitHub Secrets?
**A:** 
1. Przejdź do: `Settings` → `Secrets and variables` → `Actions`
2. Kliknij `New repository secret`
3. Dodaj wszystkie 8 sekretów wymienione w `SETUP_SECRETS.md`

### Q: Jak wygenerować klucz SSH?
**A:**
```bash
ssh-keygen -t rsa -b 4096 -C "github-actions" -f ~/.ssh/lab5_key -N ""
```
To wygeneruje parę kluczy bez hasła w `~/.ssh/`

### Q: Jak dodać klucz SSH na serwer?
**A:**
```bash
# Automatycznie:
ssh-copy-id -i ~/.ssh/lab5_key.pub user@server

# Lub ręcznie:
cat ~/.ssh/lab5_key.pub | ssh user@server "mkdir -p ~/.ssh && cat >> ~/.ssh/authorized_keys"
```

### Q: Czy muszę używać GitHub Actions?
**A:** Nie! Możesz wdrożyć ręcznie używając instrukcji w `QUICK_START.md` sekcja "Opcja 2".

---

## 🗄️ Baza danych

### Q: Jak utworzyć bazę danych?
**A:**
```sql
-- Połącz się z MySQL
mysql -h 136.114.93.122 -u root -p

-- Utwórz bazę
CREATE DATABASE student_123456;

-- Nadaj uprawnienia
GRANT ALL PRIVILEGES ON student_123456.* TO 'stud'@'%';
FLUSH PRIVILEGES;
```

### Q: Co to są migracje?
**A:** Migracje to skrypty SQL w katalogu `sql/`, które automatycznie tworzą i aktualizują strukturę bazy danych. Uruchamiane są przez `migrations.php`.

### Q: Jak dodać nową migrację?
**A:**
1. Utwórz plik `sql/003_moja_migracja.sql`
2. Dodaj swoje zapytania SQL
3. Commit i push - migracja uruchomi się automatycznie

### Q: Błąd "Access denied" do bazy danych?
**A:** Sprawdź:
```bash
# 1. Czy użytkownik istnieje i ma uprawnienia
mysql -h DB_HOST -u DB_USER -p

# 2. Czy host jest prawidłowy
ping 136.114.93.122

# 3. Czy firewall nie blokuje portu 3306
telnet 136.114.93.122 3306
```

---

## 🖥️ Serwer i Apache

### Q: Jak zainstalować Apache i PHP?
**A:**
```bash
sudo apt update
sudo apt install -y apache2 php php-mysql php-cli libapache2-mod-php
sudo systemctl start apache2
```

### Q: Gdzie znajdują się pliki aplikacji?
**A:** W katalogu `/var/www/<APP_NAME>/` (domyślnie: `/var/www/lab5app/`)

### Q: Jak sprawdzić logi błędów?
**A:**
```bash
# Logi Apache
sudo tail -f /var/log/apache2/lab5app_error.log

# Logi PHP
sudo tail -f /var/log/apache2/error.log

# Wszystkie logi na raz
sudo tail -f /var/log/apache2/*.log
```

### Q: Błąd 403 Forbidden?
**A:**
```bash
# Napraw uprawnienia
sudo chown -R www-data:www-data /var/www/lab5app
sudo chmod -R 755 /var/www/lab5app

# Sprawdź konfigurację Apache
sudo apache2ctl configtest

# Zrestartuj Apache
sudo systemctl restart apache2
```

### Q: Błąd 500 Internal Server Error?
**A:**
```bash
# Włącz wyświetlanie błędów PHP
sudo nano /etc/php/8.1/apache2/php.ini
# Zmień: display_errors = On

# Zrestartuj Apache
sudo systemctl restart apache2

# Zobacz szczegółowy błąd w przeglądarce lub logach
sudo tail -f /var/log/apache2/error.log
```

---

## 🔐 SSH i bezpieczeństwo

### Q: "Permission denied (publickey)" przy SSH?
**A:**
```bash
# 1. Sprawdź czy klucz jest dodany
cat ~/.ssh/authorized_keys

# 2. Sprawdź uprawnienia
chmod 700 ~/.ssh
chmod 600 ~/.ssh/authorized_keys

# 3. Testuj z verbose
ssh -vvv -i ~/.ssh/lab5_key user@server
```

### Q: Czy mogę używać hasła zamiast klucza SSH?
**A:** Tak, ale NIE jest to zalecane dla automatyzacji. GitHub Actions wymaga klucza bez hasła.

### Q: Jak zabezpieczyć serwer?
**A:**
```bash
# 1. Wyłącz logowanie root przez SSH
sudo nano /etc/ssh/sshd_config
# Ustaw: PermitRootLogin no

# 2. Skonfiguruj firewall
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw enable

# 3. Zainstaluj fail2ban
sudo apt install fail2ban
```

---

## 🔄 GitHub Actions

### Q: Gdzie zobaczyć status wdrożenia?
**A:** Zakładka Actions w repozytorium: https://github.com/Daniel-Leat/workflows-actions-LAB/actions

### Q: Workflow się nie uruchamia?
**A:** Sprawdź:
1. Czy workflow jest w `.github/workflows/`
2. Czy plik ma rozszerzenie `.yml`
3. Czy składnia YAML jest poprawna
4. Czy branch nazywa się `main`

### Q: Błąd "SSH connection failed"?
**A:**
```bash
# 1. Sprawdź czy sekret SSH_PRIVATE_KEY jest poprawny
# 2. Sprawdź czy klucz publiczny jest na serwerze
# 3. Sprawdź czy VM_HOST i VM_USER są poprawne
# 4. Testuj lokalnie:
ssh -i ~/.ssh/lab5_key VM_USER@VM_HOST
```

### Q: Jak ręcznie uruchomić workflow?
**A:**
1. Przejdź do zakładki `Actions`
2. Wybierz workflow "Deploy PHP App to VM"
3. Kliknij `Run workflow`
4. Wybierz branch `main`
5. Kliknij zielony przycisk `Run workflow`

### Q: Workflow kończy się błędem na etapie migracji?
**A:**
```bash
# SSH do serwera i uruchom ręcznie
ssh user@server
cd /var/www/lab5app
export DB_HOST="136.114.93.122"
export DB_NAME="student_123456"
export DB_USER="stud"
export DB_PASSWORD="Uwb123!!"
php migrations.php
```

---

## 🐛 Debugowanie

### Q: Jak debugować aplikację PHP?
**A:**
```bash
# 1. Włącz error reporting w index.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

# 2. Sprawdź logi PHP
sudo tail -f /var/log/apache2/error.log

# 3. Testuj bezpośrednio z CLI
php -f /var/www/lab5app/index.php
```

### Q: Blank page (pusta strona)?
**A:**
1. Sprawdź logi: `sudo tail -f /var/log/apache2/error.log`
2. Włącz display_errors w php.ini
3. Sprawdź czy wszystkie pliki są na miejscu
4. Sprawdź uprawnienia plików

### Q: Dane z bazy się nie wyświetlają?
**A:**
```bash
# Testuj połączenie
php db.php

# Sprawdź czy tabela istnieje
mysql -h DB_HOST -u DB_USER -p DB_NAME -e "SHOW TABLES;"

# Sprawdź czy są dane
mysql -h DB_HOST -u DB_USER -p DB_NAME -e "SELECT * FROM users;"
```

---

## 📚 Rozwój aplikacji

### Q: Jak dodać nową funkcjonalność?
**A:**
1. Edytuj pliki lokalnie
2. Testuj lokalnie (opcjonalnie)
3. `git add .`
4. `git commit -m "Opis zmian"`
5. `git push origin main`
6. GitHub Actions automatycznie wdroży zmiany

### Q: Jak dodać nową tabelę w bazie?
**A:**
1. Utwórz nowy plik w `sql/`, np. `003_create_products.sql`
2. Dodaj SQL:
```sql
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL
);
```
3. Commit i push - migracja uruchomi się automatycznie

### Q: Jak zmienić wygląd aplikacji?
**A:** Edytuj sekcję `<style>` w `index.php`. Możesz też utworzyć oddzielny plik CSS.

---

## 🎯 Najlepsze praktyki

### Q: Czy powinienem commitować hasła?
**A:** **NIE!** Używaj zmiennych środowiskowych i GitHub Secrets.

### Q: Jak często powinienem robić backup bazy?
**A:**
```bash
# Backup bazy danych
mysqldump -h DB_HOST -u DB_USER -pDB_PASSWORD DB_NAME > backup_$(date +%Y%m%d).sql

# Automatyczny backup (cron)
0 2 * * * mysqldump -h 136.114.93.122 -u stud -pUwb123!! student_123456 > /backups/db_$(date +\%Y\%m\%d).sql
```

### Q: Jak testować zmiany przed wdrożeniem?
**A:**
1. Utwórz branch `dev`: `git checkout -b dev`
2. Testuj zmiany lokalnie
3. Merge do `main` gdy gotowe: `git checkout main && git merge dev`

---

## 💡 Wskazówki

### Q: Czy mogę zmienić port Apache?
**A:** Tak! W `deploy_app.sh` zmień parametr portu i zaktualizuj `/etc/apache2/ports.conf`

### Q: Jak dodać HTTPS?
**A:**
```bash
# Zainstaluj certbot
sudo apt install certbot python3-certbot-apache

# Uzyskaj certyfikat
sudo certbot --apache -d twoja-domena.pl
```

### Q: Jak monitorować aplikację?
**A:**
```bash
# Instalacja monitoring tools
sudo apt install htop iotop nethogs

# Status Apache
sudo systemctl status apache2

# Aktywne połączenia
sudo netstat -tuln | grep :80
```

---

## 📞 Potrzebujesz więcej pomocy?

1. **Sprawdź dokumentację:**
   - `README.md` - Pełna dokumentacja
   - `QUICK_START.md` - Szybki start
   - `SETUP_SECRETS.md` - Konfiguracja

2. **Sprawdź przykłady:**
   - [valdemarcz/uwb_app](https://github.com/valdemarcz/uwb_app)

3. **Oficjalna dokumentacja:**
   - [GitHub Actions](https://docs.github.com/en/actions)
   - [PHP](https://www.php.net/docs.php)
   - [Apache](https://httpd.apache.org/docs/2.4/)
   - [MySQL](https://dev.mysql.com/doc/)

---

**Nie znalazłeś odpowiedzi? Sprawdź logi i dokumentację techniczną!** 🔍
