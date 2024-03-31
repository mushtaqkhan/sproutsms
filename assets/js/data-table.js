// npm package: datatables.net-bs5
// github link: https://github.com/DataTables/Dist-DataTables-Bootstrap5


  
$(document).ready(function() {
    $('#dataTableExample').DataTable( {
        dom: 'Bfrtip',
        className: 'btn btn-success',
        buttons: [
            
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
        
    } );
} );

  
$(document).ready(function() {
    $('.dataTableExample').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
        
    } );
} );
