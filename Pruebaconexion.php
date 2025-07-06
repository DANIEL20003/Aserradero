
<?php
$conexion = mysqli_connect(hostname: 'localhost',
                            username: 'root',
                            password: '',
                            database: 'aserradero');
 
// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit(); // Stop script execution if connection fails
} else {
    echo "¡Conexión exitosa a la base de datos!";
    // You can now perform database operations here
}
 
// It's good practice to close the connection when you're done
mysqli_close($conexion);
 
?>