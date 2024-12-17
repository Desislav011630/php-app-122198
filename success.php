<?php
if (isset($_GET['message'])) {
    echo "<h1>" . htmlspecialchars($_GET['message']) . "</h1>";
}
?>
<a href="index.php">Върнете се към началната страница</a>

