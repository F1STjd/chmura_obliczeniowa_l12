# LEMP Stack + phpMyAdmin - Docker Compose
**Autor:** Konrad Nowak  
**Data:** 10 czerwca 2025  
**Projekt:** Laboratoryjna implementacja stacka LEMP z phpMyAdmin

## 📋 Opis projektu

Kompletny stack LEMP (Linux, Nginx, MySQL, PHP) z interfejsem phpMyAdmin do zarządzania bazą danych, wdrożony przy użyciu Docker Compose v2.

### 🏗️ Architektura

Stack składa się z 4 mikroserwisów:

1. **Nginx** (`nginx:1.25-alpine`) - Reverse proxy i serwer statyczny
2. **PHP-FPM** (`php:8.2-fpm-alpine`) - Interpreter PHP
3. **MySQL** (`mysql:8.0`) - Serwer bazy danych
4. **phpMyAdmin** (`phpmyadmin:5.2-apache`) - GUI do zarządzania bazą

### 🌐 Sieci Docker

- **frontend** - dostępna dla Nginx i phpMyAdmin (publiczny dostęp)
- **backend** - wewnętrzna komunikacja między serwisami (MySQL, PHP-FPM, Nginx)

### 🚪 Porty

- **Nginx**: `http://localhost:4001`
- **phpMyAdmin**: `http://localhost:6001`

---

## 🚀 Uruchomienie projektu

### Wymagania wstępne
- Docker Desktop (Windows)
- Docker Compose v2
- Git

### 1. Klonowanie i inicjalizacja repozytorium

```powershell
# Inicjalizacja repozytorium Git
git init
git remote add origin git@github.com:F1STjd/chmura_obliczeniowa_l12.git

# Dodanie plików do repozytorium
git add .
git commit -m "Initial commit with working LEMP stack + phpMyAdmin"
git push -u origin master
```

### 2. Uruchomienie stacka

```powershell
# Zbudowanie i uruchomienie wszystkich kontenerów
docker compose up -d

# Sprawdzenie statusu kontenerów
docker compose ps

# Podgląd logów
docker compose logs

# Sprawdzenie działania aplikacji
curl http://localhost:4001

# Dostęp do phpMyAdmin w przeglądarce
start http://localhost:6001
```

---

## ✅ Weryfikacja działania

### 1. Status kontenerów
```powershell
PS D:\code\university\6\chmura_obliczeniowa\lab12> docker compose ps
NAME                IMAGE                     COMMAND                  SERVICE       CREATED          STATUS                    PORTS
lemp_mysql          mysql:8.0                 "docker-entrypoint.s…"   mysql         16 minutes ago   Up 16 minutes             3306/tcp, 33060/tcp
lemp_nginx          nginx:1.25-alpine         "/docker-entrypoint.…"   nginx         16 minutes ago   Up 16 minutes             0.0.0.0:4001->80/tcp
lemp_php            lab12-php-fpm:latest      "docker-php-entrypoi…"   php-fpm       16 minutes ago   Up 16 minutes             9000/tcp
lemp_phpmyadmin     phpmyadmin:5.2-apache     "/docker-entrypoint.…"   phpmyadmin    16 minutes ago   Up 16 minutes             0.0.0.0:6001->80/tcp
```

### 2. Test połączenia z aplikacją
```powershell
PS D:\code\university\6\chmura_obliczeniowa\lab12> curl http://localhost:4001
StatusCode        : 200
StatusDescription : OK
Content           : <!DOCTYPE html>
                    <html lang="pl">
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>LEMP Stack - Konrad Nowak</title>
                    ...
```

### 3. Sprawdzenie rozszerzeń PHP MySQL
```powershell
PS D:\code\university\6\chmura_obliczeniowa\lab12> docker compose exec php-fpm php -m | findstr -i mysql
mysqli
mysqlnd
pdo_mysql

PS D:\code\university\6\chmura_obliczeniowa\lab12> docker compose exec php-fpm php -r "echo 'PDO drivers: ' . implode(', ', PDO::getAvailableDrivers()) . PHP_EOL;"
PDO drivers: sqlite, mysql
```

