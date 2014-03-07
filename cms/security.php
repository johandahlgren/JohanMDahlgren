<?php
if ($_REQUEST["apa"] != "" && $_REQUEST["ipa"] != "") {
    if ($_REQUEST["apa"] == "johan" && $_REQUEST["ipa"] == "apa123") {
        $_SESSION["loggedIn"] = true;
    }
    header("Location: admin.php");
} else if ($_SESSION["loggedIn"] != true) {
    ?>
        <head>
            <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
            <link rel="stylesheet" type="text/css" href="style/style.css">
            <script type="text/javascript" src="javascript/jquery-latest.pack.js"></script>
            <script type="text/javascript" src="javascript/javascript.php"></script>
        </head>
        <body>
            <div id="loginDiv">
                <form id="loginForm" method="post" action="admin.php">
                    <input type="hidden" name="userAction" value="login" />
                    <div class="formLabel">Anv&auml;ndar-ID:</div>
                    <input type="text" class="formField" name="apa" autofocus/>
                    <div class="formLabel">L&ouml;senord:</div>
                    <input type="password" class="formField" name="ipa" />
                    <div class="center">
                        <button class="loginButton" onclick="document.getElementById('loginForm').submit();">Logga in</button>
                    </div>
                    <div class="clear"></div>
                </form>
            </div>
        </body>
    <?php
    die();
}
?>