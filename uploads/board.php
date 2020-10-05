<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
</head>
  <body>
    <?php header("Content-Type: text/html; charset=UTF-8");
     session_start(); ?>
     <?php if( (!isset($_SESSION['id'])) && (!isset($_SESSION['nickname'])) ) { ?>
    <a href="join.html">회원가입</a> <br>
    <a href="login.html">로그인</a> <br>
  <?php } else { ?>
    <a href="logout.php">로그아웃</a> <br>
  <?php } ?>
  <a href="board.php">게시판</a> <br>
  <?php
  header("Content-Type: text/html; charset=UTF-8");
  $conn = new mysqli("localhost", "root", "as8534", "web");
  mysqli_query($conn,'SET NAMES utf8');
  if(isset($_GET['page'])) {
    $page = $_GET['page'];
  } else {
    $page = 1;
  }
  if(isset($_GET['pagination'])) {
    $pagination = $_GET['pagination'];
  } else {
    $pagination = 1;
  }
  $sql = "select *from board";
  $res = $conn -> query($sql);
  $totalboardnum = mysqli_num_rows($res); //총 게시물 수
  $totalpagenum = ceil($totalboardnum/10); //총 페이지수 = 총 게시물 수 / 한 페이지에 나타낼 게시물 수
  $totalblocknum = ceil($totalpagenum/5); //총 블록 수 = 총 페이지 수 / 한 블록에 나타낼 페이지 수
  $currentpagenum = (($page-1)*10); //현재 페이지 번호 = (페이지 번호-1)*10
  $sql2 = "select *from board order by boardnum asc limit $currentpagenum,10";
  $res2 = $conn -> query($sql2);
  $num2 = (($page-1)*10)+1;
  ?>
  <div>
  <table>
    <tr>
      <th>번호</th> <th>제목</th> <th>작성자</th> <th>작성일</th> <th>조회수</th>
    </tr>
      <?php while ($row=mysqli_fetch_array($res2)) { $num=$row['boardnum'];
      $title=str_replace(">","&gt",str_replace("<","&lt",str_replace($row['boardtitle'], mb_substr($row['boardtitle'],0,30,"utf-8")."...",$row['boardtitle'])));
      $title2=str_replace(">","&gt",str_replace("<","&lt",$row['boardtitle']));
      ?>
      <tr style = "cursor:pointer;" onClick = "location.href='boardread.php?x=<?php echo $num;?>'">
        <th><?php echo $num2;?></th>
        <th><?php if(mb_strlen($row['boardtitle'],"utf-8") > 30) {echo $title;} else {echo $title2;}?></th>
        <th><?php echo $row['nickname'];?></th>
        <th><?php echo mb_substr($row['date'],0,11,"utf-8");?></th>
        <th><?php echo $row['hit'];?></th>
      </tr>
      <?php $num2++;}?>
  </table>
</div>
<div>
<div>
<?php
$before=$pagination-1; //현재 블록 위치 -1
$after=$pagination+1; //현재 블
$before2=$before*5; //변동 요망
$after2=$after*5-4;
if($pagination>1)
{
  echo "<a href='board.php?pagination=$before&page=$before2'>&laquo;</a>";
}
for($i=$pagination*5-4; $i<=$pagination*5; $i++)
{
  if($i<=$totalpagenum) {
  echo "<a href='board.php?pagination=$pagination&page=$i'>[$i]</a>";
  } else {
      break;
  }
}
if($pagination<$totalblocknum) {
  echo "<a href='board.php?pagination=$after&page=$after2'>&raquo;</a>";
}
?>
</div>

<?php if((isset($_SESSION['id']) && isset($_SESSION['nickname']))) { ?>
<button onclick="location.href='boardwrite.php?starttime=<?php echo time();?>'">게시글 작성</button>
<?php } ?>
</div>
  </body>
</html>
