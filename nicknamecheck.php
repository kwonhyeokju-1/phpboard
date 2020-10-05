<?php
header("Content-Type: application/json; charset=UTF-8");
$obj = json_decode($_POST["content"], false);
$conn = new mysqli("localhost","root","as8534","web");
mysqli_query($conn,'SET NAMES utf8');
$sql = "select *from member where nickname='$obj->nickname'";
$res = $conn->query($sql);
if($res -> num_rows > 0)  {
echo json_encode('1');
exit();
} else {
echo json_encode('0');
}
?>
