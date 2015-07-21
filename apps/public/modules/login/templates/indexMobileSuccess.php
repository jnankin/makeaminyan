<h2 class="centeredTitle">Please log in</h2>

<? MAMUtils::writeFlashBlock('login');?>

<form id="loginForm" method="post" action="<? echo url_for('login/index'); ?>" class="tableless">
    <div class="fieldset">
        <div class="fields">
            <div class="field">
                <input style="font-weight: bold" value="<?=$sf_request->getParameter('username');?>" type="text" name="username" />
                <label>Email or Phone Number</label>
            </div>
            <div class="field">
                <input style="font-weight: bold" type="password" name="password" />
                 <label>Password</label>
            </div>
        </div>
    </div>

    <? Utils::clearDiv(); ?>

    <input type="hidden" name="remember_me" value="true" />
    <input type="hidden" name="redirect" value="<?=$redirectUrl;?>" />

    <div class="centered">
        <button class="greenButtonFixed" type="submit">Login</button>
        <br><br>
        <a class="whiteButtonFixed" href="/signup">Don't have an account?</a>
    </div>
</form>