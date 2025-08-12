<?php
    header("Access-Control-Allow-Origin: https://hochautomatisiert.at/");
    session_start();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">
    <title>Automatisierung für KMUs</title>
    <meta name="description" content="Steigern Sie Effizienz und senken Sie Kosten mit maßgeschneiderten KI-Lösungen für die Optimierung von Geschäftsprozessen.">
    <link rel="stylesheet" href="/style/styles.css">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@700&display=swap" rel="stylesheet">
</head>
<body>

    <nav>
        <div class="nav-container">
            <div class="logo-title">
                <a class="home-link" href="/">
                    <img src="images/logo.png" alt="Logo" class="logo" />
                    <span class="title">hochautomatisiert.at</span>
                </a>
            </div>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="#produkt">Produkt</a></li>
                <li><a href="#ueberuns">Über uns</a></li>
                <li><a href="#loesungen">Unsere Lösungen</a></li>
                <li><a href="#kontakt">Kontakt</a></li>
            </ul>
        </div>
    </nav>

    <main>

        <h1>Ihre Prozesse. Unsere KI. Klare Resultate.</h1>

        <section id="produkt">
            <h2>Maßgeschneiderte Automatisierung für kleine und mittlere Unternehmen</h2>
            <p>
                Erhöhen Sie Ihre operative Effizienz durch gezielt eingesetzte <strong>generative Künstliche Intelligenz</strong>.<br>
                Wir identifizieren Optimierungspotenziale, entwickeln individuelle Lösungen und schaffen intelligente Systeme, die Ihre Arbeitsabläufe nachhaltig vereinfachen und beschleunigen.<br>
                Mit klarer Struktur und praxisnaher Umsetzung unterstützen wir KMUs auf ihrem Weg in eine produktivere Zukunft.
            </p>
        </section>

        <section id="ueberuns">
            <h2>Wer wir sind – und wofür wir stehen</h2>
            <p>hochautomatisiert.at ist eine spezialisierte Agentur für die intelligente Automatisierung von Geschäftsprozessen in kleinen und mittleren Unternehmen.</p>
            <p>Wir entwickeln und implementieren <strong>maßgeschneiderte KI-Lösungen</strong>, die exakt auf die Strukturen, Ziele und Abläufe unserer Kunden abgestimmt sind. Unser Anspruch ist es, Künstliche Intelligenz <strong>strategisch sinnvoll, nachhaltig und effizient</strong> einzusetzen – mit Fokus auf konkrete Resultate und messbaren Mehrwert.</p>
            <p>Unser Arbeitsstil ist klar strukturiert, verständlich kommuniziert und konsequent lösungsorientiert. Dabei begleiten wir Unternehmen <strong>partnerschaftlich durch alle Phasen der digitalen Transformation</strong> – von der Analyse über die Umsetzung bis zur Integration in den Alltag.</p>
            <p>Wir stehen für praxisnahe KI-Anwendungen, die echten Fortschritt ermöglichen – technologisch fundiert, menschlich greifbar und unternehmerisch wirksam.</p>
        </section>

        <section id="loesungen">
            <h2>Unsere Lösungen: Der passende Einstieg in Ihre KI-Zukunft</h2>
            <p>Ob Sie erste Schritte in die Welt der Künstlichen Intelligenz wagen oder Ihr gesamtes Unternehmen transformieren möchten – wir bieten Ihnen die passende Lösung. Wählen Sie das Programm, das am besten zu Ihren Zielen passt:</p>
            <div class="solutions-table-wrapper">
                <table class="solutions-table">
                    <tr>
                        <td>
                        <a href="/unser-angebot#quickstart" class="solution-link">
                            <img src="/images/Prozess-AutoPilot-30.png" alt="Prozess-AutoPilot 30" class="solution-image">
                        </a>
                        </td>
                        <td>
                        <a href="/unser-angebot#sprint" class="solution-link">
                            <img src="/images/AI-transformation-sprint.png" alt="AI Transformation Sprint" class="solution-image">
                        </a>
                        </td>
                    </tr>
                </table>
            </div>
            <p>Unabhängig davon, wo Sie heute stehen – wir holen Sie genau dort ab und führen Ihr Unternehmen Schritt für Schritt in eine produktivere Zukunft.</p>
            <p>Vereinbaren Sie jetzt ein kostenloses und unverbindliches Erstgespräch.<br>
            Lassen Sie uns gemeinsam herausfinden, welcher Weg für Ihr Unternehmen der richtige ist.</p>
            <a href="unser-angebot#erstgespraech" class="cta-button">Erstgespräch vereinbaren</a>
        </section>

        <section id="kontakt">
            <h2>Kontaktieren Sie uns</h2>
            <p>Senden Sie uns eine <a href="mailto:kontakt@hochautomatisiert.at">Email</a> oder schreiben Sie uns eine Nachricht:</p>
            <form id="send-form" name="send-form">

                <input type="text" id="first_name" name="first_name" placeholder="Vorname*" class="form-group" >

                <input type="text" id="last_name" name="last_name" placeholder="Nachname*" class="form-group" >

                <input type="email" id="email" name="email" placeholder="E-Mail*" class="form-group" >

                <input type="text" id="phone_number" name="phone_number" placeholder="Tel.Nr*" class="form-group" required>

                <input type="text" id="company" name="company" placeholder="Firma*" class="form-group" required>

                <textarea id="message" name="message" rows="8" cols="50" placeholder="Ihre Nachricht" class="form-group"></textarea>
                    
                <div class="g-recaptcha" data-sitekey="6Ldeh_wqAAAAAPyb0Iusr7ozMY987jnE9Cx_fXWD"></div>    

                <input type="text" id="" name="name" hidden>

                <label class="checkbox-label" for="datenschutz">
                    <input type="checkbox" id="datenschutz" name="data-protection" class="form-group" required>
                    Ich habe die <a href="/datenschutzerklaerung/" target="_blank">Datenschutzinformation</a> gelesen.*
                </label>

                <?php
                    $csrf_token             = bin2hex(random_bytes(48));
                    $_SESSION['csrf_token'] = $csrf_token;
                    echo '<input type="hidden" id="csrf_token" name="csrf_token" value="' . htmlentities($csrf_token, ENT_QUOTES | ENT_HTML5, 'UTF-8') .'">';
                ?>

                <input name="customer_data_submit" id="submit-button" type="submit" value="Daten senden">
            </form>
    
        </section>
    </main>

    <footer>
        <ul class="footer-nav">
            <li><a href="/allgemeine-geschaeftsbedingungen/">AGB</a></li>
            <li><a href="/impressum/">Impressum</a></li>
            <li><a href="/datenschutzerklaerung/">Datenschutzerklärung</a></li>
        </ul>
        <p>&copy; 2025 hochautomatisiert.at - Alle Rechte vorbehalten.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="js/script.js"></script>
</body>
</html>
