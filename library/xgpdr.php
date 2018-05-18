<?php
if (isset($_COOKIE['gpdr']) && $_COOKIE['gpdr'] == "1") {
    //User Accepted already everything
} else {
    ?>
    <h1>Privacy Settings and Informations</h1>
    <p>
        Since 25th of may, the european union decided to enhance their rules and laws about user-privacy on websites.<br/>
        You now have to accept that this site will track your IP-address via the WebServer itself and
        in addition we are using GoogleAnalytics, which tracks like everything of you.<br/>
        <br/>
        Sie können darüber hinaus die Erfassung der durch das Cookie erzeugten und auf Ihre Nutzung der Website bezogenen Daten
        (inkl. Ihrer IP-Adresse) an Google sowie die Verarbeitung dieser Daten durch Google verhindern,
        indem sie das unter dem folgenden Link verfügbare Browser-Plugin herunterladen und installieren:
        <a href="http://tools.google.com/dlpage/gaoptout?hl=de" target="_blank">http://tools.google.com/dlpage/gaoptout?hl=de</a><br/>
        <br/>
        Also: In contactforms, your data may be sent via Email to a first-party-owned email-account.<br/>
        To be able to see the website, you need to allow cookies on your browser and have JavaScript enabled.<br/>
        <br/>
        <button onclick="privacy_accepted()">I accept to be tracked by various tools and the webserver of this Website</button>
    </p>
    <script>
        function privacy_accepted() {
            document.cookie = 'gpdr=1';
            location.reload(true);
        }
    </script>
    <?php
    exit();
}