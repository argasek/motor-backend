<?php

namespace Motor\Backend\Forms\Backend;

use Kris\LaravelFormBuilder\Form;

class LanguageForm extends Form
{
    public function buildForm()
    {
        $this
            ->add('iso_639_1', 'text', ['label' => trans('backend/languages.iso_639_1'), 'rules' => 'required'])
            ->add('native_name', 'text', ['label' => trans('backend/languages.native_name'), 'rules' => 'required'])
            ->add('english_name', 'text', ['label' => trans('backend/languages.english_name'), 'rules' => 'required'])
            ->add('submit', 'submit', ['attr' => ['class' => 'btn btn-primary'], 'label' => trans('backend/languages.save')]);
    }
}
