<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <div class="d-flex justify-content-between">
                <h5 class="card-title mb-3">Informasi Data Stok</h5>
                <h5 class="card-title mb-3">Stok Tersedia  : <b>{{$result->stocks()->sum("qty")}}</b></h5>
            </div>
            <div class="row mb-3">
                <div class="col-lg-12">
                    <a href="#" class="btn btn-primary btn-sm btn-add-stock mb-3"><i class="fa fa-plus"></i> Input Stok </a>
                    <div class="table-responsive">
                        <div class="table">
                            <table class="table table-striped table-bordered datatables">
                                <thead>
                                    <th>Aksi</th>
                                    <th>No</th>
                                    <th>Quantity</th>
                                    <th>Keterangan</th>
                                    <th>Author</th>
                                    <th>Dibuat Pada</th>
                                </thead>
                                <tbody>
                                    @foreach ($result->stocks as $index => $row)
                                    <tr>
                                        <td>
                                            <div class="dropdown-primary me-2 mt-2">
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a href="#" class="dropdown-item btn-edit-stock" data-id="{{$row->id}}" data-qty="{{$row->qty}}" data-note="{{$row->note}}"><i class="fa fa-edit"></i> Edit</a>
                                                    <a href="#" class="dropdown-item btn-delete-stock" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Hapus</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{$index + 1}}</td>
                                        <td>{{$row->qty}}</td>
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