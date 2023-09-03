<div class="row pb-2">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Informasi Pembayaran Manual</h5>
                <div class="row">
                    <div class="col-12">

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Bukti Order
                            </div>
                            <div class="col-md-9">
                                : @if(!empty($result->proof_order)) <a href="{{asset($result->proof_order)}}">Lihat</a> @else - @endif
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Catatan Pembayaran
                            </div>
                            <div class="col-md-9">
                                : {{$result->payment_note}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Payment URL
                            </div>
                            <div class="col-md-9">
                                : {{route('landing-page.manual-payments.proofOrder',$result->code)}}
                            </div>
                        </div>

                        @if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]) || (Auth::user()->hasRole([\App\Enums\RoleEnum::AGEN,\App\Enums\RoleEnum::ADMIN_AGEN]) && $result->status == \App\Enums\OrderEnum::STATUS_WAITING_PAYMENT))
                        <div class="row">
                            <div class="col-12">
                                <a href="#" class="btn btn-success btn-sm btn-proof-order" data-id="{{$result->id}}"><i class="fa fa-file"></i> Upload Bukti Pembayaran</a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>