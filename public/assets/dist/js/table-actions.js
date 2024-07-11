$(function () {
   

    var users_table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true, 
        pageLength: 100,
        lengthMenu: [ 100, 200, 300, 400, 500, ],
        ajax: $("#route_name").val(),
        columns: [
            {data: 'firstname', name: 'firstname'},
            {data: 'username', name: 'username'},
            {data: 'email', name: 'email'},         
            {data: 'status', "width": "15%",  name: 'status', orderable: false},  
            {data: 'created_at', "width": "14%", name: 'created_at'},  
            {data: 'action', "width": "12%", orderable: false},  
        ],
    });

    var parent_table = $('#parentTable').DataTable({
        processing: true,
        serverSide: true, 
        pageLength: 100,
        lengthMenu: [ 100, 200, 300, 400, 500, ],
        ajax: $("#route_name").val(),
        columns: [
            {data: 'firstname', name: 'firstname'},
            {data: 'username', name: 'username'},
            {data: 'email', name: 'email'},         
            {data: 'status', "width": "15%",  name: 'status', orderable: false},  
            {data: 'created_at', "width": "14%", name: 'created_at'},  
            {data: 'action', "width": "12%", orderable: false},  
        ],
    });

    var child_table = $('#childTable').DataTable({
        processing: true,
        serverSide: true, 
        pageLength: 100,
        lengthMenu: [ 100, 200, 300, 400, 500, ],
        ajax: $("#route_name").val(),
        columns: [
            {data: 'firstname', name: 'firstname'},
            {data: 'username', name: 'username'},
            {data: 'email', name: 'email'},         
            {data: 'status', "width": "15%",  name: 'status', orderable: false},  
            {data: 'created_at', "width": "14%", name: 'created_at'},  
            {data: 'action', "width": "12%", orderable: false},  
        ],
    });

    var category_table = $('#categoryTable').DataTable({
        processing: true,
        serverSide: true, 
        pageLength: 100,
        lengthMenu: [ 100, 200, 300, 400, 500, ],
        ajax: $("#route_name").val(),
        columns: [
            {data: 'name', name: 'name'},
            {data: 'status', "width": "15%",  name: 'status', orderable: false},  
            {data: 'created_at', "width": "14%", name: 'created_at'},  
            {data: 'action', "width": "12%", orderable: false},  
        ],
    });

    var province_table = $('#provinceTable').DataTable({
        processing: true,
        serverSide: true, 
        pageLength: 100,
        lengthMenu: [ 100, 200, 300, 400, 500, ],
        ajax: $("#route_name").val(),
        columns: [
            {data: 'name', name: 'name'},
            {data: 'status', "width": "15%",  name: 'status', orderable: false},  
            {data: 'created_at', "width": "14%", name: 'created_at'},  
            {data: 'action', "width": "12%", orderable: false},  
        ],
    });

    var school_table = $('#schoolTable').DataTable({
        processing: true,
        serverSide: true, 
        pageLength: 100,
        lengthMenu: [ 100, 200, 300, 400, 500, ],
        ajax: $("#route_name").val(),
        columns: [
            {data: 'name', name: 'name'},
            {data: 'town', name: 'town'},
            {data: 'province_id', name: 'province_id'},
            {data: 'status', "width": "15%",  name: 'status', orderable: false},  
            {data: 'created_at', "width": "14%", name: 'created_at'},  
            {data: 'action', "width": "12%", orderable: false},  
        ],
    });



    
    var sectionTableMap = {
        'users_table': users_table,
        'parent_table': parent_table,
        'child_table': child_table,
        'category_table': category_table,
        'province_table': province_table,
        'school_table': school_table,
    };


    //Delete Record
    $('.datatable-dynamic tbody').on('click', '.deleteRecord', function (event) {
        event.preventDefault();
        var id = $(this).attr("data-id");
        var url = $(this).attr("data-url");
        var section = $(this).attr("data-section");

        swal({
            title: "Are you sure?",
            text: "You want to delete this record?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: "No, cancel",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: url,
                    type: "DELETE",
                    headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
                    success: function(data){

                        var table = sectionTableMap[section];
                        console.log(table);

                        if (table) {
                            table.row('.selected').remove().draw(false);
                        }
                  
                        swal("Deleted", "Your data successfully deleted!", "success");
                    }
                });
            } else {
                swal("Cancelled", "Your data safe!", "error");
            }
        });
    });

    //Change Status
    $('.datatable-dynamic tbody').on('click', '.assign_unassign', function (event) {
        event.preventDefault();
        var url = $(this).attr('data-url');
        var id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var table_name = $(this).attr("data-table_name");
        var section = $(this).attr("data-table_name");

        var l = Ladda.create(this);
        l.start();
        $.ajax({
            url: url,
            type: "post",
            data: {
                'id': id,
                'type': type,
                'table_name': table_name,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
            success: function(data){
                l.stop();

                if(type=='unassign'){
                    $('#assign_remove_'+id).hide();
                    $('#assign_add_'+id).show();
                } else {
                    $('#assign_remove_'+id).show();
                    $('#assign_add_'+id).hide();
                }

                if(section=='users_table'){
                    users_table.draw(false);
                } else if(section=='users_table'){
                    users_table.draw(false);
                } else if(section=='users_table'){
                    users_table.draw(false);
                } else if(section=='options_table'){
                    options_table.draw(false);
                }
            }
        });
    });

    
    
});