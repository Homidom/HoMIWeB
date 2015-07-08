<?php

if(!$ConExist) {
		// Si pas de connexion, on affiche la page des param�tres
		include("homidom-web-param.php");
}
if(!empty($_GET)) { // d�but test sur zoneid

	$zoneid=$_GET['zoneid'];
	switch($zoneid) {
		
		case "" :
		break;
		
		case "servercheck":
		// *****************  d�but bloc pour servercheck *****************
		include("servercheck.php");

		break;
		// *****************  fin bloc pour servercheck *****************

		case "Parametres":
		// *****************  d�but bloc pour parametre *****************
		if($ConExist) include("homidom-web-param.php");

		break;
		// *****************  fin bloc pour parametre *****************

		default :
		// *****************  d�but bloc pour zone *****************
		$homizone=$homidom->ReturnZoneByID($zoneid);

		$FileNameZone = "imagezone.php?zoneid=" . $zoneid;
	
		foreach($ListeXMLZone as $zone) 
			if(utf8_decode($zone->getAttribute("ID"))==utf8_decode($homizone->_Id)) {
				$ListeDevice = $zone->getElementsByTagName("HOMIDEVICE");
			}
?>

<SCRIPT language=javascript>
 	function getXhr()
	{
		var xhr = null; 
		if(window.XMLHttpRequest) // Firefox et autres
		   xhr = new XMLHttpRequest(); 
		else if(window.ActiveXObject)
		{ // Internet Explorer 
		   try {
					xhr = new ActiveXObject("Msxml2.XMLHTTP");
				} catch (e) 
				{
					xhr = new ActiveXObject("Microsoft.XMLHTTP");
				}
		}
		else { // XMLHttpRequest non support� par le navigateur 
		   alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest..."); 
		   xhr = false; 
		} 
						return xhr
	}

	// Node cleaner
	function go(c)
	{
		if(!c.data.replace(/\s/g,''))
			c.parentNode.removeChild(c);
	}

	function clean(d)
	{
		var bal=d.getElementsByTagName('*');

		for(i=0;i<bal.length;i++){
			a=bal[i].previousSibling;
			if(a && a.nodeType==3)
				go(a);
			b=bal[i].nextSibling;
			if(b && b.nodeType==3)
				go(b);
		}
		return d;
	} 

	
	/**
	* M�thode qui sera appel�e par le timer
	*/
	function GetHomiData()
	{
		var ZoneId = "<?php echo $zoneid ?>";
		var xhr = getXhr()
		// On d�fini ce qu'on va faire quand on aura la r�ponse
		xhr.onreadystatechange = function()
		{
			// On ne fait quelque chose que si on a tout re�u et que le serveur est ok
			var NameHomiDevice,strValueHomiDevice;
			if(xhr.readyState == 4 && xhr.status == 200)
			{
				response = clean(xhr.responseXML.documentElement);
				var HomiDevice = response.getElementsByTagName("HOMIDEVICE");
				var eltDEVICENOXML = document.getElementById("DEVICENOXML");
				eltDEVICENOXML.innerHTML = "";
				
				count = HomiDevice.length;
				for(i = 0; i < count; i++) 
				{ 
				NameHomiDevice = HomiDevice[i].getElementsByTagName("NAME")[0].firstChild.nodeValue;
				IDHomiDevice = HomiDevice[i].getElementsByTagName("ID")[0].firstChild.nodeValue;
				if(HomiDevice[i].getElementsByTagName("VALUE")[0].firstChild)
				{
					strValueHomiDevice = HomiDevice[i].getElementsByTagName("VALUE")[0].firstChild.nodeValue;
				}
				else
				{
					strValueHomiDevice = null;
				}
				switch(IDHomiDevice)	{
<?php 
if(!empty($ListeDevice))
foreach($ListeDevice as $device) {
	echo "					case '" . $device->getAttribute("ID") . "':\n";
	echo "						elt = document.getElementById(\"Div_" . $device->getAttribute("ID") . "\");\n";
	echo "						switch(strValueHomiDevice)\n" ;
	echo "							{\n";
	foreach($device->getElementsByTagName("ETAT") as $DeviceValue)
		{
			if($DeviceValue->getAttribute("VALUE") == "NULL") {
					echo "							case null :\n";
					echo "								elt.innerHTML = \"<IMG SRC=\\\"" . $DeviceValue->getAttribute("IMG") . 
															"\\\" height=\\\"" . $DeviceValue->getAttribute("HEIGHT") .
															"\\\" width=\\\"" . $DeviceValue->getAttribute("WIDTH") . "\\\">\";\n";
					echo "								break;\n\n";
					}
			else 	{
				echo "							case '" . $DeviceValue->getAttribute("VALUE") . "' :\n";
					echo "								elt.innerHTML = \"<IMG SRC=\\\"" . $DeviceValue->getAttribute("IMG") . 
															"\\\" height=\\\"" . $DeviceValue->getAttribute("HEIGHT") .
															"\\\" width=\\\"" . $DeviceValue->getAttribute("WIDTH") . "\\\">\";\n";
				echo "								break;\n\n";
				}
		}
	echo "							default:\n";
	echo "								elt.innerHTML = \"<font style=\\\"font-size:12px ; color: #FFFFFF\\\">\" + \n";
	echo "								strValueHomiDevice + \"</font>\";\n";
	echo "								break;\n";
	echo "							}\n";
	echo "						break;\n\n";
}

?>
					default:
						if(strValueHomiDevice)
							eltDEVICENOXML.innerHTML = eltDEVICENOXML.innerHTML + "<font style=\"font-size:12px ; color: #FFFFFF\">" + NameHomiDevice +
										" : " + HomiDevice[i].getElementsByTagName("VALUE")[0].firstChild.nodeValue + "</FONT><BR>";
						else
							eltDEVICENOXML.innerHTML = eltDEVICENOXML.innerHTML + "<font style=\"font-size:12px ; color: #FFFFFF\">" + NameHomiDevice +
										" : " + "</FONT><BR>";
						break;
					}
					
				} 
			}
		}
		xhr.open("POST","getdevicebyzone.php",true);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		xhr.send("zoneid="+ZoneId);
	}
		
	
	function ExecuteDevice(strNameDevice,strAction,strParam)
	{
		var xhr = getXhr()
		// On envoie une commande pour un device

		xhr.open("POST","executedevice.php",true);
		xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
		xhr.send("strNameDevice="+strNameDevice+"&strAction="+strAction+"&strParam="+strParam);
	}	
	
   function Timer() 
   {
		var dt=new Date()
		GetHomiData();
		setTimeout("Timer()",5000);
   }
   
 
  GetHomiData();
  Timer();

</SCRIPT>

<?php
		echo "<DIV ID=\"ZoneName\" STYLE=\"position:absolute; left:100px\"><H1>" . utf8_decode($homizone->_Name) . "</H1></DIV>\n";
echo "<DIV ID=\"IDFOND\" STYLE=\"position:absolute; top:50px; \"><IMG SRC=\"" . $FileNameZone . "\"></DIV>\n";

if(!empty($ListeDevice))
		foreach($ListeDevice as $device) {
			$PosY = $device->getAttribute("POSY") + 50;
			$PosX = $device->getAttribute("POSX");
			$ListeAction = $device->getElementsByTagName("ACTION");
			if($ListeAction->length > 0) {
				echo "<DIV ID=\"DivIcon_" . $device->getAttribute("ID") . "\" style=\"position: absolute; " . "top:" . ($PosY - 10) . "px; left:" . 
					($PosX - 20) . "px\" onclick=\"MontrerMenu('CMenu_" . $device->getAttribute("ID") . "');\">\n" . 
					"<IMG SRC=\"" . $device->getAttribute("ICON") . 
					"\" WIDTH=\"" . $device->getAttribute("WIDTH") . "\" HEIGHT=\"" . $device->getAttribute("HEIGHT") . "\"></DIV>\n"; 
					
				echo "<DIV ID=\"Div_" . $device->getAttribute("ID") . "\" style=\"position: absolute; " .
					  "top:" . $PosY . "px; left:" . $PosX . "px\" onclick=\"MontrerMenu('CMenu_" . $device->getAttribute("ID") . "');\"></DIV>\n";
				}
			else {
				echo "<DIV ID=\"DivIcon_" . $device->getAttribute("ID") . "\" style=\"position: absolute; " . "top:" . ($PosY - 10) . "px; left:" . 
					($PosX - 20) . "px\">\n" . 
					"<IMG SRC=\"" . $device->getAttribute("ICON") . 
					"\" WIDTH=\"" . $device->getAttribute("WIDTH") . "\" HEIGHT=\"" . $device->getAttribute("HEIGHT") . "\"></DIV>\n"; 
					
				echo "<DIV ID=\"Div_" . $device->getAttribute("ID") . "\" style=\"position: absolute; " .
					  "top:" . $PosY . "px; left:" . $PosX . "px\"></DIV>\n";
				}
		  }
		  
		// *****************  fin bloc pour zone *****************
	break;
	}
}


?>
