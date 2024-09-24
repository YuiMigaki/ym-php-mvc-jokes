<?php
/**
 * FILE TITLE GOES HERE
 *
 * DESCRIPTION OF THE PURPOSE AND USE OF THE CODE
 * MAY BE MORE THAN ONE LINE LONG
 * KEEP LINE LENGTH TO NO MORE THAN 96 CHARACTERS
 *
 * Filename:        index.view.php
 * Location:        ${FILE_LOCATION}
 * Project:         XXX-PHP-MVC-Jokes
 * Date Created:    DD/MM/YYYY
 *
 * Author:          YOUR NAME <STUDENT_ID@tafe.wa.edu.au>
 *
 */

/* Load HTML header and navigation areas */
$pageTitle = "Users | YM-PHP-MVC-Jokes";

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

        <section class="flex flex-row gap-8 ">
            <?php
                foreach ($jokes as $joke):
                    ?>
                    <article class="max-w-96 min-w-64 bg-white shadow rounded p-2 flex flex-col">
                        <header class="-mx-2 bg-zinc-700 text-zinc-200 text-lg p-4 -mt-2 mb-4 rounded-t flex-0">
                            <h4>
                                <?= $joke->category_name ?>
                            </h4>
                        </header>
                        <section class="flex-grow grid grid-cols-5">
                            <p class="ml-4 col-span-2">
                                <img class="w-24 h-24 " src="https://dummyimage.com/200x200/a1a1aa/fff&text=Image+Here"
                                     alt="">
                            </p>
                            <p class="col-span-3 text-zinc-600"><?= $joke->joke ?></p>
                        </section>
<!--                        <section class="flex-grow grid grid-cols-5">-->
<!--                            <p class="col-span-3 text-zinc-600">--><?php //= $joke->tags ?><!--</p>-->
<!--                        </section>-->
                        <?php if(!empty($joke->tags)) : ?>
                            <section class="col-span-3 text-zinc-600 text-center">
                                Tags: <?= $joke-> tags ?>
                            </section>
                        <?php endif; ?>
                        <footer class="-mx-2 bg-zinc-200 text-zinc-900 text-sm px-4 py-1 mt-4 -mb-2 rounded-b flex-0">
                            <p class="block w-full text-center px-5 py-2.5 shadow-sm rounded border
                                              text-base font-medium text-zinc-700 bg-zinc-100 hover:bg-zinc-200">
                                Added by: <?= $joke->author_name ?>
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
