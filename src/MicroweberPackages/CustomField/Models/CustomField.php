<?php
namespace MicroweberPackages\CustomField\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\HasCreatedByFieldsTrait;
use MicroweberPackages\Database\Traits\MaxPositionTrait;

class CustomField extends Model
{
    //use MaxPositionTrait;

    use HasCreatedByFieldsTrait;

    protected $fillable = [
        'value',
        'type',
        'options',
        'name',
        'name_key',
        // 'edited_by',
        // 'created_by'
    ];

    protected $table = 'custom_fields';

    public $timestamps = true;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'json',
    ];

    protected $attributes = [
        'is_active' => 1,
    ];

    public function fieldValue()
    {
        return $this->hasMany(CustomFieldValue::class, 'custom_field_id', 'id')->orderBy('position');
    }

    public function save(array $options = [])
    {
        $customFieldValueToSave = null;
        if (isset($this->value)) {
            $customFieldValueToSave = $this->value;
            unset($this->value);
        }

        if(isset($this->name)) {
            $this->name_key = \Str::slug($this->name, '-');
        }

        $saved = parent::save($options);

        if (isset($customFieldValueToSave)) {
            if (is_array($customFieldValueToSave)) {
                foreach ($customFieldValueToSave as $val) {
                    $this->createCustomFieldValue($this->id, $val);
                }
            } else {
                $this->createCustomFieldValue($this->id, $customFieldValueToSave);
            }
        }

        return $saved;
     }

     private function createCustomFieldValue($customFieldId, $val){

        $findValue = CustomFieldValue::where('custom_field_id', $customFieldId)->first();
        if (!$findValue) {
            $findValue = new CustomFieldValue();
        }

        $findValue->custom_field_id = $customFieldId;
        $findValue->value = $val;
        $findValue->save();
     }
}
