<!-----------------------------------         HUR MAN SKAPAR DATABASEN        --------------------------------------->

Ladda ner projektet och lägg mappen inuti C:/xampp/htdocs/, starta Apache och MYSQL i XAMPP-programmet. Gå sen till http://localhost/phpmyadmin/ skapa databasen atmproject och klicka på fliken *Importera* högst upp och välj filen 'schema.sql', som ligger i projektets rotmapp och klicka sen på Kör (eller Go). Öppna sen webbläsaren och gå till http://localhost/ATM-Project/. För att fylla på databasen med kunder, konton och transaktioner, öppna din webbläsare och kör seed-filen 'http://localhost/ATM-Project/seed.php', filen körs och ger dig ett bekräftelsemeddelande. Själva applikationen nås i sin helhet via 'http://localhost/ATM-Project/public/index.php'.

<!-----------------------------------         OLIKA INLOGGNING FÖR ATT KOMMA TILL OLIKA ANVÄNDARE   ----------------------->

Systemet är en rollbaserad system uppdelat på administratörer och vanliga bankkunder. Vid testning är det dessa tre olika inloggningar:
Administratör:
    Namn: Sara Eriksson
    Kontonummer: 8400
    PIN-Kod: 3400


2 Vanlig Användare (User):
    Namn: Anna Andersson
    Kontonummer: 9876 
    PIN-Kod: 9800

    Namn: Johan Carlström
    Kontonummer: 3900 
    PIN-Kod: 5400

<!-----------------------------------         HUR ADMINISTRATIONSPANELEN FUNGERAR   ----------------------->

Den fungerar som ett centralt administrationsverktyg där administratören kan söka bland alla transaktioner i systemet, filtrera på transaktionshistoriken baserat på transaktionstyp (t.ex. insättning/uttag), samt specifika datumintervall. Att även kunna se en lista över alla existerande bankkonton och aktuella saldo.


<!-----------------------------------         Hur ROLLEN STYRS OCH VALIDERAS         ----------------------->

Säkerheten av rollerna styrs globalt via funktionen require_role() i auth.php. Den validerar användares roll innan en sida laddas. Alla formulär skyddas med CSRF-Tokens för att förhindra falska förfrågningar, lösenorden valideras säkert med password_verify() mot databasens pin_hash, och databasanrop körs via Prepared Statements i PDO för att eliminera risken för SQL injektioner.