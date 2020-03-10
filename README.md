# pinger
Główną funkcją skryptu jest sprawdzanie indeksacji podstron i pingowanie ich.

Generalnie skrypt dobrze sprawdza się na wszelkiej maści VPS-ach i dedykach, gorzej na share hostach z współdzielonym IP.

aktualna wersja 0.2.2 rc

aktualna wydajność (dla jednej instancji IP):
- indeksacja ok. 4500 stron na dobę
- pingowanie ok. 11000 stron na dobę

Nowa instalacja

1. Należy skopiować wszystkie pliki z paczki na serwer.
2. Opcjonalnie (na niektórych hostach jest to potrzebne) należy nadać prawa 666 dla wszystkich plików z rozszerzeniem txt i 755 dla plików z rozszerzeniem .php.
3. Jeśli mamy kilka instalacji pingera w pliku user.php możemy odkomentować i uzupełnić linię <!-- <li><a href="http://domena.pl/pinger/"><b>drugi pinger</b></a></li>--> co pozwoli nam wygodniej przemieszczać się między kilkom instalacjami pingera.
4. Musimy dodać uruchamiania pliku automat.php do CRONE tak, aby uruchamiał się co minutę, np. w ten sposób (hekko.net, ionic.pl, az.pl):

lynx --dump http://domena.pl/pinger/automat.php >/dev/null 2>&1

lub ten (dust.pl):

wget -O /dev/null http://pinger.szern.pl/automat.php 2>&1

4.1 Uruchomienie cron w home.pl
    - tworzymy plik cron-5min.php w którym umieszczamy:
      <?php
        chdir('katalog'); // podajemy nazwę katalogu w którym znajduje się skrypt pinger
        require("automat.php");
      ?>

5. Jeśli pracujemy na hostingu do którego przypisanych jest więcej IP, tworzymy plik ip.txt w którym wpisujemy numer IP którego ma używać pinger do odpytywania Googli i zapisujemy w folderze pingera.

Upgrade:
Kasujemy na serwerze wszystkie pliki .php ZA WYJĄTKIEM PLIKU: user.php. Wgrywamy pliki ze standardowej paczki pliki na serwerze ZA WYJĄTKIEM PLIKU: user.php który pozostawiamy bez zmian.

todo:

- opcja "indeksacja - tylko Google"
- poprawić błędy z feedbacku 1.8
- napisać instrukcję
- dopisać sprawdzenie poprawności spingowania przed generowaniem raportu
- dopisać przeglądanie i kasowanie archiwalnych logów
- dodać zapis daty zaciągnięcia mapy do logu - dodać ogólny log
- konfiguracja setów pingera nie działa na serwach z mod_security2 (poprawić GET na POST?)
- sprawdzanie PR i BL do raportu indeksacji
- automatyczne wczytywanie map dla strony
- dopisać uaktualnianie sitemap
- zamiana polskich znaków w nazwach plików zapisywanych na dysku

todo wersja serwerowa:

- zapętlanie kolejki,
- dwa panele: admin i user,
- podział skryptu na: panele, dane i automaty,

zrobione:
- konfiguracja strefy czasowej serwera (do raportu)
- zmienić działanie pingera tak, aby pracował również podczas przerwy w indeksacji
- wyświetlanie kolejki na głównej
- kontrolka bana na stronie głównej
- wyświetlanie kolejki na stronie głównej
- bład: nie działają dobowe statystyki
- błąd: przy wczytywaniu mapy dołożyć rozpoznawanie przekierowania np. na www
- błąd przy próbie ustawienia pingowania niezaindeksowanych zanim sprawdziliśmy indeksację,
- moduł importu, eksportu i kasowania projektów,
- raportowanie zakończenia zadania w notesie,
- możliwość tworzenia powtarzalnych zadań dla pingera i dla sprawdzania indeksacji,
- test serwera do pingowania
- bot dla tworzenia mapy strony - wykorzystany zewnętrzny
- poprawiony bład: zaprzestanie pingowania, kiedy skończy się równocześnie działające sprawdzanie indeksacji
- usunięty błąd - przy wyłaczonym zadaniu pingera nie działa również zadanie indeksacji
- automatyczne usuwanie pustych linii z pliku z urlami (mapy)
- opcja pingowanie wszystkich
- wgrywanie linków z własnego pliku
- poprawione zachowanie na końcach pilków
- sprawdzanie przez info
- archiwizacja statystyk (bug),
- wczytywanie sitemapy (bug),
- wczytywanie sitemap <loc> bez względu na podział w wierszach,
- pierwsza konfiguracja (bugi),
- jednoczesna praca na większej ilości DC - przyspieszenie pracy,
- notatnik,
- tester i edytor listy DC (zbędne, robi się automagicznie),
- błąd przy pingowaniu niezaindeksowanych
- bład zapisu bufora indeksacji - zoptymalizować kod (rezygnacja z zapisu niezaindeksowanych, zamiast tego generowanie pliku w razie potrzeby) - dołożyć plik .bak zaindeksowanych
- wyłączanie wyświetlania zaciągniętej mapy (opcja),
- wychodzenie z funkcji do konkretnych modułów,
- wrzucanie do kolejki całej listy,
- korzystanie przez skrypt z większej ilości IP - konfig,

