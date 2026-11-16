@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h1 class="h4 fw-bold mb-1">Laporan Kondisi Drainase</h1>
            <p class="text-muted mb-0 small">
                @if (auth()->user()->isAdmin())
                    Semua laporan warga. Admin dapat memantau dan menghapus laporan.
                @else
                    Riwayat laporan yang Anda kirim. Anda hanya dapat mengelola laporan milik sendiri.
                @endif
            </p>
        </div>

        <form method="GET" action="{{ route('laporan.index') }}" class="d-flex gap-2">
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">Semua status</option>
                @foreach (\Modules\Laporan\Models\Laporan::STATUS_KONDISI as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="d-flex flex-wrap gap-2 mb-3">
        @foreach (\Modules\Laporan\Models\Laporan::STATUS_KONDISI as $status => $label)
            <span class="badge text-bg-{{ match ($status) { 'Normal' => 'success', 'Tersumbat' => 'warning', default => 'danger' } }}">
                {{ $label }}: {{ $ringkasan->get($status, 0) }}
            </span>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header"><i class="bi bi-pencil-square me-1"></i> Kirim laporan</div>
                <div class="card-body">
                    <div id="error-box" class="alert alert-danger d-none" role="alert"></div>

                    <form id="laporan-create-form" action="{{ route('laporan.store') }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="create-titik_drainase_id" class="form-label">Titik drainase</label>
                            <select id="create-titik_drainase_id" name="titik_drainase_id"
                                    class="form-control @error('titik_drainase_id') is-invalid @enderror" required>
                                <option value="">-- Pilih titik drainase --</option>
                                @foreach ($titikDrainase as $item)
                                    <option value="{{ $item->id }}" @selected(old('titik_drainase_id') == $item->id)>
                                        {{ $item->nama_titik }} &mdash; {{ $item->kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" data-error-for="titik_drainase_id"></div>
                            @error('titik_drainase_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="create-tinggi_air_cm" class="form-label">Tinggi air dari dasar (cm)</label>
                            <input type="number" id="create-tinggi_air_cm" name="tinggi_air_cm" min="0"
                                   value="{{ old('tinggi_air_cm', 0) }}"
                                   class="form-control @error('tinggi_air_cm') is-invalid @enderror" required>
                            <div class="invalid-feedback" data-error-for="tinggi_air_cm"></div>
                            @error('tinggi_air_cm')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Isi 0 jika saluran kering.</div>
                        </div>

                        <div class="mb-3">
                            <label for="create-status_kondisi" class="form-label">Status kondisi</label>
                            <select id="create-status_kondisi" name="status_kondisi"
                                    class="form-control @error('status_kondisi') is-invalid @enderror" required>
                                <option value="">-- Pilih status --</option>
                                @foreach (\Modules\Laporan\Models\Laporan::STATUS_KONDISI as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status_kondisi') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" data-error-for="status_kondisi"></div>
                            @error('status_kondisi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="create-deskripsi" class="form-label">Deskripsi kondisi</label>
                            <textarea id="create-deskripsi" name="deskripsi" rows="3"
                                      class="form-control @error('deskripsi') is-invalid @enderror"
                                      placeholder="Jelaskan kondisi saluran" required>{{ old('deskripsi') }}</textarea>
                            <div class="invalid-feedback" data-error-for="deskripsi"></div>
                            @error('deskripsi')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="create-foto_bukti" class="form-label">
                                Foto bukti <span class="text-muted fw-normal">(opsional)</span>
                            </label>
                            <input type="file" id="create-foto_bukti" name="foto_bukti" accept=".jpg,.jpeg,.png"
                                   class="form-control @error('foto_bukti') is-invalid @enderror">
                            <div class="invalid-feedback" data-error-for="foto_bukti"></div>
                            @error('foto_bukti')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">JPG, JPEG, atau PNG. Maksimal 2 MB.</div>
                        </div>

                        <button type="submit" id="btn-simpan-laporan" class="btn btn-primary w-100 fw-semibold">
                            <i class="bi bi-send me-1"></i> Kirim Laporan
                        </button>
                    </form>
                </div>  
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-list-ul me-1"></i> Daftar laporan</span>
                    <span class="badge text-bg-light border" id="jumlah-laporan" data-total="{{ $laporan->total() }}">
                        {{ $laporan->total() }} data
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Titik Drainase</th>
                                @if (auth()->user()->isAdmin())
                                    <th>Pelapor</th>
                                @endif
                                <th>Tinggi Air</th>
                                <th>Status</th>
                                <th>Bukti</th>
                                <th>Deskripsi</th>
                                <th>Tanggal</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="laporan-body">
                            @forelse ($laporan as $item)
                                @include('laporan::partials.row', ['laporan' => $item])
                            @empty
                                <tr id="laporan-empty">
                                    <td colspan="{{ auth()->user()->isAdmin() ? 8 : 7 }}">
                                        <div class="empty-state py-4">
                                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                            Belum ada laporan.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-body">
                    {{ $laporan->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditLaporan" tabindex="-1" aria-labelledby="modalEditLaporanLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="laporan-edit-form" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h2 class="modal-title h5" id="modalEditLaporanLabel">Edit Laporan</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit-titik_drainase_id" class="form-label">Titik drainase</label>
                            <select id="edit-titik_drainase_id" name="titik_drainase_id" class="form-control" required>
                                @foreach ($titikDrainase as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_titik }} &mdash; {{ $item->kecamatan }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" data-error-for="titik_drainase_id"></div>
                        </div>

                        <div class="mb-3">
                            <label for="edit-tinggi_air_cm" class="form-label">Tinggi air dari dasar (cm)</label>
                            <input type="number" id="edit-tinggi_air_cm" name="tinggi_air_cm" min="0" class="form-control" required>
                            <div class="invalid-feedback" data-error-for="tinggi_air_cm"></div>
                        </div>

                        <div class="mb-3">
                            <label for="edit-status_kondisi" class="form-label">Status kondisi</label>
                            <select id="edit-status_kondisi" name="status_kondisi" class="form-control" required>
                                @foreach (\Modules\Laporan\Models\Laporan::STATUS_KONDISI as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" data-error-for="status_kondisi"></div>
                        </div>

                        <div class="mb-3">
                            <label for="edit-deskripsi" class="form-label">Deskripsi kondisi</label>
                            <textarea id="edit-deskripsi" name="deskripsi" rows="3" class="form-control" required></textarea>
                            <div class="invalid-feedback" data-error-for="deskripsi"></div>
                        </div>

                        <div class="mb-3">
                            <label for="edit-foto_bukti" class="form-label">Ganti foto bukti (opsional)</label>
                            <input type="file" id="edit-foto_bukti" name="foto_bukti" accept=".jpg,.jpeg,.png" class="form-control">
                            <div class="invalid-feedback" data-error-for="foto_bukti"></div>
                            <div class="form-text">Kosongkan jika tidak ingin mengganti foto lama.</div>
                            <div id="edit-foto-lama" class="mt-2"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" id="btn-update-laporan" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="toast-laporan" class="toast text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toast-laporan-pesan">Berhasil.</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Tutup"></button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/vendor/jquery.min.js') }}"></script>
    <script>
        $(function () {
            const csrf = $('meta[name="csrf-token"]').attr('content');
            const isAdmin = {{ auth()->user()->isAdmin() ? 'true' : 'false' }};
            const createForm = $('#laporan-create-form');
            const editForm = $('#laporan-edit-form');
            const editModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalEditLaporan'));

            function tampilkanToast(pesan, sukses) {
                const toastEl = document.getElementById('toast-laporan');
                toastEl.classList.remove('text-bg-success', 'text-bg-danger');
                toastEl.classList.add(sukses ? 'text-bg-success' : 'text-bg-danger');
                $('#toast-laporan-pesan').text(pesan);
                bootstrap.Toast.getOrCreateInstance(toastEl).show();
            }

            function bersihkanError(form) {
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('[data-error-for]').text('').removeClass('d-block');
                $('#error-box').addClass('d-none').empty();
            }

            function tampilkanError(form, errors) {
                bersihkanError(form);

                $.each(errors, function (field, messages) {
                    const input = form.find('[name="' + field + '"]');
                    input.addClass('is-invalid');

                    const feedback = form.find('[data-error-for="' + field + '"]');
                    if (feedback.length) {
                        feedback.text(messages[0]).addClass('d-block');
                    } else {
                        input.after('<div class="invalid-feedback d-block">' + $('<div>').text(messages[0]).html() + '</div>');
                    }
                });
            }

            function perbaruiJumlah(delta) {
                const badge = $('#jumlah-laporan');
                const jumlah = Math.max(0, (parseInt(badge.data('total'), 10) || 0) + delta);
                badge.data('total', jumlah).text(jumlah + ' data');
            }

            function tambahBarisKosong() {
                if ($('#laporan-body tr').length > 0) {
                    return;
                }

                const kolom = isAdmin ? 8 : 7;
                $('#laporan-body').html(
                    '<tr id="laporan-empty"><td colspan="' + kolom + '">' +
                    '<div class="empty-state py-4"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada laporan.</div>' +
                    '</td></tr>'
                );
            }

            createForm.on('submit', function (event) {
                event.preventDefault();
                const form = this;
                const tombol = $('#btn-simpan-laporan');

                bersihkanError(createForm);
                tombol.prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm me-1"></span> Mengirim...');

                $.ajax({
                    url: createForm.attr('action'),
                    method: 'POST',
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': csrf },
                    dataType: 'json'
                })
                    .done(function (response) {
                        form.reset();
                        $('#laporan-empty').remove();
                        $('#laporan-body').prepend(response.html);
                        perbaruiJumlah(1);
                        tampilkanToast(response.message, true);
                    })
                    .fail(function (xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            tampilkanError(createForm, xhr.responseJSON.errors);
                            tampilkanToast('Periksa kembali data yang Anda isi.', false);
                        } else {
                            $('#error-box').removeClass('d-none')
                                .text('Terjadi kesalahan saat mengirim laporan. Silakan coba lagi.');
                        }
                    })
                    .always(function () {
                        tombol.prop('disabled', false)
                            .html('<i class="bi bi-send me-1"></i> Kirim Laporan');
                    });
            });

            $(document).on('click', '.btn-edit-laporan', function () {
                const tombol = $(this);
                const url = tombol.data('url');

                bersihkanError(editForm);
                editForm[0].reset();
                $('#edit-foto-lama').empty();
                tombol.prop('disabled', true);

                $.getJSON(url)
                    .done(function (data) {
                        editForm.attr('action', url);
                        $('#edit-titik_drainase_id').val(data.titik_drainase_id);
                        $('#edit-tinggi_air_cm').val(data.tinggi_air_cm);
                        $('#edit-status_kondisi').val(data.status_kondisi);
                        $('#edit-deskripsi').val(data.deskripsi);

                        if (data.foto_url) {
                            $('#edit-foto-lama').html(
                                '<a href="' + data.foto_url + '" target="_blank" rel="noopener">' +
                                '<img src="' + data.foto_url + '" class="bukti-thumb border" alt="Foto bukti saat ini"></a>' +
                                ' <span class="small text-muted">Foto saat ini</span>'
                            );
                        } else {
                            $('#edit-foto-lama').html('<span class="small text-muted">Belum ada foto bukti.</span>');
                        }

                        editModal.show();
                    })
                    .fail(function () {
                        tampilkanToast('Gagal memuat data laporan.', false);
                    })
                    .always(function () {
                        tombol.prop('disabled', false);
                    });
            });

            editForm.on('submit', function (event) {
                event.preventDefault();
                const form = this;
                const tombol = $('#btn-update-laporan');

                bersihkanError(editForm);
                tombol.prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

                $.ajax({
                    url: editForm.attr('action'),
                    method: 'POST',
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': csrf },
                    dataType: 'json'
                })
                    .done(function (response) {
                        $('#laporan-row-' + response.id).replaceWith(response.html);
                        editModal.hide();
                        tampilkanToast(response.message, true);
                    })
                    .fail(function (xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            tampilkanError(editForm, xhr.responseJSON.errors);
                        } else {
                            tampilkanToast('Gagal memperbarui laporan.', false);
                        }
                    })
                    .always(function () {
                        tombol.prop('disabled', false)
                            .html('<i class="bi bi-save me-1"></i> Simpan Perubahan');
                    });
            });

            $(document).on('click', '.btn-hapus-laporan', function () {
                const tombol = $(this);
                const url = tombol.data('url');
                const id = tombol.data('id');

                if (!confirm('Hapus laporan ini? Tindakan tidak dapat dibatalkan.')) {
                    return;
                }

                tombol.prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm"></span>');

                $.ajax({
                    url: url,
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrf },
                    dataType: 'json'
                })
                    .done(function (response) {
                        $('#laporan-row-' + id).remove();
                        perbaruiJumlah(-1);
                        tambahBarisKosong();
                        tampilkanToast(response.message, true);
                    })
                    .fail(function () {
                        tampilkanToast('Gagal menghapus laporan.', false);
                    })
                    .always(function () {
                        tombol.prop('disabled', false)
                            .html('<i class="bi bi-trash"></i>');
                    });
            });
        });
    </script>
@endpush
