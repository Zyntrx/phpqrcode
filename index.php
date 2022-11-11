<?php    
    
    echo "<h2>PHP QR Code Generator</h2><hr/>";
    
	echo "<h3> Creating a QR Code for the following: <h3>
	<ol>
		<li>URL</li>
			<ul> 
			  <li>http://www.facebook.com</li>
			</ul>  
		<li>Telephone</li>
			<ul> 
			  <li> tel:09214552001</li>
			</ul>  
		<li>SMS</li>
			<ul> 
			  <li>smsto:09214552001:How are you?</li>
			</ul> 
		<li>Email</li>
			<ul> 
			  <li>mailto:ian_val2008@yahoo.com:Testing email</li>
			</ul> 
		<li>Text</li>
			<ul> 
			  <li>I'm generating a QRCode for testing purposes only.</li>
			</ul> 
		<li>Geolocation</li>
			<ul> 
			  <li> geo:7.3136286,125.6703633?site=DNSC</li>
			</ul> 
	</ol>";

		
	
    //set it to writable location, a place for temp generated PNG files
    $PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
    
    //html PNG location prefix
    $PNG_WEB_DIR = 'temp/';

    include "qrlib.php";    
    
    //ofcourse we need rights to create temp dir
    if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
    
    
    $filename = $PNG_TEMP_DIR.'qrCode.png';
    
    //processing form input
    //remember to sanitize user input in real-life solution !!!
    $errorCorrectionLevel = 'L';
    if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
        $errorCorrectionLevel = $_REQUEST['level'];    

    $matrixPointSize = 8;
    if (isset($_REQUEST['size']))
        $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);


    if (isset($_REQUEST['data'])) { 
    
        //it's very important!
        if (trim($_REQUEST['data']) == '')
            die('data cannot be empty! <a href="?">back</a>');
            
        // user data
        $filename = $PNG_TEMP_DIR.'test'.md5($_REQUEST['data'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
        QRcode::png($_REQUEST['data'], $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
        
    } else {    
    
        //default data
     
        QRcode::png('Lecture on QR Code!!! By: Engr. Ian Val P. Delos Reyes, DIT', $filename, $errorCorrectionLevel, $matrixPointSize, 2);    

		// SAMPLE DATA:
			//	url 		- http://www.facebook.com
			//	telephone	- tel:09214552001
			//	sms			- smsto:09214552001:How are you?
			// 	email		- mailto:ian_val2008@yahoo.com:Testing email
			//  Text		- I'm generating a QRCode
			//  geolocation - geo:7.3136286,125.6703633?site=DNSC
    }    
        
    //display generated file
    echo '<img src="'.$PNG_WEB_DIR.basename($filename).'" /><hr/>';  
    
    //config form
    echo '<form action="index.php" method="post">
        Sample data to be generated for QR Code:&nbsp;
		<input name="data"  style="width: 500px; height:40px" value="'.(isset($_REQUEST['data'])?htmlspecialchars($_REQUEST['data']):'').'" />&nbsp;
		<br>
        ECC:&nbsp;<select name="level">
            <option value="L"'.(($errorCorrectionLevel=='L')?' selected':'').'>L - smallest</option>
            <option value="M"'.(($errorCorrectionLevel=='M')?' selected':'').'>M</option>
            <option value="Q"'.(($errorCorrectionLevel=='Q')?' selected':'').'>Q</option>
            <option value="H"'.(($errorCorrectionLevel=='H')?' selected':'').'>H - best</option>
        </select>&nbsp;
		<br>
        Size:&nbsp;<select name="size">';
        
    for($i=1;$i<=10;$i++)
        echo '<option value="'.$i.'"'.(($matrixPointSize==$i)?' selected':'').'>'.$i.'</option>';
        
    echo '</select>&nbsp;
        <input type="submit" value="GENERATE"></form><hr/>';


		
?>    

    