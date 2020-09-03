# Instalacja

 1. Umieszczamy plik installer w katalogu /home

 2. Następnie nadajemy umprawnienia i uruchamiamy skrypt i postępujemy zgodnie z instrukcją:
  ```sh
  > chmod 777 installer
  > ./installer
  ```

 3. Po instalacji wpisujemy komendę
  ```sh
  > cd ts3aB
  ```
  i uruchamiamy bota komendą 
  ```sh
  dotnet TS3AudioBot.dll
  ```
 4. Następnie klikami klawisz "y" i podajemy swoje unikalne id
 5. Następnie wpisujemy adres serwera teamspeak i klkamy enter dwa razy
 6. Gdy bot się połączy piszemy do niego wiadomość
  > !api token
 7. Wyłączamy bota klawiaszami CTRL + C
 8. Wpisujemy komendę
 ```sh
 chmod 777 rights.toml
 ```
 
# Teraz zajmiemy się instalają panelu

  1. Kopiujemy pliki z panelu na serwer gdzie stoją boty lub na hosting
  Uwaga!!!!
  Jeżeli panel nie jest hostowany na innym serwerze niż ten gdzie znajdują się boty pomiń punkt od 2 do 4
  2. Wgrywamy folder ts3abApi na serwer z botami 
  3. Następnie wchodzimy do folderu inc/configs i edytujemy plik config.php
  4. Klucz api do plików botów generujemy w panelu w zakładce ustawienia z typem zewnętrznego serwera
  !!!
  5. Następnie wpisujemy komendę
  ```sh
  echo 'www-data ALL=NOPASSWD: ALL' >> /etc/sudoers
  ```
  6. Przechodzimy do katalogu z panelem i wchodzimy do folderu application/configs
  7. Tam edytujemy plik config.php i zamiast https://example.com wpiujemy adres serwera lub domenę uwzględniając protokół na którym znajduje się panel
  8. Następnie otwieramy plik database.php i na dole wpiujemy dane do bazy danych aby panel mógł się połączyć
  9. Imprtujemy plik database.sql do bazy danych
  10. Logujemy się do panelu loginem admin i hasłem foobar
  11. Przechodzimy do zakładki "Ustawienia" i tam wpisujemy adres serwera i token wygenerowany przez wiadomość wysłaną przez bota
  
# Uwagi!!!!

- Przycisk "Użyj api plików" używamy wtedy gdy nie można nawiązać połączenia z api aplikacji
- Serwer musi mieć otwarty domyślny port `58913` lub inny jeżeli zostanie zmieniony
- Boty posiadają błąd, gdy restartuje się apache2 lub nginx boty natychmiastowo zawiesza i wyłącza
  
