
<div class="modal fade" id="modalUploadProofOrder" aria-labelledby="modalUploadProofOrder-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content rounded shadow border-0">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalUploadProofOrder-title">Upload Bukti Pembayaran</h5>
                <button type="button" class="btn btn-icon btn-close" data-bs-dismiss="modal" id="close-modal"><i class="uil uil-times fs-4 text-dark"></i></button>
            </div>
            <form method="POST" id="frmUploadProofOrder" autocomplete="off">
                @csrf
                @method("PUT")
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6>Panduan Pembayaran</h6>
                            <div class="table-responsive">
                                <div class="table">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td><b>Metode Pembayaran</b></td>
                                                <td>:</td>
                                                <td>{{$result->provider->name ?? null}}</td>
                                            </tr>
                                            <tr>
                                                <td><b>Intruksi</b></td>
                                                <td>:</td>
                                                <td>{{$result->provider->note ?? null}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label>Bukti Pembayaran <span class="text-danger">*</span></i></label>
                        <input type="file" class="form-control" name="proof_order" accept="image/*">
                    </div>
                    <div class="form-group mb-3">
                        <label>Catatan <span class="text-danger">*</span></i></label>
                        <textarea class="form-control" placeholder="Catatan" name="payment_note"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>