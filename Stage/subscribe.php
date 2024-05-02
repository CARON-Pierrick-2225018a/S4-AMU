<?php

// Enter the URL of the LISTSERV web interface.
$script = 'https://list.etsi.org/scripts/wa.exe?LOGON=INDEX';

$SUBED2 = $_POST["SUBED2"];
$A = $_POST["A"];
$L = $_POST["L"];
$p = $_POST["p"];
$q = $_POST["q"];
$s = $_POST["s"];
$t = $_POST["t"];
$b = $_POST["b"];
$a = $_POST["a"];
$e = $_POST["e"];
$sub = $_POST["sub"];
$unsub = $_POST["unsub"];
$charset = $_POST["_charset_"];

if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {

    // Enter your reCAPTCHA site key here.
    $secret = '6Lep-M0pAAAAAEo8sCoS4f_t3AbqIZkI3p9-jc5C';
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
    $responseData = json_decode($verifyResponse);

    if (isset($responseData->success) && $responseData->success) {

        // The verification was successful. Provide data to LISTSERV.
        $data = [
            'SUBED2' => $SUBED2,
            'A' => $A,
            'L' => $L,
            'p' => $p,
            'q' => $q,
            's' => $s,
            't' => $t,
            'b' => $b,
            'a' => $a,
            'e' => $e,
            'sub' => $sub,
            'unsub' => $unsub,
            '_charset_' => $charset
        ];

        $postdata = http_build_query($data);

        $options = [
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata,
            ],
        ];

        $context  = stream_context_create($options);
        $result = file_get_contents($script, false, $context);

        echo $result;

    } else {

        // The verification failed. Redirect the user back to the subscription screen or the index page.
        if(isset($_POST["SUBED2"]) && !empty($_POST["SUBED2"])) {
            header("Location: $script?SUBED2=$SUBED2&A=$A&L=$L&p=$p&q=$q&s=$s&t=$t&sub=$sub&unsub=$unsub&_charset_=$charset");
        } else {
            header("Location: $script?INDEX");
        }
    }

} else {

    // The reCAPTCHA box has not been clicked. Redirect the user back to the subscription screen or the index page.
    if(isset($_POST["SUBED2"]) && !empty($_POST["SUBED2"])) {
        header("Location: $script?SUBED2=$SUBED2&A=$A&L=$L&p=$p&q=$q&s=$s&t=$t&sub=$sub&unsub=$unsub&_charset_=$charset");
    } else {
        header("Location: $script?INDEX");
    }
}

?>
