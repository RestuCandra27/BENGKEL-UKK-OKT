<x-app-layout>

<h3>Upload Bukti Pembayaran</h3>

<form method="POST" 
      action="{{ route('pelanggan.payments.store') }}" 
      enctype="multipart/form-data">

@csrf

<input type="hidden" name="invoice_id" value="{{ $invoice->id }}">

<div class="form-group">
    <label>Metode Pembayaran</label>
    <select name="metode_bayar" required class="form-control">
        <option>Transfer</option>
        <option>QRIS</option>
        <option>Debit</option>
    </select>
</div>

<div class="form-group">
    <label>Jumlah Bayar</label>
    <input type="number"
           name="jumlah_bayar"
           value="{{ $invoice->sisa_tagihan }}"
           required
           class="form-control">
</div>

<div class="form-group">
    <label>Upload Bukti</label>
    <input type="file" name="bukti" required class="form-control">
</div>

<button class="btn btn-success">
    Kirim Pembayaran
</button>

</form>

</x-app-layout>
