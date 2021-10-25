    <?php
    define('HOSTNAME','localhost');
    define('DB_USERNAME','admin');
    define('DB_PASSWORD','admin@123');
    define('DB_NAME', 'webiste');
    $con = mysqli_connect(HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME) or die ("error");
    // Check connection
    if(mysqli_connect_errno($con))  echo "Failed to connect MySQL: " .mysqli_connect_error();
    //else echo "connected";

$response = array("Error" => FALSE);

$json = file_get_contents('php://input');
$data = json_decode($json);

if(!empty($data)){

    $name = $data->name;
    $mobile = $data->mobile;
    $email = $data->email;
    $message = $data->message;
}
//echo $name ,$mobile ,$email , $message;

echo $sql = "INSERT INTO contacts (name,mobile,email,message)
VALUES ('$name','$mobile','$email','$message')";


if ($con->query($sql) === TRUE) 
{
	echo "New record created successfully";
}
else {
    	echo "Error: " . $sql . "<br>" . $con->error;
}

$con->close();

?>