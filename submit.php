<?php
header('Content-Type: application/json; charset=utf-8');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';


if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: index.html");
    exit;

}

/*----------------------------------------------------
    Google reCAPTCHA v2
-----------------------------------------------------*/

$secret = "6LclPlgtAAAAAEXqqz5fOyF10DmOWy0DClyYQ1Ng";

$response = $_POST['g-recaptcha-response'] ?? '';

if (empty($response)) {

    die("Please complete reCAPTCHA.");

}

$verify = file_get_contents(

    "https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}"

);

$result = json_decode($verify, true);

if (!$result['success']) {

    echo "<pre>";
    print_r($result);
    exit;

}


/*----------------------------------------------------
    Detect Form Type
-----------------------------------------------------*/

$formType = $_POST['form_type'] ?? '';

switch ($formType) {

    case "application":

        $subject = "お申し込みフォーム";
        break;

    case "pre_consultation":

        $subject = "事前相談フォーム";
        break;

    case "media_registration":

        $subject = "プレスリリース受信フォーム";
        break;

    default:

        $subject = "Website Form";

}

/*----------------------------------------------------
    PHPMailer
-----------------------------------------------------*/

$mail = new PHPMailer(true);

try {

    // Debug (Production = 0)
    $mail->SMTPDebug = 0;

    $mail->isSMTP();

    $mail->Host = "sv13049.xserver.jp";

    $mail->SMTPAuth = true;

    $mail->Username = "web@pressreach.jp";

    $mail->Password = "GTD-6584-awgh";

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

    $mail->Port = 465;

    $mail->CharSet = "UTF-8";

    $mail->setFrom(

        "web@pressreach.jp",
        "PressReach Website"

    );

    $mail->addAddress(

        "web@pressreach.jp"

    );

    $mail->Subject = $subject;

    $mail->isHTML(true);
/*----------------------------------------------------
    Field Labels
-----------------------------------------------------*/

$fieldLabels = [

    "company" => "会社名・屋号",
    "company_kana" => "会社名・屋号（カナ）",

    "representative_last_name" => "代表者（姓）",
    "representative_first_name" => "代表者（名）",

    "representative_last_name_kana" => "代表者（セイ）",
    "representative_first_name_kana" => "代表者（メイ）",

    "contact_last_name" => "ご担当者（姓）",
    "contact_first_name" => "ご担当者（名）",

    "contact_last_name_kana" => "ご担当者（セイ）",
    "contact_first_name_kana" => "ご担当者（メイ）",

    "phone" => "電話番号",
    "contact_phone" => "連絡先電話番号",

    "zip_code" => "郵便番号",

    "address" => "住所",

    "building_name" => "ビル・マンション名",

    "industry" => "業種",

    "website" => "ウェブサイト",

    "content" => "掲載内容",

    "consultation" => "相談内容",

    "remarks" => "備考",

    "plan" => "お申込みプラン",

    "interview_type" => "取材タイプ",

    "media_name" => "メディア名",

    "media_name_kana" => "メディア名（カナ）",

    "media_url" => "メディアURL",

    "distribution_email" => "配信先メールアドレス",

    "contact_email" => "担当者メールアドレス",

    "department" => "担当者部署"

];


/*----------------------------------------------------
    Email Body
-----------------------------------------------------*/

$body = '

<h2>'.$subject.'</h2>

<table
border="1"
cellpadding="10"
cellspacing="0"
width="100%"
style="border-collapse:collapse;">

';


foreach ($_POST as $key => $value) {

    if (

        $key == "g-recaptcha-response" ||

        $key == "agree" ||

        $key == "form_type"

    ) {

        continue;

    }

    if ($value === "") {

        continue;

    }

    if (is_array($value)) {

        $value = implode(", ", $value);

    }

    $label = $fieldLabels[$key] ?? $key;

    $body .= '

    <tr>

        <th
        align="left"
        style="background:#f5f5f5;">
        '.$label.'
        </th>

        <td>
        '.nl2br(htmlspecialchars($value)).'
        </td>

    </tr>

    ';

}

$body .= "</table>";

$mail->Body = $body;
/*----------------------------------------------------
    Send Mail
-----------------------------------------------------*/

$mail->send();

echo json_encode([
    "success" => true,
    "message" => "Mail sent successfully."
]);

exit;
}

/*----------------------------------------------------
    Error
-----------------------------------------------------*/

catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => $mail->ErrorInfo
    ]);

    exit;
}