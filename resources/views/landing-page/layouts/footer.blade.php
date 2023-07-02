<!-- Footer Start -->
<footer class="footer">    
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="footer-py-60">
                    <div class="row">
                        <div class="col-lg-6 col-12 mb-0 mb-md-4 pb-0 pb-md-2">
                            <a href="{{route('landing-page.home.index')}}" class="logo-footer">
                                <img src="{{!empty(\SettingHelper::settings('landing_page', 'logo')) ? asset(\SettingHelper::settings('landing_page', 'logo')) : URL::to('/').'/templates/landing-page/assets/images/logo-light.png'}}" height="24" alt="">
                            </a>
                            <p class="mt-4">{{ \SettingHelper::settings('landing_page', 'description')}}</p>
                            <ul class="list-unstyled social-icon foot-social-icon mb-0 mt-4">
                                @if(!empty(\SettingHelper::settings('landing_page', 'facebook')))
                                <li class="list-inline-item mb-0"><a href="https://www.facebook.com/{{ \SettingHelper::settings('landing_page', 'facebook')}}" target="_blank" class="rounded"><i class="uil uil-facebook-f align-middle" title="facebook"></i></a></li>
                                @endif
                                @if(!empty(\SettingHelper::settings('landing_page', 'instagram')))
                                <li class="list-inline-item mb-0"><a href="https://www.instagram.com/{{\SettingHelper::settings('landing_page', 'instagram')}}" target="_blank" class="rounded"><i class="uil uil-instagram align-middle" title="instagram"></i></a></li>
                                @endif
                                @if(!empty(\SettingHelper::settings('landing_page', 'twitter')))
                                <li class="list-inline-item mb-0"><a href="https://www.twitter.com/{{ \SettingHelper::settings('landing_page', 'twitter')}}" target="_blank" class="rounded"><i class="uil uil-twitter align-middle" title="twitter"></i></a></li>
                                @endif
                                @if(!empty(\SettingHelper::settings('landing_page', 'email')))
                                <li class="list-inline-item mb-0"><a href="mailto:{{ \SettingHelper::settings('landing_page', 'email')}}" class="rounded"><i class="uil uil-envelope align-middle" title="email"></i></a></li>
                                @endif
                            </ul><!--end icon-->
                        </div><!--end col-->
                        <div class="col-lg-2 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                            <h5 class="footer-head">Link Cepat</h5>
                            <ul class="list-unstyled footer-list mt-4">
                                <li><a href="{{route('landing-page.home.index')}}" class="text-foot"><i class="uil uil-angle-right-b me-1"></i> Home</a></li>
                                <li><a href="{{route('landing-page.pages.index','tentang-kami')}}" class="text-foot"><i class="uil uil-angle-right-b me-1"></i> Tentang Kami</a></li>
                                <li><a href="{{route('landing-page.our-services.index')}}" class="text-foot"><i class="uil uil-angle-right-b me-1"></i> Layanan Kami</a></li>
                                <li><a href="{{route('landing-page.faqs.index')}}" class="text-foot"><i class="uil uil-angle-right-b me-1"></i> Faq</a></li>
                                <li><a href="{{route('landing-page.contact-us.index')}}" class="text-foot"><i class="uil uil-angle-right-b me-1"></i> Hubungi Kami</a></li>
                                <li><a href="{{route('landing-page.orders.index')}}" class="text-foot"><i class="uil uil-angle-right-b me-1"></i> Status Order</a></li>
                            </ul>
                        </div><!--end col-->
                
                        <div class="col-lg-3 col-md-4 col-12 mt-4 mt-sm-0 pt-2 pt-sm-0">
                            <h5 class="footer-head">Kebijakan</h5>
                            <ul class="list-unstyled footer-list mt-4">
                                <li><a href="{{route('landing-page.pages.index','terms-and-conditions')}}" class="text-foot"><i class="uil uil-angle-right-b me-1"></i> Terms and Conditions</a></li>
                                <li><a href="{{route('landing-page.pages.index','privacy-policy')}}" class="text-foot"><i class="uil uil-angle-right-b me-1"></i> Privacy Policy</a></li>
                            </ul>
                        </div><!--end col-->
                    </div><!--end row-->
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->

    <div class="footer-py-30 footer-bar">
        <div class="container text-center">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="text-center">
                        <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> {{ \SettingHelper::settings('landing_page', 'footer')}}</p>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </div>
</footer><!--end footer-->
<!-- Footer End -->
