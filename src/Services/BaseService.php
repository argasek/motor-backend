<?php

namespace Motor\Backend\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Kris\LaravelFormBuilder\Fields\CheckableType;
use Kris\LaravelFormBuilder\Fields\ChildFormType;
use Kris\LaravelFormBuilder\Fields\SelectType;
use Kris\LaravelFormBuilder\Form;
use Motor\Backend\Forms\Fields\CheckboxCollectionType;
use Motor\Backend\Forms\Fields\DatepickerType;
use Motor\Backend\Forms\Fields\DatetimepickerType;
use Motor\Core\Filter\Filter;
use Motor\Core\Filter\Renderers\PerPageRenderer;
use Motor\Core\Filter\Renderers\SearchRenderer;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;

abstract class BaseService
{

    protected $filter;

    protected $request;

    protected $model;

    protected $record;

    protected $form;

    protected $data = [];

    protected $result;

    protected $sortableField = 'id';

    protected $sortableDirection = 'ASC';


    /**
     * Basic create method.
     * Usually called by an API
     *
     * @param Request $request
     *
     * @return mixed
     */
    public static function create(Request $request)
    {
        return $instance = ( new static() )->setRequest($request)->doCreate();
    }


    /**
     * Create method with support from a Form class
     * Usually called from a backend controller
     *
     * @param Request $request
     * @param Form    $form
     *
     * @return mixed
     */
    public static function createWithForm(Request $request, Form $form)
    {
        return $instance = ( new static() )->setRequest($request)->setForm($form)->doCreate();
    }


    /**
     * Basic update method.
     * Usually called by an API
     *
     * @param Model   $record
     * @param Request $request
     *
     * @return mixed
     */
    public static function update(Model $record, Request $request)
    {
        return ( new static() )->setRequest($request)->setRecord($record)->doUpdate();
    }


    /**
     * Create method with support from a Form class
     * Usually called from a backend controller
     *
     * @param Model   $record
     * @param Request $request
     * @param Form    $form
     *
     * @return mixed
     */
    public static function updateWithForm(Model $record, Request $request, Form $form)
    {
        return ( new static() )->setRequest($request)->setRecord($record)->setForm($form)->doUpdate();
    }


    /**
     * Simple wrapper to return the given record
     *
     * @param $record
     *
     * @return mixed
     */
    public static function show($record)
    {
        return ( new static() )->setRecord($record)->doShow();
    }


    /**
     * Wrapper to return paginated results
     * Applies basic filters and adds filters through the individual services filters() method
     *
     * @param string $alias
     *
     * @return static
     */
    public static function collection($alias = '', $sorting = null)
    {
        $instance         = new static();
        $instance->filter = new Filter($alias);
        $instance->defaultFilters();
        $instance->filters();

        if (array_get($_GET, 'sortable_field') && array_get($_GET, 'sortable_direction')) {
            $instance->setSorting([array_get($_GET, 'sortable_field'), array_get($_GET, 'sortable_direction')]);
        }

        if (!is_null($sorting)) {
            $instance->setSorting($sorting);
        }

        return $instance;
    }


    /**
     * Simple wrapper around the delete method of the record
     *
     * @param $record
     *
     * @return mixed
     */
    public static function delete($record)
    {
        return ( new static() )->setRecord($record)->doDelete();
    }


    /**
     * Sets default filters to use with the collection() method
     */
    public function defaultFilters()
    {
        $this->filter->add(new SearchRenderer('search'));
        $this->filter->add(new PerPageRenderer('per_page'))->setup();
    }


    /**
     * Returns the filter class
     * Usually necessary to get filters to the grid when displaying a collection
     *
     * @return mixed
     */
    public function getFilter()
    {
        return $this->filter;
    }


    /**
     * Returns the result of create/update/delete/record methods
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }


    /**
     * Returns the paginator for the model
     *
     * @return mixed
     */
    public function getPaginator()
    {
        $query = ( $this->model )::filteredByMultiple($this->getFilter());
        $query = $this->applyScopes($query);
        $query = $this->applySorting($query);

        return $query->paginate($this->getFilter()->get('per_page')->getValue());
    }


    /**
     * Set sorting array
     *
     * @param array $sorting
     */
    public function setSorting(Array $sorting)
    {
        list($this->sortableField, $this->sortableDirection) = $sorting;

        return $this;
    }


    /**
     * Add custom sorting, if available
     *
     * @param $query
     */
    public function applySorting($query)
    {
        // FIXME: we can't assume that the sorting will always be on the base model!?
        if ( ! is_null($this->sortableField)) {
            return $query->orderBy($query->getModel()->getTable().'.'.$this->sortableField, $this->sortableDirection);
        }

        return $query;
    }


    /**
     * Add custom scopes to query
     *
     * @param $query
     *
     * @return mixed
     */
    public function applyScopes($query)
    {
        return $query;
    }


    /**
     * Show the record (set result to the current record)
     * Also invokes before and after methods
     *
     * @return $this
     */
    public function doShow()
    {
        $this->beforeShow();
        $this->result = $this->record;
        $this->afterShow();

        return $this;
    }


    /**
     * Creates a record and sets the result to the record when successful, or false when unsuccessful
     * Also invokes before and after methods
     *
     * @return $this
     */
    public function doCreate()
    {
        $this->record = new $this->model();
        $this->beforeCreate();
        $this->record->fill($this->data);
        $this->result = $this->record->save();
        $this->afterCreate();
        if ($this->result) {
            $this->result = $this->record->fresh();
        }

        return $this;
    }


    /**
     * Updates a record and sets the result to the record when successful, or false when unsuccessful
     * Also invokes before and after methods
     *
     * @return $this
     */
    public function doUpdate()
    {
        $this->beforeUpdate();
        $this->result = $this->record->update($this->data);
        $this->afterUpdate();
        if ($this->result) {
            $this->result = $this->record->fresh();
        }

        return $this;
    }


