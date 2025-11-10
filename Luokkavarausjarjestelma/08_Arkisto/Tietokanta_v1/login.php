<?php 
include 'db.php';

$email = $_POST['email'];
$salasana = md5($_POST['salasana']);

$sql = "SELECT * FROM kayttajat WHERE email=? AND salasana=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $salasana);
$stmt->execute();
$result = $stmt->get_result();

if ($user=$result->fetch_assoc())
{
    $_SESSION['kayttaja'] = $user;
    echo json_encode(["status" => "success", "user" => $user]);
}
else 
{
    echo json_encode(["status" => "error", "message" => "Virheellinen kirjautuminen"]);
}

?>