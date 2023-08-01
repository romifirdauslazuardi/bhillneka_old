<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <div class="d-flex justify-content-between">
                <h5 class="card-title mb-3">Informasi Data Stok</h5>
                <h5 class="card-title mb-3">Stok Tersedia  : <b>{{$result->stock}}</b></h5>
            </div>
            <div class="row mb-3">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <div class="table">
                            <table class="table table-striped table-bordered datatables">
                                <thead>
                                    <th>Aksi</th>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Stok Masuk</th>
                                    <th>Stok Keluar</th>
                                    <th>Tersedia</th>
                                    <th>Catatan</th>
                                    <th>Author</th>
                                    <th>Dibuat Pada</th>
                                </thead>
                                <tbody>
                                    @foreach ($result->stocks as $index => $row)
                                    <tr class="@if($row->type == \App\Enums\ProductStockEnum::TYPE_MASUK) table-success @else table-danger @endif">
                                        <td>
                                            <div class="dropdown-primary me-2 mt-2">
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a href="#" class="dropdown-item btn-edit" data-id="{{$row->id}}" data-type="{{$row->type}}" data-date="{{$row->date}}" data-qty="{{$row->qty}}" data-note="{{$row->note}}"><i class="fa fa-edit"></i> Edit</a>
                                                    <a href="#" class="dropdown-item btn-delete" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Hapus</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{$index + 1}}</td>
                                        <td>{{date("d-m-Y",strtotime($row->date))}}</td>
                                        <td>
                                            @if($row->type == \App\Enums\ProductStockEnum::TYPE_MASUK)
                                            {{$row->qty}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($row->type == \App\Enums\ProductStockEnum::TYPE_KELUAR)
                                            {{$row->qty}}
                                            @endif
                                        </td>
                                        <td>{{$row->available}}</td>
                                        <td>{{$row->note}}</td>
                                        <td>{{$row->author->name ?? null}}</td>
                                        <td>{{date('d-m-Y H:i:s',strtotime($row->created_at))}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>