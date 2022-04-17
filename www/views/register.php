<h1>Registration page</h1>

<div class="container">
    <?php $form = app\core\forms\Form::begin("", "post"); ?>
    <div class="row">
        <div class="col">
            <?php echo $form->field($model, "Firstname"); ?>
        </div>
        <div class="col">
            <?php echo $form->field($model, "Lastname"); ?>
        </div>
    </div>
    <?php echo $form->field($model, "Email"); ?>
    <?php echo $form->field($model, "Username"); ?>
    <div class="row">
        <div class="col">
            <?php echo $form->field($model, "Password")->passwordField(); ?>
        </div>
        <div class="col">
            <?php echo $form->field($model, "Confirm")->passwordField(); ?>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
    <?php app\core\forms\Form::end(); ?>
</div>
