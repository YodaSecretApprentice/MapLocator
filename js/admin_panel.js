
$(document).ready(function() {

	AmCharts.loadJSON = function(url) {
		// create the request
		if (window.XMLHttpRequest) {
			// IE7+, Firefox, Chrome, Opera, Safari
			var request = new XMLHttpRequest();
		} else {
			// code for IE6, IE5
			var request = new ActiveXObject('Microsoft.XMLHTTP');
		}

		// load it
		// the last "false" parameter ensures that our code will wait before the
		// data is loaded
		request.open('GET', url, false);
		request.send();

		// parse adn return the output
		return eval(request.responseText);
	};

	var chart;

	// create chart
	AmCharts.ready(function() {

	  // load the data
	  var chartData = AmCharts.loadJSON('fetch_ticket_stats.php');
	  // SERIAL CHART    
	  chart = new AmCharts.AmPieChart();
	  chart.dataProvider = chartData;
	  chart.valueField = "value";
	  chart.titleField = "category";
	  chart.innerRadius = "50%";
	  chart.radius = "30%";
	  chart.colors = ["#FF0F00", "#FF6600", "#FF9E01", "#FCD202", "#F8FF01", "#B0DE09"];
	  // GRAPHS

	  // WRITE
	  chart.write("chartdiv");
	});

		// EDIT users data function
		$('.edit_users_table').editable('./update_users.php?type=ajax_edit', { 
        type      : 'text',
        tooltip   : 'Click to edit...',
        height:($(".edit_users_table").height()) + "px",
    	width: ($(".edit_users_table").width()) + "px",
	    submit : 'OK'
		});

		$('.edit_role').editable('./update_users.php?type=ajax_edit', { 
	    data   : " {'Admin':'Admin','User':'User'}",
	    type   : 'select',
	    submit : 'OK'
	});

		// Delete a user stuff
    // delete the entry once we have confirmed that it should be deleted
    $('.deletebut').click(function() {
        var parent = $(this).closest('tr');
        var checkstr =  confirm('are you sure you want to delete this?');

        if(checkstr == true) {

	        $.ajax({
	            type: 'get',
	            url: 'update_users.php', // <- replace this with your url here
	            data: 'type=ajax_del&delete=' + $(this).attr('id'),
	            beforeSend: function() {
	                parent.animate({'backgroundColor':'#fb6c6c'},300);
	            },
	            success: function() {
	                parent.fadeOut(300, function() {
	                    parent.remove();
	                });
	            }
	        });        
	    }
	    else
	    	return false;
    });

    // open tickets: change state and comment
    $('.edit_open_tickets').editable('./update_open_tickets.php', { 
        type      : 'text',
        height:($(".edit_open_tickets").height()) + "px",
    	width: ($(".edit_open_tickets").width()) + "px",
	    submit : 'OK'
	});

	$('.edit_state').editable('./update_open_tickets.php', { 
	    data   : " {'Open':'Open','Closed':'Closed'}",
	    type   : 'select',
	    submit : 'OK'
	});

    // edit categories 
    $('.edit_cat').editable('./edit_category.php', { 
        type      : 'text',
        height:($(".edit_cat").height()) + "px",
    	width: ($(".edit_cat").width()) + "px",
	    submit : 'OK'
	});


	// make the table showing the currently open tickets pageable
	$('#open_tickets_table').dataTable( {
	    "lengthMenu": [[20, 50, -1], [20, 50, "All"]],
	    "ordering":  false
	} );

	$('#users_table').dataTable( {
		"lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]],
		"ordering":  false,
		"columnDefs": [ { "width": "5%", "targets": 5 }]
	} );

	$('#closed_tickets_table').dataTable( {
		"lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]],
		"ordering":  false
	} );

	$('#ticket_categories_table').dataTable( {
		"lengthMenu": [[10, 20, 50, -1], [10, 20, 50, "All"]],
		"ordering":  false,
		"columnDefs": [ { "width": "5%", "targets": 1 }]
	} );


	$('#add_new_category').ajaxForm({
		success : function (response) {
			response = response.split('[%delimiter%]');

			document.getElementById('category_added').innerHTML = 'Category: "' + response[0] + '" added';
			$('#ticket_categories_table').append('<tr><td>' + response[0] + '</td><td><button type="button" id="button_' + response[1] + '" class="deletecat">Delete category</button> </td></tr>');
		}
	});

    // delete the entry once we have confirmed that it should be deleted
    $('#ticket_categories_table').on('click', 'button.deletecat', function() {
        var parent = $(this).closest('tr');
        var checkstr =  confirm('are you sure you want to delete this?');

        if(checkstr == true) {

	        $.ajax({
	            type: 'get',
	            url: 'edit_category.php', // <- replace this with your url here
	            data: 'type=ajax_del&delete=' + $(this).attr('id'),
	            beforeSend: function() {
	                parent.animate({'backgroundColor':'#fb6c6c'},300);
	            },
	            success: function() {
	                parent.fadeOut(300, function() {
	                    parent.remove();
	                });
	            }
	        });        
	    }
	    else
	    	return false;
    });

    $(".fancybox").fancybox();
    
});