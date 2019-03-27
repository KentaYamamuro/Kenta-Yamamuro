<html>
  <head>
  <meta charset = "utf-8">
  </head>
  <body>
<h1>TECH-BASE掲示板</h1>

<?php
$dsn='データベース名';
$user='ユーザー名';
$password='パスワード';
$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_WARNING));
//データベース接続完了

$sql="CREATE TABLE IF NOT EXISTS finalmission"."("."id INT AUTO_INCREMENT ,"."name char(32),"."comment TEXT,"."password TEXT,"."primary key(id)".");";
$stmt=$pdo->query($sql);//テーブル作成おわり

if(!empty($_POST["editNo"])&& !empty($_POST["E_pass"])) //編集選択(2-4)//
{
  $sql='SELECT*FROM finalmission';
  $stmt=$pdo->query($sql);
  $results=$stmt->fetchAll();
    foreach($results as $editrow){
     if($_POST["editNo"]==$editrow['id'] && $_POST["E_pass"]==$editrow['password']){
       $selectnumber=$editrow['id'];
       $selectname=$editrow['name'];
       $selectcomment=$editrow['comment'];
     }
    }
}//編集選択おわり

if(!empty($_POST["deleteNo"])&&!empty($_POST["D_pass"])) //削除実行(2-3)
{
  $sql='SELECT*FROM finalmission';
  $stmt=$pdo->query($sql);
  $results=$stmt->fetchAll();
    foreach($results as $deleterow){
      if($_POST["deleteNo"]==$deleterow['id']&& $_POST["D_pass"]==$deleterow['password'] ){
        $id=$deleterow['id'];
        $sql='delete from finalmission where id=:id';
        $stmt=$pdo->prepare($sql);
        $stmt->bindParam(':id',$id,PDO::PARAM_INT);
        $stmt->execute();
      }
    }
}//削除おわり

if(!empty($_POST["namae"])&&!empty($_POST["comment"])&&!empty($_POST["edithidden"])&&!empty($_POST["password"])) //編集実行(2-4)//
{
  $id=$_POST['edithidden'];
  $name=$_POST["namae"];
  $comment=$_POST["comment"];
  $sql='update finalmission set name=:name,comment=:comment where id=:id';
  $stmt=$pdo->prepare($sql);
  $stmt->bindParam(':name',$name,PDO::PARAM_STR);
  $stmt->bindParam(':comment',$comment,PDO::PARAM_STR);
  $stmt->bindParam(':id',$id,PDO::PARAM_INT);
  $stmt->execute();
}

if(!empty($_POST["namae"])&&!empty($_POST["comment"])&&empty($_POST["edithidden"])&&!empty($_POST["password"])) {//新規投稿処理(2-1)//
  $sql=$pdo->prepare("INSERT INTO finalmission(name,comment,password) VALUES(:name,:comment,:password)");
  $sql->bindParam(':name',$name,PDO::PARAM_STR);
  $sql->bindParam(':comment',$comment,PDO::PARAM_STR);
  $sql->bindParam(':password',$pass,PDO::PARAM_STR);
  $name=$_POST["namae"];
  $comment=$_POST["comment"];
  $pass=$_POST["password"];
  $sql->execute();
}

?>
<form method = "POST" action = "mission_4-1.php"> <!--投稿フォーム-->
 <input type = "text" name = "namae" value = "<?php echo $selectname; ?>" placeholder = "名前">
 <input type = "text" name = "comment" value = "<?php echo $selectcomment; ?>" placeholder = "コメント">
 <input type ="text" name= "password" value ="" placeholder="パスワード">
 <input type = "hidden" name = "edithidden" value= "<?php echo $selectnumber; ?>"> <!--隠す編集番号-->
 <input type = "submit" value = "送信"><br/>
</form>
<form method = "POST" action = "mission_4-1.php"> <!--削除フォーム-->
 <input type = "text" name = "deleteNo" placeholder = "削除対象番号">
 <input type = "text" name = "D_pass" placeholder="パスワード">
 <input type = "submit" value = "削除"><br/>
</form>
<form method = "POST" action = "mission_4-1.php"> <!--編集フォーム-->
 <input type = "text" name = "editNo" placeholder = "編集対象番号">
 <input type = "text" name = "E_pass" placeholder="パスワード">
 <input type = "submit" value = "編集"><br/>
</form><!--フォームすべておわり-->
 </body>
 </html>
<?php

$sql='SELECT*FROM finalmission ORDER by id ASC';
$stmt=$pdo->query($sql);
$results=$stmt->fetchAll();
  foreach($results as $row){
    echo $row['id'].',';
    echo $row['name'].',';
    echo $row['comment'].'<br>';
  }
?>
