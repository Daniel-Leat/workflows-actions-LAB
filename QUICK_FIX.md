# ⚡ SZYBKI START - Jak uruchomić bazę danych

## 🚨 Problem: Baza danych nie działa?

**Przyczyna:** Brakuje sekretów w GitHub! Workflow SQL nie mógł utworzyć tabeli.

## ✅ Rozwiązanie w 3 krokach:

### 1️⃣ Dodaj sekrety w GitHub (5 minut)

Przejdź do: https://github.com/Daniel-Leat/workflows-actions-LAB/settings/secrets/actions

Kliknij **"New repository secret"** i dodaj każdy z poniższych:

```
DB_HOST = 136.114.93.122
DB_NAME = 89413
DB_USER = stud
DB_PASSWORD = Uwb123!!

VM_HOST = 136.116.111.59
VM_USER = github-actions
VM_SSH_PASSPHRASE = github
APP_PORT = 8007

VM_SSH_KEY = [cała zawartość pliku gcp_vm_key]
GCP_SA_KEY = {}
```

**Szczegółowa instrukcja:** Zobacz `SETUP_SECRETS.md`

---

### 2️⃣ Uruchom workflow SQL (2 minuty)

1. Idź: https://github.com/Daniel-Leat/workflows-actions-LAB/actions
2. Wybierz **"DB Migrations"** z menu po lewej
3. Kliknij **"Run workflow"** (po prawej)
4. Wybierz branch **main**
5. Kliknij zielony przycisk **"Run workflow"**
6. Poczekaj ~1 minutę

✅ Powinno zakończyć się zielonym checkmarkiem

---

### 3️⃣ Sprawdź stronę

Odśwież: **http://136.116.111.59:8007**

🎉 **Powinny się pojawić użytkownicy z bazy danych!**

---

## 📖 Dodatkowa dokumentacja:

- `DLACZEGO_NIE_DZIALA.md` - Szczegółowa analiza problemu
- `SETUP_SECRETS.md` - Instrukcja konfiguracji sekretów
- `README.md` - Pełna dokumentacja projektu
- `FAQ.md` - Często zadawane pytania

---

## 🆘 Nadal nie działa?

### Sprawdź logi workflow:
https://github.com/Daniel-Leat/workflows-actions-LAB/actions

### Możliwe błędy:

| Komunikat | Co zrobić |
|-----------|-----------|
| `Unknown database '89413'` | Zmień `DB_NAME` na `s89413` |
| `Access denied` | Sprawdź czy hasło to `Uwb123!!` |
| `credentials_json required` | Dodaj `GCP_SA_KEY = {}` |

### Napisz do wykładowcy:
📧 v.cerniavski@uwb.edu.pl

```
Proszę o weryfikację:
- Nazwa bazy dla indeksu 89413: czy to "89413" czy "s89413"?
- Czy user "stud" ma uprawnienia z IP GitHub Actions?

Moje repo: https://github.com/Daniel-Leat/workflows-actions-LAB
Aplikacja: http://136.116.111.59:8007
```
