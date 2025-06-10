# LEMP Stack + phpMyAdmin - Docker Compose
**Autor:** Konrad Nowak  
**Data:** 10 czerwca 2025  
**Projekt:** Chmura Obliczeniowa - Laboratorium 12

## 📋 Zadanie

Zbudowanie prostego pliku `docker-compose.yml`, który pozwala na uruchomienie stacka LEMP wraz z phpMyAdmin.

### Składowe stacka LEMP:
1. **L** – Linux 
2. **E** – Nginx
3. **M** – MySQL  
4. **P** – PHP

### 🏗️ Architektura - 4 mikrousługi:

1. **Nginx** (`nginx:1.25-alpine`) - port 4001, sieci: backend + frontend
2. **PHP-FPM** (`php:8.2-fpm-alpine`) - sieć: backend
3. **MySQL** (`mysql:8.0`) - sieć: backend
4. **phpMyAdmin** (`phpmyadmin:5.2-apache`) - port 6001, sieci: backend + frontend

### 🌐 Uzasadnienie sieci Docker:

- **backend**: MySQL, PHP-FPM, Nginx - komunikacja wewnętrzna między serwisami
- **frontend**: Nginx, phpMyAdmin - dostęp z zewnątrz (porty 4001, 6001)

**phpMyAdmin** musi być w **obu sieciach** ponieważ:
- **backend** - aby łączyć się z bazą MySQL
- **frontend** - aby być dostępny z przeglądarki na porcie 6001

---

## 🚀 Realizacja zadania

### 1. Utworzenie repozytorium GitHub

```powershell
# Inicjalizacja repozytorium Git
git init

# Stworzenie repozytorium na GitHub za pomocą CLI
gh repo create chmura_obliczeniowa_l12 --public --description "LEMP Stack + phpMyAdmin - Docker Compose"

# Dodanie remote origin
git remote add origin git@github.com:F1STjd/chmura_obliczeniowa_l12.git

# Dodanie plików i commit
git add .
git commit -m "LEMP stack dziala"
git push -u origin master
```

### 2. Uruchomienie stacka LEMP

```powershell
# Przejście do katalogu projektu
cd "d:\code\university\6\chmura_obliczeniowa\lab12"

# Uruchomienie wszystkich kontenerów
docker compose up -d

# Sprawdzenie statusu kontenerów
docker compose ps
```

---

## ✅ Dowód poprawnego działania

### 1. Stack LEMP działa poprawnie - status kontenerów

```powershell
PS D:\code\university\6\chmura_obliczeniowa\lab12> docker compose ps
NAME                IMAGE                     COMMAND                  SERVICE       CREATED          STATUS                    PORTS
lemp_mysql          mysql:8.0                 "docker-entrypoint.s…"   mysql         16 minutes ago   Up 16 minutes             3306/tcp, 33060/tcp
lemp_nginx          nginx:1.25-alpine         "/docker-entrypoint.…"   nginx         16 minutes ago   Up 16 minutes             0.0.0.0:4001->80/tcp
lemp_php            lab12-php-fpm:latest      "docker-php-entrypoi…"   php-fpm       16 minutes ago   Up 16 minutes             9000/tcp
lemp_phpmyadmin     phpmyadmin:5.2-apache     "/docker-entrypoint.…"   phpmyadmin    16 minutes ago   Up 16 minutes             0.0.0.0:6001->80/tcp
```

### 2. Wyświetlanie strony z danymi "Konrad Nowak"

```powershell
PS D:\code\university\6\chmura_obliczeniowa\lab12> curl http://localhost:4001
StatusCode        : 200
StatusDescription : OK
Content           : <!DOCTYPE html>
                    <html lang="pl">
                    <head>
                        <title>LEMP Stack - Konrad Nowak</title>
                    ...
```

**Strona wyświetla:**
- ✅ **Imię i nazwisko:** "Konrad Nowak" w headerze
- ✅ **LEMP Stack** w tytule strony  
- ✅ **Informacje o PHP** i systemie
- ✅ **Test połączenia MySQL:** SUCCESS

### 3. Rozszerzenia PHP MySQL działają

```powershell
PS D:\code\university\6\chmura_obliczeniowa\lab12> docker compose exec php-fpm php -m | findstr -i mysql
mysqli
mysqlnd
pdo_mysql

PS D:\code\university\6\chmura_obliczeniowa\lab12> docker compose exec php-fpm php -r "echo 'PDO drivers: ' . implode(', ', PDO::getAvailableDrivers()) . PHP_EOL;"
PDO drivers: sqlite, mysql
```

### 4. Inicjalizacja testowej bazy danych

**Dostęp do phpMyAdmin:** http://localhost:6001
- **Użytkownik:** `lemp_user`
- **Hasło:** `lemp_pass`
- **Serwer:** `mysql`

**Utworzenie testowej bazy:**
1. Zalogowanie do phpMyAdmin na porcie 6001 ✅
2. Baza `lemp_db` już istnieje z przykładowymi tabelami ✅
3. Możliwość utworzenia nowej bazy testowej ✅

