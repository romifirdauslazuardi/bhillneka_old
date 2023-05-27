<!-- JAVASCRIPT -->
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/feather-icons/feather.min.js"></script>
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/simplebar/simplebar.min.js"></script>
<!-- Main Js -->
<script src="{{URL::to('/')}}/templates/dashboard/assets/js/plugins.init.js"></script>
<script src="{{URL::to('/')}}/templates/dashboard/assets/js/app.js"></script>
<script src="{{URL::to('/')}}/templates/dashboard/assets/js/jquery.min.js"></script>
<!-- SweetAlert 2 -->
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/sweetalert2/sweetalert2.min.js"></script>
<!-- Select2 -->
<script src="{{URL::to('/')}}/templates/dashboard/assets/libs/bootstrap-select2/select2.min.js"></script>
<script>
    $(function() {
        if ($('.select2').length >= 1) {
            $('.select2').select2({
                width : "100%",
            });
        }
    });

    function openLoader(){
        $('.preloader').removeClass('d-none');
        $('.preloader').css('display','block');
        $('.preloader').css('visibility','visible');
        $('.preloader').css('opacity','1');
        $('.preloader').find('#status').css('display','block');
    }

    function closeLoader(){
        $('.preloader').addClass('d-none');
        $('.preloader').css('display','none');
        $('.preloader').css('visibility','none');
        $('.preloader').css('opacity','0');
        $('.preloader').find('#status').css('display','none');
    }

    function responseSuccess(message, callback = null) {
	    Swal.fire({
	        icon: 'success',
	        title: 'success',
	        html: message,
            timer : 5000,
	    }).then((ok) => {
	        if (callback != null) {
	            return location.href = callback
	        }
	    })
	}

	function responseFailed(message) {
	    Swal.fire({
	        icon: 'error',
	        title: 'Oops...',
	        html: message,
            timer : 5000,
	    })
	}

	function responseInternalServerError() {
	    Swal.fire({
	        icon: 'error',
	        title: 'Oops...',
	        html: 'Internal server error',
            timer : 5000,
	    })
	}

    function getProvince(selector,selectedId=null){
        $.ajax({
            url : '{{route("api.indonesia.province")}}',
            method : "GET",
            dataType : "JSON",
            beforeSend : function(){
                return openLoader();
            },
            success : function(resp){
                if(resp.success == false){
                    responseFailed(resp.message);       
                    $(selector+'').html("");            
                }
                else{
                    let html = "";
                    $.each(resp.data,function(index,element){
                        if(selectedId != null && element.code == selectedId){
                            html += '<option value="'+element.code+'" selected>'+element.name+'</option>';
                        }
                        else{
                            html += '<option value="'+element.code+'">'+element.name+'</option>';
                        }
                    });
                    $(selector+'').append(html);
                }
            },
            error: function (request, status, error) {
                if(request.status == 422){
                    responseFailed(request.responseJSON.message);
                }
                else{
                    responseInternalServerError();
                }
            },
            complete :function(){
                return closeLoader();
            }
        })
    }

    function getCity(selector,province_code,selectedId=null){
        $.ajax({
            url : '{{route("api.indonesia.city")}}',
            method : "GET",
            data : {
                province_code : province_code
            },
            dataType : "JSON",
            beforeSend : function(){
                return openLoader();
            },
            success : function(resp){
                if(resp.success == false){
                    responseFailed(resp.message);       
                    $(selector+'').html("");            
                }
                else{
                    let html = "";
                    $.each(resp.data,function(index,element){
                        if(selectedId != null && element.code == selectedId){
                            html += '<option value="'+element.code+'" selected>'+element.name+'</option>';
                        }
                        else{
                            html += '<option value="'+element.code+'">'+element.name+'</option>';
                        }
                    });
                    $(selector+'').append(html);
                }
            },
            error: function (request, status, error) {
                if(request.status == 422){
                    responseFailed(request.responseJSON.message);
                }
                else{
                    responseInternalServerError();
                }
            },
            complete :function(){
                return closeLoader();
            }
        })
    }

    function getDistrict(selector,city_code,selectedId=null){
        $.ajax({
            url : '{{route("api.indonesia.district")}}',
            method : "GET",
            data : {
                city_code : city_code
            },
            dataType : "JSON",
            beforeSend : function(){
                return openLoader();
            },
            success : function(resp){
                if(resp.success == false){
                    responseFailed(resp.message);       
                    $(selector+'').html("");            
                }
                else{
                    let html = "";
                    $.each(resp.data,function(index,element){
                        if(selectedId != null && element.code == selectedId){
                            html += '<option value="'+element.code+'" selected>'+element.name+'</option>';
                        }
                        else{
                            html += '<option value="'+element.code+'">'+element.name+'</option>';
                        }
                    });
                    $(selector+'').append(html);
                }
            },
            error: function (request, status, error) {
                if(request.status == 422){
                    responseFailed(request.responseJSON.message);
                }
                else{
                    responseInternalServerError();
                }
            },
            complete :function(){
                return closeLoader();
            }
        })
    }

    function getVillage(selector,district_code,selectedId=null){
        $.ajax({
            url : '{{route("api.indonesia.village")}}',
            method : "GET",
            data : {
                district_code : district_code
            },
            dataType : "JSON",
            beforeSend : function(){
                return openLoader();
            },
            success : function(resp){
                if(resp.success == false){
                    responseFailed(resp.message);       
                    $(selector+'').html("");            
                }
                else{
                    let html = "";
                    $.each(resp.data,function(index,element){
                        if(selectedId != null && element.code == selectedId){
                            html += '<option value="'+element.code+'" selected>'+element.name+'</option>';
                        }
                        else{
                            html += '<option value="'+element.code+'">'+element.name+'</option>';
                        }
                    });
                    $(selector+'').append(html);
                }
            },
            error: function (request, status, error) {
                if(request.status == 422){
                    responseFailed(request.responseJSON.message);
                }
                else{
                    responseInternalServerError();
                }
            },
            complete :function(){
                return closeLoader();
            }
        })
    }
</script>
@yield("script")