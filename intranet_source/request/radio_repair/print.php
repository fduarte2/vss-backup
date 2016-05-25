<?
include("pow_session.php");

  include("connect.php");
  $db = "ccds";

  $pg_conn = pg_connect ("host=$host dbname=$db user=$dbuser");
  if(!$pg_conn){
      die("Could not open connection to PostgreSQL database server");
  }
  $data = array();
  $check = "check.PNG";
  $check_off = "check_off.PNG";

  $ticket_no = $HTTP_GET_VARS['ticket_no'];

  if ($ticket_no <>""){
        $sql = "select to_char(date, 'mm/dd/yyyy'), user_name, model_no, serial_no, ticket_no, problem
		from radio_repair_header
                where ticket_no = $ticket_no";
        $result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
        $rows = pg_num_rows($result);
        if ($rows > 0){
                $row = pg_fetch_row($result, 0);
                $date = $row[0];
                $user = $row[1];
                $model = $row[2];
                $serial = $row[3];
                $ticket_no = $row[4];
		$problem = $row[5];

                $sql = "select id, replace_id, replace from radio_replace c left outer join radio_replace_requested r
                        on c.id = r.replace_id and ticket_no = $ticket_no order by id";
                $replace_result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
                $replace_rows = pg_num_rows($replace_result);
                for ($i = 0; $i < $replace_rows; $i++){
                        $row = pg_fetch_row($replace_result, $i);
                        $replace[$i+1] = $row[2];
                        if ($row[1] <> ""){
                                $rImage[$i+1] = $check;
                        }else{
                                $rImage[$i+1] = $check_off;
                        }
                }

                $sql = "select id, channel_id, channel from radio_channel c left outer join radio_channel_requested r
                        on c.id = r.channel_id and ticket_no = $ticket_no order by id";
                $channel_result = pg_query($pg_conn, $sql) or die("Error in query: $sql. " . pg_last_error($pg_conn));
                $channel_rows = pg_num_rows($channel_result);
                for ($i = 0; $i < $channel_rows; $i++){
                        $row = pg_fetch_row($channel_result, $i);
			$channels[$i+1] = $row[2];
                        if ($row[1] <> ""){
                                $image[$i+1] = $check;
                        }else{
                                $image[$i+1] = $check_off;
                        }
                }
	}
  }

include 'class.ezpdf.php';

$pdf = new Cezpdf('letter', 'portrait');
$pdf ->ezSetMargins(50, 50, 55, 55);
$pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
$tmp = array('b'=>'Helvetica-Bold.afm', 'i'=>'Helvetica-Oblique.afm', 'bi'=>'Helvetica-BoldOblique.afm', 'ib'=>'Helvetica-BoldOblique.afm');
$pdf->setFontFamily('Helvetica.afm', $tmp);


$pdf ->ezSetDy(-20);
$pdf ->ezText("<b>NEW RADIO / RADIO REPAIR REQUEST</b>", 16, $center);

$pdf->addText(50, 650, 10, "Date: <b>$date</b>");
$pdf->addText(200, 650, 10, "User: <b>$user</b>");
$pdf->addText(400, 650, 10, "Radio Request Ticket#: <b>$ticket_no</b>");
$pdf->addText(50, 630, 10, "Model#: <b>$model</b>");
$pdf->addText(200, 630, 10, "Serial#: <b>$serial</b>");

/*
$arrHeading = array('catg'=>'<b>Catergory</b>', 'prob'=>'<b>Problem</b>');
$arrCol = array('catg'=>array('width'=>80, 'justification'=>'left'),
                   'prob'=>array('width'=>420, 'justification'=>'left'));
$heading = array();
array_push($heading, $arrHeading);

$pdf ->ezSetDy(-90);
$pdf->ezTable($heading, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol));
   $pdf->selectFont('/usr/lib/php/Pdf/fonts/Helvetica.afm');
$pdf->ezTable($data, $arrHeading, '', array('showHeadings'=>0, 'shaded'=>0, 'showLines'=>2, 'width'=>510,'cols'=>$arrCol));
*/

$pdf->addText(50, 610, 10, "<b>Problem Detail:</b>");
$pdf->rectangle(50,545,510,60);

$pdf ->ezSetDy(-100);
$pdf ->ezText($problem, 10, $left);

$pdf ->addText(50, 520, 10, "<b>Please Replace:</b>");

$pdf->addPngFromFile($rImage[1], 60, 500, 10);
$pdf->addText(75, 500, 10, $replace[1]);
$pdf->addPngFromFile($rImage[2], 160, 500, 10);
$pdf->addText(175, 500, 10, $replace[2]);
$pdf->addPngFromFile($rImage[3], 260, 500, 10);
$pdf->addText(275, 500, 10, $replace[3]);
$pdf->addPngFromFile($rImage[4], 360, 500, 10);
$pdf->addText(375, 500, 10, $replace[4]);


$pdf->addPngFromFile($rImage[5], 60, 480, 10);
$pdf->addText(75, 480, 10, $replace[5]);
$pdf->addPngFromFile($rImage[6], 160, 480, 10);
$pdf->addText(175, 480, 10, $replace[6]);
$pdf->addPngFromFile($rImage[7], 260, 480, 10);
$pdf->addText(275, 480, 10, $replace[7]);
$pdf->addPngFromFile($rImage[8], 360, 480, 10);
$pdf->addText(375, 480, 10, $replace[8]);



$pdf ->addText(50, 430, 10, "<b>Channels Requested:</b>");

$pdf->addPngFromFile($image[1], 60, 410, 10);
$pdf->addText(75, 410, 10, $channels[1]);
$pdf->addPngFromFile($image[2], 160, 410, 10);
$pdf->addText(175, 410, 10, $channels[2]);
$pdf->addPngFromFile($image[3], 260, 410, 10);
$pdf->addText(275, 410, 10, $channels[3]);
$pdf->addPngFromFile($image[4], 360, 410, 10);
$pdf->addText(375, 410, 10, $channels[4]);
$pdf->addPngFromFile($image[5], 460, 410, 10);
$pdf->addText(475, 410, 10, $channels[5]);

$pdf->addPngFromFile($image[6], 60, 390, 10);
$pdf->addText(75, 390, 10, $channels[6]);
$pdf->addPngFromFile($image[7], 160, 390, 10);
$pdf->addText(175, 390, 10, $channels[7]);
$pdf->addPngFromFile($image[8], 260, 390, 10);
$pdf->addText(275, 390, 10, $channels[8]);
$pdf->addPngFromFile($image[9], 360, 390, 10);
$pdf->addText(375, 390, 10, $channels[9]);

$pdf ->addText(50, 310, 12, "Signature:");
$pdf ->line(110,310, 290, 310);
$pdf ->addText(300, 310, 12, "Date:");
$pdf ->line(330,310, 450, 310);
$pdf ->addText(50, 280, 12, "Department Director:");
$pdf ->line(163,280, 360, 280);
$pdf ->addText(50,345,10, "Please print this request form, have it initialed by your department director, and drop it off in Inigo's Office.");


$pdf->ezStream();

?>
