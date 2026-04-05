<!DOCTYPE html>
<html>
<head>
    <title>Vérification de votre compte</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #4e73df;">Bienvenue sur WILINK-TICKET</h2>
        <p>Bonjour,</p>
        <p>Merci de vous être inscrit. Pour activer votre compte, veuillez utiliser le code de vérification ci-dessous :</p>
        
        <div style="background-color: #f8f9fc; padding: 15px; text-align: center; border-radius: 5px; margin: 20px 0;">
            <span style="font-size: 24px; font-weight: bold; letter-spacing: 5px; color: #4e73df;">{{ $code }}</span>
        </div>
        
        <p>Ce code expirera dans 10 minutes.</p>
        <p>Si vous n'avez pas demandé ce code, vous pouvez ignorer cet email.</p>
        
        <p style="margin-top: 30px; font-size: 12px; color: #858796;">
            Ceci est un email automatique, merci de ne pas y répondre.
        </p>
    </div>
</body>
</html>
