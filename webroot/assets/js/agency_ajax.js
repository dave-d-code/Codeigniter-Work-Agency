// AJAX JS file
// the purpose here is to auto refresh a "table" containing work shifts every 5 mins
// The JS code will check for any new inserted table rows, and will use Jquery to flash and ping that row
// to the user
// uses & compares id numbers in the table rows, which is hidden by CSS


var myCurrentTable, theNewTable;
var oldArray, theFUarray, oddOnes;



	$("document").ready(function() {
		$("#changer").append('<div class="alert alert-info text-center">This table will auto update every 5 mins if left untouched. This page'
		+ ' will alert you to any changes and highlight the row concerned!</div>');
		getData();
		setInterval("getData2()", 300000);
						
			
	});

	// init function to load the first table using CodeIgniter

	function getData() {
		
		$("#newtable1").load(myDirectory);
	}

	// reload table and check for any new inserted rows or minus rows.

	function getData2() { 



		oldArray = [];
		theFUarray = [];
		oddOnes = [];


		myCurrentTable = $("#newtable1 tr").length;

		$(".hidedata").each(function() {
			oldArray.push($(this).html());
		});


		getData();

		$(document).ajaxStop(function() {

			theNewTable = $("#newtable1 tr").length;
			

			$(".hidedata").each(function() {
			theFUarray.push($(this).html());
			});

			oddOnes = arr_diff(oldArray, theFUarray);
			
			if (myCurrentTable != theNewTable) {

				
				$("#changer").html("").append('<div class="alert alert-danger text-center"><h3><span class="glyphicon glyphicon-bell"></span>'
				+  '  New Table Data detected!</h3></div>');
				$("#testsound")[0].play();


				if (theNewTable > myCurrentTable) { flashRow();	}
													
			} else {
				var theTime = new Date();
				$("#changer").html("").append('<div class="alert alert-success text-center"><h3><span class="glyphicon glyphicon-thumbs-up"></span>'  
				+	'  No Change to table detected. Last update at '
					+ theTime.toUTCString() + '</h3></div>');

			}

			theFUarray = [];
			oddOnes = [];

		}); // end of ajax function




		
	} // end of getData2

	// function to work out which values are different in 2 arrays

	function arr_diff (a1, a2) {

	    var a = [], diff = [];

	    for (var i = 0; i < a1.length; i++) {
	        a[a1[i]] = true;
	    }

	    for (var i = 0; i < a2.length; i++) {
	        if (a[a2[i]]) {
	            delete a[a2[i]];
	        } else {
	            a[a2[i]] = true;
	        }
	    }

	    for (var k in a) {
	        diff.push(k);
	    }

	    return diff;
	}

	// function to highlight the rows that are new

	function flashRow () {
		$(".hidedata").each(function() {

		for (var ii = 0; ii < oddOnes.length; ii++) {
			if ($(this).html() == oddOnes[ii]) {
				$(this).parent().attr("class", "myrow");
			}
		}
		});

		$(".myrow").effect("pulsate", {times:20}, 8000);

		$(".myrow").each(function() {
			$(this).removeClass("myrow");
			});
	}


	
	


