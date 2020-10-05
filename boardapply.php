<?php
header("Content-Type: application/json; charset=UTF-8");
$obj = json_decode($_POST["x"], false);
$conn = new mysqli("localhost", "root", "as8534", "web");
mysqli_query ($conn, 'SET NAMES utf8');
$boardtitle = addslashes($obj->boardtitle);
$boardcontents = addslashes($obj->boardcontents);
$nickname = addslashes($obj->nickname);
$date = addslashes($obj->date);
$starttime = addslashes($obj->starttime);
$stmt = $conn->prepare("INSERT INTO $obj->table(boardtitle,boardcontents,nickname,date,starttime)
VALUES ('$boardtitle','$boardcontents','$nickname','$date','$starttime')");
$stmt->execute();
$sql = "select *from board where boardtitle ='$boardtitle' and boardcontents ='$boardcontents'
and nickname ='$nickname' and date ='$date'";
$res = $conn->query($sql);
if($res->num_rows > 0) {
    echo json_encode('1');
    exit();
} else {
    echo json_encode('0');
}
?>
