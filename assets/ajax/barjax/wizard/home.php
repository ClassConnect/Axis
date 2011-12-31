<?php
if (!isset($wizName) && $_SESSION['wizardStep'] == 0) {
?>
tempEr();
<?php
} elseif ((isset($wizName) && $_SESSION['wizardStep'] == 0)) {
?>
window.location = 'home.cc';
<?php
}
?>