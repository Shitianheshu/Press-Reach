<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: application-form.html");
    exit;
}

function h($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

$data = $_POST;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>確認画面</title>

<style>
body{
    font-family:Arial,sans-serif;
    background:#f7f7f7;
    margin:0;
    padding:40px;
}

.container{
    max-width:900px;
    margin:auto;
    background:#fff;
    padding:40px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
}

h2{
    margin-top:0;
    margin-bottom:30px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    border:1px solid #ddd;
    padding:14px;
    text-align:left;
    vertical-align:top;
}

th{
    width:260px;
    background:#f3f3f3;
}

.buttons{
    display:flex;
    justify-content:center;
    gap:20px;
    margin-top:40px;
}

button{
    padding:15px 40px;
    border:none;
    cursor:pointer;
    border-radius:6px;
    font-size:16px;
}

.back{
    background:#999;
    color:#fff;
}

.submit{
    background:#0077cc;
    color:#fff;
}
</style>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<script>
document.getElementById('confirmForm').addEventListener('submit', function (e) {

    const response = grecaptcha.getResponse();

    console.log("Token:", response);

    if (!response) {
        e.preventDefault();
        alert("Please complete the reCAPTCHA.");
        return;
    }

});
</script>
</head>
<body>

<div class="container">

<h2>入力内容の確認</h2>

<form action="submit.php" method="post" id="confirmForm">

<table>

<tr>
<th>会社名・屋号</th>
<td><?=h($data['company'])?></td>
</tr>

<tr>
<th>会社名・屋号（カナ）</th>
<td><?=h($data['company_kana'])?></td>
</tr>

<tr>
<th>代表者</th>
<td>
<?=h($data['representative_last_name'])?>
<?=h($data['representative_first_name'])?>
</td>
</tr>

<tr>
<th>代表者（カナ）</th>
<td>
<?=h($data['representative_last_name_kana'])?>
<?=h($data['representative_first_name_kana'])?>
</td>
</tr>

<tr>
<th>ご担当者</th>
<td>
<?=h($data['contact_last_name'])?>
<?=h($data['contact_first_name'])?>
</td>
</tr>

<tr>
<th>ご担当者（カナ）</th>
<td>
<?=h($data['contact_last_name_kana'])?>
<?=h($data['contact_first_name_kana'])?>
</td>
</tr>

<tr>
<th>電話番号</th>
<td><?=h($data['phone'])?></td>
</tr>

<tr>
<th>連絡先電話番号</th>
<td><?=h($data['contact_phone'])?></td>
</tr>

<tr>
<th>住所</th>
<td><?=h($data['address'])?></td>
</tr>

<tr>
<th>ビル・マンション名</th>
<td><?=h($data['building_name'])?></td>
</tr>

<tr>
<th>取材タイプ</th>
<td><?=h($data['interview_type'])?></td>
</tr>

<tr>
<th>お申込みプラン</th>
<td><?=h($data['plan'])?></td>
</tr>

<tr>
<th>業種</th>
<td><?=h($data['industry'])?></td>
</tr>

<tr>
<th>掲載内容</th>
<td><?=nl2br(h($data['content']))?></td>
</tr>

<tr>
<th>ウェブサイト</th>
<td><?=h($data['website'])?></td>
</tr>

<tr>
<th>備考欄</th>
<td><?=nl2br(h($data['remarks']))?></td>
</tr>

</table>

<?php foreach($data as $key=>$value): ?>

<?php
if($key == 'g-recaptcha-response'){
    continue;
}
?>

<?php if(is_array($value)): ?>

<?php foreach($value as $v): ?>

<input
type="hidden"
name="<?=h($key)?>[]"
value="<?=h($v)?>">

<?php endforeach; ?>

<?php else: ?>

<input
type="hidden"
name="<?=h($key)?>"
value="<?=h($value)?>">

<?php endif; ?>

<?php endforeach; ?>

      
	  <div class="form-row full">
    <div class="g-recaptcha"
         data-sitekey="6LdpOFctAAAAALmGUWeTjB2dzt3zv0OpKwfGlp-M">
    </div>

</div>

<div class="buttons">

<button
type="button"
class="back"
onclick="history.back();">
戻る
</button>

<button
type="submit"
class="submit">
送信する
</button>

</div>

</form>



</div>

</body>
</html>