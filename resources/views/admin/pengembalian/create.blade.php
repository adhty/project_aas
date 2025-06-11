<div class="form-group">
    <label for="kondisi_barang">Kondisi Barang</label>
    <select class="form-control" id="kondisi_barang" name="kondisi_barang" required>
        <option value="baik">Baik</option>
        <option value="rusak">Rusak</option>
        <option value="hilang">Hilang</option>
    </select>
</div>

<div class="form-group denda-manual-container" style="display: none;">
    <label for="biaya_denda_manual">Biaya Denda (Rp)</label>
    <input type="number" class="form-control" id="biaya_denda_manual" name="biaya_denda_manual" min="0" placeholder="Masukkan jumlah denda manual">
    <small class="form-text text-muted">Masukkan jumlah denda yang ditentukan admin untuk barang rusak/hilang.</small>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kondisiBarangSelect = document.getElementById('kondisi_barang');
    const dendaManualContainer = document.querySelector('.denda-manual-container');
    
    // Tampilkan field denda manual hanya jika kondisi barang rusak atau hilang
    kondisiBarangSelect.addEventListener('change', function() {
        if (this.value === 'rusak' || this.value === 'hilang') {
            dendaManualContainer.style.display = 'block';
        } else {
            dendaManualContainer.style.display = 'none';
        }
    });
    
    // Cek kondisi awal saat halaman dimuat
    if (kondisiBarangSelect.value === 'rusak' || kondisiBarangSelect.value === 'hilang') {
        dendaManualContainer.style.display = 'block';
    }
});
</script>

