<?php
include 'set.php';

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

$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$isJavaClient = preg_match('/(?:Java\/|Apache-HttpClient\/)/i', $userAgent) === 1;
$requestHeaders = apache_request_headers();

function renderRequestTable($params, $headers) {
    echo '<table border="1"><thead><tr><th>Paramname</th><th>Value</th></tr></thead><tbody>';

    foreach ($params as $name => $value) {
        $displayValue = is_scalar($value) || $value === null
            ? (string) $value
            : json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        echo '<tr><td>' . htmlspecialchars((string) $name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
            . '</td><td>' . htmlspecialchars($displayValue === false ? '' : $displayValue, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
            . '</td></tr>';
    }

    foreach ($headers as $name => $value) {
        echo '<tr><td>' . htmlspecialchars('Header: ' . $name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
            . '</td><td>' . htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
            . '</td></tr>';
    }

    echo '</tbody></table>';
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

		if (!$isJavaClient) {
			renderRequestTable([], $requestHeaders);
		}

		// print_r($body);

		?>
	</div>

	<h2>GET action</h2>
	<p>To invoke an action, send a GET request with the <code>action</code> query parameter, for example <code>dmn.php?action=404</code>. Supported status actions are <code>400</code>, <code>403</code>, <code>404</code>, and <code>500</code>. For Java HTTP clients, other action values are returned in the response; browser requests display their parameters and headers in a table. You can also include an optional <code>message</code> parameter, for example <code>dmn.php?action=example&amp;message=Something+went+wrong</code>.</p>


</body>

</html>

	<?php

	

	}

		



	;

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

					if ($isJavaClient && isset($_GET['message'])) {

					 echo 'action='.$_GET['action'].'&message='.$_GET['message'].'&errorCode='.$_GET['message'].'&merchantUniqueId='.date("d-m-Y h:m:s").date("H:i:s").rand(1, 10000);

						

					}elseif ($isJavaClient){

					echo 'action='.$_GET['action'];

					}

				break;

		}

	};

	if (!empty($_REQUEST) && !$isJavaClient) {
		?>
<html>
<head>
	<title>StefanovWeb DMN request</title>
</head>
<body>
	<h2>Incoming request</h2>
	<?php renderRequestTable($_REQUEST, $requestHeaders); ?>
</body>
</html>
		<?php
	}



$main  = array();

$main['server_name'] = $_SERVER['SERVER_NAME'];

$main['remote_addr'] = $_SERVER['REMOTE_ADDR'];

foreach ($requestHeaders as $key => $value) {

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

   error_log($e->getMessage());
   echo "Databse operation failed.";

    }



	$conn = null;





// mysqli



$s = array('HTTP_HOST' =>  $_SERVER['HTTP_HOST'],

						'HTTP_USER_AGENT' => $_SERVER['HTTP_USER_AGENT'],

						'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR']

 );

$sqlparams = $_REQUEST;

;







?>

