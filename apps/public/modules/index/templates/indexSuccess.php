<? use_stylesheet('signupIndex.css'); ?>

<script type="text/javascript">
    $().ready(function(){
        createPhoneMask("#signup_phone");
    });
</script>
<center>
    <div id="splash">
        <div id="signup">
            <h1>Sign Up <span class="requiredDescription">(all fields required)</span></h1>
            <form class="tableless" action="<?php echo url_for('signup/index'); ?>" method="post">

                <div class="fieldset">
                    <div class="fields">
                        <div class="shortField">
                            <?= $form['first_name']->render(); ?>
                            <label>First Name</label>
                        </div>
                        <div class="shortField">
                            <?= $form['last_name']->render(); ?>
                            <label>Last Name</label>
                        </div>

                        <div class="field">
                            <?= $form['email']->render(); ?>
                            <label>Email <span style="color: #CCCCCC"> - we promise to keep it safe</span></label>
                        </div>

                        <div class="field">
                            <?= $form['phone']->render(); ?>
                            <label>Phone</label>
                        </div>

                        <div class="field">
                            <?= $form['password']->render(); ?>
                            <label>Password</label>
                        </div>

                        <? FormUtils::writeCSRFToken($form); ?>
                        <div class="field" style="text-align: right;">
                            <button style="margin-right: -10px;" class="bigGreen" type="submit">Sign up!</button>
                        </div>
                    </div>
                </div>
                <?=Utils::clearDiv(); ?>
            </form>
        </div>
    </div>
</center>
