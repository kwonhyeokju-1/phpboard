<?php
header("Content-Type: text/html; charset=UTF-8");
$conn = new mysqli("localhost","root","as8534","web");
mysqli_query($conn,'SET NAMES utf8');
$id = $_POST['id'];
$hiddenid = $_POST['hiddenid'];
$nickname = $_POST['nickname'];
$hiddennickname = $_POST['hiddennickname'];
$password = $_POST['password1'];
$hiddenpassword = $_POST['hiddenpassword'];
if($hiddenid == 1) {
$sql = "select *from member where id = '$id'";
$res = $conn->query($sql);
if($res -> num_rows > 0) {
echo "<script>alert('이미 존재하는 아이디입니다.'); location.href='/join.html';</script>";
exit();
}

}
 else {
echo "<script>alert('아이디 양식이 올바르지 않습니다.'); location.href='/join.html'</script>";
exit();
}

if($hiddennickname == 1) {
$sql2 = "select *from member where nickname = '$nickname'";
$res2 = $conn->query($sql2);

if($res2 -> num_rows > 0) {
echo "<script>alert('이미 존재하는 닉네임입니다.'); location.href='/join.html'</script>";
exit();
}

} else {
echo "<script>alert('닉네임 양식이 올바르지 않습니다.'); location.href='/join.html'</script>";
exit();
}

if($hiddenpassword != 1) {
echo "<script>alert('비밀번호 제출 양식이 올바르지 않습니다.'); location.href='/join.html';</script>";
exit();
}

$sql3 = "insert into member (id,nickname,password) values ('$id','$nickname','$password')";
$res3 = $conn->query($sql3);
$sql4 = "select *from member where id = '$id' and nickname='$nickname'";
$res4 = $conn->query($sql4);

if($res4 -> num_rows > 0) {
echo "<script>alert('회원가입을 성공하였습니다.'); location.href='/join.html'</script>";
exit();
}  else {
echo "<script>alert('회원가입을 성공하지 못하였습니다.'); location.href='/join.html'</script>";
}
?>
