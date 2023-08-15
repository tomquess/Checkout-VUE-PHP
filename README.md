# Smartbees Zadanie - Instrukcja instalacji

## Wymagania

Przed przystąpieniem do instalacji upewnij się, że spełnione są następujące wymagania:

- PHP 7.3 lub nowsze
- MySQL
- Serwer Apache lub Nginx

## Przygotowanie aplikacji

1. **Pobranie repozytorium**: Sklonuj to repozytorium na swoje urządzenie.

2. **Przeniesienie folderów**: Przenieś foldery `SmartbeesCheckout-back` oraz `SmartbeesCheckout-front` do ścieżki `/path/xampp/htdocs/`.

3. **Konfiguracja serwera**:

   Skonfiguruj "document roots" dla Twojego serwera:

   - Dla frontendu: `/path/SmartbeesCheckout-back/` z użyciem adresu URL `smartbees-zadanie.local`
   - Dla backendu: `/path/SmartbeesCheckout-front/dist/` z użyciem adresu URL `api.smartbees-zadanie.local`

   Przykład konfiguracji dla serwera Apache:

   ```apache
   # Backend
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

   - Windows: c:\Windows\System32\Drivers\etc\hosts
   - Linux: /etc/hosts
     
     Dodaj następujące wpisy:
    ```
    127.0.0.1   smartbees-zadanie.local
    127.0.0.1   api.smartbees-zadanie.local
    ```
5. **Przejdź w terminalu do folderu SmartbeesCheckout-front i wykonaj**
    ```
    npm install
    
    npm run build
    // Można też wykonać 'npm run dev' ale backend nie będzie poprawnie przyjmował zapytań frontendowych
    ```
6. **Otwórz przeglądarkę i przejdź do adresu smartbees-zadanie.local**
