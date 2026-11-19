# Fase 5: Test (Valideren & verbeteren)

Deze pagina bestaat uit vier delen:

1. **Testopzet** – wat we testen, met wie en hoe.
2. **Eisencheck** – welke eisen uit [Define](02-define.md) zijn gebouwd.
3. **Expert review** – bevindingen uit onze eigen controle van de code en het ontwerp.
4. **Test­resultaten & iteratie** – in te vullen tijdens en na de gebruikerstest.

> **Status:** onderdelen 1–3 zijn ingevuld. De gebruikerstest (onderdeel 4) is nog niet uitgevoerd; de resultaattabellen zijn bewust leeg gelaten, zodat we alleen echte testdata invullen.


## 1. Testopze

### 1.1 Wat testen we?

| Onderzoeksvraag | Gekoppeld aan |
|---|---|
| Vinden jongeren binnen 1 minuut een recept dat past bij "iets simpels voor vanavond"? | Must: zoeken, bekijken · Pain: geen inspiratie |
| Is een recept (ingrediënten + stappen) zonder uitleg te volgen? | Must: duidelijke recepten · Pain: moeilijke recepten |
| Begrijpen gebruikers de personen-kiezer en vertrouwen ze de herberekende hoeveelheden? | Eigen toevoeging |
| Lukt registreren en een eigen recept plaatsen zonder hulp? | Must: inloggen · Should: recept toevoegen |
| Werkt alles prettig op een telefoon? | Must: responsive (Mobile First) |

### 1.2 Wie zijn de testpersonen?

* **Aantal:** 5 personen (met 5 testers vind je het merendeel van de grote bruikbaarheidsproblemen).
* **Profiel:** Gen Z, 15–29 jaar, bij voorkeur gelijk aan persona Jayden: weinig kookervaring, beperkt budget, gebruikt de telefoon.
* **Spreiding:** minimaal 3 testen op een telefoon, minimaal 1 persoon met (bijna) geen kookervaring en 1 met wel ervaring.
* **Niet:** teamleden of vrienden die de site al kennen.

### 1.3 Methode

Moderated usability test van ±10 minuten per persoon, hardop denkend:

1. Korte introductie (script hieronder) en toestemming om notities te maken. Resultaten worden anoniem verwerkt.
2. De tester voert de taken uit; de testleider helpt **niet** en noteert wat er gebeurt.
3. Na de taken: 1 vraag per taak (SEQ) en de korte SUS-vragenlijst.
4. Afsluitend gesprek: wat vond je fijn, wat was vervelend?

**Introductiescript:** *"We testen de website, niet jou. Er zijn geen foute antwoorden. Zeg hardop wat je denkt en doet. Als iets niet lukt, is dat nuttige informatie voor ons."*

### 1.4 Taken

| # | Taak (zo voorgelegd aan de tester) | Geslaagd als… | Doel |
|---|---|---|---|
| T1 | "Je hebt zin in iets met pasta. Zoek een recept." | Recept geopend binnen 60 s | Zoeken |
| T2 | "Bekijk alle lunchrecepten en open er één." | Via categorie/filter, binnen 3 klikken | Filteren |
| T3 | "Je kookt voor 4 personen. Hoeveel feta heb je nodig?" | Juiste hoeveelheid genoemd na personen-kiezer | Personen-kiezer |
| T4 | "Hoe lang duurt dit recept en wat doe je als eerste?" | Tijd en eerste stap correct benoemd | Leesbaarheid |
| T5 | "Maak een account aan." | Account aangemaakt zonder hulp | Registreren |
| T6 | "Plaats je eigen recept met een foto." | Recept zichtbaar in overzicht | Recept toevoegen |
| T7 | "Pas je recept aan en verwijder het daarna." | Beide acties gelukt | Beheren |
| T8 | T1 opnieuw, maar op de telefoon | Zelfde succescriterium | Mobiel |

### 1.5 Wat meten we?

* **Taaksucces:** ja / met hulp / nee.
* **Tijd** per taak en **aantal klikken/fouten**.
* **SEQ** (Single Ease Question, 1–7): "Hoe makkelijk was deze taak?" Doel: gemiddeld ≥ 5,5.
* **SUS** (System Usability Scale, 10 vragen): doel ≥ 68 (landelijk gemiddelde); ons streven is ≥ 80.
* **Succescriterium project:** ≥ 80% van de taken zonder hulp geslaagd.

