<?php

/**
 * A wiki-like website script.
 *
 * This file brings several small libraries together to create a wiki-like website
 * powered by a flat-file storage. The idea is to be simple and effective. To
 * itterate on the code often. And to use it.
 *
 * PHP version 7
 *
 * @author    Martijn van der Ven <margijn@vanderven.se>
 * @copyright 2017 Martijn van der Ven
 * @license   https://opensource.org/licenses/MIT  MIT License
 * @link      https://github.com/Zegnat/wiki.zegnat.net
 */

require '../vendor/autoload.php';

use Zegnat\Http\ServerRequestFromGlobals;
use Nyholm\Psr7\Factory\StreamFactory;
use Nyholm\Psr7\Factory\ServerRequestFactory;
use League\Plates\Engine;
use OTPHP\TOTP;

// Normalise the request.
$request = (new ServerRequestFromGlobals(
    new ServerRequestFactory,
    new StreamFactory()
))->create();

// Block unexpected request methods.
if (!in_array($request->getMethod(), ['POST', 'HEAD', 'GET'])) {
    header('HTTP/1.1 405 Method Not Allowed');
    header('Allow: HEAD, GET, POST');
    die('Method not allowed.');
}

// Determine the page path and file path.
$path = '/' . trim($request->getUri()->getPath(), '/');
$file = '../storage' . ($path === '/' ? '/index' : $path) . '.md';

// Handle possible posts.
if ($request->getMethod() === 'POST') {
    $post = $request->getParsedBody();
    $secret = file_get_contents('../storage/secret.txt');
    $totp = new TOTP('Wiki', $secret, 30, 'sha512', 6);
    if (!$totp->verify($post['passcode'])) {
        header('HTTP/1.1 403 Forbidden');
        die('Forbidden.');
    }
    if (!is_dir(dirname($file))) {
        mkdir(dirname($file), 0777, true);
    }
    $content = preg_replace('%\R%u', "\n", $post['content']);
    if (substr($content, -1) !== "\n") $content .= "\n";
    file_put_contents($file, $content);
}

// Load the required file, if available.
if (file_exists($file)) {
    $missing = false;
    $raw = file_get_contents($file);
} else {
    $missing = true;
    $raw = file_get_contents('../storage/404.md');
}

// Extract a title from the file, if available.
$firstline = strstr($raw, "\n", true);
if (substr($firstline, 0, 2) === '# ') {
    $title = substr($firstline, 2);
} else {
    $title = 'Untitled';
}

// Check to see if we are supposed to be editting.
if (array_key_exists('edit', $request->getQueryParams())) {
    $template = 'edit';
    $body = $missing ? '' : $raw;
    $title = $missing ? 'Create new page' : $title;
    $missing = false;
} else {
    $template = 'main';
    $body = Parsedown::instance()->text($raw);
}

// Add appropriate header.
if ($missing) {
    header('HTTP/1.1 404 Not Found');
}

// Output the wiki.
echo (new Engine('../templates'))->render(
    $template,
    [
        'body' => $body,
        'title' => $title,
        'path' => $path,
    ]
);
