<!-- send라는 아이디 값을 div영역에 부여-->
<div id="send">
<?php
//text/html형식에 대해 utf-8설정
header("Content-Type: text/html; charset=UTF-8");
//세션 시작
session_start();
//게시물 번호
$boardnum = $_POST["reply1boardnum"];
//댓글 내용
$replycontents = $_POST["reply1textarea"];
//역슬래시 자동 추가
$replycontents = addslashes($replycontents);
//페이지 실행 시간
$starttime = time();
//DB접속 및 쿼리에 대해 utf8설정
$conn = new mysqli("localhost", "root", "as8534", "web");
mysqli_query ($conn, 'SET NAMES utf8');
//게시물에 등록된 댓글들의 번호중 가장 높은 값 출력. (등록된 댓글이 없으면 0을 출력해줌)
$sql = "SELECT MAX(replynum) AS replynum FROM reply WHERE boardnum = '$boardnum'";
$res = $conn->query($sql);
$row=mysqli_fetch_array($res);
//이미 저장되어 있는 댓글들중 최신 댓글의 번호 값 + 1
$replynum=($row['replynum']+1);
//게시물번호,댓글번호,댓글내용,닉네임,reply1apply파일 실행 시간 저장
$sql2 = "insert into reply (boardnum, replynum, replycontents, nickname, starttime) values('$boardnum',
'$replynum','$replycontents','".$_SESSION['nickname']."','$starttime')";
$res2 = $conn->query($sql2);
//제대로 저장이 되었는지 점검 후 닉네임,reply1apply파일 실행 시간,댓글 내용 순으로 출력
$sql3 = "select *from reply where boardnum='$boardnum' and replynum='$replynum' and nickname='".$_SESSION['nickname']."'
and replycontents='$replycontents' and starttime='$starttime'";
$res3 = $conn->query($sql3);
$row3=mysqli_fetch_array($res3);
//4강
echo "<div id=\"reply1show".$row3['replynum']."\">".$row3['nickname']." ".date('y년 m월 d일 h시 i분 s초',$row3['starttime'])."<br><span id=\"reply1text".$row3['replynum']."\">".str_replace("<","&lt;",$row3['replycontents'])."</span><br>";

if(isset($_SESSION['nickname'])) {
if($_SESSION['nickname'] == $row3['nickname']) {
echo "<span onclick='reply1update(".$row3['replynum'].")'>수정 </span>";

//4강
echo "<span onclick='reply1delete(".$row3['replynum']."); reply1deleteformid".$row3['replynum'].".submit();'>삭제 </span>";
echo "<form id='reply1deleteformid".$row3['replynum']."' action='reply1delete.php' method='post' target='reply1'>";
//게시물 번호 전송
echo "<input type='hidden' name='reply1deleteboardnum' value='".$boardnum."'>";
//댓글 번호 전송
echo "<input type='hidden' name='reply1deletereplynum' value='".$row3['replynum']."'>";
echo "</form>";

echo "<fieldset id='reply1updateid".$row3['replynum']."' style='margin-top:20px; display:none;'>";
echo "<legend>댓글 수정란</legend>";
echo "<form id='reply1updateformid".$row3['replynum']."' action='reply1update.php' method='post' target='reply1'>";
//게시물 번호 전송
echo "<input type='hidden' name='reply1updateboardnum' value='".$boardnum."'>";
//댓글 번호 전송
echo "<input type='hidden' name='reply1updatereplynum' value='".$row3['replynum']."'>";
//텍스트 전송
echo "<textarea type='text' name='reply1updatetextarea' style='width:100%; height:100px; border:1px solid; resize:none;'>".str_replace("<","&lt",$row3['replycontents'])."</textarea>";
//등록 취소 혹은 등록
echo "</form>";
echo "<button style='float:right; border:1px solid; background:none;' onclick='reply1updatecancel(".$row3['replynum'].")'>취소</button><button style='float:right; border:1px solid; background:none;' onclick='reply1updateformid".$row3['replynum'].".submit()'>댓글 수정</button>";
echo "</fieldset>";
}
}
echo "<br><br></div>";
?>
</div>
<script>
//iframe의 부모 영역에 있는 내용들중 replys 라는 이름을 지닌 영역의 html값에다가
//현재 페이지에 출력된 내용을 맨 윗줄에 추가해줌
parent.document.getElementById("replys").innerHTML = document.getElementById("send").innerHTML + parent.document.getElementById("replys").innerHTML;
</script>
