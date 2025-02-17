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
            {data: 'username', name: 'username'},
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
            {data: 'username', name: 'username'},
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
            // data: {
            //     parent_id: $('#parent_id').val() 
            // }
        },
        columns: [
            {data: 'parent_name', name: 'parent_name'},
            {data: 'full_name', name: 'full_name'},
            {data: 'username', name: 'username'},
            {data: 'email', name: 'email'},         
            {data: 'payment_status', name: 'payment_status'},         
            {data: 'plan_expire_date', name: 'plan_expire_date'},         
            {data: 'status', "width": "15%",  name: 'status', orderable: false},
            {data: 'child_payment_status', name: 'child_payment_status'},   
            {data: 'created_at', "width": "14%", name: 'created_at'},  
            {data: 'action', "width": "15%", orderable: false},  
        ],
    });

    var childrensub_table = $('#childsubTable').DataTable({
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
            {data: 'parent_name', name: 'parent_name'},
            {data: 'full_name', name: 'full_name'},
            {data: 'username', name: 'username'}, 
            {data: 'email', name: 'email'},    
            {data: 'payment_status', name: 'payment_status'},         
            {data: 'plan_expire_date', name: 'plan_expire_date'},       
            {data: 'status', "width": "15%",  name: 'status', orderable: false},  
            {data: 'child_payment_status', name: 'child_payment_status'}, 
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
            {data: 'action', "width": "15%", orderable: false},  
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
            {data: 'action', "width": "15%", orderable: false},  
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
            {data: 'action', "width": "15%", orderable: false},  
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
                data: 'drag', 
                name: 'drag', 
                orderable: false, 
                searchable: false 
            }, // Drag handle column
            {
                "name": "featured_image",
                "data": "featured_image",
                "render": function (data, type, full, meta) {
                    return "<img src=\"" + data + "\" height=\"50\"/>";
                },
                "title": "Featured Image",
                "orderable": false,
                "searchable": true
            },
            // {data: 'featured_image', name: 'featured_image'},

            {data: 'name', name: 'name'},
            {data: 'category_id', name: 'category_id'},
            {data: 'status', "width": "15%",  name: 'status', orderable: false},  
            {data: 'created_at', "width": "14%", name: 'created_at'},  
            {data: 'action', "width": "15%", orderable: false},  
        ],
    });

    /* SKILL - ROWS DATA SORTABLE DRAG DROP TO CHANGE SKILL POSITIONS */
    $('#skillTable tbody').sortable({
        handle: '.drag-handle', // Sets the drag handle class
        update: function(event, ui) {
            let order = [];
            var route_name = $('.drag-handle').data('url');
            console.log(route_name);

            $('#skillTable tbody tr').each(function(index, element) {
                order.push({
                    id: $(this).data('id'),
                    position: index + 1
                });
            });

            // Send the new order to the server
            $.ajax({
                url: route_name,
                method: 'POST',
                data: { order: order, _token: $('meta[name="_token"]').attr('content') },
                success: function(response) {
                    if (response.status === 'success') {
                        skill_table.ajax.reload(null, false); // Reload DataTable without resetting pagination
                    }
                }
            });
        }
    }).disableSelection();


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
            {data: 'action', "width": "15%", orderable: false},  
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
            {data: 'action', "width": "15%", orderable: false},  
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
            {data: 'action', "width": "15%", orderable: false},  
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
            {data: 'action', "width": "15%", orderable: false},  
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
            {data: 'action', "width": "15%", orderable: false},  
        ],
    });



    var notification_table = $('#notificationTable').DataTable({
        processing: true,
        serverSide: true, 
        pageLength: 100,
        lengthMenu: [ 100, 200, 300, 400, 500, ],
        ajax: $("#route_name").val(),
        columns: [
            {data: 'user_id', name: 'user_id'},
            {data: 'province', name: 'province'},
            {data: 'school', name: 'school'},
            {data: 'message', name: 'message'},
            {data: 'created_at', "width": "14%", name: 'created_at'},  
            {data: 'action', "width": "12%", orderable: false}, 
        ],
    });

    var ranking_table = $('#rankingTable').DataTable({
        processing: true,
        serverSide: true, 
        pageLength: 100,
        lengthMenu: [ 100, 200, 300, 400, 500, ],
        ajax: {
            url: $("#route_name").val(),
            data: function(d) {
                d.province_id = $('#province_id').val();
                d.school_id = $('#school_id').val();
                d.skill_id = $('#skill_id').val();
                d.age_group = $('#age_group').val();
                d.gender = $('#gender').val();
            }
        },
        columns: [
            {data: 'student_id', name: 'student_id'},
            {data: 'age_group', name: 'age_group'},
            {data: 'gender', name: 'gender'},
            {data: 'skill_id', name: 'skill_id'},
            { 
                data: 'score', 
                name: 'score',
                render: function(data, type, row) {
                    return data;
                }
            },
            {data: 'ranking', name: 'ranking'},
           
        ],
        "order": [[1, "ASC"]]
    });

    var payment_table = $('#paymentTable').DataTable({
        processing: true,
        serverSide: true, 
        pageLength: 100,
        lengthMenu: [ 100, 200, 300, 400, 500, ],        
        ajax: {
            url: $("#route_name").val(),
            data: function (d) {
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
            }
        },
        columns: [
            {data: 'fullname', name: 'fullname'},
            {data: 'transaction_id', name: 'transaction_id'},
            {data: 'amount', name: 'amount'},
            {data: 'payment_date', name: 'payment_date'},         
            {data: 'status', "width": "15%",  name: 'status', orderable: false}, 
            
        ],
    });


    var global_ranking_table = $('#globalRankingTable').DataTable({
        processing: true,
        serverSide: true, 
        pageLength: 100,
        lengthMenu: [ 100, 200, 300, 400, 500, ],
        ajax: {
            url: $("#route_name").val(),
            data: function(d) {
                d.province_id = $('#province_id').val();
                d.school_id = $('#school_id').val();
                d.skill_id = $('#skill_id').val();
                d.age_group = $('#age_group').val();
                d.gender = $('#gender').val();
            }
        },
        columns: [
            {data: 'student_id', name: 'student_id'},
            {data: 'age_group', name: 'age_group'},
            {data: 'gender', name: 'gender'},
            // {data: 'skill_id', name: 'skill_id'},
            { 
                data: 'score', 
                name: 'score',
                render: function(data, type, row) {
                    return data;
                }
            },
            {data: 'ranking', name: 'ranking'},
            // {data: 'created_at', "width": "14%", name: 'created_at'},  
        ],
        "order": [[3, "DESC"]]
    });

    var pages_template = $('#pagesTable').DataTable({
        processing: true,
        serverSide: true, 
        pageLength: 100,
        lengthMenu: [ 100, 200, 300, 400, 500, ],
        ajax: $("#route_name").val(),
        columns: [
            {data: 'title', name: 'title'},       
            {data: 'action', "width": "15%", orderable: false},  
        ],
    });

    var sectionTableMap = {
        'users_table': users_table,
        'parent_table': parent_table,
        'children_table': children_table,
        'childrensub_table': childrensub_table,
        'category_table': category_table,
        'province_table': province_table,
        'school_table': school_table,
        'skill_table': skill_table,
        'sponsors_table': sponsors_table,
        'email_template': email_template,
        'cms_page_table': cms_table,
        'contactus_table': contactus_table,
        'skill-review_table': skillreview_table,
        'notification_table': notification_table,
        'payment_table': payment_table,
        'global_ranking_table': global_ranking_table,
        'ranking_table': ranking_table,
        'pages_template': pages_template,
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

                        if(data == 0){
                            swal("Error", "You can't delete this because it is currently assigned", "error");
                            return false;
                        }

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

     //Change Status
     $('.datatable-dynamic tbody').on('click', '.on_off_child_payment', function (event) {
        event.preventDefault();
        var url = $(this).attr('data-url');
        var id = $(this).attr("data-id");
        var parentid = $(this).attr("data-parentid");        
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
                'parentid': parentid,
                'type': type,
                'table_name': table_name,
            },
            headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
            success: function(data){
                l.stop();

                if(type=='pending'){
                    $('#paid_remove_'+id).hide();
                    $('#paid_add_'+id).show();
                } else {
                    $('#paid_remove_'+id).show();
                    $('#paid_add_'+id).hide();
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
    /* SAVE CHILDREN DATA BY AJAX REQUEST */
    $('#saveBtn').click(function (e) {
        e.preventDefault();

        var laddaButton = Ladda.create(document.querySelector('#saveBtn'));
        laddaButton.start(); // Start the loading animation

        $('#childModel').on('show.bs.modal', function () {
            $('#childForm').find('.text-danger').remove();
        });
    
        // Clear previous validation errors when modal is hidden
        $('#childModel').on('hidden.bs.modal', function () {
            $('#childForm').find('.text-danger').remove();
        });
    
        var url = $('#modal_redirect_url').val();
        var formData = new FormData($('#childForm')[0]); // Create FormData object with form data
    
        $.ajax({
            data: formData,
            url: url,
            headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
            type: "POST",
            contentType: false, // Important for file uploads
            processData: false, // Important for file uploads
            dataType: 'json',
            success: function (data) {
                $('#childForm').trigger("reset");
                $('#childModel').modal('hide');
                childrensub_table.draw();
    
                $('#saveBtn').removeAttr('data-loading');
                $('#saveBtn').removeAttr('disabled');
                laddaButton.stop(); // Stop the loading animation
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $("#" + key).closest('.form-group').append(`
                            <span class="text-danger">
                                <strong>${value}</strong>
                            </span>
                        `);
                    });
                }
                laddaButton.stop(); // Stop the loading animation
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
            $('#gender').val(data.gender).trigger('change');
            $('#province_id').val(data.province_id).trigger('change');
            $('#school_id').val(data.school_id).trigger('change');

            $('#child_phone').val(data.phone);

            $('#child_dob').datepicker("setDate", data.date_of_birth );

            $('#looking_sponsor').prop('checked',false);
            if(data.looking_sponsor == 1){
                $('#looking_sponsor').prop('checked',true);
            }

            $('#province_id').val(data.province_id);
            $('#school_id').val(data.school_id);
            $('#childImage').attr('src',data.image);


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

    // $('#score_instruction').summernote({
    //     placeholder: 'Write your instruction',
    //     tabsize: 2,
    //     height: 250,
    //     toolbar: [
    //       ['style', ['style']],
    //       ['font', ['bold', 'underline', 'clear']],
    //       ['color', ['color']],
    //       ['para', ['ul', 'ol', 'paragraph']],
    //       ['insert', ['link']],
    //       ['view', ['fullscreen', 'codeview']]
    //     ]
    // }); 

    $('#short_description').summernote({
        placeholder: 'Write short description',
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

    $('#long_description').summernote({
        placeholder: 'Write long description',
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

    $('#content').summernote({
        placeholder: 'Enter Content',
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

    $('#clear-filter').click(function() {
        var dataType = $(this).data('type');

        $('#ranking-filter-Form')[0].reset();
        $(".select2").val("").trigger("change");

        if(dataType=='ranking'){
            ranking_table.ajax.reload(null, false);
        }else if (dataType == 'globalranking') {
            global_ranking_table.ajax.reload(null, false);
        }
    });

    $('#apply-filter').click(function() {
        var dataType = $(this).data('type');

        if (dataType == 'ranking') {
            var province_id = $('#province_id').val();
            var school_id = $('#school_id').val();
            var skill_id = $('#skill_id').val();
            var age_group = $('#age_group').val();
            var gender = $('#gender').val();
    
            ranking_table.ajax.reload(function (json) {
                ranking_table.ajax.params({
                    province_id: province_id,
                    school_id: school_id,
                    skill_id: skill_id,
                    age_group: age_group,
                    gender: gender,
                });
            }, false);
        }else if (dataType == 'globalranking') {
            var province_id = $('#province_id').val();
            var school_id = $('#school_id').val();
            var skill_id = $('#skill_id').val();
            var age_group = $('#age_group').val();
            var gender = $('#gender').val();
    
            global_ranking_table.ajax.reload(function (json) {
                global_ranking_table.ajax.params({
                    province_id: province_id,
                    school_id: school_id,
                    skill_id: skill_id,
                    age_group: age_group,
                    gender: gender,
                });
            }, false);
        }
    });

    /* GET SCHOOL BY PROVINCE ID */
    $('.provinceId_filter').on('change', function() {
        var provinceId = $(this).val();

        console.log(provinceId);
        $.ajax({
            url: baseUrl+'/getSchoolByProvinceId/' + provinceId,
            method: 'GET',
            dataType: 'json',
            headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') },
            success: function(data) {
                $('.schoolId_filter').empty().append('<option value="">Select School</option>');
                $.each(data, function(key, value) {
                    $('.schoolId_filter').append('<option value="' + key + '">' + value + '</option>');
                });
            }
        });
    });

    $('#paymentFilter').click(function () {

        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();

         // Validate date inputs
        if (!startDate || !endDate) {            
            swal("Error", "Both Start Date and End Date are required.", "error");
            return; 
        }

        if (new Date(startDate) > new Date(endDate)) {          
            swal("Error", "Start Date cannot be later than End Date.", "error");
            return; 
        }
        payment_table.ajax.reload(); // Reload the table with new parameters
    });
}); 