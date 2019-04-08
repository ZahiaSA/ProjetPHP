<?php
if (isset($_SERVER['HTTP_ORIGIN'])) {
header("Access-Control-Allow-Origin: {$_SERVER[‘HTTP_ORIGIN’]}");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');
}
// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

}
require "connecxion.php";

//J'ai sélectionné un matricule_REL(204609)dans la condition where pour tester si la requete sql fonctionne, 
//après ça sera en fonction de chaque REL qui va se connecter

$sql ="SELECT a.matricule_agents,a.nom_agents,a.date_validite_permis,a.date_validite_FCO,a.nom_site,
s.dates_suivi, s.prochaine_date_suivi, s.commentaire_suivi, 
eap.derniere_date_EAP, eap.prochaine_date_EAP, eap.commentaire_EAP,
ac.dates_validation_AC, ac.commentaire_AC,
pp.date_ouverture_PP, pp.date_cloture_PP, pp.commentaire_PP,
re.total_jours_absences, re.jour_reaccueil, re.commentaire_reaccueil
FROM agents a
inner join suivi s
on a.matricule_agents = s.matricule_agents 
INNER JOIN entretien_appreciation_professionnelle eap
on a.matricule_agents = eap.matricule_agents 
inner join ambition_clients ac
on a.matricule_agents = ac.matricule_agents
inner join plan_de_progres pp
on a.matricule_agents = pp.matricule_agents 
inner join re_accueil re
on a.matricule_agents = re.matricule_agents 
where a.matricule_REL ='204609'";
       

$result = mysqli_query($con, $sql);
$response = array();

while($row = mysqli_fetch_array($result)){
array_push($response, array("matricule_agents"=>$row[0],
                            "nom_agents"=>$row[1],
                            "date_validite_permis"=>$row[2],
                            "date_validite_FCE"=>$row[3],
                            "nom_site"=>$row[4],

                            "dates_suivi"=>$row[1],
                            "prochaine_date_suivi"=>$row[2],
                            "commenatire_suivi"=>$row[3],

                            "derniere_date_EAP"=>$row[1],
                            "prochaine_date_EAP"=>$row[2],
                            "commentaire_EAP"=>$row[3],
                            
                            "dates_validation_AC"=>$row[1],
                            "commentaire_AC"=>$row[2],
                            
                            "date_ouverture_PP"=>$row[1],
                            "date_cloture_PP"=>$row[2],
                            "commentaire_PP"=>$row[3],

                            "total_jours_absences"=>$row[1],
                            "jour_reaccueil"=>$row[2],
                            "commentaire_reaccueil"=>$row[3],
                            ));
}
echo json_encode(array("server_response"=> $response));
mysqli_close($con);

?>