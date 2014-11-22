<!DOCTYPE html>
<html>
<head>
    <title>Moss / <?= $method ?></title>
    <style>
        body, code { font: medium/1.4em monospace; }
        code { display: block; background: #eee; }
        small { color: #696; font-size: small; }
        .error { color: red; }
    </style>
</head>
<body>
<h1><?= $method ?> <small>&lt;-- this is namespaced controller class and its currently executed action</small></h1>

<p>Moss sample controller and <a href="<?= $url('source') ?>">it looks like this</a></p>

<p>Sample app uses <em>plain PHP</em> templates by default, but also includes
    <a href="http://twig.sensiolabs.org/">Twig</a> templates for <var>moss/bridge</var></p>

<p>This can be easily changed, just <a href="?view=twig">click here</a> and app will use
    <var>moss/bridge</var> with Twig templates.</p>

<p><small>This view is rendered from <strong>PHP template</strong></small></p>

</body>
</html>