### 4. Test połączenia z bazą danych

**Wyniki na stronie `http://localhost:4001`:**
- ✅ **MySQL Connection: SUCCESS**
- **Database:** lemp_db
- **MySQL Version:** 8.0.37
- **Tables:** 4

**phpMyAdmin (`http://localhost:6001`):**
- Dostęp z użytkownikiem: `lemp_user` / hasło: `lemp_pass`
- Baza danych `lemp_db` zawiera 4 tabele:
  - `users` (3 rekordy)
  - `products` (5 rekordów)
  - `user_permissions` (7 rekordów)
  - `app_settings` (5 rekordów)

---

## 🗂️ Struktura projektu

```
chmura_obliczeniowa_l12/
├── docker-compose.yml          # Główna konfiguracja Docker Compose
├── README.md                   # Dokumentacja projektu
├── .env                        # Zmienne środowiskowe (opcjonalne)
├── app/                        # Aplikacja PHP
│   ├── index.php              # Strona główna z testami
│   ├── database.php           # Testy bazy danych
│   └── phpinfo.php            # Informacje o PHP
├── nginx/                      # Konfiguracja Nginx
│   └── default.conf           # Konfiguracja wirtualnego hosta
├── php/                        # Konfiguracja PHP-FPM
│   ├── Dockerfile             # Obraz PHP z rozszerzeniami MySQL
│   └── php.ini                # Konfiguracja PHP
└── mysql/                      # Konfiguracja MySQL
    ├── init.sql               # Skrypt inicjalizacji bazy
    └── my.cnf                 # Konfiguracja MySQL
```

---

## 🔧 Konfiguracja

### Zmienne środowiskowe MySQL
```yaml
MYSQL_ROOT_PASSWORD: root123
MYSQL_DATABASE: lemp_db
MYSQL_USER: lemp_user
MYSQL_PASSWORD: lemp_pass
```

### Sieć Docker
- **frontend**: nginx, phpmyadmin
- **backend**: nginx, php-fpm, mysql, phpmyadmin

### Woluminy
- **mysql_data**: Persistentne dane MySQL
- **app**: Kod aplikacji PHP (bind mount)
- **nginx config**: Konfiguracja Nginx (bind mount)

---

## 🛠️ Zarządzanie

### Podstawowe komendy

```powershell
# Uruchomienie stacka
docker compose up -d

# Zatrzymanie stacka
docker compose down

# Przebudowanie kontenerów
docker compose build --no-cache
docker compose up -d

# Podgląd logów konkretnego serwisu
docker compose logs nginx
docker compose logs php-fpm
docker compose logs mysql
docker compose logs phpmyadmin

# Dostęp do kontenera
docker compose exec php-fpm sh
docker compose exec mysql mysql -u lemp_user -p lemp_db

# Usunięcie stacka wraz z woluminami
docker compose down -v
```

### Debugowanie

```powershell
# Sprawdzenie stanu kontenerów
docker compose ps -a

# Sprawdzenie zasobów
docker compose top

# Sprawdzenie sieci
docker network ls | findstr lemp

# Test połączeń sieciowych
docker compose exec php-fpm ping mysql
docker compose exec nginx ping php-fpm
```

---

## 🎯 Funkcjonalności

### ✅ Zrealizowane wymagania

1. **Stack LEMP**: ✅ Linux (Alpine) + Nginx + MySQL + PHP
2. **Porty**: ✅ Nginx:4001, phpMyAdmin:6001
3. **Sieci**: ✅ frontend/backend poprawnie skonfigurowane
4. **phpMyAdmin**: ✅ Dostęp do MySQL z obu sieci
5. **Zmienne środowiskowe**: ✅ MySQL konfigurowane przez ENV
6. **Restart policy**: ✅ `unless-stopped` dla wszystkich serwisów
7. **PHP-MySQL**: ✅ PDO i MySQLi działają poprawnie
8. **Pełna funkcjonalność**: ✅ Wszystko działa po `docker compose up`

