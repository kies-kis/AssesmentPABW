@php($isEdit = isset($titikDrainase))

<div class="row g-3">
    <div class="col-md-6">
        <label for="nama_titik" class="form-label">Nama titik drainase</label>
        <input type="text" id="nama_titik" name="nama_titik"
               value="{{ old('nama_titik', $titikDrainase->nama_titik ?? '') }}"
               class="form-control @error('nama_titik') is-invalid @enderror"
               placeholder="Contoh: Saluran Jalan Raya Soreang" required>
        @error('nama_titik')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="kecamatan" class="form-label">Kecamatan</label>
        <input type="text" id="kecamatan" name="kecamatan"
               value="{{ old('kecamatan', $titikDrainase->kecamatan ?? '') }}"
               class="form-control @error('kecamatan') is-invalid @enderror"
               placeholder="Contoh: Soreang" required>
        @error('kecamatan')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="kedalaman_cm" class="form-label">Kedalaman saluran (cm)</label>
        <input type="number" id="kedalaman_cm" name="kedalaman_cm" min="0"
               value="{{ old('kedalaman_cm', $titikDrainase->kedalaman_cm ?? '') }}"
               class="form-control @error('kedalaman_cm') is-invalid @enderror"
               placeholder="Contoh: 80">
        @error('kedalaman_cm')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <div class="form-text">Boleh dikosongkan jika belum diketahui.</div>
    </div>

    <div class="col-12">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi" rows="4"
                  class="form-control @error('deskripsi') is-invalid @enderror"
                  placeholder="Keterangan lokasi atau kondisi saluran">{{ old('deskripsi', $titikDrainase->deskripsi ?? '') }}</textarea>
        @error('deskripsi')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
