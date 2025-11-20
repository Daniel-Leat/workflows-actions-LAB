# 👥 Contributors & License

## 📚 Projekt

**LAB5 - Automatyczne wdrożenie aplikacji PHP z GitHub Actions**

Projekt laboratoryjny z przedmiotu "Usługi w Chmurze" 

## 🎓 Informacje akademickie

- **Uczelnia:** Uniwersytet w Białymstoku
- **Laboratorium:** LAB5
- **Temat:** Wdrożenie aplikacji webowej PHP z bazą danych MySQL przy użyciu GitHub Actions

## 🛠️ Wykorzystane technologie

### Backend & Infrastructure
- **PHP** 7.4+ - Język programowania aplikacji
- **MySQL/MariaDB** - System zarządzania bazą danych
- **Apache 2.4+** - Serwer HTTP

### DevOps & CI/CD
- **GitHub Actions** - Automatyzacja wdrożeń
- **SSH** - Bezpieczne połączenie z serwerem
- **Bash** - Skrypty wdrożeniowe

### Narzędzia deweloperskie
- **Git** - Kontrola wersji
- **VS Code** - Edytor kodu

## 📖 Referencje i inspiracje

### Przykładowe repozytorium:
- [valdemarcz/uwb_app](https://github.com/valdemarcz/uwb_app) - Przykładowa aplikacja PHP z instrukcjami wdrożenia

### Dokumentacja:
- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [PHP PDO Documentation](https://www.php.net/manual/en/book.pdo.php)
- [Apache Virtual Hosts Guide](https://httpd.apache.org/docs/2.4/vhosts/)
- [MySQL Documentation](https://dev.mysql.com/doc/)

## 📝 Struktura projektu

```
LAB5/
├── .github/workflows/       # GitHub Actions workflows
│   └── deploy.yml          # Automatyczne wdrożenie
├── sql/                    # Migracje bazy danych
│   ├── 001_create_users_table.sql
│   └── 002_add_more_users.sql
├── index.php              # Główna aplikacja
├── db.php                 # Test połączenia z bazą
├── migrations.php         # System migracji
├── deploy_app.sh          # Skrypt wdrożeniowy
├── README.md              # Dokumentacja główna
├── SETUP_SECRETS.md       # Instrukcja konfiguracji GitHub Secrets
├── QUICK_START.md         # Szybki start
├── DEPLOYMENT_CONFIG.md   # Template konfiguracji
└── .gitignore            # Pliki ignorowane przez Git
```

## 🎯 Funkcjonalności aplikacji

- ✅ Wyświetlanie informacji o serwerze i środowisku PHP
- ✅ Połączenie z zewnętrzną bazą danych MySQL
- ✅ System migracji bazy danych
- ✅ Wyświetlanie danych z bazy (tabela users)
- ✅ Responsywny design UI
- ✅ Automatyczne wdrożenie przez GitHub Actions
- ✅ Logging i monitoring

## 🔐 Bezpieczeństwo

### Implementowane praktyki:
- ✅ Zmienne środowiskowe dla wrażliwych danych
- ✅ GitHub Secrets dla danych uwierzytelniających
- ✅ Prepared statements w PDO (ochrona przed SQL Injection)
- ✅ HTML escaping (ochrona przed XSS)
- ✅ Klucze SSH bez hasła dla automatyzacji
- ✅ `.gitignore` dla plików wrażliwych

### ⚠️ Uwagi bezpieczeństwa:
> **Nigdy nie commituj:**
> - Kluczy SSH (prywatnych)
> - Haseł do bazy danych
> - Tokenów API
> - Plików konfiguracyjnych z danymi wrażliwymi

## 📊 Workflow wdrożenia

```mermaid
graph LR
A[Push do GitHub] --> B[GitHub Actions]
B --> C[Checkout kodu]
C --> D[SSH do serwera]
D --> E[Kopiowanie plików]
E --> F[Konfiguracja Apache]
F --> G[Migracje DB]
G --> H[Test wdrożenia]
H --> I[Aplikacja live!]
```

## 🎓 Cele edukacyjne

Ten projekt demonstruje:

1. **Continuous Deployment (CD)**
   - Automatyczne wdrożenie po push do repository
   - Workflow GitHub Actions
   - Zero-downtime deployment

2. **Infrastructure as Code**
   - Skrypty automatyzujące konfigurację serwera
   - Powtarzalny proces wdrożenia

3. **Bezpieczeństwo w DevOps**
   - Zarządzanie sekretami
   - Bezpieczne połączenia SSH
   - Separacja kodu i konfiguracji

4. **Architektura aplikacji webowych**
   - Backend w PHP
   - Relacyjna baza danych
   - Serwer HTTP Apache

5. **Dobre praktyki programowania**
   - Migracje bazy danych
   - Prepared statements
   - Error handling
   - Logging

## 📄 Licencja

Ten projekt jest stworzony w celach edukacyjnych w ramach zajęć akademickich.

```
MIT License - możesz używać, modyfikować i dystrybuować kod
```

## 🙏 Podziękowania

- Prowadzący zajęcia "Usługi w Chmurze" - UwB
- Społeczność GitHub za dokumentację i przykłady
- Autorzy przykładowego repozytorium [valdemarcz/uwb_app](https://github.com/valdemarcz/uwb_app)

## 📞 Kontakt i wsparcie

### W przypadku problemów:

1. **Sprawdź dokumentację:**
   - `README.md` - Pełna dokumentacja
   - `QUICK_START.md` - Szybki start
   - `SETUP_SECRETS.md` - Konfiguracja

2. **Sprawdź logi:**
   ```bash
   # GitHub Actions
   https://github.com/Daniel-Leat/workflows-actions-LAB/actions
   
   # Logi serwera
   sudo tail -f /var/log/apache2/lab5app_error.log
   ```

3. **Testuj komponenty:**
   - Połączenie SSH
   - Połączenie z bazą danych
   - Konfigurację Apache

## 🔄 Historia wersji

- **v1.0.0** (2024-11-20) - Pierwsza wersja
  - Podstawowa aplikacja PHP z MySQL
  - GitHub Actions workflow
  - Kompletna dokumentacja
  - Skrypty wdrożeniowe

---

**Projekt wykonany w ramach LAB5 - Usługi w Chmurze**

Uniwersytet w Białymstoku © 2024
