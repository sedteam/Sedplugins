<?PHP
/* ====================
Seditio - Website engine
Copyright Neocrome & Seditio Team
https://seditio.org

[BEGIN_SED]
File=plugins/feedback/feedback.ajx.php
Version=179
Updated=2022-jul-18
Type=Plugin
Author=Amro
Description=
[END_SED]

[BEGIN_SED_EXTPLUGIN]
Code=feedback
Part=Plugin
File=feedback.ajx
Hooks=ajax
Tags=
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]
==================== */

if (!defined('SED_CODE')) {
  die('Wrong URL.');
}

header('Content-Type: application/json');

// обработка только ajax запросов (при других запросах завершаем выполнение скрипта)
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {
  exit();
}

// обработка данных, посланных только методом POST (при остальных методах завершаем выполнение скрипта)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  exit();
}

// имя файла для хранения логов
define('LOG_FILE', 'plugins/feedback/logs/' . date('Y-m-d') . '.log');
// писать предупреждения и ошибки в лог
define('HAS_WRITE_LOG', true);
// проверять ли капчу
define('HAS_CHECK_CAPTCHA', true);
// обязательно ли наличие файлов, прикреплённых к форме
define('HAS_ATTACH_REQUIRED', false);

const ALLOWED_EXTENSIONS = array(
  'jpg', 'jpeg', 'bmp', 'gif', 'png', 'doc', 'docx', 'xls', 'xlsx', 'pdf'
);

// разрешённые mime типы файлов
const ALLOWED_MIME_TYPES = array(
  'image/jpeg',
  'image/gif',
  'image/png',
  'application/msword',
  'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
  'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
  'application/vnd.ms-word.document.macroEnabled.12',
  'application/vnd.ms-word.template.macroEnabled.12',
  'application/vnd.ms-excel',
  'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
  'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
  'application/vnd.ms-excel.sheet.macroEnabled.12',
  'application/vnd.ms-excel.template.macroEnabled.12',
  'application/vnd.ms-excel.addin.macroEnabled.12',
  'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
  'application/vnd.oasis.opendocument.text',
  'application/vnd.oasis.opendocument.spreadsheet',
  'application/vnd.oasis.opendocument.presentation',
  'application/pdf',
  'text/csv',
  'application/octet-stream'
);
// максимально-допустимый размер файла
define('MAX_FILE_SIZE', 2048 * 1024);
// директория для хранения файлов
define('UPLOAD_PATH', 'plugins/feedback/uploads/');

// отправлять письмо
define('HAS_SEND_EMAIL', true);
// добавить ли прикреплённые файлы в тело письма в виде ссылок
define('HAS_ATTACH_IN_BODY', false);
const EMAIL_SETTINGS = array(
  'addresses' => ['admin@batareya30.ru'], // кому необходимо отправить письмо
  'from' => ['noreply-batareya@nmn.su', 'БАТАРЕЯ'], // от какого email и имени необходимо отправить письмо
  'subject' => 'Сообщение с формы обратной связи batareya30.ru', // тема письма
  'host' => 'nmn.su', // SMTP-хост
  'username' => 'noreply-batareya@nmn.su', // // SMTP-пользователь
  'password' => 'Y99vBHGEvyxel8fP', // SMTP-пароль
  'port' => '465' // SMTP-порт
);
define('HAS_SEND_NOTIFICATION', false);
define('BASE_URL', 'http://batareya30.ru/');
define('SUBJECT_FOR_CLIENT', 'Ваше сообщение доставлено');
//
define('HAS_WRITE_TXT', true);

function itc_log($message)
{
  if (HAS_WRITE_LOG) {
    error_log('Date:  ' . date('d.m.Y h:i:s') . '  |  ' . $message . PHP_EOL, 3, LOG_FILE);
  }
}

$data = [
  'errors' => [],
  'form' => [],
  'logs' => [],
  'result' => 'success'
];

$attachs = [];

/* 4 ЭТАП - ВАЛИДАЦИЯ ДАННЫХ (ЗНАЧЕНИЙ ПОЛЕЙ ФОРМЫ) */

// валидация name
if (!empty($_POST['name'])) {
  $data['form']['name'] = htmlspecialchars($_POST['name']);
} else {
  $data['result'] = 'error';
  $data['errors']['name'] = 'Заполните это поле.';
  itc_log('Не заполнено поле name.');
}

