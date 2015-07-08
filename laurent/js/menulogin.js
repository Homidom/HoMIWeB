	var ListeServeurs = [];
	var SiteActuel = 0;
	
// enregistre les informations d'un site
function ClickBouton(){

	zones.removeAll();
	macros.removeAll();
	
	// Lancement de l'application 
	 if (!window.sessionStorage){
        return;
	 }
	// alert("Lancement de l'application"+ SiteActuel);
	sessionStorage.SiteActuel= SiteActuel;

	ServerName =  ListeServeurs()[SiteActuel].SERVER; 
	PortActuel = ListeServeurs()[SiteActuel].Port;
	AdresseIPActuel = ListeServeurs()[SiteActuel].AdresseIP;
	IDSERVERActuel = ListeServeurs()[SiteActuel].IDSERVER;
	ConnectServer();
}  

// stocke tous les informations de site dans un tableau
function ReadSites(){
	var key;
	// Vide le tableau contenant la definition des sites
	$('#ListeServeurs').empty();
	$("#modalLogin").modal('show');	
	
	if(typeof(Storage)!=="undifined"){
		var Cpt = localStorage.length;
	}	
	
	if  (Cpt)  {
	// Redessine toutes les icones
		for (i=0;i<Cpt;i++) {
			key = localStorage.key(i);
			InfosServeur = JSON.parse(localStorage.getItem(key));
			console.log("Valeurs sauvegard�es : "+key);
			console.log("Valeurs Site: "+i+" "+InfosServeur.SERVER);			
			ListeServeurs.push(InfosServeur);
			if  (InfosServeur.ServerDef) {
				// AfficheSite(i);
				SiteActuel = i;
				ClickBouton();
				return false;
			} 
		}
		console.log("passage par Aucun site par defaut -Affichage de la modal");
		//Aucun site par defaut -Affichage de la modal
//		$('#modalLogin').modal('show');
	}
	else {
		console.log("passage par Aucun site -Affichage de la modal");
		//Aucun site par defaut -Affichage de la modal
//		$('#modalLogin').modal('show');
	}
}	

// Affiche les infos du site selectionn�
function AfficheSite(IndexSite){
//	alert("Passage par la fonction AfficheSite avec le parametre : "+IndexSite +" / "+ ListeServeurs()[IndexSite].SERVER);
	document.getElementById('ServerName').value = ListeServeurs()[IndexSite].SERVER;
	document.getElementById('ServerID').value = ListeServeurs()[IndexSite].IDSERVER;
	document.getElementById('ServerIP').value = ListeServeurs()[IndexSite].AdresseIP;
	document.getElementById('ServerPort').value = ListeServeurs()[IndexSite].Port;
	$("#ServerDef").prop('checked',ListeServeurs()[IndexSite].ServerDef);
	SiteActuel = IndexSite;
}

// Affiche les infos du site selectionn�
function SupprimeSite(){
	if (ListeServeurs().length) {
		//alert("Passage par la fonction SupprimeSite avec le parametre : "+SiteActuel + " - " +ListeServeurs()[SiteActuel].NomSite)  ;	
		localStorage.removeItem(ListeServeurs()[SiteActuel].SERVER);
		ListeServeurs.splice(SiteActuel,1);	
	}	
}

// enregistre les informations d'un site
function AjouterSite(){
	
	var InfosServeur = new Object();
	var ServerName = document.getElementById('ServerName').value.toUpperCase();
	var ServerDefaut = $('#ServerDef').is(":checked");
	var ServerUpdate = false;
	
	console.log("Le nom du serveur est : "+ ServerName+ " / "+ "\"SERVER\":\""+ServerName+"\"");
	console.log("Le serveur est trouv� en : "+ ListeServeurs.indexOf("SERVER:\""+ServerName+"\""));
	if (!ServerName=="") {	
		//suppression des definitions existantes 
		for (i=0;i<localStorage.length;i++) {localStorage.removeItem(ListeServeurs()[i].SERVER);}	
		InfosServeur.SERVER    = ServerName;
		InfosServeur.IDSERVER  = document.getElementById('ServerID').value;
		InfosServeur.AdresseIP = document.getElementById('ServerIP').value;
		InfosServeur.Port      = document.getElementById('ServerPort').value;
		InfosServeur.ServerDef = $('#ServerDef').is(":checked");
		for (i=0; i < ListeServeurs().length; i++) {
			if (ServerDefaut) { ListeServeurs()[i].ServerDef  = false; }
			if (ListeServeurs()[i].SERVER.toLowerCase() == ServerName.toLowerCase()) {
		
			console.log("Debug ToLowCase : "+ ListeServeurs()[i].SERVER.toLowerCase()+" / "+ServerName.toLowerCase());
				// Mise a jour definition existante
				ListeServeurs()[i].SERVER = capitaliseFirstLetter(ServerName);
				ListeServeurs()[i].IDSERVER = InfosServeur.IDSERVER ;
				ListeServeurs()[i].AdresseIP = InfosServeur.AdresseIP ;
				ListeServeurs()[i].Port = InfosServeur.Port  ;
				ListeServeurs()[i].ServerDef =InfosServeur.ServerDef ;
				ServerUpdate = true;
			}
		}
		if (ServerUpdate==false){
			console.log("Passage par ajout Site : "+ ServerName.toLowerCase());
			ListeServeurs.push(InfosServeur);		
		}
		//sauvegardes de toutes les definitions 
		console.log("Nb localstorage : "+ localStorage.length);
		console.log("Nb ListeServeurs : "+ ListeServeurs().length);
		for (i=0; i < ListeServeurs().length; i++) {localStorage.setItem(ListeServeurs()[i].SERVER, JSON.stringify(ListeServeurs()[i]));}	}
	else {
		alert('Veuillez saisir les informations ! ');
	}}  

function onBlur(el) {
		if (el.value == '') {
		el.value = el.defaultValue;
	}
}
function onFocus(el) {
		if (el.value == el.defaultValue) {
		el.value = '';
	}
}
function capitaliseFirstLetter(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}

