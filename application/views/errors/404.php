<!DOCTYPE html>
<html>
<head>
    <title>Edwards Homepage</title>
    <link rel="stylesheet" type="text/css" href="/styles/GGS.css">
</head>
<body class="wrapper">
<h1>404 - File not found</h1>

<p>Leider kann die von ihnen gestellte Anfrage aufgrund eines 404 Fehlers nicht bearbeitet werden.</p>
<?php
if (DEBUG) self::printMessage($exception);
?>
<p><a href="/">Homepage von Edward Gerhold</a></p>
<hr/>
<h1>Search my Stuff&trade;</h1>

<h2>Alternative</h2>

<p>Durchsuchen sie den Index von http://linux-swt.de/ mit einer Google Custom Search Engine.</p>

<div id="cse" style="width: 100%;">Loading</div>
<script src="http://www.google.com/jsapi" type="text/javascript"></script>
<script>
    google.load('search', '1', {language: 'de'});
    google.setOnLoadCallback(function () {
        var customSearchOptions = {};
        var customSearchControl = new google.search.CustomSearchControl(
            '009263426696692172493:wmu1r4nikdw', customSearchOptions);
        customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
        customSearchControl.draw('cse');
    }, true);
</script>
<link rel="stylesheet" href="http://www.google.com/cse/style/look/default.css" type="text/css"/>
<?php if (DEBUG): ?>
    <pre><?php print_r($exception); ?></pre>
<?php endif; ?>
</body>
</html>
