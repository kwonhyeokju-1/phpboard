<!DOCTYPE html>
<html>
<body>
<?php header("Content-Type: text/html; charset=UTF-8");
session_start(); ?>
<?php if( (!isset($_SESSION['id'])) &&  (!isset($_SESSION['nickname'])) ){ ?>
<a href="join.html">회원가입</a><br>
<a href="login.html">로그인</a><br>
<?php } else {?>
<a href="logout.php">로그아웃</a><br>
<?php } ?>
<a href="board.php">게시판</a><br>

<?php
header("Content-Type: text/html; charset=UTF-8");
$conn = new mysqli("localhost", "root", "as8534", "web");
mysqli_query ($conn, 'SET NAMES utf8');
$boardnum=$_GET['x'];
$cookie_name = $boardnum; //쿠키 이름은 게시판 번호로 넣어준다.
$cookie_value = "1"; //쿠기 값으로 넣어준다.
setcookie($cookie_name, $cookie_value, time() + (86400), "/"); // 1일 동안 쿠키를 유지하도록 해준다.
if(!isset($_COOKIE[$cookie_name])) { //쿠키가 삭제되지 않는 이상 조회수는 첫 조회시만 1 증가시켜준다.
    $sql2 = "UPDATE board set hit=hit+1 WHERE boardnum=$boardnum";
    $res2 = $conn->query($sql2);
}
$sql = "select *from board where boardnum='$boardnum'";
$res = $conn->query($sql);
$row=mysqli_fetch_array($res);
if($res->num_rows!=1) {
echo "<script>alert('존재하지 않는 게시물 경로입니다.'); location.href='board.php';</script>";
exit();
}
?>
<table>
<!--  <?php $title=str_replace(">","&gt",str_replace("<","&lt",$row['boardtitle'])); echo $title; ?> -->
<tr><th colspan="3">제목 : <?php $title=str_replace(">","&gt;",str_replace("<","&lt;",$row['boardtitle'])); echo $title; ?></th></tr>
<tr><th>작성자 : <?php echo $row['nickname']; ?></th><th>작성일 : <?php echo $row['date']; ?></th><th>조회수 : <?php echo $row['hit']; ?></th></tr>
<tr></tr>
</table>
<div>내용 : <?php  echo str_replace("＆","&",$row['boardcontents']); ?></div>
<div><?php $sql2 = "select *from upload where starttime='".$row['starttime']."' and nickname='".$row['nickname']."'";
$res2 = $conn->query($sql2);
while($row2=mysqli_fetch_array($res2)) {
echo "<div><a href='download.php?filepath=".$row2['changename']."&filename=".$row2['realname']."'>".$row2['realname']."</a></div>";
}
?></div>

<?php if(isset($_SESSION['nickname'])) {
if($_SESSION['nickname'] == $row['nickname']) {
echo "<a href='boardupdate.php?boardnum=".$row['boardnum']."'>게시물 수정</a>";
echo "<a href='boarddelete.php?boardnum=".$row['boardnum']."'>게시물 삭제</a>";
}
} ?>

<!-- 세션에 닉네임 값이 존재한다면 -->
<?php if(isset($_SESSION['nickname'])) { ?>
<!-- post형식으로 reply1apply.php 파일에 데이터를 전송하되,
reply1이라는 이름을 지닌 iframe에서 결과물이 출력되도록 만든다. -->
<form id="reply1applyid" action="reply1apply.php" method="post" target="reply1">
<!-- 필드지정. 윗여백 20px부여. -->
<fieldset style="margin-top:20px;">
<!-- 댓글 작성란이란 제목을 필드에 붙여줌. -->
<legend>댓글 작성란2</legend>
<!-- 게시물 번호 -->
<input type="hidden" name="reply1boardnum" value="<?php echo $row['boardnum']; ?>">
<!-- 댓글 내용 -->
<textarea type="text" name="reply1textarea" style="width:100%; height:100px; border:1px solid; resize:none;"></textarea>
<!-- 닉네임 -->
<input type="hidden" name="reply1nickname" value="<?php echo $_SESSION['nickname']; ?>">
<!-- 댓글 등록 -->
<input type="submit" style='float:right; border:1px solid; background:none;' value="댓글 등록">
</fieldset>
</form>
<?php } ?>
<!-- reply1이라는 이름을 지닌 iframe영역. 보이지 않도록 설정. -->
<iframe name="reply1" style="display:none;"></iframe>

<!-- replys라는 아이디 값 부여. 400픽셀을 넘어갈 경우 스크롤이 생기도록 설정. -->
<div id="replys" style="border:1px solid; height:400px; overflow:auto;">
<?php
//게시물에 단 댓글들 중 최신 값 5개까지만 출력되도록 설정
$sql3="SELECT * FROM reply WHERE boardnum=$boardnum ORDER BY reply.replynum DESC limit 5";
$res3 = $conn->query($sql3);
$savenum="0";
$count="0";
while($row3=mysqli_fetch_array($res3)) {
$count++;
if($count=="5") {
if($savenum=="0") {
//맨 처음 불러온 댓글중 가장 최상단에 출력된 댓글의 번호를 저장한다.
$savenum=$row3['replynum'];
}
}
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
}
//더보기 버튼 생성
$sql4="SELECT * FROM reply WHERE boardnum=$boardnum ORDER BY reply.replynum DESC";
$res4 = $conn->query($sql4);
if($res4->num_rows > 5) {
echo "<p id='rcb' style='text-align:center;' onclick=\"this.style.display='none';+reply1limitfunction($savenum)&reply1moreid.submit()\">더보기</p>";
}
?>
</div>
<!-- 더보기 버튼 클릭시 제출 양식 -->
<form id="reply1moreid" action="reply1more.php" method="post" target="reply1m">
<!-- 게시물 번호 -->
<input type="hidden" name="reply1boardnum" value="<?php echo $boardnum; ?>">
<!-- 댓글 번호 -->
<input type="hidden" id="reply1replynumid" name="reply1replynum">
</form>
<!-- reply1moreid 폼 양식 제출 결과물을 출력해준다. -->
<iframe name="reply1m" style="display:none;"></iframe>
<script>
var rn1=0;
function reply1update(rbs1) {
//답글 수정란 항목을 감추어준다.
if(rn1 != 0) {
document.getElementById('reply1updateid'+rn1).style.display = 'none';
rn1=0;
}
//답글 수정란 항목을 나타나도록 만들어준다.
document.getElementById('reply1updateid'+rbs1).style.display = 'block';
rn1=rbs1;
}

//4강
function reply1delete(rbs1) {
if(rn1 != 0) {
document.getElementById('reply1updateid'+rn1).style.display = 'none';
rn1=0;
}
}

//댓글 수정창 닫기
function reply1updatecancel(rbs1) {
document.getElementById('reply1updateid'+rbs1).style.display = 'none';
rn1=0;
}

//댓글 번호를 전송한다.
function reply1limitfunction(r1rni) {
document.getElementById("reply1replynumid").value = r1rni;
}
</script>
</body>
</html>