    /**
     * Deletes a record and sets the result to either true or false
     * Also invokes before and after methods
     *
     * @return $this
     */
    public function doDelete()
    {
        $this->beforeDelete();
        if ($this->record->exists) {
            $this->result = $this->record->delete();
        }
        $this->afterDelete();

        return $this;
    }


    /**
     * Sets a Form object to use when creating/updating
     *
     * @param Form $form
     *
     * @return $this
     */
    public function setForm(Form $form)
    {
        $this->form = $form;
        $this->data = $this->handleFormValues($this->form, $this->data);

        return $this;
    }


    /**
     * Sets a record
     *
     * @param Model $record
     *
     * @return $this
     */
    public function setRecord(Model $record)
    {
        $this->record = $record;

        return $this;
    }


    /**
     * Sets a request object
     *
     * @param Request $request
     *
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
        $this->data    = $this->request->all();

        return $this;
    }


    /**
     * Loops through the fields of the Form object to handle some special cases (date/datetime and checkboxes)
     *
     * @param Form  $form
     * @param array $data
     *
     * @return array
     */
    public function handleFormValues(Form $form, array $data)
    {
        foreach ($form->getFields() as $name => $field) {

            // Handle subforms
            if ($field instanceof ChildFormType) {
                $data[$field->getRealName()] = $this->handleFormValues($field->getForm(),
                    Arr::get($data, $field->getRealName()));
            }

            // Handle empty checkbox values
            if ($field instanceof CheckableType) {
                if ( ! isset($data[$field->getRealName()])) {
                    $data[$field->getRealName()] = false;
                }
            }

            // Handle empty checkboxcollection
            if ($field instanceof CheckboxCollectionType) {
                if ( ! isset($data[$field->getRealName()])) {
                    $data[$field->getRealName()] = [];
                }
            }

            // Handle empty date values
            if ($field instanceof DatepickerType || $field instanceof DatetimepickerType) {

                if ($data[$field->getRealName() . '_picker'] == '') {
                    $data[$field->getRealName()] = '';
                }
                if ( ! isset($data[$field->getRealName()]) || ( isset($data[$field->getRealName()]) && $data[$field->getRealName()] == '' || $data[$field->getRealName()] == '0000-00-00 00:00:00' || $data[$field->getRealName()] == '0000-00-00' )) {
                    $data[$field->getRealName()] = null;
                }
            }

            // Handle empty select values
            if ($field instanceof SelectType && isset($data[$field->getRealName()]) && $data[$field->getRealName()] == '') {
                $data[$field->getRealName()] = null;
            }
        }

        return $data;
    }


    /**
     * Handles file uploads either with a UploadedFile object or a base64 encoded file
     *
     * @param        $file
     * @param string $identifier
     * @param null   $collection
     *
     * @return $this
     */
    public function uploadFile($file, $identifier = 'image', $collection = null, $record = null)
    {
        if ( ! is_null($record) && ! $record instanceof HasMedia) {
            return $this;
        }

        if (is_null($record)) {
            $record = $this->record;
        }

        if ( ! $record instanceof HasMedia) {
            return $this;
        }

        $collection = ( ! is_null($collection) ? $collection : $identifier );

        if ( ! is_null($file) || $this->isValidBase64(Arr::get($this->data, $identifier)) || Arr::get($this->data,
                Str::slug($identifier) . '_delete') == 1
        ) {
            $record->clearMediaCollection($identifier);
            if ( ! is_null($collection)) {
                $record->clearMediaCollection($collection);
            }
        }

        if ($file instanceof UploadedFile && $file->isValid()) {
            $record->addMedia($file)->toCollection($collection);
        } else {
            if ($this->isValidBase64(Arr::get($this->data, $identifier))) {
                $image = base64_decode($this->data[$identifier]);

                $tempFilename = tempnam(sys_get_temp_dir(), 'upload');

                $name = Arr::get($this->data, $identifier . '_name', $tempFilename);

                $handle = fopen($tempFilename, "w");
                fwrite($handle, $image);
                fclose($handle);
                $record->addMedia($tempFilename)->setName($name)->setFileName($name)->toCollection($collection);
            }
        }

        return $this;
    }


    /**
     * Helper method to check if a file upload field is base64 encoded
     *
     * @param $string
     *
     * @return bool
     */
    protected function isValidBase64($string)
    {
        $decoded = base64_decode($string, true);
        // Check if there is no invalid character in strin
        if ( ! preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string)) {
            return false;
        }

        // Decode the string in strict mode and send the responce
        if ( ! base64_decode($string, true)) {
            return false;
        }

        // Encode and compare it to origional one
        if (base64_encode($decoded) != $string) {
            return false;
        }

        return true;
    }


    /**
     * Stub for the filters method of the child class
     */
    public function filters()
    {
    }


    /**
     * Stub for the beforeCreate method of the child class
     */
    public function beforeCreate()
    {
    }


    /**
     * Stub for the afterCreate method of the child class
     */
    public function afterCreate()
    {
    }


    /**
     * Stub for the beforeUpdate method of the child class
     */
    public function beforeUpdate()
    {
    }


    /**
     * Stub for the afterUpdate method of the child class
     */
    public function afterUpdate()
    {
    }


    /**
     * Stub for the beforeDelete method of the child class
     */
    public function beforeDelete()
    {
    }


    /**
     * Stub for the afterDelete method of the child class
     */
    public function afterDelete()
    {
    }


    /**
     * Stub for the beforeShow method of the child class
     */
    public function beforeShow()
    {
    }


    /**
     * Stub for the afterShow method of the child class
     */
    public function afterShow()
    {
    }
}