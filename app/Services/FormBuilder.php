<?php


namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class FormBuilder
{
    /**
     * Model used to retrieve default fields value.
     *
     * @var \stdClass
     */
    private $model;

    /**
     * @var ViewErrorBag
     */
    private $errors;

    /**
     * HTTP Method (get, post, put, delete).
     *
     * @var string
     */
    private $method;

    /**
     * FormBuilder constructor.
     *
     * @param Request $request
     */
    public function __construct()
    {
        $this->fieldName('asf.');
        $this->errors = session()->get('errors') ?: new ViewErrorBag();
    }

    /**
     * Convert array to html string.
     *
     * @param array $attr Name-value tag attributes.
     * @return string
     */
    protected function attrToHtml(array $attr = [])
    {
        $html = '';
        foreach ($attr as $name => $value) {
            $html .= " $name=\"$value\" ";
        }

        return $html;
    }

    /**
     * Get form open tag.
     *
     * @param string $method HTTP Method (get/post/put/delete).
     * @param string $url
     * @param bool $files Set true if form sends file(s).
     * @param array $attr Additional tag attributes.
     * @param bool $meta Append _token and _method fields.
     * @return HtmlString
     */
    public function open($method, $url, $files = false, array $attr = [], $meta = true)
    {
        $this->method = $method;
        $attr += ['action' => $url];

        // Use only get or post method for better compatibility
        $spoof = $method == 'get' ? 'get' : 'post';
        $attr += ['method' => $spoof];

        // Use other encryption if files are uploaded
        if ($files) {
            $attr['enctype'] = 'multipart/form-data';
        }

        // Build HTML
        $params = $this->attrToHtml($attr);
        $html = "<form $params>";

        if($meta) {
            $html .= method_field($method);
            $html .= csrf_field();
        }

        return new HtmlString($html);
    }

    /**
     * Get value of field in the following order:
     * 1. Old value (filled in)
     * 2. Default value (see FormBuilderService::model)
     * 3. Empty string
     *
     * @param string $field Field name.
     * @param string|null $default
     * @return mixed
     */
    protected function fieldValue($field, $default = null)
    {
        $value = array_get($this->model, $field, $default);

        return e(old($field, $value));
    }

    /**
     * Convert dot-like name to array-like name
     * (eq. "colors.red" will return "colors[red]")
     *
     * Note: "colors." will return "colors[]"
     * (useful for checkboxes and radio buttons).
     *
     * @param string $name
     * @return string
     */
    protected function fieldName($name)
    {
        $parts = explode('.', $name);

        $result = array_shift($parts);
        foreach($parts as $part) {
            $result .= "[$part]";
        }

        return $result;
    }

    /**
     * Get form open tag
     * (and set default form values).
     *
     * @param mixed $model Model used to retrieve default values for fields.
     * @param string $method HTTP Method (get/post/put/delete).
     * @param string $url
     * @param bool $files Set true if form sends file(s).
     * @param array $attr Additional tag attributes.
     * @return HtmlString
     */
    public function model($method, $url, $model, $files = false, array $attr = [])
    {
        $this->model = $model;

        return $this->open($method, $url, $files, $attr);
    }

    /**
     * Get label tag html.
     *
     * @param string $name
     * @param string|null $text
     * @param array $attr Additional tag attributes.
     * @return HtmlString
     */
    public function label($name, $text = null, array $attr = [])
    {
        $attr += ['for' => $name];
        $text = $text ?: trans("form.labels.$name");

        $params = $this->attrToHtml($attr);
        $html = "<label $params>$text</label>";

        return new HtmlString($html);
    }

    /**
     * Get input tag html.
     *
     * @param string $type Input type (text, password, checkbox, ...)
     * @param string $name
     * @param string $value
     * @param array $attr Additional tag attributes.
     * @return HtmlString
     */
    protected function input($type, $name, $value = null, array $attr = [])
    {
        $attr += [
            'type' => $type,
            'name' => $this->fieldName($name),
            'value' => $value,
        ];

        $params = $this->attrToHtml($attr);
        $html = "<input $params>";

        return new HtmlString($html);
    }

    /**
     * Get string input tag html.
     *
     * @param string $name
     * @param string|null $default
     * @param array $attr Additional tag attributes.
     * @return HtmlString
     */
    public function string($name, $default = null, array $attr = [])
    {
        $value = $this->fieldValue($name, $default);

        return $this->input('text', $name, $value, $attr);
    }

    /**
     * Get hidden input tag html.
     *
     * @param string $name
     * @param string|null $value
     * @param array $attr Additional tag attributes.
     * @return HtmlString
     */
    public function hidden($name, $value = null, array $attr = [])
    {
        $value = !is_null($value) ? $value : $this->fieldValue($name, $value);
        $html = $this->input('hidden', $name, $value, $attr);

        return new HtmlString($html);
    }

    /**
     * Get password input tag html.
     *
     * @param string $name
     * @param string $default
     * @param array $attr Additional tag attributes.
     * @return HtmlString
     */
    public function password($name, $default = '', array $attr = [])
    {
        $value = !is_null($default) ? $default : $this->fieldValue($name);

        return $this->input('password', $name, $value, $attr);
    }

    /**
     * Get text area tag html.
     *
     * @param string $name
     * @param string|null $default
     * @param array $attr Additional tag attributes.
     * @return HtmlString
     */
    public function text($name, $default = null, array $attr = [])
    {
        $value = $this->fieldValue($name, $default);
        $attr += [
            'name' => $this->fieldName($name),
        ];

        $params = $this->attrToHtml($attr);
        $html = "<textarea $params>$value</textarea>";

        return new HtmlString($html);
    }

    /**
     * Get date input tag html.
     *
     * @param string $name
     * @param string|null $default
     * @param array $attr Additional tag attributes.
     * @return HtmlString
     */
    public function date($name, $default = null, array $attr = [])
    {
        // TODO: Add and integrate date picker.

        return $this->string($name, $default, $attr);
    }

    /**
     * Get datetime input tag html.
     *
     * @param string $name
     * @param string|null $default
     * @param array $attr Additional tag attributes.
     * @return HtmlString
     */
    public function datetime($name, $default = null, array $attr = [])
    {
        // TODO: Add and integrate datetime picker.

        return $this->string($name, $default, $attr);
    }

    /**
     * Get checkbox input tag html.
     *
     * @param string $name
     * @param string $value
     * @param string|null $label
     * @param string|null $default
     * @param array $attr Additional tag attributes.
     * @return HtmlString
     */
    public function checkbox($name, $value, $label = null, $default = null, array $attr = [])
    {
        $attr += [
            'name' => $this->fieldName($name),
            'value' => e($value),
        ];

        // Checkbox state
        $old = $this->fieldValue($name, $default);
        if($value == $old) {
            $attr += ['checked' => 'checked'];
        }

        // Build tag
        $label = $label ?: trans("form.labels.$name");
        $checkbox = $this->input('checkbox', $name, $old, $attr);
        $html = "<label><div class=\"form-toggable\">$checkbox<div class=\"form-toggable-icon\"></div></div><span class=\"mls text-middle\">$label</span></label>";

        return new HtmlString($html);
    }

    /**
     * Get radio input tag html.
     *
     * @param string $name
     * @param string $value
     * @param string|null $default
     * @param array $attr Additional tag attributes.
     * @return HtmlString
     */
    public function radio($name, $value, $label = null, $default = null, array $attr = [])
    {
        $attr += [
            'name' => $this->fieldName($name),
            'value' => e($value),
        ];

        // Radio state
        $old = $this->fieldValue($name, $default);
        if($value == $old) {
            $attr += ['checked' => 'checked'];
        }

        // Build tag
        $label = $label ?: trans("form.labels.$name");
        $checkbox = $this->input('radio', $name, $old, $attr);
        $html = "<label><div class=\"form-toggable\">$checkbox<div class=\"form-toggable-icon\"></div></div><span class=\"mls text-middle\">$label</span></label>";

        return new HtmlString($html);
    }

    /**
     * Get select input tag html.
     *
     * @param string $name
     * @param $values
     * @param string|null $default
     * @param array $attr Additional tag attributes.
     * @return HtmlString
     */
    public function select($name, $values, $default = null, array $attr = [])
    {
        $id = "_select_$name";
        $attr += [
            'id' => $id,
            'name' => $this->fieldName($name),
        ];

        // Build options
        $options = '';
        $value = $this->fieldValue($name, $default);
        foreach($values as $text => $val) {
            $checked = $value == $val ? 'selected' : '';
            $options .= "<option value=\"$val\" $checked>$text</option>";
        }

        // Build select with options
        $params = $this->attrToHtml($attr);
        $label = "<label for=\"$id\"><div class=\"form-select-label\"><div class=\"form-select-icon\"><span class=\"icon-arrow_drop_down\"></span></div><div class=\"form-select-value\"></div></div></label>";
        $html = "<div class=\"form-select\"><select $params>$options</select>$label</div>";

        return new HtmlString($html);
    }

    /**
     * Get file input tag html.
     *
     * @param string $name
     * @param array $attr Additional tag attributes.
     * @return HtmlString
     */
    public function image($name, $default = null, array $attr = [])
    {
        $input = $this->input('file', $name, $default, $attr);
        $preview = "<div style=\"display: none\" class=\"form-image-preview\"><img/></div>";
        $html = "<div class=\"form-image\">$input<div class=\"form-image-box\"><p class=\"form-image-default-text\"> Click to choose file </p>$preview</div></div>";

        return new HtmlString($html);
    }

    /**
     * Get error tag html with first error message.
     *
     * @param string $name Field name.
     * @param array $attr Additional tag attributes.
     * @return HtmlString Empty string if no errors.
     */
    public function error($name, array $attr = [])
    {
        // Empty string for valid fields
        if(!$this->errors->has($name)) {
            return new HtmlString('');
        }

        $msg = $this->errors->first($name);
        $params = $this->attrToHtml($attr);
        $html = "<div class=\"form-error\" $params><div class=\"form-error-msg\">$msg</div></div>";

        return new HtmlString($html);
    }

    /**
     * Get subbmit button tag html.
     *
     * @param string|null $text
     * @param array $attr Additional tag attributes.
     * @return HtmlString
     */
    public function submit($text = null, array $attr = [])
    {
        $text = $text ?: trans("form.buttons.{$this->method}");
        $attr += [
            'type' => 'submit',
            'class' => 'button',
        ];

        $params = $this->attrToHtml($attr);
        $html = "<button $params>$text</button>";

        return new HtmlString($html);
    }

    /**
     * Get form close tag.
     *
     * @return HtmlString
     */
    public function close()
    {
        return new HtmlString('</form>');
    }
}