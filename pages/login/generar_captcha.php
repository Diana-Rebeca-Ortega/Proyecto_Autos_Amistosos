<?php
//Utilizamos la libreria GD de PHP para generar una imagen con un número aleatorio, que es la función básica de un CAPTCHA simple
session_start();
// Esto indica al navegador que el contenido que sigue es una imagen JPEG
header("Content-type: image/jpeg"); 

// 1. Generar un número aleatorio (el código secreto)
$codigo_captcha = rand(1000, 9999);

// 2. Guardar el código en la sesión
$_SESSION['captcha_code'] = $codigo_captcha;

// 3. Crear y configurar la imagen usando la librería GD de PHP
$imagen = imagecreate(120, 40);
$color_fondo = imagecolorallocate($imagen, 240, 240, 240); 
$color_texto = imagecolorallocate($imagen, 50, 50, 50);   

// Escribir el código en la imagen
imagestring($imagen, 5, 30, 10, $codigo_captcha, $color_texto);

// 4. Mostrar la imagen al navegador
imagejpeg($imagen);
imagedestroy($imagen);
?>