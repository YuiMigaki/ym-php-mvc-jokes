<?php
/**
 * Header display page
 *
 * This file is to display header
 *
 * Filename:        header.view.php
 * Location:        /App/views/partials
 * Project:         ym-php-mvc-jokes
 * Date Created:    23/08/2024
 *
 * Author:          Yui Migaki <20098757@tafe.wa.edu.au>
 *
 */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $pageTitle ?? "Joker" ?></title>
    <link rel="stylesheet" href="/assets/css/site.css">
    <!-- SimpleMDE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <!-- SimpleMDE JS -->
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
</head>
<body class="bg-zinc-800 flex flex-col h-screen justify-between">

