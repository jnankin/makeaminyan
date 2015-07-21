<? use_javascript('jquery.form.js'); ?>
<style>
    table.minyanim {
        width: 640px;
        font-size: 16px;
        margin-top: 10px;
    }

    table.minyanim td {
        vertical-align: top;
    }
</style>


<div style="float: right; padding: 10px; border: 1px solid #EBE6D1; width: 260px;">
    <a href="/files/subscriberForm.pdf">
        <img border="0" src="/images/pdf.png" align="right" style="margin-left: 5px; margin-bottom: 5px" />
        <strong>Download a subscriber form</strong>
    </a>
    <br/><br/>
    Put this handy subscriber form somewhere in your shul to collect emails and phone numbers
    for your minyan.  Then, enter the information on this page using the "Add New Subscriber" button.
</div>
<h2>Subscribers for <?=$minyan->getName();?></h2><br/>

<?=MAMUtils::writeFlashBlock('subscribers'); ?>

<button onclick="showAddSubscriberForm();" class="fancy">
    Add New Subscriber
</button> 

<button onclick="deleteSubscribers();" class="fancy">
    Delete Subscribers
</button>


<br/><br/>
<strong><?=count($minyanUsers);?> total subscriber(s)</strong><br/>


<table class="minyanim">
    <? foreach($minyanUsers as $minyanUser):
        $user = $minyanUser->getUser();
        ?>
    <tr>
        <td width="10%">
            <input class="subscriberMinyanId" type="checkbox" value="<?=$minyanUser->getId();?>" />
        </td>
        <td width="90%"><?=$user->getFullName();?></td>
    </tr>
    <? endforeach; ?>
</table>


<script type="text/javascript">

    function showAddSubscriberForm(){
        $.get('/minyanim/addNewSubscriber', {'minyanId' : <?=$minyan->getId();?> },
            function(data){
                new Boxy(data, {
                    modal: true,
                    title: 'Add new Subscriber',
                    unloadOnHide: true,
                    afterShow: function(){initAddSubscriberForm()}
                });
            },
         'html');
    }

    function initAddSubscriberForm(){
        createPhoneMask("#signup_phone");

        $('#modal_addSubscriberForm form').ajaxForm(function(data) {
             try {
                 data = JSON.parse(data);
                 if (data.success) {
                     location.href = '/minyanim/subscribers?minyanId=' + data.message;
                 }
             }
             catch(e){}

             $("#modal_addSubscriberForm").html(data);
             initAddSubscriberForm();
        });
    }


    function deleteSubscribers(){
        var param = [];
        $('input.subscriberMinyanId:checked').each(function(){
            param.push($(this).val());
        });

        if (param.length == 0){
            return;
        }
        else {
            Boxy.confirm("<div class='warningModal'><img src='/images/icons/x.png' align='absmiddle' style='padding: 0 10px 10px 0;'/> Are you sure you want to delete these subscribers?</div>", function() {
                $.post('/minyanim/deleteSubscribers', {
                    'minyanUserIds' : param
                }, function(data){
                    if (data.success){
                        location.reload();
                    }
                }, 'json');
            }, {
                title: 'Warning!'
            });

        }
    }
</script>