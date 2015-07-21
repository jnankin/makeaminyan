<!-- logintok:6039542760 -->
<h1>Log in</h1>
<? MAMUtils::writeFlashBlock('login');?>

<form class="tableless" action="<? echo url_for('login/index'); ?>" method="POST">
    <div class="fieldset">
        <div class="fields">
            <div class="field">
                <input type="text" name="username" value="<?=$sf_request->getParameter('username');?>" style="width: 350px;" />
                <label>Email or Phone Number</label>
            </div>

            <div class="field">
                <input type="password" name="password" style="width: 350px;" />
                <label>Password</label>
            </div>

            <div class="field">
                <input type="checkbox" name="remember_me"/> Remember me
            </div>
            <div class="field">
                <input type="hidden" name="redirect" value="<? echo $sf_data->getRaw('redirectUrl'); ?>" />
                <button type="submit" class="fancy important">
                    Login
                </button>
                <br><br><? echo link_to('Forgot your password?', 'forgotPassword/index'); ?>
            </div>
        </div>
    </div>
    <? Utils::clearDiv(); ?>
</form>
