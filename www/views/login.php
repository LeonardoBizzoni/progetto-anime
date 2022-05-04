<h1>Login page</h1>

<div class="container">
    <?php $form = app\core\forms\Form::begin("", "post"); ?>
    <?php echo $form->field($model, "email"); ?>
    <?php echo $form->field($model, "password")->passwordField(); ?>

    <button type="submit" class="btn btn-primary">Submit</button>
    <?php app\core\forms\Form::end(); ?>
</div>
