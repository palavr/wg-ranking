var dataChanged = false;

// eingabe: spalte welche neu zu berechnen ist.
function calcPoints(column) {
	var totalPoints = 0;
	// berechne punkte neu
	totalPoints += 30;

	return totalPoints;
}

// setze data changed listener
// gehe alle trs durch
$('.editable').each(function(i, tr){
	// gehe alle tds durch
	$.each(tr.children, function(it, td){
		addInputEvt(td);
	});
});

// setze 
// gehe alle trs durch
$('.editable').each(function(i, tr){
	// gehe alle tds durch
	$.each(tr.children, function(it, td){
		addBlurEvt(td);
	});
});

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
			if (dataChanged){
				var index = $(this).parent().children().index($(this));
				// berechne punkte neu
				var points = calcPoints(index);
				// update punkte in table
				$(this).parent().siblings(":last").children().eq(index).html(points);
				// adde neue zelle
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


// berechne hero und villain
/*function honor(){
	val ind=0;
	$.each(array, function(i, val) {

	})
}*/


