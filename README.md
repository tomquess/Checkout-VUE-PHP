# Smartbees Zadanie - Instrukcja instalacji

## Wymagania

Przed przystąpieniem do instalacji upewnij się, że spełnione są następujące wymagania:

- PHP 7.3 lub nowsze
- MySQL 10.4.22-MariaDB
- Serwer Apache lub Nginx

## Przygotowanie aplikacji

1. **Pobranie repozytorium**: Sklonuj to repozytorium na swoje urządzenie.

2. **Przeniesienie folderów**: Przenieś foldery `SmartbeesCheckout-back` oraz `SmartbeesCheckout-front` do ścieżki `\path\xampp\htdocs\`.

3. **Konfiguracja serwera**:

   Skonfiguruj "document roots" (`\path\xampp\apache\conf\extra\httpd-vhosts.conf`) dla Twojego serwera:

   - Dla frontendu: `\path\SmartbeesCheckout-back\` z użyciem adresu URL `smartbees-zadanie.local`
   - Dla backendu: `\path\SmartbeesCheckout-front\dist\` z użyciem adresu URL `api.smartbees-zadanie.local`

   Przykład konfiguracji dla serwera Apache:

   ```apache
   # Backend
   # Trzeba pamiętać o podaniu dobrego portu, domyślny to :80, a ja używam :8080
   # Trzeba pamiętać o podaniu dobrej ścieżki
   <VirtualHost api.smartbees-zadanie.local:8080>
       DocumentRoot "F:/XAMPP/htdocs/SmartbeesCheckout-back/"
       ServerName api.smartbees-zadanie.local
   </VirtualHost>

   # Frontend
   <VirtualHost smartbees-zadanie.local:8080>
       DocumentRoot "F:/XAMPP/htdocs/SmartbeesCheckout-front/dist"
       ServerName smartbees-zadanie.local
   </VirtualHost>
   ```
4. **Aktualizacja pliku hosta**:

   Zaktualizuj plik hosta, aby kierował na nowe domeny:

   - Windows: `c:\Windows\System32\Drivers\etc\hosts`
   - Linux: `/etc/hosts`
     
     Dodaj następujące wpisy:
    ```
    127.0.0.1   smartbees-zadanie.local
    127.0.0.1   api.smartbees-zadanie.local
    ```
4. **Zaimportuj bazę danych**:

   Plik do wykonania importu to `checkout-zadanie.sql` w repozytorium.
   
6. **Skonfiguruj połączenie z bazą danych w backendzie, oraz domenę backendu we frontendzie**

   Plik do edycji to `\SmartbeesCheckout-back\classes\dbh.class.php`
    ```
    private $host = "localhost";     //Adres bazy danych
    private $port = "4306";          //Port bazy danych
    private $user = "checkout";      //Nazwa użytnownika który zarządza połączeniamy z bazą danych (Można dać "root")
    private $pwd = "admin";          //Hasło do użytkownika powyżej (Można dać pustego stringa "") 
    private $dbname="checkout";      //Nazwa bazy danych (zostawić tak jak jest)
    ```

   Plik do edycji to `\SmartbeesCheckout-front\.env`
   ```
   VITE_APP_BACKEND_URL='http://api.smartbees-zadanie.local:8080'  //Zmienić port na domyślny :80, ja używam :8080
   ```
5. **Skonfiguruj połączenie z bazą danych w backendzie, oraz domenę backendu we frontendzie**

   Plik do edycji to `\SmartbeesCheckout-back\classes\dbh.class.php`
    ```
    private $host = "localhost";     //Adres bazy danych
    private $port = "4306";          //Port bazy danych
    private $user = "checkout";      //Nazwa użytnownika który zarządza połączeniamy z bazą danych (Można dać "root")
    private $pwd = "admin";          //Hasło do użytkownika powyżej (Można dać pustego stringa "") 
    private $dbname="checkout";      //Nazwa bazy danych (zostawić tak jak jest)
    ```

   Plik do edycji to `\SmartbeesCheckout-front\.env`
   ```
   VITE_APP_BACKEND_URL='http://api.smartbees-zadanie.local:8080'  //Zmienić port na domyślny :80, ja używam :8080
   ```
6. **Przejdź w terminalu do folderu SmartbeesCheckout-front i wykonaj**
    ```
    npm install
    
    npm run build
    // Można też wykonać 'npm run dev' ale backend nie będzie poprawnie przyjmował zapytań frontendowych
    ```
7. **Otwórz przeglądarkę i przejdź do adresu smartbees-zadanie.local**


## Testy

   Aby uruchomić testy przejdź w terminalu do ścieżki `path\SmartbeesCheckout-back`
   i wykonaj:
   ```
   composer update

   ./vendor/bin/phpunit tests/validationtest.php
   ```

## Kody zniżkowe

   W bazie danych w tabeli `discountcodes` znajdują się domyślnie 3 kody testowe:
   - Aktywny kod zniżkowy `discount15` na zniżkę 15%
   - Nieaktywny kod zniżkowy `discount25` na zniżkę 25%
   - Aktywny kod zniżkowy `test` na zniżkę 50%

     Backend też reaguje na podanie kodu nie istniejącego w bazie danych.
