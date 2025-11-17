<?php

ob_start(); // active le tampon de sortie
session_start(); // démarre la session

require 'route.php';
ob_end_flush();

?>