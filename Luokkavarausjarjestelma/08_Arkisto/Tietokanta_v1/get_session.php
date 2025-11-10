<?php
session_start();
if (isset($_SESSION['kayttaja']))
{
    echo json_encode($_SESSION['kayttaja']);
}
else
{
    echo json_encode(null);
}
?>