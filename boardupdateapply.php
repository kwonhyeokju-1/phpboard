<?php
header("Content-Type: application/json; charset=UTF-8");
$obj = json_decode($_POST["x"], false);
$conn = new mysqli("localhost", "root", "as8534", "web");
mysqli_query ($conn, 'SET NAMES utf8');
$boardtitle = addslashes($obj->boardtitle);
$boardcontents = addslashes($obj->boardcontents);
$boardnum = $obj->boardnum;
$stmt = $conn->prepare("UPDATE $obj->table SET boardtitle='$boardtitle',boardcontents='$boardcontents' WHERE boardnum='$boardnum'");
$stmt->execute();
$sql = "select *from board where boardtitle ='$boardtitle' and boardcontents ='$boardcontents'
and boardnum ='$boardnum'";
$res = $conn->query($sql);
if($res->num_rows > 0) {
    echo json_encode('1');
    exit();
} else {
    echo json_encode('0');
}
?>
