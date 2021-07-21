Základné pokyny: 
Projekty sa budú robiť v 5-členných tímoch, pričom sa počíta s tým, že úlohy si medzi sebou rozdelíte rovnomerne.  
Zadanie je potrebné odovzdať do MS Teams najneskôr do 14.5.2020 (23:55) jedným členom tímu. Neskoršie odovzdanie projektu bude penalizované 1 bodom za každý deň omeškania na každého člena tímu. 
Pre účely ukončenia predmetu je potrebné mať celý projekt umiestnený na školskom serveri.  
Zameranie projektu 
Vytvorte aplikáciu, ktorá umožní online testovanie študentov. Ukážku ako by táto aplikácia mohla fungovať z pohľadu študenta si môžete pozrieť na videu (http://wt3.fei.stuba.sk/ukazka.mp4). Nie je potrebné kopírovať grafiku a celú funkcionalitu. Stačí, keď aplikácia bude spĺňať funkcionálne požiadavky špecifikované nižšie. 
V aplikácii budú 2 role: študent a učiteľ. Každá z nich vyžaduje prihlásenie sa do aplikácie. 
Pohľad študenta 
Do aplikácie sa bude dať prihlásiť na základe na základe preddefinovaného kľúča, ktorý zároveň definuje, aký test sa študentovi zobrazí.  
  
Pri prihlasovaní si študent uvedie svoje meno, priezvisko a identifikačné číslo.  
Po prihlásení sa zobrazí test, na ktorý sa študent prihlásil zadaním kľúča. Všetky otázky v teste sa zobrazia naraz. 
V rámci aplikácie sa môže vyskytnúť 5 typov otázok: 
1.	otázky s otvorenou krátkou odpoveďou (body za ich správnosť automaticky priraďuje aplikácia; v prípade zlého vyhodnotenia, učiteľ môže túto odpoveď prebodovať), 
2.	otázky s výberom správnej odpovede (body za ich správnosť automaticky priraďuje aplikácia), 
3.	párovanie správnych odpovedí (body za ich správnosť automaticky priraďuje aplikácia), 
4.	odpoveď vyžaduje nakreslenie obrázku (body za ich správnosť priraďuje učiteľ), 
5.	odpoveď vyžaduje napísanie matematického výrazu (body za ich správnosť priraďuje učiteľ). 
Možný spôsob zodpovedania otázok je zrejmý z poskytnutého videa. Pri obrázku a matematickom výraze je potrebné umožniť dvojaký spôsob zodpovedania otázky: 
•	otvorením editora, kde sa nakreslí a napíše správna odpoveď. Spôsob uloženia odpovede je ľubovoľný, t.j. nemusí byť rovnaký ako to ukazuje video. Je však potrebné, aby bolo zrejmé, ktorá odpoveď patrí ku ktorej otázke. 
Pre obidva typy editorov existujú dostupné nástroje na Internete (napr. matematický editor http://camdenre.github.io/src/app/html/EquationEditor) 
•	Nakreslenie alebo napísanie odpovede na papier, ktorá sa potom zoskenuje pomocou mobilu a vloží do web aplikácie. 
Ukončenie testu sa bude dať spraviť buď stlačením tlačidla na odoslanie odpovedí alebo po prejdení preddefinovaného času. V prípade druhej možnosti je potrebné uložiť aktuálny stav zodpovedania otázok. Kontrola ubehnutého času bude robená voči času na serveri, t.j. nestačí ju realizovať iba na strane klienta (na strane klienta môže byť realizovaná iba prvotná kontrola). 
Pohľad učiteľa 
Z pohľadu učiteľa je potrebné, aby aplikácia umožnila: 
•	prihlásenie do aplikácie na základe vlastnej registrácie, 
•	zadefinovanie viacerých testov a definovanie, ktoré testy sú aktívne a ktoré nie (každý test má svoj jedinečný kľúč, ktorým sa študent prihlasuje do aplikácie), 
•	pri každom teste bude možné zadefinovať časový limit na jeho vypracovanie, 
•	jednoduché zadefinovanie 5 typov otázok a pri tých otázkach, ktoré sa budú automaticky vyhodnocovať aj zadefinovanie správnych odpovedí, 
•	počas zbiehania testu sledovať, ktorý zo študentov test už ukončil a ktorý ho ešte robí, 
•	počas zbiehania testu dostávať notifikácie, ak niektorý zo študentov opustí tab, v ktorom má spustený test (https://alligator.io/js/page-visibility-api/) 
•	možnosť si prezrieť odpovede každého zo študentov a dohodnotiť otázky, ktoré potrebujú manuálne vyhodnotenie, 
•	možnosť hromadne vyexportovať pre zvolený test otázky a odpovede každého študenta do pdf súboru, 
•	vyexportovať výsledky vo formáte csv, kde v prvom stĺpci bude ID študenta, v druhom a treťom meno a priezvisko a v poslednom stĺpci sumárne bodové hodnotenie za celý test. 
Ďalšie požiadavky 
Vytvorenú aplikáciu je potrebné odovzdať vo forme docker balíčka. V prípade, že tím sa rozhodne nerealizovať túto úlohu príde o 16 bodov. 
Odovzdanie projektu sa robí cez MS Teams a je tam potrebné vložiť: 
•	docker balíček (je možné použiť aj Laravel sail), alebo 
•	spakované súbory vrátane konfiguračného súboru, v ktorom je potrebné definovať všetky nastavenia, 
•	sql súbor pre naplnenie databázy. 
 
Okrem toho pri odovzdávaní je potrebné uviesť: 
•	adresu umiestnenia, aby sme vedeli, pod koho menom máme projekt hľadať, • 	login a heslo pre administrátorský prístup do databázy a do aplikácie, • 	adresu projektu vo verzionovacom systéme.  
Súčasťou projektu bude podstránka s technickou dokumentáciou k projektu a rozdelením úloh medzi jednotlivých členov tímu. 
