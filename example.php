<html>
<head>
<title>phpaspsms Sample</title>
</head>
<body>
  <h1>phpaspsms Sample</h1>
<?php

  require_once("SMS.php");

  error_reporting(E_ALL);

  // Insert username, password and originator
  $username = '.....';
  $password = '.....';
  $originator = "phpaspsms";

  if (isset($_POST["send"])) {
    $sms = new SMS($username, $password);

    $sms->setOriginator($originator);
    $sms->addRecipient($_POST["recipient"]);
    $sms->setContent($_POST["content"]);

    $result = $sms->sendSMS();
    if ($result != 1) {
      $error = $sms->getErrorDescription();
    }
  }
?>

<script language="JavaScript"><!--
  function showChars(f) {
    f.charsCount.value = f.content.value.length;
    return true;
  }
// --></script>

<?php
  if (isset($error) && $error != "") {
    print "<div class=\"error\">Error: $error</div>";
  }
?>

<form action="<?php print $_SERVER["PHP_SELF"]; ?>" method="post">
  <p>
  Recipient:<br>
  <input type="text" name="recipient" value="">
  </p>

  <p>
  Text:<br>
  <textarea name="content" rows="4" cols="33" wrap="soft" onKeyUp="return showChars(this.form);"><?php if (isset($_POST["content"])) { print $_POST["content"]; } ?></textarea>
  </p>
  <p>
    Chars:
    <input name="charsCount" value="0" size="3" disabled> (max 160)
  </p>

  <input type="submit" name="send" value="Send">
</form>
</body>
</html>
