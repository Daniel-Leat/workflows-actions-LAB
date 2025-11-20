# 🔐 KONFIGURACJA GITHUB SECRETS - WYMAGANE!

## ⚠️ PROBLEM: Baza danych nie działa, bo brakuje sekretów w GitHub!

**WAŻNE:** Według dokumentacji przykładowego repozytorium (`valdemarcz/uwb_app`), workflow SQL wykonuje się **bezpośrednio z GitHub Actions do bazy MySQL**, a NIE przez serwer VM. Dlatego **musisz dodać sekrety** w GitHub, inaczej tabela nigdy nie zostanie utworzona!

## Krok 1: Przejdź do ustawień repozytorium

1. Otwórz: https://github.com/Daniel-Leat/workflows-actions-LAB/settings/secrets/actions
2. Kliknij **New repository secret**

## Krok 2: Dodaj następujące sekrety (WSZYSTKIE WYMAGANE!)

### 1. DB_HOST
**Wartość:**
```
136.114.93.122
```
*(Adres serwera bazy danych MySQL)*

---

### 2. DB_NAME
**Wartość:**
```
89413
```
*(Twój numer indeksu - jeśli nie zadziała, spróbuj `s89413`)*

---

### 3. DB_USER
**Wartość:**
```
stud
```
*(Użytkownik bazy danych)*

---

### 4. DB_PASSWORD
**Wartość:**
```
Uwb123!!
```
*(Hasło do bazy danych)*

---

### 5. VM_HOST
**Wartość:**
```
136.116.111.59
```
*(Adres IP serwera VM)*

---

### 6. VM_USER
**Wartość:**
```
github-actions
```
*(Użytkownik SSH na serwerze)*

---

### 7. VM_SSH_PASSPHRASE
**Wartość:**
```
github
```
*(Passphrase dla klucza SSH)*

---

### 8. APP_PORT
**Wartość:**
```
8007
```
*(Twój port - indeks 89413 = port 8007)*

---

### 9. VM_SSH_KEY
**Wartość:** Cała zawartość pliku `gcp_vm_key`

**Jak skopiować:**
1. Otwórz plik `gcp_vm_key` w edytorze
2. Zaznacz CAŁĄ zawartość (od `-----BEGIN OPENSSH PRIVATE KEY-----` do `-----END OPENSSH PRIVATE KEY-----`)
3. Skopiuj i wklej jako wartość tego sekretu

---

### 10. GCP_SA_KEY (OPCJONALNY)
**Wartość:**
```json
{}
```
*(Możesz wstawić pusty JSON - workflow zadziała bez GCP auth)*

---

## Krok 3: Uruchom workflow SQL, aby utworzyć tabelę w bazie!

**WAŻNE:** Po dodaniu sekretów musisz **ręcznie uruchomić** workflow, który utworzy tabelę w bazie danych!

1. Przejdź do: https://github.com/Daniel-Leat/workflows-actions-LAB/actions
2. Wybierz workflow **"DB Migrations"** z lewego menu
3. Kliknij przycisk **"Run workflow"** (po prawej stronie)
4. Wybierz branch **"main"**
5. Kliknij zielony przycisk **"Run workflow"**
6. Poczekaj ~1-2 minuty aż workflow się wykona
7. Sprawdź czy zakończyło się sukcesem (zielony ✓)

---

## Krok 4: Sprawdź czy działa!

1. Odśwież stronę: http://136.116.111.59:8007
2. **Powinny pojawić się użytkownicy z bazy danych!** 🎉

---

## ❓ Dlaczego to jest potrzebne?

Zgodnie z dokumentacją przykładowego repozytorium (`valdemarcz/uwb_app`):

- Workflow **SQL** (`sql_execution.yml`) wykonuje się **bezpośrednio z GitHub Actions do serwera MySQL**
- **NIE** działa przez serwer VM - łączy się bezpośrednio do `136.114.93.122`
- Dlatego **musisz mieć sekrety `DB_*` w GitHub**
- Bez tego tabela nigdy nie zostanie utworzona w bazie danych
- Aplikacja PHP na serwerze VM próbuje się połączyć, ale tabela nie istnieje!

---

## 🔍 Jak sprawdzić logi workflow?

1. Przejdź do: https://github.com/Daniel-Leat/workflows-actions-LAB/actions
2. Kliknij na ostatnie uruchomienie workflow **"DB Migrations"**
3. Rozwiń poszczególne kroki, aby zobaczyć szczegóły

### Możliwe błędy:

| Błąd | Rozwiązanie |
|------|-------------|
| `Error: Input required and not supplied: credentials_json` | Dodaj sekret `GCP_SA_KEY` z wartością `{}` |
| `Access denied for user 'stud'@...` | Sprawdź czy sekrety `DB_USER` i `DB_PASSWORD` są poprawne |
| `Unknown database '89413'` | Spróbuj zmienić `DB_NAME` na `s89413` lub napisz do wykładowcy o prawidłową nazwę bazy |

---

## Krok 5: Weryfikacja - lista sekretów

Po dodaniu wszystkich sekretów, powinieneś zobaczyć w GitHub:
- ✅ DB_HOST
- ✅ DB_NAME  
- ✅ DB_USER
- ✅ DB_PASSWORD
- ✅ VM_HOST
- ✅ VM_USER
- ✅ VM_SSH_PASSPHRASE
- ✅ APP_PORT
- ✅ VM_SSH_KEY
- ✅ GCP_SA_KEY (opcjonalny)
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