// валидация email
if (!empty($_POST['email'])) {
  $data['form']['email'] = $_POST['email'];
  if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $data['result'] = 'error';
    $data['errors']['email'] = 'Email не корректный.';
    itc_log('Email не корректный.');
  }
} else {
  $data['result'] = 'error';
  $data['errors']['email'] = 'Заполните это поле.';
  itc_log('Не заполнено поле email.');
}

// валидация message
if (!empty($_POST['message'])) {
  $data['form']['message'] = htmlspecialchars($_POST['message']);
  if (mb_strlen($data['form']['message'], 'UTF-8') < 20) {
    $data['result'] = 'error';
    $data['errors']['message'] = 'Это поле должно быть не меньше 20 cимволов.';
    itc_log('Поле message должно быть не меньше 20 cимволов.');
  }
} else {
  $data['result'] = 'error';
  $data['errors']['message'] = 'Заполните это поле.';
  itc_log('Не заполнено поле message.');
}

// проверка капчи
if (HAS_CHECK_CAPTCHA) {
  session_start();
  if ($_POST['captcha'] === $_SESSION['captcha']) {
    $data['form']['captcha'] = $_POST['captcha'];
  } else {
    $data['result'] = 'error';
    $data['errors']['captcha'] = 'Код не соответствует изображению.';
    itc_log('Не пройдена капча. Указанный код ' . $captcha . ' не соответствует ' . $_SESSION['captcha']);
  }
}

// валидация agree
if ($_POST['agree'] == 'true') {
  $data['form']['agree'] = true;
} else {
  $data['result'] = 'error';
  $data['errors']['agree'] = 'Необходимо установить этот флажок.';
  itc_log('Не установлен флажок для поля agree.');
}

// валидация прикреплённых файлов
if (empty($_FILES['attach'])) {
  if (HAS_ATTACH_REQUIRED) {
    $data['result'] = 'error';
    $data['errors']['attach'] = 'Заполните это поле.';
    itc_log('Не прикреплены файлы к форме.');
  }
} else {
  foreach ($_FILES['attach']['error'] as $key => $error) {
    if ($error == UPLOAD_ERR_OK) {
      $name = basename($_FILES['attach']['name'][$key]);
      $size = $_FILES['attach']['size'][$key];

      $mtype = mime_content_type($_FILES['attach']['tmp_name'][$key]);

      $ext = pathinfo($_FILES['attach']['tmp_name'][$key], PATHINFO_EXTENSION);
      $upl_extension = explode(".", $_FILES['attach']['tmp_name'][$key]);
      $ext2 = end($upl_extension);

      if (!in_array($mtype, ALLOWED_MIME_TYPES) && !(in_array($ext2, ALLOWED_EXTENSIONS) && ($ext == $ext2))) {
        $data['result'] = 'error';
        $data['errors']['attach'][$key] = 'Файл имеет не разрешённый тип.';
        $data['errors']['attach']['mtype'] = $mtype;
        itc_log('Прикреплённый файл ' . $name . ' имеет не разрешённый тип.');
      } else if ($size > MAX_FILE_SIZE) {
        $data['result'] = 'error';
        $data['errors']['attach'][$key] = 'Размер файла превышает допустимый.';
        itc_log('Размер файла ' . $name . ' превышает допустимый.');
      }
    }
  }
  if ($data['result'] === 'success') {
    // перемещаем файлы в папку UPLOAD_PATH
    foreach ($_FILES['attach']['name'] as $key => $attach) {
      $ext = mb_strtolower(pathinfo($_FILES['attach']['name'][$key], PATHINFO_EXTENSION));

      $name = basename($_FILES['attach']['name'][$key], $ext);

      $tmp = $_FILES['attach']['tmp_name'][$key];

      $newName = trim(rtrim($name, '.')) . '_' . uniqid() . '.' . $ext;
      $newName = sed_newname($newName);

      if (!move_uploaded_file($tmp, UPLOAD_PATH . $newName)) {
        $data['result'] = 'error';
        $data['errors']['attach'][$key] = 'Ошибка при загрузке файла.';
        itc_log('Ошибка при перемещении файла ' . $name . '.');
      } else {
        $attachs[] = UPLOAD_PATH . $newName;
      }
    }
  }
}

