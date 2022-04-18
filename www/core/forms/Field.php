<?php
namespace app\core\forms;
use app\core\BaseModel;

class Field {
    public const TYPE_TEXT = "text";
    public const TYPE_PASSWORD = "password";
    public const TYPE_NUMBER = "number";

    public BaseModel $model;
    public string $attribute;
    public string $type;

    public function __construct(BaseModel $model, string $attribute) {
        $this->model = $model;
        $this->attribute = strtolower($attribute);
        $this->type = self::TYPE_TEXT;
    }

    public function __toString()
    {
        return sprintf(
            '
<div class="form-group">
    <label>%s</label>
    <input name="%s" type="%s" value="%s" class="form-control%s"/>
    <div class="invalid-feedback">%s</div>
</div>',
            ucfirst($this->attribute),
            $this->attribute,
            $this->type,
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? " is-invalid" : "",
            $this->model->getFirstError($this->attribute)
        );
    }

    public function passwordField() {
        $this->type = self::TYPE_PASSWORD;
        return $this;
    }
}
?>
