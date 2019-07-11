<?php

namespace Motor\Backend\Forms\Fields;

use Illuminate\Support\Str;
use Kris\LaravelFormBuilder\Fields\InputType;
use Motor\Media\Transformers\FileTransformer;

/**
 * Class FileAssociationType
 * @package Motor\Backend\Forms\Fields
 */
class FileAssociationType extends InputType
{

    /**
     * @return string
     */
    protected function getTemplate()
    {
        // At first it tries to load config variable,
        // and if fails falls back to loading view
        // resources/views/fields/datetime.blade.php
        return 'motor-backend::laravel-form-builder.file_association';
    }


    /**
     * @return array
     */
    public function getData()
    {
        $modelData = $this->parent->getModel();

        $options = [ 'file_association' => false ];

        if (is_object($modelData)) {
            $fileAssociation = $modelData->file_associations()->where('identifier', $this->getRealName())->first();
            if ( ! is_null($fileAssociation)) {
                $data = fractal($fileAssociation->file, new FileTransformer())->toArray();

                $options['file_association'] = json_encode($data['data']);
            }
        }

        return $options;
    }


    /**
     * @param array $options
     * @param bool  $showLabel
     * @param bool  $showField
     * @param bool  $showError
     * @return string
     */
    public function render(array $options = [], $showLabel = true, $showField = true, $showError = true)
    {
        $options['name']      = $this->getName();
        $options['name_slug'] = Str::slug($this->getName());

        $options = array_merge($options, $this->getData());

        return parent::render($options, $showLabel, $showField, $showError);
    }
}
