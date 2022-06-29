<?php



//$resul = array('prueba', 'hola', 'BrittoOFC', 'CheesterG', 'dsolutec', 'ExpoGanSon', 'dsolutec', 'ExpoGanSon', 'dsolutec', 'BelforFx', 'kunakrec', 'YouTube', 'Dasabuvir', 'greentechmedia', 'bardomsw', 'MdeMotion', 'iAnonymous', 'WilliamCorvera', 'MadridVen', 'Bertty17', 'SoyBobMarley', 'joseapontefelip', 'la_patilla', 'hootsuite', 'fawkestar70', 'starwars');
$result = $_SESSION['req'];

$file = fopen("user.txt", "w"); // Abrir
foreach($resul as $final) {
    fwrite($file, $final.PHP_EOL);
}
fclose($file); // Cerrar






//abrir archivo
//$archivo = fopen("texto.txt", "a");


/*while(!feof($archivo)){
    //leer archivo
    $contenido = fgets($archivo);
    echo($contenido)."<br>";
    
}*/

//escribir
/*$preguntas = array(
    array( "nombre" => "mateo",
    "pregunta" => "por que",
    "respuesta" => "por que si"),
    array( "nombre" => "mateos",
    "pregunta" => "por ques",
    "respuesta" => "por que sis") 
);

foreach($preguntas as $valor => $valo){
    fwrite($archivo, $valo);
}


fclose($archivo);


//copiar un fichero
//copy('fichero_texto.txt', 'fichero.txt') or die("error al copiar");

//renombrar un fichero
//rename('fichero_texto.txt','fichero.txt') or die("error");


//eliminar
//unlink('archivo.txt') or die("error al eliuminar");

//comprobar si un archivo existe
/*if(file_exists("fichero.txt")){
 echo "exite";
}else{
    echo "no existe";
}*/
