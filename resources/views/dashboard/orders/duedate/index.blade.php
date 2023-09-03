@if(count($result->orderDueDate) >= 1)
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <h5 class="card-title mb-3">Penjualan Berlangganan</h5>
            <div class="row mb-3">
                <div class="col-lg-12">
                <div class="table-responsive">
                        <div class="table">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th>No</th>
                                    <th>Kode Transaksi</th>
                                    <th>Total</th>
                                    <th>Progress</th>
                                    <th>Status</th>
                                    <th>Dibuat Pada</th>
                                </thead>
                                <tbody>
                                    @forelse ($result->orderDueDate as $index => $row)
                                    <tr>
                                        <td>{{$index + 1}}</td>
                                        <td>{{$row->code}}</td>
                                        <td>{{number_format($row->totalNeto(),0,',','.')}}</td>
                                        <td>
                                            <span class="badge bg-{{$row->progress()->class ?? null}}">{{$row->progress()->msg ?? null}}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{$row->status()->class ?? null}}">{{$row->status()->msg ?? null}}</span>
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