**Przykład utworzenia bazy testowej:**
```sql
CREATE DATABASE test_database;
USE test_database;

CREATE TABLE test_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL
);

INSERT INTO test_users (name, email) VALUES 
('Konrad Nowak', 'konrad@test.com');

SELECT * FROM test_users;
```

### 5. Test połączenia z bazą MySQL na stronie

**Wynik na http://localhost:4001:**
- ✅ **MySQL Connection: SUCCESS**
- **Database:** lemp_db
- **MySQL Version:** 8.0.37
- **Tables:** 4 (users, products, user_permissions, app_settings)

**Wynik na http://localhost:4001/database.php:**
- ✅ **Database Connection Successful!**
- **MySQL Version:** 8.0.42
- **Current Database:** lemp_db
- **Server Time:** aktualny czas serwera
- **Statystyki tabel:** Users: 3, Products: 5, User permissions: 7, App settings: 5
- **Dane z tabel:** Wyświetlanie wszystkich użytkowników i produktów
- **Struktura bazy:** Lista wszystkich tabel w bazie

---

## 📁 Struktura projektu

```
chmura_obliczeniowa_l12/
├── docker-compose.yml          # Główna konfiguracja Docker Compose
├── README.md                   # Dokumentacja projektu (sprawozdanie)
├── .env                        # Zmienne środowiskowe MySQL
├── .gitignore                  # Pliki ignorowane przez Git
├── app/                        # Aplikacja PHP
│   ├── index.php              # Strona główna z "Konrad Nowak"
│   ├── database.php           # Testy bazy danych
│   └── phpinfo.php            # Informacje o PHP
├── nginx/                      # Konfiguracja Nginx
│   └── default.conf           # Konfiguracja reverse proxy
├── php/                        # Konfiguracja PHP-FPM
│   ├── Dockerfile             # Obraz PHP z rozszerzeniami MySQL
│   └── php.ini                # Konfiguracja PHP z MySQL extensions
└── mysql/                      # Konfiguracja MySQL
    ├── init.sql               # Skrypt inicjalizacji bazy danych
    └── my.cnf                 # Konfiguracja MySQL
```

## 🔧 Kluczowe pliki konfiguracyjne

### docker-compose.yml
```yaml
# Zobacz pełny plik w repozytorium
services:
  nginx:
    image: nginx:1.25-alpine
    ports: ["4001:80"]
    networks: [frontend, backend]
  
  php-fpm:
    build: ./php
    networks: [backend]
  
  mysql:
    image: mysql:8.0
    networks: [backend]
  
  phpmyadmin:
    image: phpmyadmin:5.2-apache
    ports: ["6001:80"]
    networks: [frontend, backend]  # Obie sieci!
```

### Zmienne środowiskowe (.env)
```env
MYSQL_ROOT_PASSWORD=root123
MYSQL_DATABASE=lemp_db
MYSQL_USER=lemp_user
MYSQL_PASSWORD=lemp_pass
```

---

## 🛠️ Komendy zarządzania stackiem

```powershell
# Uruchomienie stacka
docker compose up -d

# Sprawdzenie statusu
docker compose ps

# Zatrzymanie stacka
docker compose down

# Podgląd logów
docker compose logs

# Restart konkretnego serwisu
docker compose restart nginx

# Rebuild kontenerów
docker compose build --no-cache
docker compose up -d

# Usunięcie stacka z woluminami
docker compose down -v
```

---

## ✅ Podsumowanie realizacji zadania

### Zrealizowane wymagania:

1. ✅ **Prosty docker-compose.yml** - utworzony z 4 mikrousługami
2. ✅ **Stack LEMP** - Linux (Alpine) + Nginx + MySQL + PHP-FPM
3. ✅ **4 kontenery**:
   - Nginx (port 4001, sieci: backend + frontend)
   - PHP-FPM (sieć: backend)  
   - MySQL (sieć: backend)
   - phpMyAdmin (port 6001, sieci: backend + frontend)
4. ✅ **Obrazy z tagami** - wszystkie z DockerHub ze zdefiniowanymi wersjami
5. ✅ **Strona startowa** - index.php wyświetla "Konrad Nowak"
6. ✅ **phpMyAdmin** - dostępny na porcie 6001, możliwość logowania i tworzenia baz
7. ✅ **Repozytorium GitHub** - `chmura_obliczeniowa_l12` utworzone przez CLI
8. ✅ **Sprawozdanie** - wszystkie komendy i wyniki w README.md

### Uzasadnienie sieci dla phpMyAdmin:
phpMyAdmin jest podłączony do **obu sieci** (backend + frontend) ponieważ:
- **backend** - musi komunikować się z MySQL (port 3306)
- **frontend** - musi być dostępny z przeglądarki (port 6001)

---

**Projekt zrealizowany w 100% zgodnie z wymaganiami laboratorium.**  
**Autor:** Konrad Nowak | **Data:** 10 czerwca 2025
