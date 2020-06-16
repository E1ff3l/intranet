	// ---------------- Fileupload perso -------------------------------------------------- //
	var uploader =				new plupload.Uploader({
		runtimes : 				"html5,flash,html4",
		containes : 			"plupload",
		browse_button : 		"browse",
		drop_element : 			"div_drop",
		url : 					"/annuaire/ajax/ajax_entreprise_image.php",
		multipart: 				true,
		urlstream_upload: 		true,
		multipart_params:{directory:'test'},
		mime_types : [
			{title : 			"Fichiers Images", extensions : "jpg,gif,png"}
		]
	});
	uploader.init();
	
	// ---- Fichier en cours d'upload... -------------------------------------------------- //
	uploader.bind( "UploadProgress", function( up, file ){
		//alert( "UploadProgress" );
		$( "#progress_" + file.id ).css( "width", file.percent + "%" );
	});
	
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
		
		else {
			// ---- On supprime la barre de progression au bout de 3 secondes ------------- //
			$( "#div_" + file.id ).delay( 3000 ).fadeOut();
			
			// ---- Enregistre en base ce fichier ----------------------------------------- //
			//alert( "On enregistre en base" );
			$.ajax({ 
				type: 				"POST", 
				url: 				"/annuaire/ajax/ajax_entreprise_image.php", 
				data: {
					mon_action:		"ajouter", 
					nom_image:		obj.nom_image
				},
				error: function() { alert( "erreur lors de l'enregistrement de l'image en base de données!" ); },
				success: function( retour ){ 
					//alert("Donnees obtenues : " + retour ); 
					
					var obj2 = 		$.parseJSON( retour );
					if ( !obj2.error ) {
						//alert( "Ajout de l'image dans la zone de droite" );
						
						var contenu = '';
						contenu += "<li id='item-" + obj2.num_image + "' class='emplacement'>\n";
						contenu += "	<a href='" + url_site + "/images/entreprise/" + obj.fichier + "' class='fancybox'>\n";
						if ( is_admin )	contenu += "		<img src='" + url_site + "/images/entreprise/crop_" + obj.fichier + "' width='150' style='border-radius:3px;' />\n";
						else			contenu += "		<img src='" + url_site + "/images/entreprise/minicrop_" + obj.fichier + "' width='90' style='border-radius:3px;' />\n";
						contenu += "	</a><br>\n";
						contenu += "	<center><a href='javascript:void(0);' data-num='" + obj2.num_image + "' class='supprimer-photo'><i class='fa fa-trash-o'></i>&nbsp;Supprimer</a></center>\n";
						contenu += "</li>\n";
						
						//alert( contenu );
						
						$( "#sortable" ).append( contenu );
					}
					else {
						$("#div_affichage_warning").html( obj2.message );
						$("#div_affichage_warning").show();
						$( "#div_" + file.id ).remove();
					}
				}
			});
		}
	});
	
	// ---- Tous les fichiers sont uploadés ----------------------------------------------- //
	uploader.bind( "UploadComplete", function( up, file ){
		//alert( "UploadComplete" );
		$( "#div_arret_upload" ).hide();
		$( "#div_drop_depot" ).show();
		$( "#div_affichage_success" ).show();
	});
	
	$( "#div_drop" ).bind({
		dragover : function(e){
			$(this).addClass( "hover" );
		},
		dragleave : function(e){
			$(this).removeClass( "hover" );
		}
	});
	// ------------------------------------------------------------------------------------ //