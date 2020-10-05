<div id="send">
<?php
header("Content-Type: text/html; charset=UTF-8");
session_start();
//게시물 번호
$boardnum = $_POST["reply1deleteboardnum"];
//댓글 번호
$replynum = $_POST["reply1deletereplynum"];
//DB접속
$conn = new mysqli("localhost", "root", "as8534", "web");
//DB 한글 사용처리
mysqli_query ($conn, 'SET NAMES utf8');
//댓글 삭제
$sql = "delete from reply where boardnum='$boardnum' and replynum='$replynum'";
$res = $conn->query($sql);
//댓글  삭제 확인
$sql2 = "select *from reply where boardnum='$boardnum' and replynum='$replynum'";
$res2 = $conn->query($sql2);
if($res2->num_rows == 0) {
echo "<script>alert('삭제 되었습니다.');</script>";
}
?>
</div>
<script>
//삭제한 내용 전달
parent.document.getElementById("<?php echo "reply1show".$replynum; ?>").innerHTML = document.getElementById("send").innerHTML;
</script>
