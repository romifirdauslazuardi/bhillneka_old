<div class="row pb-2">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3">Informasi Doku</h5>
                <div class="row">
                    <div class="col-12">

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Service ID
                            </div>
                            <div class="col-md-9">
                                : {{$result->doku_service_id}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Acquirer ID
                            </div>
                            <div class="col-md-9">
                                : {{$result->doku_acquirer_id}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Channel ID
                            </div>
                            <div class="col-md-9">
                                : {{$result->doku_channel_id}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Payment URL
                            </div>
                            <div class="col-md-9">
                                : {{$result->payment_url}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Payment Due Date
                            </div>
                            <div class="col-md-9">
                                : {{$result->payment_due_date}}
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-md-3">
                                Payment Expired Date
                            </div>
                            <div class="col-md-9">
                                : {{ date('d-m-Y H:i:s',strtotime($result->expired_date)) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER]))
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <h5 class="card-title mb-3">Histori Notifikasi Doku</h5>
            <div class="row mb-3">
                <div class="col-lg-12">
                <div class="table-responsive">
                        <div class="table">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th>No</th>
                                    <th>Target</th>
                                    <th>Data</th>
                                    <th>Dibuat Pada</th>
                                </thead>
                                <tbody>
                                    @forelse ($result->doku as $index => $row)
                                    <tr>
                                        <td>{{$index + 1}}</td>
                                        <td>{{$row->target}}</td>
                                        <td>
                                            {{json_encode($row->data,JSON_PRETTY_PRINT)}}
                                        </td>
                                        <td>{{date('d-m-Y H:i:s',strtotime($row->created_at))}}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="text-center">Data tidak ditemukan</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif