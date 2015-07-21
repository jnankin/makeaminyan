<script type="text/javascript">
    $().ready(function(){
        createPhoneMask("#signup_phone");
    });
</script>
<div style="width: 480px; margin: 0 auto;">
    <h1>Sign Up!</h1>
    <form action="<?php echo url_for('signup/index'); ?>" method="post" class="tableless">

        <div class="fieldset">
            <div class="fields">
                <div class="shortField">
                    <?= $form['first_name']->render(); ?>
                    <label>First Name</label>
                    <?=$form['first_name']->renderError();?>
                </div>
                <div class="shortField">
                    <?= $form['last_name']->render(); ?>
                    <label>Last Name</label>
                    <?=$form['last_name']->renderError();?>
                </div>

                <div class="field">
                    <?= $form['email']->render(); ?>
                    <label>Email <span style="color: #CCCCCC"> - we promise to keep it safe</span></label>
                    <?=$form['email']->renderError();?>
                </div>

                <div class="field">
                    <?= $form['phone']->render(); ?>
                    <label>Phone</label>
                    <?=$form['phone']->renderError();?>
                </div>

                <div class="field">
                    <?= $form['password']->render(); ?>
                    <label>Password</label>
                    <?=$form['password']->renderError();?>
                </div>

                <? FormUtils::writeCSRFToken($form); ?>
                <div class="field" style="margin-top: 20px;">
                    <button class="bigGreen" type="submit">Sign up!</button>
                </div>
            </div>
        </div>
    </form>
    <?=Utils::clearDiv(); ?>
</div>