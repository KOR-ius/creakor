<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    $errors = [];

    // Validation des champs
    if (empty($name)) {
        $errors[] = "Le nom est obligatoire.";
    }

    if (empty($email)) {
        $errors[] = "L'email est obligatoire.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email est invalide.";
    }

    if (empty($message)) {
        $errors[] = "Le message est obligatoire.";
    }

    // Si des erreurs existent, afficher les erreurs et ne pas envoyer l'email
    if (!empty($errors)) {
        echo json_encode(['status' => 'error', 'message' => implode(', ', $errors)]);
        exit;
    }

    // Échapper les données pour éviter les injections
    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

    // Destinataire
    $to = 'kor@tuta.com'; // Remplacez par votre adresse e-mail
    $subject = 'Nouveau message de contact';

    // Contenu du message
    $body = "Nom: $name\n";
    $body .= "Email: $email\n";
    $body .= "Message:\n$message\n";

    // En-têtes de l'e-mail
    $headers = "From: $name <$email>\r\n";

    // Envoi de l'e-mail
    if (mail($to, $subject, $body, $headers)) {
        echo json_encode(['status' => 'success', 'message' => 'Votre message a été envoyé avec succès.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Une erreur est survenue. Veuillez réessayer.']);
    }
}
?>
