<CENTER>
<H1>V�rification param�trage</H1>
</CENTER></BR>
<CENTER>
<TABLE BORDER="1">
<TR>
<TD><H2>homidom-web-param.xml</H2></TD>
<TD><H2>Server soap Homidom</H2></TD>
</TR>
<TR>
<?php

$dom = new DomDocument();
$dom->load('homidom-web-param.xml');
$ListeServer = $dom->getElementsByTagName('SERVER'); // on r�cup�re les param�tres du serveur
$server = $ListeServer->item(0);
?>
<TD>
<H3>Param�trage server</H3></BR>
<H4>Adresse IP: <?php echo $server->getAttribute("IP");?></BR>
Port: <?php echo $server->getAttribute("PORT");?></BR>
ID server: <?php echo $server->getAttribute("ID");?></BR></H4>
</TD>
<TD>
<H4>Etat connexion</BR>
<?php
$homidom = new HomidomSoap($server->getAttribute("IP"), $server->getAttribute("PORT"), $server->getAttribute("ID"),true);
echo $homidom->connect() . "</BR>";
if($homidom->GetTime())
	echo "OK � " . $homidom->GetTime() . "</BR>";
else 
	echo "KO";
?></H4>
</TD>
</TR>
<TR>
<TD>
<H3>Zones</H3><BR>
<H4>
<?php
$ListeZone = $dom->getElementsByTagName('ZONE'); // on r�cup�re les zones
foreach($ListeZone as $zone) {
	echo "Nom :" . (utf8_decode($zone->getAttribute("NAME"))) . "</BR>\n";
	echo "Image :" . (utf8_decode($zone->getAttribute("FILENAME"))) . "</BR>\n";
	echo "Icone :" . (utf8_decode($zone->getAttribute("ICON"))) . "</BR>\n";
	echo "</BR>\n";
	}
?>
</H4>
</TD>
<TD>
<H3>Zones</H3><BR>
<H4>
<?php
$x=$homidom->GetAllZones();
foreach($x->Zone as $k=>$v) {
	
	echo "Nom :" . (utf8_decode($v->_Name)) . "</BR>\n";
	echo "Id :" . (utf8_decode($v->_Id)) . "</BR>\n";
	echo "Image :" . (utf8_decode($v->_Image)) . "</BR>\n";
//			$ListeDevice = $zone->getElementsByTagName("HOMIDEVICE");
	echo "</BR>";
	}
?>
</H4>
</TD>
</TR>
</TABLE>