## 2. Eisencheck (gebouwd vs. Programma van Eisen)

Gecontroleerd door de code en pagina's door te lopen. ✅ = aanwezig · 🟡 = gedeeltelijk · ❌ = niet gebouwd.

### Must haves

| Eis | Status | Toelichting |
|---|---|---|
| Responsive (Mobile First) | ✅ | Breekpunten 1000/850/650/580 px; nog te bevestigen in de test (T8) |
| Recepten bekijken | ✅ | Home, overzicht en detailpagina |
| Recepten zoeken | ✅ | Op titel én ingrediënt |
| Ingrediënten en bereidingswijze bij recept | ✅ | Detailpagina |
| Duidelijk en makkelijk te volgen | ✅ | Genummerde stappen, personen-kiezer; te valideren in T3/T4 |
| Inloggen en registreren | ✅ | Wachtwoord gehasht, minimaal 8 tekens |

### Should haves

| Eis | Status | Toelichting |
|---|---|---|
| Recepten filteren | 🟡 | Alleen op categorie; Ideate noemde ook tijd, kosten en moeilijkheid |
| Bereidingstijd tonen | 🟡 | Alleen op de detailpagina, niet op de kaarten |
| Zelf recept toevoegen en delen | ✅ | `invoer.php` met foto-upload; bewerken en verwijderen via detailpagina |

### Could haves

| Eis | Status |
|---|---|
| Favorieten opslaan | ❌ |
| Boodschappenlijstje | ❌ |
| Kookvideo's | ❌ |
| Recept stap voor stap tonen | 🟡 Stappen zijn genummerd, maar er is geen aparte kookmodus |

### Extra's die we bovenop de eisen bouwden

Personen-kiezer met herberekening, Vegan/Halal-badge, beheerpagina voor admins en een ingelogde navigatie.

### Uit Ideate maar (nog) niet gebouwd

