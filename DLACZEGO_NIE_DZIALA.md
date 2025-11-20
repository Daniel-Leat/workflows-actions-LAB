# 🔍 Dlaczego baza danych nie działa? - ANALIZA

## Problem:
Strona http://136.116.111.59:8007 pokazuje błąd:
```
✗ Error: SQLSTATE[HY000] [1045] Access denied for user 'stud'@'136.116.111.59'
```

## Analiza przykładowego repozytorium `valdemarcz/uwb_app`:

### ✅ Co działa poprawnie u Ciebie:
1. ✅ Aplikacja PHP jest wdrożona na serwerze VM (port 8007)
2. ✅ Apache działa poprawnie
3. ✅ PHP 8.2.29 jest zainstalowany
4. ✅ Pliki są na serwerze w `/var/www/lab5app`
5. ✅ Workflow deployment działa

### ❌ Co NIE działa:
1. ❌ **Tabela `users` nie istnieje w bazie danych!**
2. ❌ **Workflow SQL nigdy się nie wykonał!**
3. ❌ **Brakuje sekretów GitHub!**

## 🎯 Rozwiązanie problemu:

### Krok 1: Zrozumienie architektury
Według dokumentacji `valdemarcz/uwb_app`:

```
┌─────────────────┐         ┌──────────────────┐
│  GitHub Actions │────────>│  MySQL Database  │
│   (SQL Script)  │         │  136.114.93.122  │
└─────────────────┘         └──────────────────┘
                                     ↑
                                     │
                            ┌────────┴─────────┐
                            │   PHP App on VM  │
                            │ 136.116.111.59   │
                            └──────────────────┘
```

**Workflow SQL wykonuje się BEZPOŚREDNIO z GitHub Actions do MySQL!**
- NIE przez serwer VM
- NIE przez SSH
- Bezpośrednie połączenie MySQL z GitHub Actions

### Krok 2: Co musisz zrobić TERAZ:

#### A. Dodaj GitHub Secrets (WYMAGANE!)

Przejdź do: https://github.com/Daniel-Leat/workflows-actions-LAB/settings/secrets/actions

Dodaj następujące sekrety:

| Nazwa | Wartość | Cel |
|-------|---------|-----|
| `DB_HOST` | `136.114.93.122` | Adres MySQL serwera |
| `DB_NAME` | `89413` | Twoja baza (numer indeksu) |
| `DB_USER` | `stud` | User MySQL |
| `DB_PASSWORD` | `Uwb123!!` | Hasło MySQL |
| `VM_HOST` | `136.116.111.59` | IP serwera VM |
| `VM_USER` | `github-actions` | SSH user |
| `VM_SSH_PASSPHRASE` | `github` | SSH passphrase |
| `VM_SSH_KEY` | *(cała zawartość `gcp_vm_key`)* | Klucz SSH |
| `APP_PORT` | `8007` | Twój port |
| `GCP_SA_KEY` | `{}` | Pusty JSON (opcjonalny) |

#### B. Uruchom workflow SQL ręcznie

1. Przejdź: https://github.com/Daniel-Leat/workflows-actions-LAB/actions
2. Wybierz **"DB Migrations"**
3. Kliknij **"Run workflow"** → **"Run workflow"**
4. Poczekaj 1-2 minuty
5. Sprawdź czy zakończyło się ✓ (zielony)

#### C. Odśwież stronę

Po wykonaniu workflow SQL, odśwież: http://136.116.111.59:8007

**Teraz powinno działać! 🎉**

---

## 📊 Porównanie z przykładem:

### Przykład `valdemarcz/uwb_app`:
- ✅ Ma wszystkie sekrety GitHub skonfigurowane
- ✅ Workflow SQL tworzył tabelę przed uruchomieniem aplikacji
- ✅ Aplikacja łączy się do już istniejącej bazy

### Twoje repozytorium (przed naprawą):
- ❌ Brak sekretów GitHub
- ❌ Workflow SQL nigdy nie wykonał się (bo brak sekretów)
- ❌ Aplikacja próbuje połączyć się z bazą, ale tabela nie istnieje

---

## 🔍 Dlaczego wykładowca powiedział "powinno działać"?

Bo struktura jest poprawna! Problem nie jest w kodzie, tylko w konfiguracji:
1. ✅ Workflows są poprawnie napisane
2. ✅ Aplikacja PHP ma prawidłowy kod
3. ✅ SQL skrypty są poprawne
4. ❌ **Tylko brakuje sekretów w GitHub!**

To jest typowy problem deployment - **wszystko jest gotowe, ale nie uruchomione**.

---

## ✅ Checklist - co zrobić:

- [ ] Dodać 10 sekretów w GitHub (patrz wyżej)
- [ ] Uruchomić workflow "DB Migrations" ręcznie
- [ ] Sprawdzić logi workflow (czy zakończyło się sukcesem)
- [ ] Odświeżyć stronę http://136.116.111.59:8007
- [ ] Zrobić screenshot działającej strony z użytkownikami
- [ ] Wysłać sprawozdanie do wykładowcy

---

## 📧 Co napisać do wykładowcy (jeśli nadal problem):

```
Temat: LAB5 - Problem z dostępem do bazy danych MySQL

Dzień dobry,

Mam problem z dostępem do bazy danych dla indeksu 89413.

Aplikacja PHP działa na: http://136.116.111.59:8007
Workflow SQL próbuje utworzyć tabelę, ale otrzymuję błąd:
"Access denied for user 'stud'@'136.116.111.59'"

Pytania:
1. Czy nazwa bazy to "89413", "s89413", czy inna?
2. Czy użytkownik "stud" ma uprawnienia z IP 136.116.111.59?
3. Czy workflow SQL powinien łączyć się z GitHub Actions (różne IP)?

GitHub repo: https://github.com/Daniel-Leat/workflows-actions-LAB
```

---

## 💡 Kluczowe wnioski:

1. **GitHub Secrets są WYMAGANE** - bez nich workflow nie zadziała
2. **Workflow SQL tworzy strukturę bazy** - musi się wykonać przed pierwszym użyciem aplikacji
3. **Aplikacja PHP tylko czyta dane** - nie tworzy tabel
4. **Deployment ≠ Konfiguracja** - kod jest wdrożony, ale baza nie skonfigurowana