Działa na następujących hostach:
- ionic.pl - wszystkie IP działają jak jedno (dostają wspólnego bana),
- az.pl - bez problemów,
- dust.pl - trzeba zastosować formę CRON podaną powyżej, poza tym bez problemów.

Nie działa na następujących hostach:
- nq.pl - CRON co pięć minut, beznadziejne zasady uprawnienia do plików uniemożliwiają pracę,
- hekko.net - stały ban wywołany przez coś innego,
- nazwa.pl - CRON co godzinę wyklucza efektywne użycie pingera.

Pinger jest wolnym oprogramowaniem; możesz go rozprowadzać dalej i/lub modyfikować na warunkach Powszechnej Licencji Publicznej GNU, wydanej przez Fundację Wolnego Oprogramowania - według wersji 2 tej Licencji lub (według twojego wyboru) którejś z późniejszych wersji.

changelog:
0.3.0 - dopisany mechanizm zmiany DC po otrzymaniu bana, poprawione bugi w sitemapie, poprawione rozpoznawanie bana
0.3.1 - usunięty drobny błąd w automacie
0.4.0 - zmiana ogólnej metody sprwdzania na stosunkowo bezpieczne mieszanie DC, usunięcie kilku bugów
0.1.0 - przede wszystkim usunięte mnóstwo błędów - wyniki są maksymalnie wiarygodne, poprawna pierwsza instalacja, poprawione wczytywanie mapy (dodatkowo można wklejać własne grupy linków), znacznie poprawiony automat, tworzenie pliku raporu zaindeksowanych, zmniejszona liczba plików, licencja Open Source, zmiana numeracji, mksymalna wydajność sprawdzania ok. 14 tysięcy site dziennie (zależy od DC)
0.1.1 - dodane zabezpieczenie przed zawieszaniem działania skryptu na słabszych serwerach i wolniejszych Dc, dodany listing raportu,
0.1.2 - automatyczna eliminacja nieczynnych DC, drobne poprawki w automacie,
0.1.4 - usnięcie błędów odpytań, odpytania przez info, wprowadzenie przerw co 250 zapytań, rozszerzona baza DC, dywersyfikacja nagłówków pingera,
0.1.5 - przerobiony silnik automatu: mniejsza szansa na banowanie, większa precyzja odpytań; dwuzadaniowość - można jednocześnie uruchomić jeden projekt sprawdzania indeksacji i drugi pingowania, oddzielna podstrona do konfiguracji, możliwość pingowania wszystkich adresów projektu bez sprawdzania indeksacji, dodawanie plików do projektu poprzez upload pliku z dysku lokalnego (jeden adres w jednym wierszu),
0.1.6 - poprawka krytyczna usuwającą błąd w automacie powodujący niemal natychmiastowego bana i nie działające pingowanie,
0.1.7 - poprawione błędy w automacie i wczytywaniu sitemapy, rozbudowany i poprawiony tester pingera, dodany zewnętrzny link do generatora sitemap
0.1.8 - możliwość tworzenia powtarzalnych zadań dla pingera i dla sprawdzania indeksacji, raportowanie zakończenia zadania w notesie, moduł importu, eksportu i kasowania projektów, zmniejszona liczba plików instalacyjnych, ostatnia linia logu wyświetlana jest na stronie głównej, poprawiono błędy: błąd przy próbie ustawienia pingowania niezaindeksowanych zanim sprawdziliśmy indeksację, błąd przełącznika funkcji, brak rozpoznawania przekierowania przy wczytywaniu mapy, nie działające dobowe statystyki, błąd generatora useragenta, instalacji
0.1.9 - poprawione liczne drobne błędy, dopracowany interfejs strony głównej, pinger działa również podczas przerw w indeksacji, konfiguracja czasu dla hostów w innej strefie czasowej
0.2.0 - raportowanie RSS, usuwanie wyeksportowanych paczek zip, poprawiony błąd usuwania urli w kolejce pingera, zmiana graficzna i organizacyjna nawigacji, możliwość edycji listy pingerów, wymiana domyślnej listy sererwów RPC, poprawiony błąd czyszczenia niezaindeksowanych po spingowaniu
0.2.1 - usunięto błąd przy pingowaniu niezaindeksowanych, zmieniono sposób generowania pliku niezaindeksowanych adresów, poprawiony błąd przełącznika kolejnych plików w kolejce
0.2.2 - wyłączanie wyświetlania zaciągniętej mapy (opcja), poprawiono wychodzenie z funkcji (do konkretnych modułów), wrzucanie do kolejki całej listy, automatyczne zaczytywanie kolejki przy wyłaczonym działaniu, dodano kilka nowych serwerów RPC
