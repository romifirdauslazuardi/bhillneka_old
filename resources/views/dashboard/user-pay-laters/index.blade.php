@extends("dashboard.layouts.main")

@section("title","Pengaturan Bayar Nanti")

@section("css")
@endsection

@section("breadcumb")
<div class="d-md-flex justify-content-between align-items-center">
    <h5 class="mb-0">Pengaturan Bayar Nanti</h5>

    <nav aria-label="breadcrumb" class="d-inline-block mt-2 mt-sm-0">
        <ul class="breadcrumb bg-transparent rounded mb-0 p-0">
            <li class="breadcrumb-item text-capitalize"><a href="#">Pengaturan Bayar Nanti</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">Pengaturan Bayar Nanti</li>
        </ul>
    </nav>
</div>
@endsection

@section("content")
<div class="row">
    <div class="col-12 mt-4">
        <div class="card border-0 rounded shadow p-4">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <div class="table">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th>No</th>
                                    <th>Bisnis</th>
                                    <th>Aksi</th>
                                </thead>
                                <tbody>
                                    @forelse ($table as $index => $row)
                                    <tr>
                                        <td>{{$table->firstItem() + $index}}</td>
                                        <td>{{$row->name ?? null}}</td>
                                        <td>
                                            <input type="checkbox" value="{{\App\Enums\UserPayLaterEnum::STATUS_TRUE}}" class="checkbox-click" style="width: 15px;height:15px;" @if(!empty($row->user_pay_later->status)) checked @endif>
                                        </td>
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
                    {!!$table->links()!!}
                </div>
            </div>
        </div>
    </div>
</div>

@include("dashboard.tables.modal.index")
@include("dashboard.components.loader")

<form id="frmStore" method="POST" action="{{ route('dashboard.user-pay-laters.store') }}">
    @csrf
    <input type="hidden" name="status"/>
</form>

@endsection

@section("script")
<script>
    $(function() {
        $('button[type="submit"]').attr("disabled",false);

        $(document).on("click", ".checkbox-click", function() {
            let status = '{{\App\Enums\UserPayLaterEnum::STATUS_FALSE}}';

            if($(this).is(":checked")){
                status = '{{\App\Enums\UserPayLaterEnum::STATUS_TRUE}}';
            }

            $("#frmStore").find('input[name="status"]').val(status);
            $("#frmStore").submit();
        })
    })
</script>
@endsection