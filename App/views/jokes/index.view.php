<?php
/**
 * Jokes index page
 *
 * This file is to show list of all the jokes on index page
 *
 * Filename:        index.view.php
 * Location:        /App/views/jokes
 * Project:         ym-php-mvc-jokes
 * Date Created:    6/09/2024
 *
 * Author:          Yui Migaki <20098757@tafe.wa.edu.au>
 *
 */

/* Load HTML header and navigation areas */
use Framework\Middleware\Authorise;

$authenticated = new Authorise();
if (!$authenticated->isAuthenticated()) {
    header('Location: /auth/login');
    exit;
}

$pageTitle = "Index | Jokes | YM-MVC-Jokes";

loadPartial("header", ["pageTitle"=>$pageTitle]);loadPartial('navigation');

?>

<main class="container mx-auto bg-zinc-50 py-8 px-4 shadow shadow-black/25 rounded-b-lg flex flex-col flex-grow">
    <article>
        <header class="bg-red-700 text-zinc-200 -mx-4 -mt-8 p-8 mb-8 flex">
            <h1 class="grow text-2xl font-bold ">Jokes</h1>
            <p class="text-md flex-0 px-8 py-2 bg-zinc-500 hover:bg-emerald-600 text-white rounded transition ease-in-out duration-500">
                <a href="/jokes/create">Add Joke</a>
            </p>
            <form method="GET" action="/jokes/search" class="block mx-5">
                <input type="text" name="keywords" placeholder="Joke search..."
                       class="w-full md:w-auto px-4 py-2 focus:outline-none text-black"/>
                <button class="w-full md:w-auto
                           bg-sky-500 hover:bg-sky-600
                           text-white
                           px-4 py-2
                           focus:outline-none transition ease-in-out duration-500">
                    <i class="fa fa-search"></i> Search
                </button>
            </form>
        </header>

        <section class="text-xl text-zinc-500 my-8">
            <?php if (isset($keywords) && $keywords>"") : ?>
                <p>Search Results for: <?= htmlspecialchars($keywords) ?> [<?= count($jokes ?? []) ?> joke(s) found]</p>
            <?php else : ?>
                <p>All Jokes</p>
            <?php endif; ?>

            <?= loadPartial('message') ?>
        </section>

        <section class="flex flex-row flex-wrap gap-8 justify-center ">
            <?php
                foreach ($jokes as $joke):
                    ?>
                    <article class="max-w-96 min-w-64 bg-white shadow rounded p-2 flex flex-col">
                        <header class="-mx-2 bg-zinc-700 text-zinc-200 text-lg p-4 -mt-2 mb-4 rounded-t flex-0 text-center">
                            <h4><strong>Title: </strong><?= $joke->title ?>

                            </h4>
                        </header>
                        <section class="flex-grow grid grid-cols-5">
                            <p class="ml-4 col-span-2">
                                <img class="w-24 h-24 " src="https://dummyimage.com/200x200/c11111/fff&text=Image+Here"
                                     alt="">
                            </p>
                            <p class="col-span-3 text-zinc-600 ml-4 mt-4"><strong>Category:</strong> <?= $joke->category_name ?></p>
                        </section>
<!--                        <section class="flex-grow grid grid-cols-5">-->
<!--                            <p class="col-span-3 text-zinc-600">--><?php //= $joke->tags ?><!--</p>-->
<!--                        </section>-->
                        <?php if(!empty($joke->tags)) : ?>
                            <p class="col-span-3 text-zinc-600 text-center mt-4">
                                <strong>Tags:</strong> <?= $joke-> tags ?>
                            </p>
                        <?php endif; ?>
                        <footer class="-mx-2 bg-zinc-200 text-zinc-900 text-sm px-4 py-1 mt-4 -mb-2 rounded-b flex-0">
                            <p class="block w-full text-center px-5 py-2.5 shadow-sm rounded border
                                              text-base font-medium text-zinc-700 bg-zinc-100">
                                Created by: <?= $joke->author_name ?>
                            </p>
                        </footer>
                        <br/>
                        <a href="/jokes/<?= $joke->id ?>"
                           class="col-span-2 text-center text-zinc-900 font-medium
                        bg-zinc-200 hover:bg-zinc-300 block
                        py-4 rounded-r
                        transition ease-in-out duration-500">
                            Details...
                        </a>
                    </article>

                <?php
                endforeach;?>
        </section>

    </article>
</main>


<?php
loadPartial("footer");
?>
