<?php

if (! isset($_POST["login"])) {

  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Max-Age: 86400');
}
// Les en-têtes de contrôle d'accès sont reçus pendant les demandes OPTIONS.
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')

  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))

    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))

  header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

require "connexion.php";

$data = file_get_contents("php://input");
if (isset($data)) {
  $request = json_decode($data);
  //echo($request);
  
   $login = $request->login;
   $password = $request->password;
 } else {
   $login = filter_input(INPUT_POST, "login", FILTER_SANITIZE_STRING);
   $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING);
}

$login = mysqli_real_escape_string($con, $login);
$password = mysqli_real_escape_string($con, $password);

$login = stripslashes($login);
$password = stripslashes($password);

$sql = "SELECT login FROM responsables_equipe_de_ligne WHERE login = '$login' and password = '$password'";

$result = mysqli_query($con, $sql);
//var_dump($sql);
//var_dump($result);

$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$count = mysqli_num_rows($result);
                   
$response = [];
//Pour tester les variables:
$response["login"] = $login;
$response["pass"]= $password;
$response["data"]= $request ?? null;

// Si le résultat correspond à login et au mot de passe, 
//le ligne du tableau doit correspondre à 1 ligne. 

if ($count > 0) {

  $response['res'] = "Votre connexion est réussie";
  $response["ok"] = true;
} else {

  $response['res'] = "Votre compte matriculaire ou mot de passe est invalide";
  $response["ok"]=false;
}
echo json_encode($response);
?>
