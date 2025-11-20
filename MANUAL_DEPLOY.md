# ⚡ SZYBKIE WDROŻENIE RĘCZNE (bez GitHub Actions)

## Krok 1: Pobierz pliki z GitHub

```bash
# Na swoim komputerze lub bezpośrednio na serwerze
git clone https://github.com/Daniel-Leat/workflows-actions-LAB.git
cd workflows-actions-LAB
```

## Krok 2: Prześlij pliki na serwer (jeśli klonowałeś lokalnie)

```bash
# Z Windows PowerShell
scp -r * twoj_user@twoj_serwer_ip:/tmp/lab5app/

# Lub użyj WinSCP / FileZilla do przesłania plików
```

## Krok 3: Na serwerze - Zainstaluj wymagania

```bash
# Połącz się z serwerem
ssh twoj_user@twoj_serwer_ip

# Zainstaluj Apache i PHP
sudo apt update
sudo apt install -y apache2 php php-mysql php-cli libapache2-mod-php mysql-client

# Uruchom Apache
sudo systemctl start apache2
sudo systemctl enable apache2
```

## Krok 4: Wdróż aplikację

```bash
# Przejdź do katalogu z plikami
cd /tmp/lab5app   # lub tam gdzie są pliki

# Uruchom skrypt wdrożeniowy
chmod +x deploy_app.sh
sudo ./deploy_app.sh lab5app 80

# Skopiuj pliki aplikacji
sudo cp -r index.php db.php migrations.php sql /var/www/lab5app/

# Ustaw uprawnienia
sudo chown -R www-data:www-data /var/www/lab5app
sudo chmod -R 755 /var/www/lab5app
```

## Krok 5: Skonfiguruj bazę danych (WAŻNE - ZMIEŃ DANE!)

```bash
# Ustaw zmienne środowiskowe - WPISZ SWOJE DANE!
export DB_HOST="136.114.93.122"           # Adres serwera MySQL
export DB_NAME="student_TWOJ_NUMER"       # ZMIEŃ NA SWÓJ NUMER ALBUMU!
export DB_USER="stud"                      # Użytkownik bazy
export DB_PASSWORD="Uwb123!!"             # Hasło do bazy

# Przejdź do katalogu aplikacji
cd /var/www/lab5app

# Uruchom migracje (utworzy tabele i doda dane)
php migrations.php
```

## Krok 6: Testuj

```bash
# Test połączenia z bazą
php db.php

# Jeśli wyświetli "Connected successfully..." - działa! ✅
```

## Krok 7: Otwórz w przeglądarce

```
http://TWOJ_SERWER_IP/
```

Powinieneś zobaczyć ładną stronę z listą użytkowników! 🎉

---

## ⚠️ NAJWAŻNIEJSZE: Konfiguracja bazy danych

**Musisz mieć:**
1. ✅ Adres serwera MySQL (DB_HOST)
2. ✅ Nazwę bazy danych (DB_NAME) - najczęściej Twój numer albumu
3. ✅ Użytkownika i hasło do bazy (DB_USER, DB_PASSWORD)

Według instrukcji z repozytorium przykładowego:
- DB_HOST: `136.114.93.122`
- DB_USER: `stud`
- DB_PASSWORD: `Uwb123!!`
- DB_NAME: Twój numer albumu (np. `student_123456`)

---

## 🔄 Aktualizacja aplikacji (ręcznie)

Jeśli coś zmienisz w kodzie:

```bash
# Lokalnie
git pull origin main

# Na serwerze
cd /tmp/lab5app
git pull origin main
sudo cp -r index.php db.php migrations.php sql /var/www/lab5app/
sudo systemctl reload apache2
```

---

## 💡 Ta metoda NIE WYMAGA GitHub Secrets!

Możesz wdrożyć aplikację natychmiast bez konfigurowania żadnych sekretów w GitHub.
