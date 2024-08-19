$(function () {
   

    var users_table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true, 
        pageLength: 100,
        lengthMenu: [ 100, 200, 300, 400, 500, ],
        ajax: $("#route_name").val(),
        columns: [
            {data: 'firstname', name: 'firstname'},
            {data: 'lastname', name: 'lastname'},
            {data: 'email', name: 'email'},         
            {data: 'status', "width": "15%",  name: 'status', orderable: false},  
            {data: 'created_at', "width": "14%", name: 'created_at'},  
            {data: 'action', "width": "15%", orderable: false},  
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
            {data: 'lastname', name: 'lastname'},
            {data: 'email', name: 'email'},         
            {data: 'status', "width": "15%",  name: 'status', orderable: false},  
            {data: 'created_at', "width": "14%", name: 'created_at'},  
            {data: 'action', "width": "15%", orderable: false},  
        ],
    });

    var children_table = $('#childTable').DataTable({
        processing: true,
        serverSide: true, 
        pageLength: 100,
        lengthMenu: [ 100, 200, 300, 400, 500, ],
        ajax: {
            url: $("#route_name").val(),
            data: {
                parent_id: $('#parent_id').val() 
            }
        },
        columns: [
            {data: 'firstname', name: 'firstname'},
            {data: 'lastname', name: 'lastname'},
            {data: 'email', name: 'email'},         
            {data: 'status', "width": "15%",  name: 'status', orderable: false},  
            {data: 'created_at', "width": "14%", name: 'created_at'},  
            {data: 'action', "width": "15%", orderable: false},  
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
            {data: 'action', "width": "13%", orderable: false},  
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
            {data: 'action', "width": "13%", orderable: false},  
        ],
    });

    var skill_table = $('#skillTable').DataTable({
        processing: true,
        serverSide: true, 
        pageLength: 100,
        lengthMenu: [ 100, 200, 300, 400, 500, ],
        ajax: $("#route_name").val(),
        columns: [
            {
                "name": "featured_image",
                "data": "featured_image",
                "render": function (data, type, full, meta) {
                    return "<img src=\"" + data + "\" height=\"50\"/>";
                },
                "title": "Featured Image",
                "orderable": true,
                "searchable": true
            },
            // {data: 'featured_image', name: 'featured_image'},

            {data: 'name', name: 'name'},
            {data: 'category_id', name: 'category_id'},
            {data: 'status', "width": "15%",  name: 'status', orderable: false},  
            {data: 'created_at', "width": "14%", name: 'created_at'},  
            {data: 'action', "width": "12%", orderable: false},  
        ],
    });


    var sponsors_table = $('#sponsorsTable').DataTable({
        processing: true,
        serverSide: true, 
        pageLength: 100,
        lengthMenu: [ 100, 200, 300, 400, 500, ],
        ajax: $("#route_name").val(),
        columns: [
            {
                "name": "image",
                "data": "image",
                "render": function (data, type, full, meta) {
                    return "<img src=\"" + data + "\" height=\"50\"/>";
                },
                "title": "Logo Image",
                "orderable": true,
                "searchable": true
            },
            {data: 'name', name: 'name'},
            {data: 'status', "width": "15%",  name: 'status', orderable: false},  
            {data: 'created_at', "width": "14%", name: 'created_at'},  
            {data: 'action', "width": "13%", orderable: false},  
        ],
    });


    var email_template = $('#emailTemplateTable').DataTable({
        processing: true,
        serverSide: true, 
        pageLength: 100,
        lengthMenu: [ 100, 200, 300, 400, 500, ],
        ajax: $("#route_name").val(),
        columns: [
            {data: 'template_name', name: 'template_name'},
            {data: 'template_subject', name: 'template_subject'},
            {data: 'status', "width": "15%",  name: 'status', orderable: false},  
            {data: 'created_at', "width": "14%", name: 'created_at'},  
            {data: 'action', "width": "8%", orderable: false},  
        ],
    });
    

    
    var cms_table = $('#cmsTable').DataTable({
        processing: true,
        serverSide: true, 
        pageLength: 100,
        lengthMenu: [ 100, 200, 300, 400, 500, ],
        ajax: $("#route_name").val(),
        columns: [
            {data: 'page_name', name: 'page_name'},
            {data: 'created_at', "width": "14%", name: 'created_at'},  
            {data: 'action', "width": "12%", orderable: false},  
        ],
    });

    var contactus_table = $('#contactUsTable').DataTable({
        processing: true,
        serverSide: true, 
        pageLength: 100,
        lengthMenu: [ 100, 200, 300, 400, 500, ],
        ajax: $("#route_name").val(),
        columns: [
            {data: 'fullname', name: 'fullname'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'created_at', "width": "14%", name: 'created_at'},  
            {data: 'action', "width": "12%", orderable: false},  
        ],
    });

    var skillreview_table = $('#skillReviewTable').DataTable({
        processing: true,
        serverSide: true, 
        pageLength: 100,
        lengthMenu: [ 100, 200, 300, 400, 500, ],
        ajax: $("#route_name").val(),
        columns: [
            {data: 'skill_id', name: 'skill_id'},
            {data: 'student_id', name: 'student_id'},
            {data: 'province_id', name: 'province_id'},
            {data: 'score', name: 'score'},
            {data: 'time_duration', name: 'time_duration'},
            {data: 'status', name: 'status'},
            {data: 'created_at', "width": "14%", name: 'created_at'},  
            {data: 'action', "width": "12%", orderable: false},  
        ],
    });



    var notification_table = $('#notificationTable').DataTable({
        processing: true,
        serverSide: true, 
        pageLength: 100,
        lengthMenu: [ 100, 200, 300, 400, 500, ],
        ajax: $("#route_name").val(),
        columns: [
            {data: 'fullname', name: 'fullname'},
            {data: 'email', name: 'email'},
            {data: 'province', name: 'province'},
            {data: 'school', name: 'school'},
            {data: 'status', name: 'status'},
            {data: 'created_at', "width": "14%", name: 'created_at'},  
            {data: 'action', "width": "12%", orderable: false},  
        ],
    });


    var sectionTableMap = {
        'users_table': users_table,
        'parent_table': parent_table,
        'children_table': children_table,
        'category_table': category_table,
        'province_table': province_table,
        'school_table': school_table,
        'skill_table': skill_table,
        'sponsors_table': sponsors_table,
        'email_template': email_template,
        'cms_table': cms_table,
        'contactus_table': contactus_table,
        'skillreview_table': skillreview_table,
        'notification_table': notification_table,
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

    $('.datatable-dynamic tbody').on('click', '.on_off', function (event) {
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
                    $('#on_remove_'+id).hide();
                    $('#on_add_'+id).show();
                } else {
                    $('#on_remove_'+id).show();
                    $('#on_add_'+id).hide();
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

     /* CHILDREN ADD/EDIT FROM MODAL POPUP */

    /* ADD NEW CHILDREN */
    $('#createNewChilren').click(function () {
        $('#saveBtn').val("create-children");
        $('#child_id').val('');
        $('#childForm').trigger("reset");
        $('#modelHeading').html("Create New Children");
        $('#childModel').modal('show');
    });

    /* SAVE CHILDREN DATA BY AJAX REQUEST */
    $('#saveBtn').click(function (e) {
        e.preventDefault();
        // $(this).html('Sending..');
        var url = $('#modal_redirect_url').val();
        var formdata = $(document).find('#childForm').serialize();
        console.log(formdata);

        $.ajax({
            data: formdata,
            url: url,
            headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
            type: "POST",
            dataType: 'json',
            success: function (data) {
        
                $('#childForm').trigger("reset");
                $('#childModel').modal('hide');
                children_table.draw();

                $('#saveBtn').removeAttr('data-loading');
                $('#saveBtn').removeAttr('disabled');
            
            },
            error: function (data) {
                console.log('Error:', data);
                // $('#saveBtn').html('Save Changes');
            }
        });
    });

    /* EDIT CHILDREN */
    $(document).on('click', '.editChild', function () {
        var child_id = $(this).data('id');
        var url = $(this).data('url');

        $.get(url +'/edit', function (data) {
            console.log(data);

            $('#modelHeading').html("Edit Children");
            $('#saveBtn').val("edit-children");
            $('#childModel').modal('show');
            $('#child_id').val(data.id);
            $('#child_firstname').val(data.firstname);
            $('#child_lastname').val(data.lastname);
            $('#child_username').val(data.username);
            $('#child_email').val(data.email);
            // $('#child_dob').val(data.date_of_birth);
            $('#child_phone').val(data.phone);

            $('#child_dob').datepicker("setDate", data.date_of_birth );

            $('#looking_sponsor').prop('checked',false);
            if(data.looking_sponsor == 1){
                $('#looking_sponsor').prop('checked',true);
            }

            $('#province_id').val(data.province_id);
            $('#school_id').val(data.school_id);


        })
    });


    $('#instruction').summernote({
        placeholder: 'Write your instruction',
        tabsize: 2,
        height: 250,
        toolbar: [
          // ['style', ['style']],
          ['font', ['bold']],
          // ['color', ['color']],
          ['para', ['ul']],
          ['insert', ['link']],
          // ['view', ['fullscreen', 'codeview']]
        ]
    }); 

    $('#score_instruction').summernote({
        placeholder: 'Write your instruction',
        tabsize: 2,
        height: 250,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['insert', ['link']],
          ['view', ['fullscreen', 'codeview']]
        ]
    }); 

    $('#message').summernote({
        placeholder: 'Write your message',
        tabsize: 2,
        height: 250,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['insert', ['link']],
          ['view', ['fullscreen', 'codeview']]
        ]
    }); 

    
    $('#description').summernote({
        placeholder: 'Enter Description',
        tabsize: 2,
        height: 250,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['insert', ['link']],
          ['view', ['fullscreen', 'codeview']]
        ]
    }); 


    
    $('#template_message').summernote({
        placeholder: 'Enter Description',
        tabsize: 2,
        height: 250,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['insert', ['link']],
          ['view', ['fullscreen', 'codeview']]
        ]
    }); 

    $('#page_content').summernote({
        placeholder: 'Enter Message',
        tabsize: 2,
        height: 250,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['insert', ['link']],
          ['view', ['fullscreen', 'codeview']]
        ]
    }); 


    //get Order Indo
    $('.datatable-dynamic tbody').on('click', '.view-info', function (event) {
        event.preventDefault();
        $("#filter_select_for").trigger('change');
        var url = $(this).attr('data-url');
        var id = $(this).attr("data-id");
        var selectForPrice = $(this).attr("data-select-for-price");

        $.ajax({
            url: url,
            type: "GET",
            data: {
                'id': id,
                'selectForPrice': selectForPrice
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
            success: function(data){
                $('#commonModal .modal-content').html(data);
                $('#commonModal').modal('show');
            }
        });
    });


    //change order status
    $('#skillReviewTable tbody').on('change', '.scoreStatus', function (event) {
        event.preventDefault();
        var scoreId = $(this).attr('data-id');
        var status = $(this).val();
        swal({
            title: "Are you sure?",
            text: "You want to update the status of this score, and the system will send an email regarding the status update.",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#17a2b8',
            confirmButtonText: 'Yes, Sure',
            cancelButtonText: "No, cancel",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: $("#score_status_update").val(),
                    type: "post",
                    data: {'id': scoreId, 'status': status, '_token': $('meta[name=_token]').attr('content') },
                    success: function(data){
                        if(data == 1){
                            swal("Success", "Score status is updated!", "success");
                        } else {
                            swal("Error", "Something is wrong!", "error");
                        }

                        skillreview_table.row('.selected').remove().draw(false);
                    }
                });
            } else {
                swal("Cancelled", "Your data is safe!", "error");
            }
        });
    });

}); 