# 🎯 KONFIGURACJA GITHUB SECRETS - TWOJE DANE

## ✅ Wartości do wpisania w GitHub Secrets

Przejdź do: **https://github.com/Daniel-Leat/workflows-actions-LAB/settings/secrets/actions**

Kliknij **"New repository secret"** i dodaj każdy z poniższych sekretów:

---

### 1. SSH_PRIVATE_KEY

**Wartość:** Cała zawartość pliku `gcp_vm_key` (włącznie z BEGIN i END)

```
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAACmFlczI1Ni1jdHIAAAAGYmNyeXB0AAAAGAAAABAGf+L5Nv
xstF38YvKlDGz0AAAAEAAAAAEAAAIXAAAAB3NzaC1yc2EAAAADAQABAAACAQCR1n4zL69D
... (cała zawartość klucza) ...
ycAadB4ZsP6oWsSFTAIHvLTXY=
-----END OPENSSH PRIVATE KEY-----
```

> ⚠️ **Skopiuj CAŁY klucz z pliku `gcp_vm_key`**

---

### 2. SSH_PASSPHRASE

**Wartość:**
```
github
```

---

### 3. APP_PORT

**Wartość:** (wybierz wolny port z listy - polecam 8002)
```
8002
```

> 📝 **Uwaga:** Możesz wybrać inny port z listy: 8002, 8003, 8004, 8005, 8006, 8007, 8008, 8009, 8010, 8011, 8012

---

### 4. DB_HOST

**Wartość:**
```
136.114.93.122
```

---

### 5. DB_NAME

**Wartość:** (WPISZ SWÓJ NUMER ALBUMU!)
```
89419
```

> ⚠️ **ZMIEŃ TO NA SWÓJ NUMER ALBUMU!** Według listy portów, może to być jeden z numerów: 89419, 89402, 89428, 89412, 88360, 89413, 88327, 89404, 89403, 89411, 89417

---

### 6. DB_USER

**Wartość:**
```
stud
```

---

### 7. DB_PASSWORD

**Wartość:**
```
Uwb123!!
```

---

## 📋 Checklist konfiguracji

Zaznacz po dodaniu każdego sekretu:

- [ ] **SSH_PRIVATE_KEY** - Cały klucz SSH
- [ ] **SSH_PASSPHRASE** - `github`
- [ ] **APP_PORT** - `8002` (lub inny wolny port)
- [ ] **DB_HOST** - `136.114.93.122`
- [ ] **DB_NAME** - Twój numer albumu (np. `89419`)
- [ ] **DB_USER** - `stud`
- [ ] **DB_PASSWORD** - `Uwb123!!`

---

## 🚀 Po dodaniu sekretów

### Krok 1: Commit i push

```bash
git add .
git commit -m "Update workflows with correct configuration"
git push origin main
```

### Krok 2: GitHub Actions automatycznie wdroży aplikację!

Sprawdź postęp: https://github.com/Daniel-Leat/workflows-actions-LAB/actions

### Krok 3: Otwórz aplikację w przeglądarce

```
http://136.116.111.59:8002
```

(lub inny port jeśli wybrałeś inny niż 8002)

---

## 🧪 Ręczne testowanie (opcjonalne)

Jeśli chcesz przetestować ręcznie przed automatycznym wdrożeniem:

```bash
# Z katalogu LAB5
ssh -i gcp_vm_key github-actions@136.116.111.59
# Hasło: github

# Na serwerze:
cd /tmp
git clone https://github.com/Daniel-Leat/workflows-actions-LAB.git
cd workflows-actions-LAB

# Wdróż
chmod +x deploy_app.sh
sudo ./deploy_app.sh lab5app 8002

# Skopiuj pliki
sudo cp -r index.php db.php migrations.php sql /var/www/lab5app/
sudo chown -R www-data:www-data /var/www/lab5app

# Uruchom migracje
export DB_HOST="136.114.93.122"
export DB_NAME="89419"  # TWÓJ NUMER ALBUMU
export DB_USER="stud"
export DB_PASSWORD="Uwb123!!"
cd /var/www/lab5app
php migrations.php

# Testuj
php db.php
```

---

## 🎓 Informacje do sprawozdania

### Dane serwera:
- **IP:** 136.116.111.59
- **Użytkownik:** github-actions
- **Port aplikacji:** 8002 (lub inny wybrany)
- **Ścieżka:** /var/www/lab5app

### Baza danych:
- **Host:** 136.114.93.122
- **Nazwa:** Twój numer albumu
- **User:** stud
- **Hasło:** Uwb123!!

### Repozytorium:
- **URL:** https://github.com/Daniel-Leat/workflows-actions-LAB
- **Workflows:** 
  - `deploy.yml` - Automatyczne wdrożenie aplikacji
  - `sql_execution.yml` - Wykonywanie skryptów SQL

### URL aplikacji:
```
http://136.116.111.59:8002
```

---

**Gotowe do wysłania na: v.cerniavski@uwb.edu.pl** ✅
