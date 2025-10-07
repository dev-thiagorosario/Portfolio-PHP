<?php
declare(strict_types=1);
session_start();

require_once __DIR__ . '/../../vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mensagem
{
    private string $para;
    private string $assunto;
    private string $mensagem;

    public function __construct(string $para, string $assunto, string $mensagem)
    {
        $this->para = $para;
        $this->assunto = $assunto;
        $this->mensagem = $mensagem;
    }
    public function MensagemValida(): bool
    {
       if (empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
            return false;
       }
       return true;
    }
}

$para     = $_POST['para']     ?? '';    
$assunto    = $_POST['assunto']  ?? '';      
$mensagem = $_POST['mensagem'] ?? '';

$mensagemObj = new Mensagem($para, $assunto, $mensagem);

if (!$mensagemObj->MensagemValida()) {
    die('Mensagem inválida');
} 

$mail = new PHPMailer(true);

try {
    // Configurações do servidor SMTP
    // Utilizando credenciais do MailerSend (https://www.mailersend.com/)
    $mail->isSMTP();
    $mail->Host       = 'smtp.mailersend.net';   // Substitua pelo seu servidor SMTP
    $mail->SMTPAuth   = true;
    $mail->Username   = 'MS_VsDBwV@test-r6ke4n1rv23gon12.mlsender.net';   // Substitua pelo seu usuário SMTP
    $mail->Password   = 'mssp.N1dYwot.0p7kx4xp517l9yjr.ADVAeIC';             // Substitua pela sua senha SMTP
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;                  // Ou 2525 ambas portas funcionam

    // Remetente e destinatário
    $mail->setFrom('MS_VsDBwV@test-r6ke4n1rv23gon12.mlsender.net', 'QuickMessage App de mensagens');
    $mail->addAddress($para);     // Adicione o destinatário
    // $mail->addReplyTo('responder@exemplo.com', 'Informações'); // opcional

    // Conteúdo do e-mail
    $mail->isHTML(true);
    $mail->Subject = $assunto;
    $mail->Body    = $mensagem;
    $mail->AltBody = strip_tags($mensagem); // Versão texto simples para clientes que não suportam HTML

    // Enviar e-mail
    $mail->send();
    $_SESSION['flash_success'] = 'Mensagem enviada com sucesso!';
    $_SESSION['last_message_meta'] = [
        'para' => $para,
        'assunto' => $assunto,
        'enviado_em' => time(),
    ];
    header('Location: ../pages/sent-message.php');
} catch (Exception $e) {
    echo "❌ Erro ao enviar mensagem: {$mail->ErrorInfo}";

}
