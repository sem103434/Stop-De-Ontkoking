# Fase 5: Test (Valideren & verbeteren)

Deze pagina bestaat uit vier delen:

1. **Testopzet** – wat we testen, met wie en hoe.
2. **Eisencheck** – welke eisen uit [Define](02-define.md) zijn gebouwd.
3. **Expert review** – bevindingen uit onze eigen controle van de code en het ontwerp.
4. **Test­resultaten & iteratie** – in te vullen tijdens en na de gebruikerstest.

> **Status:** onderdelen 1–3 zijn ingevuld. De gebruikerstest (onderdeel 4) is nog niet uitgevoerd; de resultaattabellen zijn bewust leeg gelaten, zodat we alleen echte testdata invullen.


## 1. Testopzet

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