

jQuery(function($) {

	"use strict";


  var loading_html = '<div class="spinner"><div class="rect1" style="margin-right: 1px;"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>';
  var base_url = $('#base_url').val();

  $('[data-toggle="tooltip"]').tooltip(); 
	
  $('.datatable').dataTable();


    $(document).on('change', '.btn-file :file', function() {
    var input = $(this),
      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
    input.trigger('fileselect', [label]);
    });
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#img-upload').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imgInp").change(function(){
        readURL(this);
    });   
   
    //-- ajax login user function 
    $('#login-form').submit(function(){
      alert('hi'); return false;
        $.post($('#login-form').attr('action'), $('#login-form').serialize(), function(json){
            if ( json.st == 0 ){
                $('#login_pass').val('');
                swal({
                  title: "Error..",
                  text: "Sorry your email or password is not correct !",
                  type: "error",
                  confirmButtonText: "Try Again"
                });

            }else if(json.st == 2){
                swal({
                  title: "Error..",
                  text: "Your account has been suspended!",
                  type: "error",
                  confirmButtonText: "Try Again"
                });
            } else {
              window.location = json.url;
            }
        },'json');
        return false;
    });



    $(function(){
        $('#cahage_pass_form').submit(function(){
          
            $.post($('#cahage_pass_form').attr('action'), $('#cahage_pass_form').serialize(), function(json){

                if (json.st == 1) {
                    $('#cahage_pass_form')[0].reset();
                    swal({
                          title: "Oops!",
                          text: "Action disabled for demo",
                          type: "warning",
                          showConfirmButton: true
                    });
                }else if (json.st == 2) {
                    $('#cahage_pass_form')[0].reset();
                    swal({
                      title: "Opps !",
                      text: "Your Confirm Password doesn't Match",
                      type: "error",
                      showConfirmButton: true
                    });
                }else {
                    $('#cahage_pass_form')[0].reset();
                    swal({
                      title: "Error!",
                      text: "Your Old Password doesn't Match",
                      type: "error",
                      showConfirmButton: true
                    });
                }
            },'json');
            return false;
        });

    });




    $(document).on('click', "#make_embaded", function() {

        var url = $("#video_url").val();
        var post_data = {
            'url': url,
            'csrf_test_name' : csrf_token
        };

        $.ajax({
            type: "POST",
            url: base_url + "admin/video/generate_embed_code",
            data: post_data,
            success: function (response) {
                $("#video_embed_code").html(response);
                if (response != "Invalid Url") {
                    $("#video_preview").attr('src', response);
                    $("#video_play_icon").hide();
                }
            }
        });


        $.ajax({
            type: "POST",
            url: base_url + "admin/video/get_video_thumbnails",
            data: post_data,
            success: function (response) {
                $("#video_thumbnails_url").val(response);
                $("#video_thumbnails_img").attr('src', response);
            }
        });

    });



    $(document).on('click', ".approve_img", function() {
        var imgId = $(this).attr('data-id');

        var url = base_url+'admin/photos/approve_img/0/'+imgId;
        $.post(url, { data: 'value', 'csrf_test_name': csrf_token }, function(json) {
           if(json.st == 1){
                swal({
                  title: "Success",
                  text: "Image has been approved.",
                  type: "success",
                  showCancelButton: false
                }),
                $('#img_'+imgId).slideUp();
            }
        }, 'json' );
        return false;
    });


    $(document).on('click', ".add_featured", function() {
        var imgId = $(this).attr('data-id');

        var url = base_url+'admin/photos/approve_img/1/'+imgId;

        $.post(url, { data: 'value', 'csrf_test_name': csrf_token }, function(json) {
           if(json.st == 1){
                swal({
                  title: "Success",
                  text: "Image has been approved and selected featured.",
                  type: "success",
                  showCancelButton: false
                }),
                $('#img_'+imgId).slideUp();
            }
        }, 'json' );
        return false;
    });


    $(document).on('click', ".reject_img", function() {
        var imgId = $(this).attr('data-id');

        var url = base_url+'admin/photos/reject_img/'+imgId;

        $.post(url, { data: 'value', 'csrf_test_name': csrf_token }, function(json) {
           if(json.st == 1){
                swal({
                  title: "Success",
                  text: "Image has been rejected.",
                  type: "success",
                  showCancelButton: false
                }),
                $('#img_'+imgId).slideUp();
            }
        }, 'json' );
        return false;
    });



    $(".sort").change(function(){
        $('.sort_form').submit();
    });

  
    $(document).on('click', ".add_btn", function() {
        $('.add_area').show();
        $('.list_area').hide();
        return false;
    });

    $(document).on('click', ".cancel_btn", function() {
        $('.add_area').hide();
        $('.list_area').show();
        return false;
    });


    $(document).on('click', ".scheduled_post", function() {
        $('.date_area').slideToggle();
        $('this').checked();
        return false;
    });


    $(document).on('change', "#category", function() {
        var catId = $(this).val();
        if(catId != ''){
            var url = base_url+'admin/post/load_subcategory/'+catId;
                $.post(url,{ data: 'value', 'csrf_test_name': csrf_token },function(data){
                    $('#sub_category').html(data);
                    $('#sub_category').prop('disabled', false);
                }
            );
        }  
    });

  


    $(document).on('click', ".delete_item", function() {

        var del_url = $(this).attr('href');
        var imgId = $(this).attr('data-id');


            swal({
              title: "Are you sure?",
              text: "You will not be able to recover this file",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "Yes, delete it!",
              closeOnConfirm: false
            },
            function(){ 

                $.post(del_url, { data: 'value', 'csrf_test_name': csrf_token }, function(json) {
                    if(json.st == 1){     
                        swal({
                          title: "Oops!",
                          text: "Delete options is disabled for demo",
                          type: "warning",
                          showCancelButton: false
                        }),                
                        $("#row_"+imgId).slideUp();
                    }
                },'json');

            });

        return false;

    });
    



    $('.change_pass').click(function(){
        $('.change_password_area').slideDown();
        $('.edit_account_area').hide();
        $("html, body").animate({ scrollTop: 200 }, "slow");
        return false;
    });

    $('.cancel_pass').click(function(){
        $('.change_password_area').hide();
        $('.edit_account_area').slideDown();
        return false;
    });





});