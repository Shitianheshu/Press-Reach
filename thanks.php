<?php
// thanks.php
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>送信完了</title>

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:Arial, Helvetica, sans-serif;
            background:#f5f5f5;
        }

        .wrapper{
            max-width:700px;
            margin:80px auto;
            background:#fff;
            padding:60px;
            text-align:center;
            border-radius:10px;
            box-shadow:0 5px 20px rgba(0,0,0,.08);
        }

        h1{
            color:#222;
            margin-bottom:20px;
            font-size:32px;
        }

        p{
            color:#666;
            line-height:1.8;
            margin-bottom:35px;
            font-size:16px;
        }

        .btn{
            display:inline-block;
            padding:14px 35px;
            background:#0077cc;
            color:#fff;
            text-decoration:none;
            border-radius:5px;
            transition:.3s;
        }

        .btn:hover{
            background:#005fa3;
        }
    </style>

</head>
<body>

<div class="wrapper">

    <h1>送信完了</h1>

    <p>
        お申し込みありがとうございます。<br>
        内容を確認のうえ、担当者よりご連絡いたします。
    </p>

    <a href="index.html" class="btn">
        トップページへ戻る
    </a>

</div>

</body>
</html>