<?php
/* 
 * PHP Lab Script v2.00 (2014-03-20)
 * Brandon Thomas (brandon@uncon.net)
 */

function setHttpResponse() {
	$httpResponseMessage = array(
		// Informational
		100 => '100 Continue',
		101 => '101 Switching Protocols',
		// Successful
		200 => '200 OK',
		201 => '201 Created',
		202 => '202 Accepted',
		203 => '203 Non-Authoritative Information',
		204 => '204 No Content',
		205 => '205 Reset Content',
		206 => '206 Partial Content',
		// Redirection
		300 => '300 Multiple Choices',
		301 => '301 Moved Permanently',
		302 => '302 Found',
		303 => '303 See Other',
		304 => '304 Not Modified',
		305 => '305 Use Proxy',
		306 => '306 (Unused)',
		307 => '307 Temporary Redirect',
		// Client Error
		400 => '400 Bad Request',
		401 => '401 Unauthorized',
		402 => '402 Payment Required',
		403 => '403 Forbidden',
		404 => '404 Not Found',
		405 => '405 Method Not Allowed',
		406 => '406 Not Acceptable',
		407 => '407 Proxy Authentication Required',
		408 => '408 Request Timeout',
		409 => '409 Conflict',
		410 => '410 Gone',
		411 => '411 Length Required',
		412 => '412 Precondition Failed',
		413 => '413 Request Entity Too Large',
		414 => '414 Request-URI Too Long',
		415 => '415 Unsupported Media Type',
		416 => '416 Requested Range Not Satisfiable',
		417 => '417 Expectation Failed',
		// Server Error
		500 => '500 Internal Server Error',
		501 => '501 Not Implemented',
		502 => '502 Bad Gateway',
		503 => '503 Service Unavailable',
		504 => '504 Gateway Timeout',
		505 => '505 HTTP Version Not Supported'
	);

	if(! @$httpResp = (int)rawurldecode($_GET['resp'])) $httpResp = 200;
	if($httpResp >= 100 && $httpResp < 600) {
		$httpHeader = 'HTTP/1.1 ' . $httpResponseMessage[$httpResp];
		header($httpHeader);
	}
	return $httpHeader;
}

function doSleep() {
	if(! @$sleep = (int)rawurldecode($_GET['sleep'])) $sleep = 0;
	if($sleep > 0) {
		$slept['start time'] = date('h:i:s');
		sleep($sleep);
		$slept['end time'] = date('h:i:s');
	}
	if(!empty($slept)) return $slept;
}

function doPad() {
        if(! @$pad = (int)rawurldecode($_GET['pad'])) $pad = 0;
        if($pad > 8) {
                $realPad = $pad - 4;
                $padding = str_pad("<!-- ", $realPad, "#") . " -->";
                $padded['padded'] = $pad . " bytes" . $padding;
        }
        if(!empty($padded)) return $padded;
}

function setHttpLocation() {
	if(! @$locationHeader = rawurldecode($_GET['loc'])) $locationHeader = "";
	if(! $locationHeader == "") {
		header("Location: " . $locationHeader);
	}
}

function getHttpParams() { 
	$param = '';
	foreach ($_SERVER as $name => $value) {
		if (substr($name, 0, 5) == 'HTTP_') { 
			$param[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
		}
	}
	return $param;
}

function getRequestParams() { 
	$param = '';
	foreach ($_SERVER as $name => $value) {
		if (substr($name, 0, 8) == 'REQUEST_') { 
			$param[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 8)))))] = $value;
		}
	}
	return $param;
}

function getDocumentParams() { 
	$param = '';
	foreach ($_SERVER as $name => $value) {
		if (substr($name, 0, 9) == 'DOCUMENT_') { 
			$param[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 9)))))] = $value;
		}
	}
	return $param;
}

function getServerParams() { 
	$param = '';
	foreach ($_SERVER as $name => $value) {
		if (substr($name, 0, 7) == 'SERVER_') { 
			$param[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 7)))))] = $value;
		}
	}
	return $param;
}

function getRemoteParams() { 
	$param = '';
	foreach ($_SERVER as $name => $value) {
		if (substr($name, 0, 7) == 'REMOTE_') { 
			$param[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 7)))))] = $value;
		}
	}
	return $param;
}

function buildTable($params) {
	$table = "";
	if (!empty($params)) {
		$table = "<table>\n\t<tbody>\n";
		foreach ($params as $Header => $Value) {
			$table .= "\t\t<tr>\n";
			$table .= "\t\t\t<th>$Header</th>";
			$table .= "<td>$Value</td>\n";
			$table .= "\t\t</tr>\n";
		}
		$table .= "\t</tbody>\n</table>\n";
	}
	return $table;
}

function buildFilesTable($params) {
	$table = "";
	if (!empty($params)) {
		$table = "<table>\n\t<tbody>\n";
		foreach ($params as $File => $Value) {
			$table .= "\t\t<tr>\n";
			$table .= "\t\t\t<th>$File</th>";
			$table .= "<td>$Value[name]</td>";
			$table .= "<td>$Value[type]</td>";
			$table .= "<td>$Value[size] bytes</td>\n";
			$table .= "\t\t</tr>\n";
		}
		$table .= "\t</tbody>\n</table>\n";
	}
	return $table;
}

