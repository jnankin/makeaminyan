<style>
    table.status {
        width: 100%;
        margin-top: 10px;
    }

    table.status td {
        width: 25%;
        font-size: 14px;
        font-weight: bold;
    }
    table.status td span.number {
        font-size: 48px;
        font-weight: bold;
    }

    table#statusDetail {
        width: 100%;
        font-size: 14px;
        margin-top: 20px;
    }

    table#statusDetail td {
        padding: 5px;
        background-color: #F2F2F2;
    }

    table#statusDetail td.name {
        font-weight: bold;
        width: 33%;
    }

    table#statusDetail td.value {
        width: 77%;
    }

    table#statusDetail tr.yes td {
        background-color: #DFFFE3;
    }

    table#statusDetail tr.no td {
        background-color: #FFBFC2;
    }

</style>
<h2>Blast Dashboard for <?=$blast->getEventType();?> Minyan for
<?=$blast->getMinyan()->getName();?> - <?=date('m/d/Y', strtotime($blast->getCreatedAt()));?></h2>

<table class="status">
    <tr>
        <td>
            <span class="number" id="yesNumber">0</span>
            <br/>
            YES
        </td>
        <td>
            <span class="number" id="noNumber">0</span>
            <br/>
            NO
        </td>
        <td>
            <span class="number" id="maybeNumber">0</span>
            <br/>
            MAYBE
        </td>
        <td>
            <span class="number" id="noResponseNumber">0</span>
            <br/>
            NO RESPONSE
        </td>
    </tr>
</table>

<table id="statusDetail">

</table>



<script type="text/javascript">
    $().ready(function(){
       pollForData();
       setInterval(pollForData, 10000);
    });

    function pollForData(){
        $.get('/blast/getResponses', {'blastId' : <?=$blast->getId();?> }, function(data){
            if (data.success){
                data = data.data;
                if (data && data.totals){
                    var totals = data.totals;
                    $('#yesNumber').text(totals['yes']);
                    $('#noNumber').text(totals['no']);
                    $('#maybeNumber').text(totals['maybe']);
                    $('#noResponseNumber').text(totals['noresponse']);
                }

                if (data && data.responseData){
                    var responseData = data.responseData;

                    $('table#statusDetail').html('');
                    
                    for(var i = 0; i < responseData.length; i++){
                        var response = responseData[i];
                        var statusName = response['name'];
                        var statusMessage = '';
                        var className = response['status'];

                        if (response['status'] == 'noresponse') continue;
                        
                        switch(response['status']){
                            case 'yes':
                                statusMessage = '+' + response['footprint'];
                                break;
                            case 'no':
                                statusMessage = 'Not coming';
                                break;
                            case 'maybe':
                                statusMessage = 'Maybe coming';
                                break;

                        }

                        $('table#statusDetail').append(
                            $("<tr>").addClass(className).append(
                                $("<td>").addClass('name').text(statusName)
                            ).append(
                                $("<td>").addClass('value').text(statusMessage)
                            )
                        );
                    }

                }
            }
        },
        'json');
    }
</script>