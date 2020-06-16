	// ---------------- Fileupload perso -------------------------------------------------- //
	var uploader =				new plupload.Uploader({
		runtimes : 				"html5,html4",
		browse_button : 		"browse",
		drop_element : 			"div_drop",
		url : 					url_upload,
		multipart: 				true,
		urlstream_upload: 		true,
		multipart_params:{directory:'test'}
	});
	uploader.init();

	/*if ( uploader.features.triggerDialog ) {
		plupload.addEvent( document.getElementById( 'div_drop' ), 'click', function(e) {
			var input = 		document.getElementById( uploader.id + '_html5' );
			if ( input && !input.disabled ) { // for some reason FF (up to 8.0.1 so far) lets to click disabled input[type=file]
				input.click();
			}
			e.preventDefault();
		}); 
	}*/
	// ------------------------------------------------------------------------------------ //
	
	// ---- Fichier en cours d'upload... -------------------------------------------------- //
	uploader.bind( "UploadProgress", function( up, file ){
		//alert( "UploadProgress" );
		$( "#progress_" + file.id ).css( "width", file.percent + "%" );
	});
	// ------------------------------------------------------------------------------------ //
	
	uploader.bind( "Error", function( up, err ){
		//$("#div_affichage_erreur").html( err.message );
		//$("#div_affichage_erreur").show();
		
		var contenu = "";
		contenu += "<div class='alert alert-danger' style='margin-top: 10px;'>\n";
		contenu += "	<button class='close' data-dismiss='alert' type='button'>&times;</button>" + err.message + "\n";
		contenu += "</div>\n";
		$( "#filelist" ).append( contenu );
		
		//$( "#div_drop" ).removeClass();
		uploader.refresh();
	});
	
	uploader.bind( "FilesAdded", function( up, files ){
		//alert( "Fichiers ajoutés" );
		//alert( files.toSource() );
		//alert( uploader.getOption( "max_file_size" ) );
		
		$("#div_affichage_success").hide();
		$("#div_affichage_erreur").hide();
		$( "#div_drop" ).removeClass( "erreur" );
		
		var filelist = $( "#filelist" );
		var cpt = 0;
		for(var i in files) {
			var file = files[i];
			//alert( file.toSource() );
			
			var contenu = "";	
			contenu += "<div id='div_" + file.id + "' style='margin-top: 25px; border:0px solid blue;'>\n";
			contenu += "	<h5>" + file.name + "</h5>\n";
			contenu += "	<div class='progress'>\n";
			contenu += "		<div id='progress_" + file.id + "' class='progress-bar' style='width:0%'></div>\n";
			contenu += "	</div>\n";
			contenu += "</div>\n";
			
			filelist.append( contenu );
			cpt++;
		}
		
		// ---- On cache la zone de dépôt et on affiche le bouton d'annulation d'upload --- //
		$( "#div_drop_depot" ).hide();
		$( "#div_arret_upload" ).show();
		
		// ---- Lance l'upload ------------------------------------------------------------ //
		uploader.start();
		
		// ---- Réinitialise -------------------------------------------------------------- //
		uploader.refresh();
	});
	
	// ---- Fichier uploadé --------------------------------------------------------------- //
	uploader.bind( "FileUploaded", function( up, file, response ){
		var retour = 				response.response;
		//alert( "FileUploaded : " + retour );
		
		var obj = 					$.parseJSON( retour );
		
		// ---- Une erreur est survenue! -------------------------------------------------- //
		if ( obj.error ) {
			//alert( obj.message );
			var message = 			obj.message.replace( "%nom%", file.name );
			
			var contenu = 	"";
			contenu += 		"<div class='alert alert-danger' style='margin-top: 10px;'>\n";
			contenu += 		"	<button class='close' data-dismiss='alert' type='button'>&times;</button>" + message + "\n";
			contenu += 		"</div>\n";
			$( "#filelist" ).append( contenu );
			
			$( "#div_affichage_success").hide();
			$( "#div_" + file.id ).remove();
		}
		// -------------------------------------------------------------------------------- //

		// ---- Ajout du fichier en base -------------------------------------------------- //
		else {
			// ---- On supprime la barre de progression au bout de 1 seconde -------------- //
			$( "#div_" + file.id ).delay( 1000 ).fadeOut();
			
			// ---- Enregistre en base ce fichier ----------------------------------------- //
			//alert( "On enregistre en base" );
			$.ajax({ 
				type: 				"POST", 
				url: 				url_ajout, 
				data: {
					id_projet:		id_projet,
					fichier:		obj.fichier
				},
				success: 			function( data ){ 
					//alert("Donnees obtenues : " + data ); 
					
					//var obj2 = 		$.parseJSON( retour );
					if ( !data.error ) {
						//alert( "Ajout de l'image dans la zone de droite" );

						// ---- MAJ de la liste des fichiers associés --------------------- //
						$( "#liste_fichier > tbody" ).html( data.html );
					}
					else {
						$("#div_affichage_warning").html( data.message );
						$("#div_affichage_warning").show();
						$( "#div_" + file.id ).remove();
					}
				}
			});
		}
		// -------------------------------------------------------------------------------- //

	});
	// ------------------------------------------------------------------------------------ //
	
	// ---- Tous les fichiers sont uploadés ----------------------------------------------- //
	uploader.bind( "UploadComplete", function( up, file ){
		//alert( "UploadComplete" );
		$( "#div_arret_upload" ).hide();
		$( "#div_drop_depot" ).show();
		$( "#div_affichage_success" ).show();
	});
	// ------------------------------------------------------------------------------------ //
	
	$( "#div_drop" ).bind({
		dragover : 				function(e){
			$(this).addClass( "hover" );
		},
		dragleave : 			function(e){
			$(this).removeClass( "hover" );
		}
	});
	// ------------------------------------------------------------------------------------ //