<!DOCTYPE html>
<html>
<head>
    <title>Nouveau message de contact</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #4e73df;">Nouveau message du site web</h2>
        
        <p><strong>Nom :</strong> {{ $data['name'] }}</p>
        <p><strong>Email :</strong> {{ $data['email'] }}</p>
        <p><strong>Sujet :</strong> {{ $data['subject'] }}</p>
        
        <hr style="border: 1px solid #eee; margin: 20px 0;">
        
        <h3>Message :</h3>
        <p style="background-color: #f8f9fc; padding: 15px; border-radius: 5px;">
            {!! nl2br(e($data['message'])) !!}
        </p>

        <p style="font-size: 12px; color: #888; margin-top: 30px;">
            Cet email a été envoyé depuis le formulaire de contact de {{ config('app.name') }}.
        </p>
    </div>
</body>
</html>