| Idee | Status | Opmerking |
|---|---|---|
| **Hottest-systeem** (populairste bovenaan) | ❌ | Home sorteert op nieuwste; er is geen teller die weergaven bijhoudt |
| Reageren op recepten, likes | ❌ | Niet in MoSCoW; kandidaat voor een volgende iteratie |
| Pushmeldingen | ❌ | Valt buiten scope ("geen mobiele app" bij Won't haves) |
| Profielpagina met eigen recepten | 🟡 | `dashboard.php` bestaat, maar werkt nog niet (zie E1) |

## 3. Expert review: bevindingen

Dit is **geen** gebruikerstest, maar een controle door het team van code en ontwerp. Ernst: **4** = blokkeert/kritiek · **3** = ernstig · **2** = hinderlijk · **1** = cosmetisch.

| ID | Bevinding | Ernst | Voorstel |
|---|---|---|---|
| E1 | **Dashboard werkt niet.** `dashboard.php` roept `haal_recepten_van_gebruiker()`, `controleer_csrf()`, `csrf_veld()`, `zet_melding()` en `STATUS_LABELS` aan, maar die staan niet in `includes/functions.php`. Er is ook geen link naar het dashboard in het menu. | 4 | Ontbrekende functies toevoegen en dashboard in het menu zetten, of de pagina tijdelijk verbergen |
| E2 | **`schema.sql` is onvolledig.** Kolommen die de code gebruikt ontbreken: `recepten.standaard_personen`, `recepten.status`, `recepten.weergaven`, `ingredienten.dierlijk`, `ingredienten.varkensvlees`. Een verse installatie geeft fouten. | 4 | Schema bijwerken vanuit de live database (zie [datamodel](04-prototype.md?id=44-datamodel)) |
| E3 | **Databasegegevens staan in de code.** `config/database.php` bevat een vaste gebruikersnaam en wachtwoord als terugval, en die staan in Git. Ook staat er een testbeheerder in `schema.sql`. | 4 | Wachtwoord direct wijzigen, terugval verwijderen, alleen `config.local.php` gebruiken, een `.gitignore` toevoegen (die ontbreekt nu) en het beheerderswachtwoord wijzigen |
| E4 | **Geen CSRF-bescherming** bij verwijderen (`detail.php`), `admin.php`, `invoer.php`, inloggen en registreren. Alleen `dashboard.php` is bedoeld om het wel te hebben. | 3 | Eén CSRF-helper in `functions.php` en overal gebruiken |
| E5 | **Hottest-systeem ontbreekt.** Belofte uit Ideate; `weergaven` wordt nergens opgehoogd. | 2 | Teller in `detail.php`, sorteren op weergaven (en recentheid) op home |
| E6 | **Bereidingstijd niet op de kaart en niet filterbaar**, terwijl de gain "binnen 20 minuten klaar" is. | 2 | Tijd op de kaart tonen en filter "≤ 20 min" toevoegen |
| E7 | **Kleurcontrast te laag** voor kleine tekst (WCAG AA vraagt 4,5 : 1): eyebrow-groen `#6E8C51` op crème = 3,65 : 1, grijsgroen `#718078` = 3,99 : 1, oranje `#EF8752` = 2,45 : 1. | 2 | Tekstkleuren donkerder maken; oranje niet voor tekst of achter witte tekst |
| E8 | **Zware foto's.** Sommige geüploade foto's zijn 0,3 tot 1,4 MB; dat is traag op mobiel. | 2 | Bij uploaden verkleinen (bijv. max. 1200 px breed) |
| E9 | **Kale foutpagina.** Een onbekend recept geeft alleen de tekst "Recept niet gevonden." zonder menu. | 1 | 404-pagina in de huisstijl met link naar het overzicht |
| E10 | **Google Fonts worden extern geladen** (privacy/AVG en afhankelijk van internet). | 1 | Lettertypen zelf hosten |
| E11 | **Dubbele map `includes/`** in de root en in `Geen-Stress-web/` (zelfde bestanden). Risico op uit elkaar lopen. | 1 | Eén versie bewaren |
| E12 | **Ontwerp ≠ bouw bij dashboard-status.** In het ontwerp heeft een recept de status "Online / In review"; in `invoer.php` wordt geen status gezet. | 2 | Kiezen: moderatie bouwen of status uit het ontwerp halen |

## 4. Testresultaten (in te vullen)

### 4.1 Per testpersoon

| Persoon | Leeftijd | Kookervaring | Apparaat | Opmerking |
|---|---|---|---|---|
| P1 | | | | |
| P2 | | | | |
| P3 | | | | |
| P4 | | | | |
| P5 | | | | |

### 4.2 Taaksucces (✅ zonder hulp · 🟡 met hulp · ❌ niet gelukt)

| Taak | P1 | P2 | P3 | P4 | P5 | Succes % | Gem. SEQ | Opvallend |
|---|---|---|---|---|---|---|---|---|
| T1 Zoeken | | | | | | | | |
| T2 Filteren | | | | | | | | |
| T3 Personen-kiezer | | | | | | | | |
| T4 Recept lezen | | | | | | | | |
| T5 Registreren | | | | | | | | |
| T6 Recept toevoegen | | | | | | | | |
| T7 Aanpassen/verwijderen | | | | | | | | |
| T8 Mobiel zoeken | | | | | | | | |

**SUS-score per persoon:** P1 … · P2 … · P3 … · P4 … · P5 … → **gemiddeld:** …

### 4.3 Observaties en citaten

| Persoon | Wat gebeurde er / wat zei de tester? | Gekoppeld aan taak |
|---|---|---|
| | | |

## 5. Iteratie

### 5.1 Verbeterplan op basis van de expert review

| Prio | Verbetering | Bron | Status |
|---|---|---|---|
| 1 | Dashboard repareren of verbergen | E1 | Open |
| 1 | `schema.sql` bijwerken en testen op een lege database | E2 | Open |
| 1 | Databasewachtwoord vervangen, terugval uit code halen | E3 | Open |
| 2 | CSRF-bescherming overal | E4 | Open |
| 2 | Bereidingstijd op kaart + filter "≤ 20 min" | E6 | Open |
| 2 | Kleurcontrast verbeteren | E7 | Open |
| 3 | Hottest-systeem (weergaventeller + sortering) | E5 | Open |
| 3 | Foto's verkleinen bij upload | E8 | Open |
| 4 | 404-pagina, fonts lokaal, dubbele map opruimen | E9–E11 | Open |

### 5.2 Verbeteringen na de gebruikerstest (invullen na sessies)

| Probleem uit test | Aantal testers | Ernst | Wat passen we aan? | Wie | Klaar? |
|---|---|---|---|---|---|
| | | | | | |

### 5.3 Conclusie

*Nog in te vullen na de gebruikerstest: is het doel uit Define (snel en goedkoop een recept vinden zonder gedoe) gehaald? Wat nemen we mee naar een volgende iteratie?*
