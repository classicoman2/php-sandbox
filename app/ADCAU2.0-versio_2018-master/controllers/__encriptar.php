<html>

<body>

<?php
//including the database connection file
require_once("config.php");

$tb="members";

$hashed_password = crypt('admin'); // let the salt be automatically generated

$sql="UPDATE $tb SET password='$hashed_password' WHERE username='admin'";

$result = dbQuery($sql);


echo "Hem encriptat la contrasenya";

dbClose();
?>


</body>

</html>
