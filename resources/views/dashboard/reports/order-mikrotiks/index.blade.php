@extends("dashboard.layouts.main")

@section("title","Order Mikrotik")

@section("css")
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Order Mikrotik</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Report</a></li>
            <li class="breadcrumb-item text-capitalize"><a href="#">Order Mikrotik</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Daftar Laporan Order Mikrotik</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="d-flex">
                        <a href="#" class="btn btn-success btn-sm btn-filter" style="margin-right: 5px;"><i class="fa fa-filter"></i> Filter</a>
                        <a href="{{route('dashboard.reports.order-mikrotiks.index')}}" class="btn @if(!empty(request()->all())) btn-warning @else btn-secondary @endif btn-sm"><i class="fa fa-refresh"></i> Refresh</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <div class="table">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th>Aksi</th>
                                    <th>No</th>
                                    <th>Customer</th>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Tipe</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Status</th>
                                    <th>Dibuat Pada</th>
                                </thead>
                                <tbody>
                                    @forelse ($table as $index => $row)
                                    <tr>
                                    <td>
                                        <div class="dropdown-primary me-2 mt-2">
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a href="{{route('dashboard.reports.order-mikrotiks.show',$row->id)}}" class="dropdown-item"><i class="fa fa-eye"></i> Show</a>
                                                    <a href="{{route('dashboard.reports.order-mikrotiks.edit',$row->id)}}" class="dropdown-item"><i class="fa fa-edit"></i> Edit</a>
                                                    <a href="#" class="dropdown-item btn-delete" data-id="{{$row->id}}"><i class="fa fa-trash"></i> Hapus Dari Mikrotik</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{$table->firstItem() + $index}}</td>
                                        <td>
                                            @if(!empty($row->order_item->order->customer))
                                            {{$row->order_item->order->customer->name ?? null}}
                                            <br>
                                            {{$row->order_item->order->customer->phone ?? null}}
                                            @else
                                            {{$row->order_item->order->customer_name}}
                                            <br>
                                            {{$row->order_item->order->customer_phone}}
                                            @endif
                                        </td>
                                        <td>{{$row->order_item->product_name ?? null}}</td>
                                        <td>{{number_format($row->order_item->product_price,0,',','.')}}</td>
                                        <td>{{$row->order_item->product->mikrotik() ?? null}} </td>
                                        <td>{{$row->username}}</td>
                                        <td>{{$row->password}}</td>
                                        <td>{!!$row->disabled_mikrotik!!}</td>
                                        <td>{{date('d-m-Y H:i:s',strtotime($row->created_at))}}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="15" class="text-center">Data tidak ditemukan</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {!!$table->links()!!}
                </div>
            </div>
        </div>
    </div>
</div>

@include("dashboard.reports.order-mikrotiks.modal.index")
@include("dashboard.components.loader")

<form id="frmDelete" method="POST">
    @csrf
    @method('DELETE')
    <input type="hidden" name="id" />
</form>

@endsection

@section("script")
<script>
    $(function() {
        $(document).on("click", ".btn-filter", function(e) {
            e.preventDefault();

            $("#modalFilter").modal("show");
        });

        $(document).on("click", ".btn-delete", function() {
            let id = $(this).data("id");
            if (confirm("Apakah anda yakin ingin menghapus data ini ?")) {
                $("#frmDelete").attr("action", "{{ route('dashboard.reports.order-mikrotiks.destroy', '_id_') }}".replace("_id_", id));
                $("#frmDelete").find('input[name="id"]').val(id);
                $("#frmDelete").submit();
            }
        })
    })
</script>
@endsection