use PHPMailer\PHPMailer\PHPMailer;
//use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'plugins/feedback/phpmailer/phpmailer/src/Exception.php';
require 'plugins/feedback/phpmailer/phpmailer/src/PHPMailer.php';
require 'plugins/feedback/phpmailer/phpmailer/src/SMTP.php';

if ($data['result'] == 'success' && HAS_SEND_EMAIL == true) {
  // получаем содержимое email шаблона и заменяем в нём
  $template = file_get_contents('plugins/feedback/tpl/email.tpl');
  $search = ['%subject%', '%name%', '%email%', '%message%', '%date%'];
  $replace = [EMAIL_SETTINGS['subject'], $data['form']['name'], $data['form']['email'], $data['form']['message'], date('d.m.Y H:i')];
  $body = str_replace($search, $replace, $template);
  // добавление файлов в виде ссылок
  if (HAS_ATTACH_IN_BODY && count($attachs)) {
    $ul = 'Файлы, прикреплённые к форме:<ul>';
    foreach ($attachs as $attach) {
      $href = str_replace($_SERVER['DOCUMENT_ROOT'], '', $attach);
      $name = basename($href);
      $ul .= '<li><a href="' . BASE_URL . $href . '" target="_blank">' . $name . '</a></li>';

      $data['href'][] = BASE_URL . $href;
    }
    $ul .= '</ul>';
    $body = str_replace('%attachs%', $ul, $body);
  } else {
    $body = str_replace('%attachs%', '', $body);
  }
  $mail = new PHPMailer();
  try {
    //Server settings
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host = EMAIL_SETTINGS['host'];
    $mail->SMTPAuth = true;
    $mail->Username = EMAIL_SETTINGS['username'];
    $mail->Password = EMAIL_SETTINGS['password'];
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = EMAIL_SETTINGS['port'];
    //Recipients
    $mail->setFrom(EMAIL_SETTINGS['from'][0], EMAIL_SETTINGS['from'][1]);
    foreach (EMAIL_SETTINGS['addresses'] as $address) {
      $mail->addAddress(trim($address));
    }
    //Attachments
    if (!HAS_ATTACH_IN_BODY && count($attachs)) {
      foreach ($attachs as $attach) {
        $mail->addAttachment($attach);
      }
    }
    //Content
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    $mail->isHTML(true);
    $mail->Subject = EMAIL_SETTINGS['subject'];
    $mail->Body = $body;
    $mail->send();
    itc_log('Форма успешно отправлена.' . $mail->ErrorInfo);
  } catch (Exception $e) {
    $data['result'] = 'error';
    itc_log('Ошибка при отправке письма: ' . $mail->ErrorInfo);
  }
}

if ($data['result'] == 'success' && HAS_SEND_NOTIFICATION) {
  // очистка адресов и прикреплёных файлов
  $mail->clearAllRecipients();
  $mail->clearAttachments();
  // получаем содержимое email шаблона и заменяем в нём плейсхолдеры на соответствующие им значения
  $template = file_get_contents('plugins/feedback/tpl/email_client.tpl');
  $search = ['%subject%', '%name%', '%date%'];
  $replace = [SUBJECT_FOR_CLIENT, $data['form']['name'], date('d.m.Y H:i')];
  $body = str_replace($search, $replace, $template);
  try {
    // устанавливаем параметры
    $mail->Subject = SUBJECT_FOR_CLIENT;
    $mail->Body = $body;
    $mail->addAddress($data['form']['email']);
    $mail->send();
    itc_log('Успешно отправлено уведомление пользователю.');
  } catch (Exception $e) {
    itc_log('Ошибка при отправке уведомления пользователю: ' . $mail->ErrorInfo);
  }
}

if ($data['result'] == 'success' && HAS_WRITE_TXT) {
  $output = '=======' . date('d.m.Y H:i') . '=======';
  $output .= 'Имя: ' . $data['form']['name'] . PHP_EOL;
  $output .= 'Email: ' . $data['form']['email'] . PHP_EOL;
  $output .= 'Сообщение: ' . $data['form']['message'] . PHP_EOL;
  if (count($attachs)) {
    $output .= 'Файлы:' . PHP_EOL;
    foreach ($attachs as $attach) {
      $output .= $attach . PHP_EOL;
    }
  }
  $output = '=====================';
  error_log($output, 3, 'plugins/feedback/logs/forms.log');
}

echo json_encode($data);
exit();
