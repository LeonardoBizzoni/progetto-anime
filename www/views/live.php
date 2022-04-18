<h1>Live page</h1>

<div class="container">
    <?php $form = app\core\forms\Form::begin("", "post"); ?>
    <?php echo $form->field($model, "Link"); ?>
    <button type="submit" class="btn btn-primary">Submit</button>
    <?php app\core\forms\Form::end(); ?>
</div>
