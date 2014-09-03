
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
			var index = $(this).parent().children().index($(this));
			console.log("hello");
			var points = calcPoints(index);
			$(this).parent().siblings(":last").children().eq(index).html(points);
		})
	});
});


