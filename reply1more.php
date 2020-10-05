<!DOCTYPE html>
<html>
<body>
<div id="send">
<?php
header("Content-Type: text/html; charset=UTF-8");
session_start();
$boardnum = $_POST["reply1boardnum"];
$replynum = $_POST["reply1replynum"];
$conn = new mysqli("localhost", "root", "as8534", "web");
mysqli_query ($conn, 'SET NAMES utf8');
$sql="SELECT * FROM reply WHERE boardnum=$boardnum and replynum<$replynum ORDER BY reply.replynum DESC LIMIT 5";
$res = $conn->query($sql);
$savenum="0";
$count="0";
while($row=mysqli_fetch_array($res)) {
$count++;
if($count=="5") {
if($savenum=="0") {
$savenum=$row['replynum'];
}
}
//4강
echo "<div id=\"reply1show".$row['replynum']."\">".$row['nickname']." ".date('y년 m월 d일 h시 i분 s초',$row['starttime'])."<br><span id=\"reply1text".$row['replynum']."\">".str_replace("<","&lt;",$row['replycontents'])."</span><br>";

if(isset($_SESSION['nickname'])) {
if($_SESSION['nickname'] == $row['nickname']) {
echo "<span onclick='reply1update(".$row['replynum'].")'>수정 </span>";

//4강
echo "<span onclick='reply1delete(".$row['replynum']."); reply1deleteformid".$row['replynum'].".submit()'>삭제 </span>";
echo "<form id='reply1deleteformid".$row['replynum']."' action='reply1delete.php' method='post' target='reply1'>";
//게시물 번호 전송
echo "<input type='hidden' name='reply1deleteboardnum' value='".$boardnum."'>";
//댓글 번호 전송
echo "<input type='hidden' name='reply1deletereplynum' value='".$row['replynum']."'>";
echo "</form>";

echo "<fieldset id='reply1updateid".$row['replynum']."' style='margin-top:20px; display:none;'>";
echo "<legend>댓글 수정란</legend>";
echo "<form id='reply1updateformid".$row['replynum']."' action='reply1update.php' method='post' target='reply1'>";
//게시물 번호 전송
echo "<input type='hidden' name='reply1updateboardnum' value='".$boardnum."'>";
//댓글 번호 전송
echo "<input type='hidden' name='reply1updatereplynum' value='".$row['replynum']."'>";
//텍스트 전송
echo "<textarea type='text' name='reply1updatetextarea' style='width:100%; height:100px; border:1px solid; resize:none;'>".str_replace("<","&lt",$row['replycontents'])."</textarea>";
//등록 취소 혹은 등록
echo "</form>";
echo "<button style='float:right; border:1px solid; background:none;' onclick='reply1updatecancel(".$row['replynum'].")'>취소</button><button style='float:right; border:1px solid; background:none;' onclick='reply1updateformid".$row['replynum'].".submit()'>댓글 수정</button>";
echo "</fieldset>";
}
}
echo "<br><br></div>";
}
//더보기
$sql2="SELECT * FROM reply WHERE boardnum=$boardnum and replynum<$savenum ORDER BY reply.replynum DESC";
$res2 = $conn->query($sql2);
if($res2->num_rows > 0) {
echo "<p id='rcb' style='text-align:center;' onclick=\"this.style.display='none';+reply1moreid.submit();\">더보기</p>";
}
?>
</div>
<script>
parent.document.getElementById("replys").innerHTML += document.getElementById("send").innerHTML;
parent.document.getElementById("reply1replynumid").value = <?php echo $savenum; ?>;
</script>
</body>
</html>