function getColor() {
	switch (substr($_SERVER['SERVER_ADDR'], -1)) {
	case 0:
		$pageColor = "Black";
		break;
	case 1:
		$pageColor = "Blue";
		break;
	case 2:
		$pageColor = "Red";
		break;
	case 3:
		$pageColor = "Green";
		break;
	case 4:
		$pageColor = "Maroon";
		break;
	case 5:
		$pageColor = "Navy";
		break;
	case 6:
		$pageColor = "Olive";
		break;
	case 7:
		$pageColor = "Purple";
		break;
	case 8:
		$pageColor = "Gray";
		break;
	case 9:
		$pageColor = "Teal";
		break;
	default:
		$pageColor = "Black";
	}
	return $pageColor;
}

function setHttpCookie() {
	if (!empty($_POST["NewCookie"])) setcookie("CookieTest" . time(), $_POST["NewCookie"], time()+300, "/");
}

$slept = doSleep();
$pad = doPad();
$httpHeader = setHttpResponse();
setHttpLocation();
setHttpCookie();
$serverName = php_uname("n");
$pageColor = getColor();
$pageTitle = strtolower("$serverName $pageColor");

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Pragma" content="no-cache">
<title><?php echo $pageTitle; ?></title>
<style>
body {
	font-family: Helvetica, Arial, sans-serif;
	color: White;
	background-color: <?php echo $pageColor ?>;
	font-size: 11pt;
}
a, a:link, a:visited, a:hover, a:active {
	color: DarkGray;
	text-decoration: none ;
}
table {
	margin-left: 12px;
	border: 1px solid White;
}
th {
	text-align: left;
	padding: 4px;
	background: rgb(128, 128, 128);
	background: rgba(0, 0, 0, .25);
}
td {
	text-align: left;
	padding: 4px;
	background: rgb(64, 64, 64);
	background: rgba(0, 0, 0, .125);
}
</style>
</head>
<body>

<h1><?php echo $pageTitle; ?></h1>

<?php if(!empty($slept)) {
	echo "<h2>slept</h2>\n";
	echo buildTable($slept);
} ?>

<?php if(!empty($pad)) {
        echo "<h2>pad</h2>\n";
        echo buildTable($pad);
} ?>

<h2>request</h2>
<?php echo buildTable(getRequestParams()); ?>

<h2>http (headers)</h2>
<?php echo buildTable(getHttpParams()); ?>

<?php if(!empty($_COOKIE)) {
	echo "<h2>cookies</h2>\n";
	echo buildTable($_COOKIE);
} ?>

<?php if(!empty($_POST) || !empty($_FILES)) {
	echo "<h2>post</h2>\n";
	echo buildTable($_POST);
	echo buildFilesTable($_FILES);
} ?>

<h2>document</h2>
<?php echo buildTable(getDocumentParams()); ?>

<h2>remote</h2>
<?php echo buildTable(getRemoteParams()); ?>

<h2>server</h2>
<?php echo buildTable(getServerParams()); ?>

<h2>links</h2>
<table>
	<tbody>
		<tr>
			<td><a href="<?php echo $_SERVER['REQUEST_URI']; ?>">myself</a></td>
		</tr><tr>
			<td><a href="/">root</a></td>
		</tr><tr>
			<td><a href="http://google.com">Google</a></td>
		</tr>
	</tbody>
</table>

<h2>create cookie</h2>
<table>
	<tbody>
		<tr>
			<th>add a cookie</th>
		</tr>
		<tr>
			<td>
				<form action=" <?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
					<input maxlength=2048 name="NewCookie" size=55>
					<input type="submit" value="create" />
				</form>
			</td>
		</tr>
		<tr>
			<td>(this cookie will expire 5 minutes after creation)</td>
		</tr>
	</tbody>
</table>

<h2>create post</h2>
<table>
	<tbody>
		<tr>
			<th>post text</th>
		</tr>
		<tr>
			<td>
				<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
					<input maxlength=2048 name="PostText" size=55 />
					<input type="submit" value="post">
				</form>
			</td>
		</tr>
	</tbody>
</table>
<br />
<table>
	<tbody>
		<tr>
			<th>post a file</th>
		</tr>
		<tr>
			<td>
				<form enctype="multipart/form-data" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
					<input name="PostFile" type=file />
					<input type="submit" value="post">
				</form>
			</td>
		</tr>
	</tbody>
</table>

<h2>options</h2>
<table>
	<tbody>
		<tr>
			<th>?sleep=X</th><td>sleep X seconds during response</td>
		</tr><tr>
                        <th>?pad=X</th><td>pad response with X bytes (must be &gt; 8)</td>
                </tr><tr>
			<th>?resp=X</th><td>set response code to X</td>
		</tr><tr>
			<th>?loc=X</th><td>set location header to X</td>
		</tr>
	</tbody>
</table>

</body>
</html>

<!-- <?php echo $pageTitle; ?> -->

