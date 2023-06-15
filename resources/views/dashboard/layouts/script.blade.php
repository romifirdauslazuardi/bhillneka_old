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

    document.onkeydown = function (e) {
        if (event.keyCode == 123) {
            return false;
        }
        if (e.ctrlKey && e.shiftKey && (e.keyCode == 'I'.charCodeAt(0) || e.keyCode == 'i'.charCodeAt(0))) {
            return false;
        }
        if (e.ctrlKey && e.shiftKey && (e.keyCode == 'C'.charCodeAt(0) || e.keyCode == 'c'.charCodeAt(0))) {
            return false;
        }
        if (e.ctrlKey && e.shiftKey && (e.keyCode == 'J'.charCodeAt(0) || e.keyCode == 'j'.charCodeAt(0))) {
            return false;
        }
        if (e.ctrlKey && (e.keyCode == 'U'.charCodeAt(0) || e.keyCode == 'u'.charCodeAt(0))) {
            return false;
        }
        if (e.ctrlKey && (e.keyCode == 'S'.charCodeAt(0) || e.keyCode == 's'.charCodeAt(0))) {
            return false;
        }
    }

    $(function() {
        if ($('.select2').length >= 1) {
            $('.select2').select2({
                width : "100%",
            });
        }

        $('.sidebar-menu').find('li').removeClass("active");
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
            url : '{{route("base.indonesia.province")}}',
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
            url : '{{route("base.indonesia.city")}}',
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
            url : '{{route("base.indonesia.district")}}',
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
            url : '{{route("base.indonesia.village")}}',
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

    function getProductCategory(selector,user_id,selectedId=null){
        $.ajax({
            url : '{{route("base.product-categories.index")}}',
            method : "GET",
            dataType : "JSON",
            data : {
                user_id : user_id    
            },
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
                        if(selectedId != null && element.id == selectedId){
                            html += '<option value="'+element.id+'" selected>'+element.name+'</option>';
                        }
                        else{
                            html += '<option value="'+element.id+'">'+element.name+'</option>';
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

    function getUnit(selector,user_id,selectedId=null){
        $.ajax({
            url : '{{route("base.units.index")}}',
            method : "GET",
            dataType : "JSON",
            data : {
                user_id : user_id    
            },
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
                        if(selectedId != null && element.id == selectedId){
                            html += '<option value="'+element.id+'" selected>'+element.name+'</option>';
                        }
                        else{
                            html += '<option value="'+element.id+'">'+element.name+'</option>';
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

    function getCustomer(selector,user_id,selectedId=null){
        $.ajax({
            url : '{{route("base.customers.index")}}',
            method : "GET",
            dataType : "JSON",
            data : {
                user_id : user_id    
            },
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
                        if(selectedId != null && element.id == selectedId){
                            html += '<option value="'+element.id+'" selected>'+element.name+' - '+element.phone+'</option>';
                        }
                        else{
                            html += '<option value="'+element.id+'">'+element.name+' - '+element.phone+'</option>';
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

    function formatRupiah(angka, prefix){

        if(!isNaN(angka)){
            angka = angka.toString();
        }

        var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split   		= number_string.split(','),
        sisa     		= split[0].length % 3,
        rupiah     		= split[0].substr(0, sisa),
        ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
    
        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
    
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }
</script>
@yield("script")