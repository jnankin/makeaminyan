<style>
    table {
        width: 100%;
        font-size: 16px;
        margin-top: 10px;
    }

    table td {
        vertical-align: top;
        text-align: left;
    }
    </style>

    <? if (count($blasts) == 0): ?>
        <h3> There are no blasts for this minyan.</h3>

    <? else: ?>

        <h3>Blasts for <?=$minyan->getName();?>: </h3>
        <table>
            <tr>
                <td style="font-weight: bold">Created on</td>
                <td style="font-weight: bold">Event Type</td>
                <td style="font-weight: bold">Yes</td>
                <td style="font-weight: bold">No</td>
                <td style="font-weight: bold">Maybe</td>
                <td style="font-weight: bold">No Response</td>
            </tr>
            <? foreach($blasts as $blast):
                $attendanceArray = $blast->getAttendanceArray();
                ?>
            <tr>
                <td><?=date("F j, Y, g:i a", strtotime($blast->getCreatedAt()));?></td>
                <td><?=link_to($blast->getEventType(), 'blast/dashboard?blastId=' . $blast->getId());?></td>
                <td><?=$attendanceArray['yes'];?></td>
                <td><?=$attendanceArray['no'];?></td>
                <td><?=$attendanceArray['maybe'];?></td>
                <td><?=$attendanceArray['noresponse'];?></td>
            </tr>

            <? endforeach; ?>
        </table>
    <? endif; ?>