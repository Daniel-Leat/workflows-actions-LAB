# 🎯 KONFIGURACJA GITHUB SECRETS - FINALNA WERSJA

## ✅ Wartości do wpisania w GitHub Secrets

Przejdź do: **https://github.com/Daniel-Leat/workflows-actions-LAB/settings/secrets/actions**

Kliknij **"New repository secret"** i dodaj każdy z poniższych sekretów:

---

### 1. VM_SSH_KEY

**Wartość:** Cała zawartość klucza SSH (z pliku `gcp_vm_key` w tym katalogu)

> ⚠️ **Skopiuj cały klucz włącznie z BEGIN i END**

---

### 2. VM_SSH_PASSPHRASE

**Wartość:**
```
github
```

---

### 3. VM_HOST

**Wartość:**
```
136.116.111.59
```

---

### 4. VM_USER

**Wartość:**
```
github-actions
```

---

### 5. APP_PORT

**Wartość:**
```
8007
```

> ✅ **Port przypisany do numeru albumu 89413**

---

### 6. DB_HOST

**Wartość:**
```
136.114.93.122
```

---

### 7. DB_NAME

**Wartość:**
```
89413
```

> ✅ **Twój numer albumu**

---

### 8. DB_USER

**Wartość:**
```
stud
```

---

### 9. DB_PASSWORD

**Wartość:**
```
Uwb123!!
```

---

### 10. GCP_SA_KEY (Opcjonalny)

**Wartość:** JSON z Service Account - tylko jeśli chcesz używać automatycznego SQL workflow

> 📝 Można pominąć - SQL będzie wykonywany przez PHP migrations.php

---

## 📋 Checklist - Sekrety do dodania

- [ ] VM_SSH_KEY
- [ ] VM_SSH_PASSPHRASE
- [ ] VM_HOST
- [ ] VM_USER
- [ ] APP_PORT
- [ ] DB_HOST
- [ ] DB_NAME
- [ ] DB_USER
- [ ] DB_PASSWORD
- [ ] GCP_SA_KEY (opcjonalny)

---

## 🚀 Po dodaniu sekretów

```bash
git push origin main
```

GitHub Actions automatycznie wdroży aplikację!

---

## 📌 Dane do sprawozdania

**Repozytorium:** https://github.com/Daniel-Leat/workflows-actions-LAB  
**Aplikacja:** http://136.116.111.59:8007  
**Numer albumu:** 89413  
**Port:** 8007  

**Workflows:**
- `deploy.yml` - Automatyczne wdrożenie PHP na Apache
- `sql_execution.yml` - Automatyczne wykonywanie SQL

**Wyślij na:** v.cerniavski@uwb.edu.pl
