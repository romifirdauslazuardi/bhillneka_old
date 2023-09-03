<!-- javascript -->
<!-- JAVASCRIPT -->
<script src="{{URL::to('/')}}/templates/landing-page/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- SLIDER -->
<script src="{{URL::to('/')}}/templates/landing-page/assets/libs/tiny-slider/min/tiny-slider.js"></script>
<!-- Parallax -->
<script src="{{URL::to('/')}}/templates/landing-page/assets/libs/jarallax/jarallax.min.js "></script>
<!-- Animation -->
<script src="{{URL::to('/')}}/templates/landing-page/assets/libs/wow.js/wow.min.js"></script>
<!-- Main Js -->
<script src="{{URL::to('/')}}/templates/landing-page/assets/libs/feather-icons/feather.min.js"></script>
<script src="{{URL::to('/')}}/templates/landing-page/assets/js/plugins.init.js"></script><!--Note: All init (custom) js like tiny slider, counter, countdown, lightbox, gallery, swiper slider etc.-->
<script src="{{URL::to('/')}}/templates/landing-page/assets/js/app.js"></script><!--Note: All important javascript like page loader, menu, sticky menu, menu-toggler, one page menu etc. -->
<script src="{{URL::to('/')}}/templates/dashboard/assets/js/jquery.min.js"></script>
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
</script>
@yield("script")