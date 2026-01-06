<?php

namespace App\Services;

use App\Models\LogAktivitas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    /**
     * Catat aktivitas ke tabel log_aktivitas.
     *
     * Cara pakai (semua ini akan didukung):
     *
     * 1) Pakai instance model + detail array
     *    ActivityLogger::log('Tambah stok masuk', $pembelian, [
     *        'sparepart_id' => 1,
     *        'jumlah'      => 10,
     *    ]);
     *
     * 2) Pakai nama model + id + detail array
     *    ActivityLogger::log('Hapus servis', 'Servis', 5, [
     *        'alasan' => 'dibatalkan',
     *    ]);
     *
     * 3) Pakai hanya aksi + detail bebas
     *    ActivityLogger::log('Login admin', null, null, [
     *        'ip' => request()->ip(),
     *    ]);
     */
    public static function log(
        string $aksi,
        $target = null,           // bisa: Model instance | string nama model | null
        $modelId = null,          // bisa: int id | array detail
        ?array $data = null       // array detail tambahan
    ): void {
        $modelName   = null;
        $targetId    = null;
        $detailArray = $data;

        // 1) Kalau parameter kedua adalah instance Model
        if ($target instanceof Model) {
            $modelName = class_basename($target);
            $targetId  = $target->getKey();

            // Kalau parameter ketiga berupa array → anggap sebagai detail
            if (is_array($modelId)) {
                $detailArray = $modelId;
            }
        }
        // 2) Kalau parameter kedua string (nama model), ketiga id
        elseif (is_string($target)) {
            $modelName = $target;
            $targetId  = $modelId;
        }
        // 3) Kalau cuma kirim aksi + array (ActivityLogger::log('...', null, [..]))
        elseif (is_array($modelId) && $detailArray === null) {
            $detailArray = $modelId;
        }

        // Simpan detail dalam bentuk JSON (kalau ada)
        $keterangan = $detailArray ? json_encode($detailArray) : null;

        LogAktivitas::create([
            'user_id'    => Auth::id(),
            'aksi'       => $aksi,
            'model'      => $modelName,
            'model_id'   => $targetId,
            'keterangan' => $keterangan,
        ]);
    }
}