### 📊 Metryki wydajności

- **Czas uruchomienia**: ~30-45 sekund
- **Użycie pamięci**: ~800MB (wszystkie kontenery)
- **Rozmiar obrazów**: 
  - PHP-FPM: 803MB (z rozszerzeniami)
  - Nginx: 43MB
  - MySQL: 685MB
  - phpMyAdmin: 562MB

---

## 🚀 Deployment

### Produkcja

Dla środowiska produkcyjnego zaleca się:

1. **Bezpieczeństwo**:
   - Zmiana domyślnych haseł
   - Usunięcie phpMyAdmin
   - Konfiguracja SSL/TLS
   - Ograniczenie dostępu do bazy

2. **Wydajność**:
   - Konfiguracja cache (Redis/Memcached)
   - Optymalizacja MySQL
   - Load balancing dla Nginx
   - Monitoring i logi

3. **Skalowalność**:
   - Zewnętrzna baza danych
   - Kubernetes deployment
   - CI/CD pipeline
   - Backup strategy

---

## 📝 Changelog

### v1.0.0 (2025-06-10)
- ✅ Pierwotna implementacja stacka LEMP
- ✅ Dodanie phpMyAdmin
- ✅ Konfiguracja sieci Docker
- ✅ Implementacja testów bazy danych
- ✅ Rozszerzenia PHP MySQL (PDO, MySQLi)
- ✅ Dokumentacja i automatyzacja

---

## 👨‍💻 Autor

**Konrad Nowak**  
Projekt laboratoryjny - Chmura Obliczeniowa  
Uniwersytet - Semestr 6  
Data: 10 czerwca 2025

## 📄 Licencja

Projekt edukacyjny - użytkowanie zgodnie z celami dydaktycznymi.

```powershell
# Uruchomienie w trybie detached (w tle)
docker compose up -d

# Lub z logami w czasie rzeczywistym
docker compose up
```

### 3. Sprawdzenie statusu kontenerów

```powershell
# Lista uruchomionych kontenerów
docker compose ps

# Szczegółowe informacje o kontenerach
docker ps --format "table {{.Names}}\t{{.Image}}\t{{.Status}}\t{{.Ports}}"
```

**Oczekiwany wynik:**
```
NAME                IMAGE                    STATUS          PORTS
lemp_nginx          nginx:1.25-alpine        Up 30 seconds   0.0.0.0:4001->80/tcp
lemp_php            php:8.2-fmp-alpine       Up 30 seconds   9000/tcp
lemp_mysql          mysql:8.0                Up 30 seconds   3306/tcp, 33060/tcp
lemp_phpmyadmin     phpmyadmin:5.2-apache    Up 30 seconds   0.0.0.0:6001->80/tcp
```

---

## 🧪 Testowanie funkcjonalności

### 1. Test strony PHP

```powershell
# Test przez curl
curl http://localhost:4001

# Lub otwórz w przeglądarce: http://localhost:4001
```

**Oczekiwany wynik:**
- Strona wyświetla: "🚀 LEMP Stack - Konrad Nowak"
- Informacje o PHP i systemie
- Test połączenia z bazą MySQL
- Linki do phpMyAdmin

### 2. Test phpMyAdmin

```powershell
# Otwórz w przeglądarce
start http://localhost:6001
```

**Dane logowania:**
- **Użytkownik:** `lemp_user`
- **Hasło:** `lemp_pass`
- **Serwer:** `mysql`

### 3. Sprawdzenie logów

```powershell
# Logi wszystkich serwisów
docker compose logs

# Logi konkretnego serwisu
docker compose logs nginx
docker compose logs php-fpm
docker compose logs mysql
docker compose logs phpmyadmin
```

### 4. Test komunikacji między kontenerami

