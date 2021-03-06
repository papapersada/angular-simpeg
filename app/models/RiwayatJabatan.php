<?php

class RiwayatJabatan extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tbl_data_riwayat_jabatan';
    protected $primaryKey = 'id_riwayat_jabatan';
    protected $fillable = array('id_pegawai', 'penempatan', 'lokasi', 'status', 'id_jabatan', 'id_unit_kerja', 'uraian', 'id_eselon','tmt_eselon', 'nomor_sk', 'tanggal_sk', 'tanggal_mulai', 'tanggal_selesai');
    public $timestamps = false;
    protected $appends = ['nama_status', 'nama_jabatan', 'nama_unit_kerja', 'nama_eselon'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    public function getNamaJabatanAttribute() {
        $name = $this->attributes['id_jabatan'];
        $data = Jabatan::find($name);
        if ($data) {
            return $data->nama_jabatan;
        }
        return '-';
    }

    public function getNamaEselonAttribute() {
        $name = $this->attributes['id_eselon'];
        $data = Eselon::find($name);
        if ($data) {
            return $data->nama_eselon;
        }
        return '-';
    }

    public function getNamaUnitKerjaAttribute() {
        $name = $this->attributes['id_unit_kerja'];
        $data = UnitKerja::find($name);
        if ($data) {
            return $data->nama_unit_kerja;
        }
        return '-';
    }

    public function getNamaStatusAttribute() {
        $name = $this->attributes['status'];
        $data = StatusPegawai::find($name);
        if ($data) {
            return $data->nama_status;
        }
        return '-';
    }

    public function pegawai() {
        return $this->belongsTo('pegawai', 'id_pegawai');
    }

    public function scopeDropdownRiwayatPangkat($query) {
        $data = array();
        $jabatan = $query->select(array('id_riwayat_jabatan', 'nama_anggota_riwayat_jabatan'))->get();
        if (count($jabatan) > 0) {
            foreach ($jabatan as $ese) {
                $data[] = array('id' => $ese->id_jabatan, 'label' => $ese->nama_jabatan);
            }
        }
        return $data;
    }

    public function getTanggalSkAttribute($value) {
        return date('m/d/Y', strtotime($value));
    }

    public function setTanggalSkAttribute($value) {
        $this->attributes['tanggal_sk'] = date('Y-m-d', strtotime($value));
    }

    public function getTanggalMulaiAttribute($value) {
        if ($value == '') {
            return $value;
        }
        return date('m/d/Y', strtotime($value));
    }

    public function setTanggalMulaiAttribute($value) {
        if ($value == '') {
            $this->attributes['tanggal_mulai'] = $value;
        } else {
            $this->attributes['tanggal_mulai'] = date('Y-m-d', strtotime($value));
        }
    }

    public function getTanggalSelesaAttribute($value) {
        if ($value == '') {
            return $value;
        }
        return date('m/d/Y', strtotime($value));
    }

    public function setTanggalSelesai($value) {
        if ($value == '') {
            $this->attributes['tanggal_cerai_meninggal'] = $value;
        } else {
            $this->attributes['tanggal_cerai_meninggal'] = date('Y-m-d', strtotime($value));
        }
    }

    public function scopeGetColumn() {
        $data = array();
        $columns = DB::table('information_schema.columns')
                ->select('COLUMN_NAME')
                ->where('table_name', $this->table)
                ->where('column_key', '!=', 'PRI')
                ->get();
        foreach ($columns as $col) {
            $data[] = $col->COLUMN_NAME;
        }
        return $data;
    }

}
