@php($bolehKelola = auth()->user()->isAdmin() || auth()->user()->id === $laporan->user_id)

<tr id="laporan-row-{{ $laporan->id }}">
    <td>
        <div class="fw-semibold">{{ $laporan->titikDrainase->nama_titik ?? '-' }}</div>
        <div class="text-muted small">{{ $laporan->titikDrainase->kecamatan ?? '-' }}</div>
    </td>
    @if (auth()->user()->isAdmin())
        <td>{{ $laporan->user->name ?? '-' }}</td>
    @endif
    <td>{{ $laporan->tinggi_air_cm }} cm</td>
    <td><span class="badge text-bg-{{ $laporan->badgeStatus() }}">{{ $laporan->status_kondisi }}</span></td>
    <td>
        @if ($laporan->foto_bukti)
            <a href="{{ Storage::disk('public')->url($laporan->foto_bukti) }}" target="_blank" rel="noopener">
                <img src="{{ Storage::disk('public')->url($laporan->foto_bukti) }}"
                     alt="Foto bukti laporan" class="bukti-thumb border">
            </a>
            <div class="small mt-1">
                <a href="{{ Storage::disk('public')->url($laporan->foto_bukti) }}" target="_blank" rel="noopener">Lihat Bukti</a>
            </div>
        @else
            <span class="text-muted small">-</span>
        @endif
    </td>
    <td class="text-muted small">{{ Str::limit($laporan->deskripsi, 50) }}</td>
    <td class="text-muted small">{{ $laporan->created_at->format('d/m/Y H:i') }}</td>
    <td class="text-end">
        @if ($bolehKelola)
            <div class="d-inline-flex gap-1">
                <button type="button" class="btn btn-sm btn-outline-secondary btn-edit-laporan"
                        data-url="{{ route('laporan.show', $laporan) }}" data-id="{{ $laporan->id }}"
                        title="Edit laporan">
                    <i class="bi bi-pencil"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger btn-hapus-laporan"
                        data-url="{{ route('laporan.destroy', $laporan) }}" data-id="{{ $laporan->id }}"
                        title="Hapus laporan">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        @else
            <span class="text-muted small">-</span>
        @endif
    </td>
</tr>
