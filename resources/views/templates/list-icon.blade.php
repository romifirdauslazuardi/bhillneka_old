<ul class="list-unstyled social-icon social mb-0">
    <li class="list-inline-item mb-0"><a href="{{ url('/' . 'shops/' . $business->slug) }}" target="_blank"
            class="rounded"><i class="uil uil-shopping-cart align-middle" title="Buy Now"></i></a></li>
    <li class="list-inline-item mb-0"><a href="{{ $data?->facebook }}" target="_blank" class="rounded"><i
                class="uil uil-facebook-f align-middle" title="facebook"></i></a></li>
    <li class="list-inline-item mb-0"><a href="{{ $data?->instagram }}" target="_blank" class="rounded"><i
                class="uil uil-instagram align-middle" title="instagram"></i></a></li>
    <li class="list-inline-item mb-0"><a href="{{ $data?->twitter }}" target="_blank" class="rounded"><i
                class="uil uil-twitter align-middle" title="twitter"></i></a></li>
    <li class="list-inline-item mb-0"><a href="mailto:{{ $data?->email }}" class="rounded"><i
                class="uil uil-envelope align-middle" title="email"></i></a></li>
</ul><!--end icon-->
