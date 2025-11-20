# 🎯 TEMPLATE KONFIGURACJI - Wypełnij swoimi danymi

## 📋 Twoje dane do wdrożenia

### Informacje o serwerze VM
```
VM Host (IP lub domena): ___________________
VM User (użytkownik SSH): ___________________
VM Password (jeśli używasz): ___________________
```

### Informacje o bazie danych
```
DB Host: ___________________
DB Name (np. numer albumu): ___________________
DB User: ___________________
DB Password: ___________________
```

### Nazwa aplikacji
```
APP_NAME (np. lab5app): ___________________
```

---

## 🔐 GitHub Secrets - Tabela do wypełnienia

| Nazwa sekretu | Wartość (WPISZ TUTAJ) | Status |
|--------------|----------------------|--------|
| SSH_PRIVATE_KEY | [Wklej cały klucz prywatny] | ⬜ |
| VM_HOST | | ⬜ |
| VM_USER | | ⬜ |
| APP_NAME | | ⬜ |
| DB_HOST | | ⬜ |
| DB_NAME | | ⬜ |
| DB_USER | | ⬜ |
| DB_PASSWORD | | ⬜ |

> **Instrukcja:** Wypełnij kolumnę "Wartość" i zaznacz ✅ w kolumnie "Status" po dodaniu do GitHub

---

## 📝 Checklist przed wdrożeniem

### Przygotowanie klucza SSH
- [ ] Wygenerowałem parę kluczy SSH
- [ ] Klucz publiczny jest dodany na serwer VM w `~/.ssh/authorized_keys`
- [ ] Klucz prywatny jest dodany do GitHub Secrets jako `SSH_PRIVATE_KEY`
- [ ] Testowałem połączenie SSH: `ssh -i klucz user@host`

### Przygotowanie serwera
- [ ] Apache jest zainstalowany i uruchomiony
- [ ] PHP jest zainstalowany (min. wersja 7.4)
- [ ] Rozszerzenie PHP MySQL jest zainstalowane
- [ ] Użytkownik SSH ma uprawnienia sudo
- [ ] Port 80 jest otwarty w firewall
- [ ] Katalog `/var/www/` jest dostępny

### Przygotowanie bazy danych
- [ ] Baza danych o nazwie `DB_NAME` istnieje
- [ ] Użytkownik `DB_USER` ma uprawnienia do bazy
- [ ] Testowałem połączenie: `mysql -h DB_HOST -u DB_USER -p`
- [ ] Serwer MySQL akceptuje zdalne połączenia

### Konfiguracja GitHub
- [ ] Repozytorium jest utworzone
- [ ] Wszystkie 8 sekretów jest dodanych w GitHub Secrets
- [ ] Kod jest wypchnienty do brancha `main`
- [ ] Workflow GitHub Actions jest aktywny

---

## 🧪 Komendy testowe

### Test 1: Połączenie SSH
```bash
ssh -i ~/.ssh/twoj_klucz <VM_USER>@<VM_HOST>
```
✅ Oczekiwany wynik: Połączenie bez pytania o hasło

### Test 2: Połączenie z bazą danych
```bash
mysql -h <DB_HOST> -u <DB_USER> -p<DB_PASSWORD> -e "SHOW DATABASES;"
```
✅ Oczekiwany wynik: Lista baz danych zawierająca `<DB_NAME>`

### Test 3: Sprawdzenie Apache
```bash
ssh <VM_USER>@<VM_HOST> "systemctl status apache2"
```
✅ Oczekiwany wynik: Apache active (running)

### Test 4: Sprawdzenie PHP
```bash
ssh <VM_USER>@<VM_HOST> "php -v"
```
✅ Oczekiwany wynik: Wersja PHP 7.4+

---

## 📤 Komendy do wdrożenia

### Wypchniecie kodu do GitHub
```bash
cd /path/to/LAB5
git add .
git commit -m "Initial deployment"
git push origin main
```

### Ręczne wdrożenie (backup plan)
```bash
# Skopiuj pliki na serwer
scp -r * <VM_USER>@<VM_HOST>:/tmp/lab5app/

# Połącz się z serwerem
ssh <VM_USER>@<VM_HOST>

# Uruchom deployment
cd /tmp/lab5app
chmod +x deploy_app.sh
sudo ./deploy_app.sh <APP_NAME> 80

# Skopiuj pliki
sudo cp -r index.php db.php migrations.php sql /var/www/<APP_NAME>/

# Ustaw zmienne i uruchom migracje
export DB_HOST="<DB_HOST>"
export DB_NAME="<DB_NAME>"
export DB_USER="<DB_USER>"
export DB_PASSWORD="<DB_PASSWORD>"
cd /var/www/<APP_NAME>
php migrations.php
```

---

## 🎓 Przykład wypełnienia (dla referencji)

### Przykładowe dane (z instrukcji):
```
VM Host: 136.116.111.59
VM User: github-actions
APP_NAME: lab5app

DB Host: 136.114.93.122
DB Name: student_123456
DB User: stud
DB Password: Uwb123!!
```

### Wygenerowanie klucza SSH:
```bash
ssh-keygen -t rsa -b 4096 -C "github-actions" -f ~/.ssh/lab5_key -N ""
```

### Dodanie klucza na serwer:
```bash
ssh-copy-id -i ~/.ssh/lab5_key.pub github-actions@136.116.111.59
```

### Pobranie klucza prywatnego do GitHub Secrets:
```bash
cat ~/.ssh/lab5_key
```
(Skopiuj całą zawartość włącznie z BEGIN i END)

---

## 📞 Co zrobić po wdrożeniu?

1. **Sprawdź GitHub Actions**
   - https://github.com/Daniel-Leat/workflows-actions-LAB/actions
   - Powinien być zielony checkmark ✅

2. **Otwórz aplikację w przeglądarce**
   - http://<VM_HOST>/
   - Powinieneś zobaczyć ładną stronę z danymi z bazy

3. **Sprawdź logi**
   ```bash
   ssh <VM_USER>@<VM_HOST>
   sudo tail -f /var/log/apache2/<APP_NAME>_error.log
   ```

4. **Testuj bazę danych**
   ```bash
   ssh <VM_USER>@<VM_HOST>
   cd /var/www/<APP_NAME>
   export DB_HOST="<DB_HOST>"
   export DB_NAME="<DB_NAME>"
   export DB_USER="<DB_USER>"
   export DB_PASSWORD="<DB_PASSWORD>"
   php db.php
   ```

---

**Powodzenia! 🚀**

> **Uwaga:** Ten plik zawiera wrażliwe dane. NIE COMMITUJ go do repozytorium!
> Jest już dodany do `.gitignore` jako `*_CONFIG.md`
