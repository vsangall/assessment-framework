<!DOCTYPE html>
<html>
<body>
<?php
parse_str($_SERVER["QUERY_STRING"], $data);
echo $data;
?>
</body>
</html>
