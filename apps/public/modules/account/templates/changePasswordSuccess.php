<?php use_stylesheet('form');  ?>

<h1>Change Password</h1>

<?php if ($sf_user->hasFlash('changePasswordFlash')):?>
<div class="successMessage" style="width: 400px;">
    <?php echo $sf_user->getFlash('changePasswordFlash'); ?>
</div>
<?php endif; ?>

<style>
    .form td.label {
        width: 300px;
    }
</style>
<form action="<?php echo url_for('account/changePassword');?>" method="post" class="form">
        <table style="border-bottom: none;">
            <?php FormUtils::writeLabel('Old Password', '', true);?>
            <?php FormUtils::writeField('old_password', $form, array('class' => 'textfield'));?>
            
            <?php FormUtils::writeLabel('New Password', 'Must be at least 6 characters', true);?>
            <?php FormUtils::writeField('new_password', $form, array('class' => 'textfield'));?>
            
            <?php FormUtils::writeLabel('Confirm New Password', '', true);?>
            <?php FormUtils::writeField('confirm_new_password', $form, array('class' => 'textfield'));?>    
            
            <?php FormUtils::writeLabel('');?>
            <?php FormUtils::writeButton('Change Password');?>
        
        </table>
        <?php FormUtils::writeCSRFToken($form);?>
        
</form>