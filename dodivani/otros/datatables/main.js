$(document).ready(function() { 
	$('#table').DataTable( { 
		select: true,
		dom: 'lBfrtip', buttons: [   'copy', 'csv', 'excel', 'pdf', 'print' ],
		fixedHeader: true
	} ); 
} );