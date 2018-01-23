# Financial Avocado

A simple finance managing website built using Symfony framework.

## Opis projektu:
Projekt został stworony z wykorzystaiem Framework'u Symfony 2.8. Dodatkowo wykorzystane zostały:
- FOSUserBundle
- Mysql
- Doctrine
- Bootstrap

Po stworzeniu konta i zalogowaniu się, każdy użytkownik ma możliwość dodawania wydatków oraz zarobków, edycję i usuwanie wczesniejszych wpisów, tworzenie kategorii do których może przypisywać poszczególne wydatki, filtrowanie i sortowanie swoich wpisów po dowolnym parametrze, wyszukiwanie wpisów po nazwie oraz tworzenie raportu z wybranych wpisów, który umożliwa dane ze strony przełożyć do arkusza kalkulacyjnego, w którym możliwa jest głębsza analiza. Został również na stronie zaimplementowany prosty chat, który pozwala wpisac jakiś swój login i pod nim pisać wiadomości.

### Wymagania serwera:
 - php 5.3.9
 - composer 1.5.6
 
### Instalacja:
Aby dokonać instalacji projektu należy wykorzystać komendę:
```bash
$ composer create-project symfony/FinancialAvocado
```
 ### Opis napotkanych problemów:
Niestety nie udało sie dokonac deploymentu projektu na serwer. Związane to jest z całym szeregeiem następujących po sobie problemów, które spróbuje teraz streścić.  
W związku z wykorzystaniem framework'a Symfony wymagany był serwer z php 5.3.9. Niestety żaden z serwerów AGH, do których mam dostęp nie spełnia tego warunku. Postanowiłem zatem wykorzystać serwis Heroku, który umożliwa darmowy hosting stron. Problem jednak polega na tym, iż Heroku jako amerykańska firma ma serwery w USA. Zarówno baza danych AGH, do której mam dostęp jak i wiele darmowych serwisów umożliwających stworzenie bazy danych. Potrzebowałem zatem znaleźć bazę danych, z którą będzie mógł połączyć sie serwer Heroku (gdyż przed dopuszczeniem projektu do upload'u Heroku sprawdza możliwość połączenia się z projektową bazą danych i przypadku niemożności połączenia się z nią odrzuca projekt). Baza danych, która mi Pani udostępniła nie działa. Pomimo, iż na Pani komputerze udało się zalogować przy pomocy moich danych i ping wykazał, że mam łączność z tym serwerem to nie jestem wciąż wstanie zalogować się na to konto z mojego komputera. Ciągle wyświetla się komunikat o odrzucenia próby zalogowania przez serwer. Próbowałem również zagraniczne serwisy udostepniające bazy danych MySQL, jednak napotkałem zadziwiającą ilość problemów. Całościowo spróbowałem ponad 10 takich serwisów i na chwilę obecną z żadnym z nich nie udało mi się stworzyć działającej bazy danych.  
Zastanawiałem się nad wykorzystaniem innego serwisu hostingowego, jednak nie udało mi się znaleźć, żadnego darmowego europejskiego serwisu, który spełniałby wymagania projektu. 
 
