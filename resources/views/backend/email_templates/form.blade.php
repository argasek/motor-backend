<div v-pre>
    {!! form_start($form) !!}
    <div class="@boxWrapper box-primary">
        <div class="@boxHeader with-border">
            <h3 class="box-title">{{ trans('motor-backend::backend/global.base_info') }}</h3>
        </div>
        <div class="@boxBody">
            {!! form_until($form, 'language_id') !!}
        </div>
    </div>
    <div class="@boxWrapper box-primary">
        <div class="@boxHeader with-border">
            <h3 class="box-title">{{ trans('motor-backend::backend/email_templates.template_info') }}</h3>
        </div>
        <div class="@boxBody">
            {!! form_until($form, 'body_html') !!}
        </div>

        <div class="@boxFooter">
            {!! form_row($form->submit) !!}
        </div>
    </div>
    {!! form_end($form) !!}
</div>
