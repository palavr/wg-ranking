var dataChanged = false;

// eingabe: spalte welche neu zu berechnen ist.
function calcPoints(column) {
	var totalPoints = 0;
	// berechne punkte neu
	totalPoints += 30;

	return totalPoints;
}

// gehe alle trs durch
$('.editable').each(function(i, tr){
	// gehe alle tds durch
	$.each(tr.children, function(it, td){
		// führe calcPoints bei input event aus
		td.addEventListener('input', function() {
		dataChanged = true;
	})
	});
});

// gehe alle trs durch
$('.editable').each(function(i, tr){
	// gehe alle tds durch
	$.each(tr.children, function(it, td){
		// führe calcPoints bei blur event aus
		td.addEventListener('blur', function() {
			if (dataChanged){
				var index = $(this).parent().children().index($(this));
				var points = calcPoints(index);
				$(this).parent().siblings(":last").children().eq(index).html(points);
				dataChanged = false;
			}
		})
	});
});

// berechne hero und villain
function honor(){
	val ind=0;
	$.each(array, function(i, val) {

	})
}


