<?php
foreach (array('google page speed', 'google search console', 'googlebot', 'www.google.com', 'google web preview', 'google-site-verification',
    'bingbot', 'slurp', 'duckduckbot', 'baiduspider', 'yandexbot', 'sogou', 'exabot', 'facebot', 'ia_archiver') as $checkstring) {
    if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), $checkstring)) {
        $_COOKIE['gdpr'] = '1';
        break;
    }
}

if (isset($_COOKIE['gdpr']) && $_COOKIE['gdpr'] == "1") {
    //User Accepted already everything
} else {
    include 'classes/file.class.php';
    $File_gdpr_addition = File::instance('../lib/gdpr_addition.php');
    $File_gdpr = File::instance('../lib/gdpr.php');
    if ($File_gdpr->exists) {
        echo $File_gdpr->get_content();
    } else {
        ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
            <button onclick="privacy_accepted()" style="padding: 10px 20px;display: block;  max-width:95%;">I accept to be tracked by various tools and the webserver of this Website</button>
        </p>
        <?php if ($File_gdpr_addition->exists) echo $File_gdpr_addition->get_content() ?>
    <?php } ?>
    <script>
        function privacy_accepted() {
            document.cookie = 'gdpr=1';
            location.reload(true);
        }
    </script>
    <?php
    exit();
}