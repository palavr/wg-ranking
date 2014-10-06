/*******************************************************************************************
						Init 
*******************************************************************************************/

var dataChanged = false;


// setze data changed listener
// gehe alle trs durch
$('.editable').each(function(i, tr){
	// gehe alle tds durch
	$.each(tr.children, function(it, td){
		addInputEvt(td);
		addBlurEvt(td);
	});
});


/*******************************************************************************************
						Event Listener
*******************************************************************************************/

// add input event listener
function addInputEvt(td){
	// setze data changed flag bei input event 
		td.addEventListener('input', function() {
		dataChanged = true;
	})
}


// add blur event listener
function addBlurEvt(td) {
	// bei blur event 
		td.addEventListener('blur', function() {
			if (dataChanged && $(this).text() != ""){
				var index = getIndex(this);
				$(this).attr('contenteditable', 'false')
				// setze neue edit zelle
				// prüfe ob spalte max einträge hat
				var bool = $('#activities tbody tr:nth-last-child(2)').children().eq(index).text()!="";
				if (!bool) {
					// falls nein: setze nächste (ex) zelle auf contenteditable	
					cellBelow(this).attr('contenteditable', 'true');
				} else {
					// falls ja: füge neue tr hinzu
					var html = "<tr>";	
					for(var i = 0; i < $(this).parent().children().length; i++) {
						// appende neue tds
						if (i == index) {
							// append editables td
							html += '<td contenteditable="true"></td>';
						} else {
							// append nicht editable td
							html += "<td></td>";
						}	
					}
					html += "</tr>";
					// append html to table TODO: vorletzte zeile
					$(html).insertBefore('#pointsTotal');

					// add blur event listener für alle children von neuem tr
					$.each($('#activities tbody tr:nth-last-child(2)').children(), function(indexInArray, newTd) {
						addBlurEvt(newTd);
						addInputEvt(newTd);
					});
				}

				dataChanged = false;
			}
		})
}

/*******************************************************************************************
						Local Storage Functions
*******************************************************************************************/

// check ob local storage supported wird
function supports_html5_storage() {
 	 try {
    	return 'localStorage' in window && window['localStorage'] !== null;
	  } catch (e) {
	    return false;
	  }
}

// speicher item in local storage unter saveId
function save(saveId, item) {
	if (supports_html5_storage){
		localStorage[saveId] = $(item).html();
		return true;
	}
	return false;
}

// speichert die tabelle in db ab
function saveDB() {
	$("#activities tbody .editable").each(function(){
		$(this).children().each(function() {
			if(($(this).attr("contenteditable")== undefined || $(this).attr("contenteditable") == "false") && $(this).html()!="") {			
				$.post("inc/db_save.php", formatActCell($(this)));
			}
		});
	});
	location.reload();
}

// speichere punkte tabelle in db ab 
function savePtsDB() {
	$('#punkte tbody').children().each(function() {
		console.log(formatPunkteCell($(this)));
		$.post("inc/db_save.php", formatPunkteCell($(this)));
	});
}

/*******************************************************************************************
						Page Processing Functions
*******************************************************************************************/

// löscht tabelle, legt neue an
function resetTable() {
	// lösche alle rows außer erste und letzte
	var childs = $('#activities tbody').children();
	$.each(childs, function(counter, item) {
		if (counter < childs.length - 1){
			item.remove();
		}
	});

	// füge an 2. stelle neue row mit leeren zellen ein
	var html = '<tr class="editable">';
	for (var i = 0; i < $('#pointsTotal').children().length; i++) {
		html += '<td contenteditable="true"></td>';
	}
	html += '</tr>';
	$(html).insertBefore('#pointsTotal');

	// add blur event listener für alle children von neuem tr
	$.each($('#activities tbody tr:nth-last-child(2)').children(), function(indexInArray, newTd) {
		addBlurEvt(newTd);
		addInputEvt(newTd);
	});
}

// erweitere verwaltungs tabelle
function addNewActivity() {
	// lies act aus
	var activity= $('#dialog').children(':first').val();
	// überprüfe ob act != ""	
	if (activity!="" && !actAlreadyEntered(activity)) {
		// falls ja
			// lies values aus
			var pts = $('#dialog').children(':eq(1)').val();
			// falls pts leer defaultwert nehmen
			if (pts=="") { 
				pts = 10;
			}
			var html = '<tr class="editable">' + '<td>'  + activity + '</td><td>' + pts + '</td></tr>';
			// trage values in tablle ein
			$('#punkte').append(html);
			// lösche text felder
			$('#dialog').children(':first').val("");
			$('#dialog').children(':eq(1)').val("");
			// hide dialog, show btn
			$('#dialog').hide();
			$('#pointBtn').show();
	} else {
		console.log("Could not enter new activity.");
	}
}

function showDialog() {
	// open dialog	
	$('#dialog').show();
	// hide btn
	$('#pointBtn').hide();
}



/*******************************************************************************************
						Utility Functions
*******************************************************************************************/

// wandelt die activity zelle in speicherbares array um 
function formatActCell(td) {
	return data = {
					"id": 1,
					"task_id": $(td).attr("task_id"), 
					"task_name": $(td).html(),
					"user_name": getUser(td),
					"wg_name": "Sophisticates"
				};
}

// wandelt die verwaltungszelle in speicherbares array um
function formatPunkteCell(td) {
	return data = {
					"id": 2,
					"task_name": $(td).children().first().html(),
					"default_points": $(td).children().first().next().html()
	};
}


// prüft ob act schon in punkte-tabelle eingetragen ist
function actAlreadyEntered(act) {
	
	var ret = false;
	$('#punkte tbody').children().each(function() {
		if (!ret && $(this).children().first().html() == act){
			ret = true;
		} 
	});
	return ret;
}

// gibt column bzw user der zelle zurück
function getUser(td) {
	return $("#activities thead tr").children().eq(getIndex(td)).html();
}

// gibt zelle darüber an
function cellAbove(td) {
	return $(td).parent().prev().children().eq(getIndex(td));
}

// gibt zelle darunter an
function cellBelow(td) {
	return $(td).parent().next().children().eq(getIndex(td));
}

// gibt index der zelle an (= spalte) 
function getIndex(td) {
	return $(td).parent().children().index($(td));
}

// löscht einzelne zelle, !!TODO!!
function deleteCell(td) {
	if (!td.parent().hasClass("editable")) {
		return false;
	}
	// get index of cell

	// gehe alle zellen danach durch
		// gehe alle tr danach durch
		// schiebe zelle 1 tr nach oben 
		// lasse letzte 2 tr aus
		// fülle tr-3 mit leerer zelle

}
