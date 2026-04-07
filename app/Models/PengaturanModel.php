<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaturanModel extends Model
{
    protected $table            = 'pengaturan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['pengaturan_key', 'pengaturan_value'];

    // Method helper to easily retrieve all settings as a key-value associative array
    public function getSettings()
    {
        $allData = $this->findAll();
        $settings = [];

        foreach ($allData as $row) {
            $settings[$row['pengaturan_key']] = $row['pengaturan_value'];
        }

        return $settings;
    }

    // Method helper to retrieve a specific setting value
    public function getValue($key)
    {
        $row = $this->where('pengaturan_key', $key)->first();
        return $row ? $row['pengaturan_value'] : null;
    }

    // Method helper to update or insert a setting
    public function updateSetting($key, $value)
    {
        $row = $this->where('pengaturan_key', $key)->first();
        if ($row) {
            return $this->update($row['id'], ['pengaturan_value' => $value]);
        } else {
            return $this->insert([
                'pengaturan_key' => $key,
                'pengaturan_value' => $value
            ]);
        }
    }
}
