<?php

function parseHeaders($headerString) {

    $headers = [];

    $lines = explode("\r\n", $headerString);

    

    foreach ($lines as $line) {

        if (strpos($line, ':') !== false) {

            list($name, $value) = explode(':', $line, 2);

            $headers[trim($name)] = trim($value);

        } elseif (strpos($line, 'HTTP/') === 0) {

            // Store the status line

            $headers['Status'] = trim($line);

        }

    }

    

    return $headers;

}



if(empty($_REQUEST)){

	$body = json_decode(file_get_contents("php://input"));

		?>

<html>

<head>

	<title>StefanovWeb DMN log</title>

</head>

<body>

	<div id="tableData">

		<?php 

		// print_r($body);

		?>

	</div>



</body>

</html>

	<?php

	

	} 

		



	;

	#accept DMN call

	if(isset($_REQUEST['clear']) && $_REQUEST['clear'] === 'true') {

		clearLogFile();

		die("logFile cleared");





	} else {







	if (isset($_GET['action'])) {



		switch ($_GET['action']) {

			case '404':

				http_response_code(404); die();

				break;

			case '500':

				http_response_code(500); die();

				break;

			case '400':

				http_response_code(400); die();

				break;

			case '403':

				http_response_code(403); die();

				break;

			default:

					if (isset($_GET['message'])) {

					 echo 'action='.$_GET['action'].'&message='.$_GET['message'].'&errorCode='.$_GET['message'].'&merchantUniqueId='.date("d-m-Y h:m:s").date("H:i:s").rand(1, 10000);

						

					}else{

					echo 'action='.$_GET['action'];

					}

				break;

		}

	};



$main  = array();

$main['server_name'] = $_SERVER['SERVER_NAME'];

$main['remote_addr'] = $_SERVER['REMOTE_ADDR'];

foreach (apache_request_headers() as $key => $value) {

	$strkey = mb_strtolower($key);

	// echo $strkey .'-' . $value. '</br>';

  $main[$strkey] = addslashes($value);

}



$main['URI'] = 'DMN';

$dmncontent = array();

if (!empty($_REQUEST) || !empty(file_get_contents("php://input"))) {

	$dmncontent = array(



		'Date' =>  date("d-m-Y")." ".date("H:i:s"),

		'Requestheaders' => $main,

		'Params' => $_REQUEST,

		'Payload'=> json_decode(file_get_contents("php://input"))

	);



} else {

	$dmncontent = array(

		'Date' =>  date("d-m-Y")." ".date("H:i:s"),

		'Requestheaders' => $main,

		'payload' => 'N/A'

	);

}



$servername = "localhost";
$dbname = "dmn";
$username = "dmn";
$password = "WAOLSEVHWRt9brrk";

//$username = "uokxd2ptlelvy";

//$password = '@ldi1(4@53*1';



//$dbname = 'db8vbwsrrvkysu';





$sqlparams = json_encode($dmncontent, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);



try {

    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    // set the PDO error mode to exception

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



 //   $sql = "INSERT INTO log (JSON)  VALUES ('$sqlparams');";
$sql = "INSERT INTO `log` (`JSON`) VALUES (:payload)";

$stmt = $conn->prepare($sql);               // <-- prepare on $pdo
$stmt->bindParam(':payload', $sqlparams, PDO::PARAM_STR);  // <-- bind on $stmt
$stmt->execute();



    /*
    // use exec() because no results are returned

    $conn->exec($sql);
	*/
    //echo "New record created successfully";

    }

	catch(PDOException $e)

    {

    echo $sql . "<br>" . $e->getMessage();//die();

    }



	$conn = null;





// mysqli









$s = array('HTTP_HOST' =>  $_SERVER['HTTP_HOST'],

						'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'],

						'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR']

 );

$sqlparams = $_REQUEST;



}

;







?>

