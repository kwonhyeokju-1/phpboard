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

<?php header("Content-Type: text/html; charset=UTF-8");
$conn = new mysqli("localhost", "root", "as8534", "web");
mysqli_query ($conn, 'SET NAMES utf8');
$boardnum=$_GET['boardnum'];
$sql = "select *from board where boardnum='$boardnum'";
$res = $conn->query($sql);
$row=mysqli_fetch_array($res);
?>
<textarea id="title"><?php echo $row['boardtitle']; ?></textarea>
<div style="border:1px solid; height:400px; overflow:auto;" id="editor" contentEditable="true"><?php echo str_replace('＆','&',$row['boardcontents']); ?></div>

<div id="demo"></div>
<form action="upload.php" method="post" enctype="multipart/form-data" target="test">
<input type="file" name="file[]" multiple="multiple" onchange="this.form.submit()">
<input type="hidden" name="nickname" value="<?php echo $_SESSION['nickname']; ?>">
<input type="hidden" name="time" value="<?php echo $row['starttime']; ?>">
</form>
<iframe src="delete.php?time=<?php echo $row['starttime'];?>" name="test"></iframe>
<button style="border:1px solid;" onclick="apply()">게시글 등록</button>
<script>
function apply() {
var x1 = document.getElementById("title").value.replace("+","＋").replace(/#/g,"＃").replace(/&/g,"＆").replace(/=/g,"＝")
	.replace(/\\/g,"＼");
var x2 = document.getElementById("editor").innerHTML.replace("+","＋").replace(/#/g,"＃").replace(/&/g,"＆").replace(/=/g,"＝")
	.replace(/\\/g,"＼");
var obj, dbParam, xmlhttp, myObj, x;
obj={"table":"board","boardtitle":x1,"boardcontents":x2,"boardnum":"<?php echo $boardnum; ?>"};
dbParam = JSON.stringify(obj);
xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
myObj = JSON.parse(this.responseText);
for (x in myObj) {
if(myObj[x] == '1') {
location.href='board.php';
return false;
} else {
document.getElementById("demo").innerHTML = "업로드 실패!";
}
}
}
};
if((x2.trim() == "<br>")||(x2.trim()=="")||(x1.trim() == "")) {
alert("입력된 텍스트가 없습니다.");
return false;
} else {
document.getElementById("editor").innerHTML = "";
xmlhttp.open("POST","boardupdateapply.php",true);
xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xmlhttp.send("x=" + dbParam);
}
}
</script>
</body>
</html>
