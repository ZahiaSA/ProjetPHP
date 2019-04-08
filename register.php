<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}
// Les en-têtes de contrôle d'accès sont reçus pendant les demandes OPTIONS.
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
}
require "connexion.php";

$data = file_get_contents("php://input");

//var_dump($data);

if (! isset($_POST["login"])) {
    $request = json_decode($data);
    //var_dump ($request);

    $matricule_REL = $request->matricule_REL;
    $nom_REL= $request->nom_REL;
    $nom_site_REL = $request->nom_site_REL;
    $email = $request->email;
    $login = $request->login;
    $password = $request->password;
} else {
    $matricule_REL = filter_input(INPUT_POST, "matricule_REL", FILTER_SANITIZE_STRING);
    $nom_REL = filter_input(INPUT_POST, "nom_REL", FILTER_SANITIZE_STRING);
    $nom_site_REL = filter_input(INPUT_POST, "nom_site_REL", FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, " email", FILTER_SANITIZE_STRING);
    $login = filter_input(INPUT_POST, "login", FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
}

//Pour proteger les données insérées par l'utilisateur des injections sql
$mtricule_REL = stripslashes($matricule_REL);
$nom_REL = stripslashes($nom_REL);
$nom_site_REL = stripslashes($nom_site_REL);
$email = stripslashes($email);
$login = stripslashes($login);
$password = stripslashes($password);

$sql = "INSERT INTO responsables_equipe_de_ligne (matricule_REL, nom_REL, nom_site_REL, email, login, password)
VALUES ('$matricule_REL', '$nom_REL', '$nom_site_REL','$email', '$login', '$password')";
//echo($sql);

$response = [];
$response["matricule"] = $matricule_REL;
$response["nom"] = $nom_REL;
$response["site"] = $nom_site_REL;
$response["email"] = $email;
$response["login"] = $login;
$response["pass"] = $password;
$response["data"] = $request ?? null;
//echo ($response);

if ($con->query($sql) === TRUE) {
    $response['res'] = "L'inscription est reussi";
    $response["ok"] = true;
} else {
    $response['res'] = "Erreur lors de l'inscription :" . $sql . "<br>" . $con->error;
    $response["ok"] = false;
}
echo json_encode($response);
