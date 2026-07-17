<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: media-registration-form.html");
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

<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<style>

body{
    margin:0;
    padding:40px;
    background:#f5f5f5;
    font-family:Arial,sans-serif;
}

.container{
    max-width:900px;
    margin:auto;
    background:#fff;
    padding:40px;
    border-radius:8px;
    box-shadow:0 5px 20px rgba(0,0,0,.08);
}

h2{
    margin-bottom:30px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,
td{
    border:1px solid #ddd;
    padding:14px;
    text-align:left;
    vertical-align:top;
}

th{
    width:260px;
    background:#f3f3f3;
}

.form-row{
    margin-top:30px;
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
    border-radius:6px;
    cursor:pointer;
    font-size:16px;
}

.back{
    background:#888;
    color:#fff;
}

.submit{
    background:#0077cc;
    color:#fff;
}

</style>

</head>

<body>

<div class="container">

<h2>入力内容の確認</h2>

<form action="submit.php" method="POST">

<table>

<tr>
<th>メディア名</th>
<td><?=h($data['media_name'])?></td>
</tr>

<tr>
<th>メディア名（カナ）</th>
<td><?=h($data['media_name_kana'])?></td>
</tr>

<tr>
<th>メディアURL</th>
<td><?=h($data['media_url'])?></td>
</tr>

<tr>
<th>配信先メールアドレス</th>
<td><?=h($data['distribution_email'])?></td>
</tr>

<tr>
<th>会社名・屋号</th>
<td><?=h($data['company'])?></td>
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
<th>担当者メールアドレス</th>
<td><?=h($data['contact_email'])?></td>
</tr>

<tr>
<th>連絡先電話番号</th>
<td><?=h($data['contact_phone'])?></td>
</tr>

<tr>
<th>担当者部署</th>
<td><?=h($data['department'])?></td>
</tr>

<tr>
<th>郵便番号</th>
<td><?=h($data['zip_code'])?></td>
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
<th>電話番号</th>
<td><?=h($data['phone'])?></td>
</tr>

<tr>
<th>備考</th>
<td><?=nl2br(h($data['remarks']))?></td>
</tr>

</table>

<?php foreach($data as $key => $value): ?>

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

<div class="form-row">

<div
class="g-recaptcha"
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