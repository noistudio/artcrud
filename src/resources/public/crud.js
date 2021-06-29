$(document).ready(function () {
    $(".btn_add_field").on("click", function () {
        var field = $(".field_add").val();
        var url = $(this).data("url");
        var _token = $("input[name='_token']").val();
        var next_element=parseInt($(this).data("next_element"));
        $.post(url, {"field": field, "_token": _token})
            .done(function (data) {

                data=data.replaceAll("[NEW]","["+next_element+"]");
                next_element=next_element+1;
                $(".btn_add_field").data("next_element",next_element);
                $(".additional_fields").append(data);
            });
    });
       $(".delete_all").on("click",function(){

       $(".delete_form_all").submit();

       return false;

     });

    $("body").on("click",".delete_row",function(){
        var id=$(this).data("row-id");
        $(".delete_row_btn").data("row-id",id);
        // var myModal = new bootstrap.Modal(document.getElementById('exampleModal'), {
        //     keyboard: false
        // })
        // myModal.show();
        return false;
    });
    $("body").on("click",".delete_row_btn",function(){
        var id=$(this).data("row-id");

        $(".delete_"+id).submit();
        return false;
    })
    $("body").on("change",".row_is_enable",function(){
        var value=0;
        var route=$(this).data("route");
        if($(this).prop("checked")){
            value=1;
        }
        $.get( route+"?val="+value, function( data ) {

        });
        return false;
    });
    $(".js_put_row_down").on("click",function(){

        var this_row = $(this).closest('tr');
        var current_sort=this_row.data("sort");


        var url_change=this_row.data("url-change");

        var next=this_row.next('tr');
        var replace_sort=next.data("sort");
        $.get( url_change+"?current="+current_sort+"&replace="+replace_sort, function( data ) {
             this_row.data("sort",replace_sort);

        });


    });
    $("body").on("click",".btn_move",function(){
        var this_row = $(this).closest('tr');
        var url_change=this_row.data("url-change");
        var current_sort=this_row.data("sort");
        var replace_sort=this_row.find(".input_new_position").val();
        $.get( url_change+"?current="+current_sort+"&replace="+replace_sort, function( data ) {
            var current_url=window.location.href;
            $.get( current_url, function( result ) {

                var parsed_html = $.parseHTML( result);
                var container = $(".rows_super_list", parsed_html);
                var inner = container[0].innerHTML;

                $('.dzenkit-basic-card-body').html(inner);

            });

        });
        return false;
    });
    $(".js_put_row_up").on("click",function(){

        var this_row = $(this).closest('tr');
        var current_sort=this_row.data("sort");


        var url_change=this_row.data("url-change");

        var next=this_row.prev('tr');
        var replace_sort=next.data("sort");
        if(replace_sort) {
            $.get(url_change + "?current=" + current_sort + "&replace=" + replace_sort, function (data) {
             this_row.data("sort",replace_sort);
            });
        }


    });

    $(".btn_create_list,.btn_create_edit").on("click",function(){
        var action_url=$(".crud_form").data("action");
        var form = document.querySelector('.crud_form');

        var data_form=new FormData(form);
        if($(this).hasClass("btn_create_list")){

            data_form.append("after_save","list");

        }else {
            data_form.append("after_save","edit");

        }
        $(".crud_notify").hide();
        $(".crud_notify").html(" ");
        $.ajax({
            url: action_url, // url where to submit the request
            type : "POST", // type of action POST || GET

            data : data_form, // post data || get data
            contentType: false,
            processData: false,
            success : function(result) {
                // you can see the result from the console
                // tab of the developer tools
                if(result.link){
                    if(document.location.href==result.link){
                        document.location.reload();
                    }else {
                        document.location.href = result.link;
                    }
                }
            },
            error: function(xhr, resp, text) {

                console.log('super err');
                console.log(text);
                var html="";

                console.log(xhr.responseJSON);
                console.log(xhr.responseJSON.errors);

                for (var key in xhr.responseJSON.errors) {
                    var error_field=key;
                    if($(".crud_field_"+error_field).length>0){
                        error_field=$(".crud_field_"+error_field).data("title");
                    }
                    xhr.responseJSON.errors[key].forEach((element) => {
                        html=html+"<p><strong>("+error_field+")</strong>-"+element+"</p>";
                    })

                }


                $(".crud_notify").show();
                $(".crud_notify").html(html);
                // console.log(xhr, resp, text);
            }
        })
    });
})

