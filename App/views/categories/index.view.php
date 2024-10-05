<?php
/**
 * Category index page
 *
 * This file is to show list of all the categories on index page
 *
 * Filename:        index.view.php
 * Location:        /App/views/categories
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

$pageTitle ="Index | Categories | YM-MVC-Jokes";

loadPartial("header", ["pageTitle"=>$pageTitle]);loadPartial('navigation');

?>

<main class="container mx-auto bg-zinc-50 py-8 px-4 shadow shadow-black/25 rounded-b-lg flex flex-col flex-grow">
    <article>
        <header class="bg-red-700 text-zinc-200 -mx-4 -mt-8 p-8 mb-8 flex">
            <h1 class="grow text-2xl font-bold ">Categories</h1>
            <p class="text-md flex-0 px-8 py-2 bg-zinc-500 hover:bg-emerald-600 text-white rounded transition ease-in-out duration-500">
                <a href="/categories/create">Add Category</a>
            </p>
            <form method="GET" action="/categories/search" class="block mx-5">
                <input type="text" name="keywords" placeholder="Category search..."
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
                <p>Search Results for: <?= htmlspecialchars($keywords) ?> [<?= count($categories ?? []) ?> category(s) found]</p>
            <?php else : ?>
                <p>All Categories</p>
            <?php endif; ?>

            <?= loadPartial('message') ?>
        </section>

        <section class="flex flex-col gap-8 ">
            <?php
                foreach ($categories as $category):
                    ?>
                    <article class="w-full bg-white shadow rounded grid grid-cols-12">
                        <header class="col-span-4 bg-zinc-700 text-zinc-200 text-lg p-4 rounded-l flex-0">
                            <h4>
                                <?= $category->name ?>
                            </h4>
                        </header>
                        <section class="col-span-6 flex flex-row py-4 gap-4 text-zinc-600 justify-items-start">
                            <p class="mr-4 -my-4">
                                <img class="w-16 h-16 " src="https://dummyimage.com/200x200/c11111/fff&text=Image+Here"
                                     alt="Avatar for <?= $category->name ?>">
                            </p>

                        <p class="align-middle pl-5"> Created: <?= $category->created_at ?></p>
                        </section>
<!--                        <section class="flex-grow grid grid-cols-5">-->
<!--                            <p class="col-span-3 text-zinc-600">--><?php //= $joke->tags ?><!--</p>-->
<!--                        </section>-->
                        <a href="/categories/<?= $category->id ?>"
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
