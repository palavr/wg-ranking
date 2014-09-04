/*******************************************************************************************
						Init Functions
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
				var index = $(this).parent().children().index($(this));
				// berechne punkte neu
				var points = calcPoints(index);
				// update punkte in table
				$(this).parent().siblings(":last").children().eq(index).html(points);
				// setze neue edit zelle
				// prüfe ob spalte max einträge hat
				var bool = $('#activities tbody tr:nth-last-child(2)').children().eq(index).text()!="";
				if (!bool) {
					// falls nein: setze nächste (ex) zelle auf contenteditable	
					$(this).parent().next().children().eq(index).attr('contenteditable', 'true');
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
					$(html).insertBefore('#points');

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

// speicher table in local storage
function saveTable(saveId) {
	if (supports_html5_storage){
		localStorage['tableActivities'] = $('#activities').html();
		return true;
	}
	return false;
}

// lade table aus local storage
function load(saveId, destination) {
	if (supports_html5_storage && localStorage[saveId] != null) {
		localStorage.removeItem('table_activities');
		$(destination).html(localStorage[saveId]);
		return true;
	}
	return false;
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
	for (var i = 0; i < $('#points').children().length; i++) {
		html += '<td contenteditable="true"></td>';
	}
	html += '</tr>';
	$(html).insertBefore('#points');
}


// eingabe: spalte welche neu zu berechnen ist.
function calcPoints(column) {
	var totalPoints = 0;
	// berechne punkte neu
	totalPoints += 30;

	return totalPoints;
}

// berechne hero und villain
/*function honor(){
	val ind=0;
	$.each(array, function(i, val) {

	})
}*/


