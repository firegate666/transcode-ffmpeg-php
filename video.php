<?
    session_start();
    ini_set('max_execution_time', '3600');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="de">
	<head>
		<title>Audio / Videotranskodierung</title>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
		<link rel="stylesheet" type="text/css" href="stylesheet.css"/>
	</head>
<body>
<h3>MediaTranscoder - Video</h3>
<?php
include './config.inc.php';
if (isset($_REQUEST['submit'])) {
	echo "<pre>";
	$width = escapeshellcmd(intval($_REQUEST['width']));
	$height = escapeshellcmd(intval($_REQUEST['height']));
	$vcodec = escapeshellcmd($CONFIG['vcodec'][$_REQUEST['vcodec']]);
	$b = escapeshellcmd(intval($_REQUEST['b']));
	$ar = escapeshellcmd(intval($_REQUEST['ar']));
	$ac = escapeshellcmd(intval($_REQUEST['ac']));
	$ab = escapeshellcmd(intval($_REQUEST['ab']));
	$acodec = escapeshellcmd($CONFIG['acodec'][$_REQUEST['acodec']]);
	$extension = escapeshellcmd($CONFIG['extension'][$vcodec]);
	$target = escapeshellcmd($CONFIG['target'][$_REQUEST['target']]);
	$targettype = escapeshellcmd($CONFIG['targettype'][$_REQUEST['targettype']]);
	
	$sourcefile = 'upload/'.$HTTP_POST_FILES['upload']['name'];
	copy($HTTP_POST_FILES['upload']['tmp_name'], $sourcefile);
	
	if (!empty($_REQUEST['target'])) {
		$extension = escapeshellcmd($CONFIG['extension'][$target]);
		$targetfile = 'download/'.$HTTP_POST_FILES['upload']['name'].$extension;
		echo "Transcoding file to $targetfile\n";
		$command = "ffmpeg -y -i ".escapeshellarg($sourcefile)." -target {$targettype}-{$target} ".escapeshellarg($targetfile);
	} else {
		$targetfile = 'download/'.$HTTP_POST_FILES['upload']['name'].$extension;
		echo "Transcoding file to $targetfile\n";
		$command = "ffmpeg -y -i ".escapeshellarg($sourcefile)." -s {$width}x{$height} -r 25 -vcodec {$vcodec} -b {$b}k -ar {$ar} -ac {$ac} -ab {$ab}k -acodec {$acodec} ".escapeshellarg($targetfile);
	}
	
	//echo "FFMPEG Command: $command\n";
	$output = array();
	exec($command, $output);
	unlink($sourcefile);
	echo "Download <a href='$targetfile'>here</a>\n";
	echo "</pre>";
}
?>

<script type="text/javascript">
	var template = new Array();
	template[1] = new Array();
	template[2] = new Array();
	template[3] = new Array();

	template[1]['vcodec'] = 4;
	template[1]['acodec'] = 0;
	template[1]['ac'] = 0;
	template[1]['width'] = 352;
	template[1]['height'] = 188;
	template[1]['b'] = 1150;
	template[1]['ar'] = 44100;
	template[1]['ab'] = 128;
	
	template[2]['vcodec'] = 3;
	template[2]['acodec'] = 4;
	template[2]['ac'] = 1;
	template[2]['width'] = 176;
	template[2]['height'] = 144;
	template[2]['b'] = 128;
	template[2]['ar'] = 8000;
	template[2]['ab'] = 16;

	template[3]['vcodec'] = 2;
	template[3]['acodec'] = 5;
	template[3]['ac'] = 0;
	template[3]['width'] = 352;
	template[3]['height'] = 288;
	template[3]['b'] = 1150;
	template[3]['ar'] = 44800;
	template[3]['ab'] = 128;

	function getTemplate(id) {
		document.getElementById('acodec').selectedIndex = template[id]['acodec'];
		document.getElementById('vcodec').selectedIndex = template[id]['vcodec'];
		document.getElementById('ac').selectedIndex = template[id]['ac'];
		
		document.getElementById('width').value = 	template[id]['width'];
		document.getElementById('height').value = 	template[id]['height'];
		document.getElementById('b').value = 	template[id]['b'];
		document.getElementById('ar').value = 	template[id]['ar'];
		document.getElementById('ab').value = 	template[id]['ab'];
	}

</script>

<form name="submit_file" action="video.php" method="post" enctype="multipart/form-data">
	<table>
		<tr>
			<th colspan="4">Zielformat w&auml;hlen</th>
		</tr>
		<tr>
			<td>Zielformat</td>
			<td colspan="3"><select name="target">
				<option value="">selber bestimmen (siehe unten)</option>
				<option value="1">VCD</option>
				<option value="2">SVCD</option>
				<option value="3">DVD</option>
				<option value="4">DV</option>
				<option value="5">DV50</option>
			  </select>
			  <select name="targettype">
				<option value="1">PAL</option>
				<option value="2">NTSC</option>
				<option value="3">FILM</option>
			  </select>
			</td>
		<tr>
			<th colspan="4">Zielformat selber bestimmen (Expertenfunktion)</th>
		</tr>
		<tr>
			<td>Vorlage verwenden</td>
			<td colspan="3"><select onChange="getTemplate(this.selectedIndex);">
				<option value="">Wähle Vorlage</option>
				<option value="0">Shockwave Flash</option>
				<option value="1">3gp</option>
				<option value="2">MP4</option>
			</select>
		</tr>
		<tr>
			<td>Breite</td>
			<td><input type="text" name="width" id="width" value="352"/></td>
			<td>H&ouml;he</td>
			<td><input type="text" name="height" id="height" value="288"/></td>
		</tr>
		<tr>
			<td>Videocodec</td>
			<td><select name="vcodec" id="vcodec">
					<option value="1">MPEG 1</option>
					<option value="2">MPEG 2</option>
					<option value="3">MPEG 4</option>
					<option value="4">H263</option>
					<option value="5">FLV</option>
			    </select></td>
			<td>Audiocodec</td>
			<td><select name="acodec" id="acodec">
					<option value="1">MP3</option>
					<option value="2">AC3</option>
					<option value="3">PCM</option>
					<option value="4">OGG</option>
					<option value="5">AMR</option>
					<option value="6">AAC</option>
			    </select>
			</td>
		</tr>
		<tr>
			<td>Videobitrate (kb)</td>
			<td><input type="text" name="b" id="b" value="1150"/></td>
			<td>Audiobitrate (kb)</td>
			<td><input type="text" name="ab" id="ab" value="128"/></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td>Samplingfrequenz</td>
			<td><input type="text" name="ar" id="ar" value="44100"/></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td>Kan&auml;le</td>
			<td><select name="ac" id="ac">
					<option value="2">stereo</option>
					<option value="1">mono</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="4">Bei der Videoerstellung ist nicht jede beliebige Kombination von Parametern m&ouml;glich.
				Diese Funktionen sollten nur von jemandem benutzt werden, der wei&szlig;, was er macht. Andernfalls
				kann die Erstellung des Videos fehlschlagen.</td>
		</tr>
		<tr>
			<th colspan="4">Datei</th>
		</tr>
		<tr>
			<td colspan="4"><input type="file" name="upload"/> (Maximale Dateigr&ouml;&szlig;e: <?=ini_get('upload_max_filesize');?>)</td>
		</tr>
		<tr>
			<td colspan="4"><input type="submit" name="submit" value="Transkodieren"/></td>
		</tr>
	</table>
</form>
<hr/>
<p><a href="index.php">Zurück zur Startseite</a> - <a href="audio.php">Audiotranskodierung/-extraktion</a></p>
</body>
</html>
