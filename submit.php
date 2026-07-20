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

    echo json_encode([
        "success" => false,
        "message" => "Please complete reCAPTCHA."
    ]);
    exit;

}

$verify = file_get_contents(

    "https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$response}"

);

$result = json_decode($verify, true);

if (!$result['success']) {

    echo json_encode([
        "success" => false,
        "message" => "reCAPTCHA verification failed."
    ]);
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

        $subject = "事前相談・お問い合わせフォーム";
        break;

    case "media_registration":

        $subject = "プレスリリース受信フォーム";
        break;

    default:

        $subject = "Website Form";

}

/*----------------------------------------------------
    Field Labels
-----------------------------------------------------*/

$fieldLabels = [

    "inquiry_type" => "お問い合わせ種別",

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

    "email" => "メールアドレス",

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
    Build form content (text / HTML)
-----------------------------------------------------*/

function buildFormContentLines(array $post, array $fieldLabels): array
{
    $lines = [];

    foreach ($post as $key => $value) {

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
        $lines[] = $label . "：" . $value;
    }

    return $lines;
}

$contentLines = buildFormContentLines($_POST, $fieldLabels);
$contentText = implode("\n", $contentLines);

$htmlBody = '

<h2>'.$subject.'</h2>

<table
border="1"
cellpadding="10"
cellspacing="0"
width="100%"
style="border-collapse:collapse;">

';

foreach ($contentLines as $line) {

    $parts = explode("：", $line, 2);
    $label = $parts[0] ?? '';
    $value = $parts[1] ?? '';

    $htmlBody .= '

    <tr>

        <th
        align="left"
        style="background:#f5f5f5;">
        '.htmlspecialchars($label).'
        </th>

        <td>
        '.nl2br(htmlspecialchars($value)).'
        </td>

    </tr>

    ';

}

$htmlBody .= "</table>";

/*----------------------------------------------------
    Auto-reply templates
-----------------------------------------------------*/

function buildAutoReplyBody(string $intro, string $contentText): string
{
    return
        "PressReach（プレスリーチ）\n" .
        $intro . "\n" .
        "1～2営業日以内に弊社担当からご連絡させていただきますので、しばらくお待ちくださいませ。\n" .
        "\n" .
        "\n" .
        "▼以下の内容が送信されました。\n" .
        "-----------------------\n" .
        $contentText . "\n" .
        "-----------------------\n" .
        "\n" .
        "PressReach（プレスリーチ）\n" .
        "株式会社ネットリンク\n" .
        "TEL 03-5829-9681\n" .
        "https://pressreach.jp/\n";
}

$autoReplyConfig = null;

switch ($formType) {

    case "pre_consultation":
        $autoReplyConfig = [
            "to" => trim($_POST["email"] ?? ""),
            "subject" => "【PressReach】事前相談・お問い合わせを受け付けました",
            "body" => buildAutoReplyBody(
                "事前相談・お問い合わせいただきありがとうございます。",
                $contentText
            ),
        ];
        break;

    case "application":
        $autoReplyConfig = [
            "to" => trim($_POST["email"] ?? ""),
            "subject" => "【PressReach】お申込みを受け付けました",
            "body" => buildAutoReplyBody(
                "お申込みいただきありがとうございます。",
                $contentText
            ),
        ];
        // お申込みテンプレートは「ご連絡」ではなく同じ文言でOK（添付どおり）
        break;

    case "media_registration":
        $mediaIntro =
            "プレスリリースを受信フォームからのご連絡ありがとうございます。\n" .
            "1～2営業日以内に弊社担当から回答させていただきますので、しばらくお待ちくださいませ。";
        $autoReplyConfig = [
            "to" => trim($_POST["distribution_email"] ?? ""),
            "subject" => "【PressReach】プレスリリース受信のお申し込みを受け付けました",
            "body" =>
                "PressReach（プレスリーチ）\n" .
                $mediaIntro . "\n" .
                "\n" .
                "\n" .
                "▼以下の内容が送信されました。\n" .
                "-----------------------\n" .
                $contentText . "\n" .
                "-----------------------\n" .
                "\n" .
                "PressReach（プレスリーチ）\n" .
                "株式会社ネットリンク\n" .
                "TEL 03-5829-9681\n" .
                "https://pressreach.jp/\n",
        ];
        break;
}

/*----------------------------------------------------
    PHPMailer - Admin notification
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
    $mail->Body = $htmlBody;

    $mail->send();

/*----------------------------------------------------
    Auto-reply to customer
-----------------------------------------------------*/

    if ($autoReplyConfig && filter_var($autoReplyConfig["to"], FILTER_VALIDATE_EMAIL)) {

        $autoMail = new PHPMailer(true);

        $autoMail->SMTPDebug = 0;
        $autoMail->isSMTP();
        $autoMail->Host = "sv13049.xserver.jp";
        $autoMail->SMTPAuth = true;
        $autoMail->Username = "web@pressreach.jp";
        $autoMail->Password = "GTD-6584-awgh";
        $autoMail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $autoMail->Port = 465;
        $autoMail->CharSet = "UTF-8";

        $autoMail->setFrom(
            "web@pressreach.jp",
            "PressReach（プレスリーチ）"
        );

        $autoMail->addAddress($autoReplyConfig["to"]);
        $autoMail->Subject = $autoReplyConfig["subject"];
        $autoMail->isHTML(false);
        $autoMail->Body = $autoReplyConfig["body"];

        $autoMail->send();
    }

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
