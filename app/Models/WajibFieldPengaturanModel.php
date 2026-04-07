<?php

namespace App\Models;

use CodeIgniter\Model;

class WajibFieldPengaturanModel extends Model
{
    protected $table            = 'wajib_field_pengaturan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'form_type',
        'field_name',
        'is_required',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Mendapatkan semua pengaturan wajib field untuk form tertentu
     */
    public function getPengaturanByForm($formType)
    {
        return $this->where('form_type', $formType)->findAll();
    }

    /**
     * Mendapatkan pengaturan untuk field tertentu pada form tertentu
     */
    public function getFieldPengaturan($formType, $fieldName)
    {
        return $this->where([
            'form_type' => $formType,
            'field_name' => $fieldName
        ])->first();
    }

    /**
     * Update pengaturan wajib field
     */
    public function updatePengaturan($formType, $fieldName, $isRequired)
    {
        $existing = $this->getFieldPengaturan($formType, $fieldName);
        
        if ($existing) {
            return $this->update($existing['id'], [
                'is_required' => (bool) $isRequired,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            return $this->insert([
                'form_type' => $formType,
                'field_name' => $fieldName,
                'is_required' => (bool) $isRequired,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    /**
     * Mendapatkan daftar field yang wajib untuk form tertentu
     */
    public function getRequiredFields($formType)
    {
        $results = $this->where([
            'form_type' => $formType,
            'is_required' => true
        ])->findAll();
        
        $requiredFields = [];
        foreach ($results as $result) {
            $requiredFields[] = $result['field_name'];
        }
        
        return $requiredFields;
    }
}