<?php

namespace App\Models;

use CodeIgniter\Model;

class TemplateDokumenFieldModel extends Model
{
    protected $table            = 'template_dokumen_field';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields    = [
        'template_id',
        'field_key',
        'field_label',
        'field_type',
        'is_required',
        'urutan',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'template_id' => 'required|is_natural_no_zero',
        'field_key'   => 'required|max_length[100]',
        'field_label' => 'required|max_length[150]',
        'field_type'  => 'required|in_list[text,textarea,date,guru_select,number]',
    ];

    public function getFieldsByTemplate(int $templateId)
    {
        return $this->where('template_id', $templateId)
                    ->orderBy('urutan', 'ASC')
                    ->findAll();
    }
}
