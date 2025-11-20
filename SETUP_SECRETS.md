# 🔐 INSTRUKCJA KONFIGURACJI GITHUB SECRETS

## Krok 1: Przejdź do ustawień repozytorium

1. Otwórz repozytorium: https://github.com/Daniel-Leat/workflows-actions-LAB
2. Kliknij **Settings** (Ustawienia)
3. W lewym menu wybierz **Secrets and variables** → **Actions**
4. Kliknij **New repository secret**

## Krok 2: Dodaj następujące sekrety

### SSH_PRIVATE_KEY
**Wartość:** Cały klucz prywatny SSH (włącznie z nagłówkami)
```
-----BEGIN RSA PRIVATE KEY-----
[tutaj zawartość klucza]
-----END RSA PRIVATE KEY-----
```

**Jak wygenerować klucz SSH (jeśli nie masz):**
```bash
ssh-keygen -t rsa -b 4096 -C "github-actions" -f ~/.ssh/github_actions_key -N ""
```

**Jak dodać klucz publiczny na serwer:**
```bash
ssh-copy-id -i ~/.ssh/github_actions_key.pub github-actions@YOUR_SERVER_IP
```

---

### VM_HOST
**Wartość:** Adres IP lub domena serwera
```
136.116.111.59
```
(Możesz zmienić na swój adres serwera)

---

### VM_USER
**Wartość:** Nazwa użytkownika SSH na serwerze
```
github-actions
```
(Lub inny użytkownik, który ma uprawnienia sudo)

---

### APP_NAME
**Wartość:** Nazwa aplikacji (będzie użyta jako nazwa katalogu)
```
lab5app
```

---

### DB_HOST
**Wartość:** Adres serwera bazy danych
```
136.114.93.122
```

---

### DB_NAME
**Wartość:** Nazwa bazy danych (np. Twój numer albumu)
```
student_123456
```
(Zamień na swoją nazwę bazy danych lub numer albumu)

---

### DB_USER
**Wartość:** Użytkownik bazy danych
```
stud
```

---

### DB_PASSWORD
**Wartość:** Hasło do bazy danych
```
Uwb123!!
```

---

## Krok 3: Weryfikacja

Po dodaniu wszystkich sekretów, powinieneś zobaczyć listę:
- ✅ SSH_PRIVATE_KEY
- ✅ VM_HOST
- ✅ VM_USER
- ✅ APP_NAME
- ✅ DB_HOST
- ✅ DB_NAME
- ✅ DB_USER
- ✅ DB_PASSWORD

## Krok 4: Przygotuj serwer

### Zainstaluj wymagane oprogramowanie na serwerze:

```bash
# Połącz się z serwerem
ssh your_user@your_server

# Zaktualizuj system
sudo apt update && sudo apt upgrade -y

# Zainstaluj Apache, PHP i klienta MySQL
sudo apt install -y apache2 php php-mysql php-cli libapache2-mod-php mysql-client

# Włącz moduły Apache
sudo a2enmod rewrite
sudo a2enmod php

# Uruchom Apache
sudo systemctl start apache2
sudo systemctl enable apache2
```

### Utwórz użytkownika dla GitHub Actions:

```bash
# Dodaj użytkownika
sudo useradd -m -s /bin/bash github-actions

# Dodaj do grupy www-data
sudo usermod -a -G www-data github-actions

# Skopiuj klucz publiczny SSH do authorized_keys użytkownika
sudo mkdir -p /home/github-actions/.ssh
sudo nano /home/github-actions/.ssh/authorized_keys
# Wklej tutaj zawartość klucza PUBLICZNEGO (~/.ssh/github_actions_key.pub)

# Ustaw odpowiednie uprawnienia
sudo chmod 700 /home/github-actions/.ssh
sudo chmod 600 /home/github-actions/.ssh/authorized_keys
sudo chown -R github-actions:github-actions /home/github-actions/.ssh
```

### Skonfiguruj sudo dla użytkownika:

```bash
sudo visudo
```

Dodaj na końcu pliku:
```
github-actions ALL=(ALL) NOPASSWD: /usr/bin/mv, /usr/bin/mkdir, /usr/bin/chown, /usr/bin/chmod, /usr/sbin/a2ensite, /usr/sbin/a2enmod, /usr/sbin/apache2ctl, /bin/systemctl reload apache2, /bin/bash
```

## Krok 5: Testuj połączenie SSH

Z lokalnego komputera:
```bash
ssh -i ~/.ssh/github_actions_key github-actions@YOUR_SERVER_IP
```

Jeśli połączenie działa bez pytania o hasło - wszystko jest gotowe!

## Krok 6: Push do GitHub i wdrożenie

```bash
# W katalogu projektu LAB5
git push -u origin main
```

GitHub Actions automatycznie rozpocznie wdrożenie. Możesz śledzić postęp w zakładce **Actions** w repozytorium GitHub.

## 📌 Ważne uwagi

1. **Bezpieczeństwo:** Nigdy nie commituj kluczy SSH ani haseł do repozytorium!
2. **Baza danych:** Upewnij się, że baza danych o nazwie `DB_NAME` istnieje i użytkownik ma do niej dostęp
3. **Firewall:** Sprawdź czy port 80 (HTTP) jest otwarty na serwerze
4. **Permissions:** Użytkownik `github-actions` musi mieć odpowiednie uprawnienia sudo

## 🆘 Rozwiązywanie problemów

### Problem: SSH connection refused
```bash
# Sprawdź czy SSH nasłuchuje
sudo systemctl status ssh

# Otwórz port SSH w firewall
sudo ufw allow 22
```

### Problem: Permission denied
```bash
# Sprawdź uprawnienia authorized_keys
ls -la /home/github-actions/.ssh/
# Powinny być: 700 dla .ssh i 600 dla authorized_keys
```

### Problem: Apache nie działa
```bash
# Sprawdź status Apache
sudo systemctl status apache2

# Zobacz logi błędów
sudo tail -f /var/log/apache2/error.log
```

### Problem: Błąd połączenia z bazą danych
```bash
# Testuj połączenie z bazą
mysql -h 136.114.93.122 -u stud -p

# Sprawdź czy baza istnieje
SHOW DATABASES;
```

---

**Po skonfigurowaniu wszystkich sekretów i przygotowaniu serwera, wypchnij kod do GitHub i obserwuj automatyczne wdrożenie!**
