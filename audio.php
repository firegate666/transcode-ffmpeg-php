<?
    session_start();
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
<h3>MediaTranscoder - Audio</h3>
<pre>
<?php
if (isset($_REQUEST['submit'])) {
	$codec = 'ac3';
	switch ($_REQUEST['codec']) {
		case 'mp3': $codec='mp3';break;
		case 'wav': $codec='pcm_s16le';break;
		default: $_REQUEST['codec'] = 'ac3';
	}
	$sourcefile = 'upload/'.$HTTP_POST_FILES['upload']['name'];
	$targetfile = 'download/'.$HTTP_POST_FILES['upload']['name'].'.'.$_REQUEST['codec'];
	copy($HTTP_POST_FILES['upload']['tmp_name'], $sourcefile);
	echo "Transcoding file to $targetfile\n";
	system('ffmpeg -y -i '.escapeshellarg($sourcefile).' -acodec '.$codec.' -ar 44100 -ab 128k '.escapeshellarg($targetfile));
	unlink($sourcefile);
	echo "Download <a href='$targetfile'>here</a>\n";
}
?>
</pre>
<form name="submit_file" action="audio.php" method="post" enctype="multipart/form-data">
	<table>
		<tr>
			<th colspan="4">Zielformat w&auml;hlen</th>
		</tr>
		<tr>
			<td>Datei</td>
			<td><input type="file" name="upload"/></td>
			<td>Zielformat</td>
			<td><select name="codec">
					<option value="ac3">AC3</option>
					<option value="mp3">MP3</option>
					<option value="wav">WAV</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="4"><input type="submit" name="submit" value="Transkodieren"/></td>
		</tr>
	</table>
</form>
<hr/>
<p><a href="index.php">Zurück zur Startseite</a> - <a href="video.php">Videotranskodierung</a></p>
</body>
</html>
