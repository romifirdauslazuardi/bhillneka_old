<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <h5 class="card-title mb-3">Informasi Data Rekening Lainnya (Approved)</h5>
            <div class="row mb-3">
                <div class="col-lg-12">
                <div class="table-responsive">
                        <div class="table">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th>No</th>
                                    <th>Pengguna</th>
                                    <th>Atas Nama</th>
                                    <th>Nomor Rekening</th>
                                    <th>Bank</th>
                                    <th>Dibuat Pada</th>
                                </thead>
                                <tbody>
                                    @forelse ($result->verified as $index => $row)
                                    <tr>
                                        <td>{{$index + 1}}</td>
                                        <td>{{$row->user->name ?? null}}</td>
                                        <td>{{$row->name}}</td>
                                        <td>{{$row->number}}</td>
                                        <td>{{$row->bank->name ?? null}}</td>
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