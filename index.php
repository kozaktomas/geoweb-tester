<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(-1);
date_default_timezone_set('CET');
session_start();

if (isset($_POST['send'])) {
    $xml = simplexml_load_file(dirname(__FILE__) . "/settings.xml");
    $url = trim($xml->target_url);
    $fields_string = "";
    $i = 1;
    foreach ($xml->path->point as $point) {
        $fields_string .= 'lat[]=' . $point->lat . '&';
        $fields_string .= 'lng[]=' . $point->lng . '&';
        $i = $i + 2;
    }
    $fields_string .= 'user_key=' . trim($xml->user_key);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, $i);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    $result = curl_exec($ch);
    curl_close($ch);
    $_SESSION['send'] = 1;
    header('Location: index.php');
    exit;
}
?>

<html>
    <head><title>GeoWeb - Tester</title></head>
    <body>
        <p style="padding: 5px; background-color: #EEE; border: 1px #AAA solid;">Odesílá HTTP POST request na cílovou adresu s nastavením z settings.xml</p>
        <form method="POST" action="index.php">
            <table style="width: 100%; padding: 5px; background-color: #EEE; border: 1px #AAA solid;">
                <tr><td style="text-align: center;">
                        <input type="submit" name="send" value="Send request" />
                    </td></tr>
            </table>
        </form>
        <?php if($_SESSION['send']){ echo '<p style="padding: 5px; background-color: #0D0; border: 1px #AAA solid; text-align:center;">Request byl odeslán v '.date('Y-m-d H:i:s').'</p>'; } ?>
    </body>
</html>


<?php
$_SESSION['send'] = 0;
?>