```powershell
# Wejście do kontenera PHP i test połączenia z MySQL
docker compose exec php-fpm sh
# Wewnątrz kontenera:
# ping mysql
# exit
```

---

## 🗄️ Konfiguracja bazy danych

### Utworzenie testowej bazy danych przez phpMyAdmin

1. Otwórz http://localhost:6001
2. Zaloguj się używając danych: `lemp_user` / `lemp_pass`
3. Kliknij "New" (Nowa)
4. Wprowadź nazwę: `test_database`
5. Kliknij "Create" (Utwórz)

### Przykładowa tabela testowa

```sql
USE test_database;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name, email) VALUES 
('Konrad Nowak', 'konrad.nowak@example.com'),
('Jan Kowalski', 'jan.kowalski@example.com');

SELECT * FROM users;
```

---

## 🔧 Zarządzanie stackiem

### Zatrzymanie stacka

```powershell
# Zatrzymanie kontenerów (zachowanie danych)
docker compose stop

# Zatrzymanie i usunięcie kontenerów (zachowanie wolumenów)
docker compose down

# Usunięcie kontenerów, sieci i wolumenów
docker compose down -v
```

### Restart pojedynczego serwisu

```powershell
# Restart konkretnego serwisu
docker compose restart nginx
docker compose restart php-fpm
docker compose restart mysql
```

### Rebuild i restart

```powershell
# Rebuild i restart całego stacka
docker compose up -d --build --force-recreate
```

---

## 📁 Struktura projektu

```
d:\code\university\6\chmura_obliczeniowa\lab12\
├── docker-compose.yml          # Główny plik konfiguracyjny
├── nginx/
│   └── default.conf           # Konfiguracja Nginx
├── app/
│   └── index.php             # Aplikacja PHP
└── README.md                 # Dokumentacja (ten plik)
```

---

## 🔐 Zmienne środowiskowe

Stack używa następujących zmiennych (z wartościami domyślnymi):

```env
MYSQL_ROOT_PASSWORD=root123
MYSQL_DATABASE=lemp_db
MYSQL_USER=lemp_user
MYSQL_PASSWORD=lemp_pass
```

Aby zmienić wartości, utwórz plik `.env`:

```env
MYSQL_ROOT_PASSWORD=twoje_haslo_root
MYSQL_DATABASE=twoja_baza
MYSQL_USER=twoj_uzytkownik
MYSQL_PASSWORD=twoje_haslo
```

---

## 🐛 Rozwiązywanie problemów

### Kontener nie startuje

```powershell
# Sprawdź logi błędów
docker compose logs [nazwa_serwisu]

# Sprawdź status portów
netstat -an | findstr :4001
netstat -an | findstr :6001
```

### Problem z uprawnieniami (MySQL)

```powershell
# Restart MySQL z czyszczeniem danych
docker compose down -v
docker compose up -d
```

### Błąd połączenia PHP-MySQL

```powershell
# Sprawdź czy kontenery są w tej samej sieci
docker network ls
docker network inspect lemp_backend
```

---

## ✅ Potwierdzenie działania

Po poprawnym uruchomieniu:

1. ✅ **Nginx**: Strona dostępna na http://localhost:4001
2. ✅ **PHP**: Wyświetla "Konrad Nowak" i informacje systemowe
3. ✅ **MySQL**: Połączenie z bazą danych działa
4. ✅ **phpMyAdmin**: Interfejs dostępny na http://localhost:6001
5. ✅ **Baza testowa**: Możliwość utworzenia i zarządzania bazami danych

---

## 📚 Dodatkowe informacje

- **Wersja Docker Compose:** 3.8
- **Strategia restartów:** `unless-stopped`
- **Wolumeny:** Dane MySQL są persistentne
- **Sieci:** Izolacja między frontend/backend
- **Bezpieczeństwo:** Podstawowe nagłówki bezpieczeństwa w Nginx

---

**Projekt zrealizowany zgodnie z wymaganiami laboratorium Cloud Computing.**
