<?php
/**
 *   Assessment Title: AT2-POR-Pt2-MVC
 *   Cluster:          SaaS: Front-End Dev - ICT50220 (Advanced Programming)
 *   Qualification:    ICT50220 Diploma of Information Technology (Back End Web Development)
 *   Name:             Yui Migaki
 *   Student ID:       20098757
 *   Year/Semester:    2024/S2
 *
 *   MY SUMMARY OF PORTFOLIO ACTIVITY
 *   This portfolio activity involves implementing an online scratch course into a small MVC project that includes users, categories, and jokes.
 *
 *   Jokes create page
 *
 *   This file is to create jokes
 *
 *   Filename:        create.view.php
 *   Location:        /App/views/jokes
 *   Project:         ym-php-mvc-jokes
 *   Date Created:    6/09/2024
 *
 *   Author:          Yui Migaki <20098757@tafe.wa.edu.au>
 *
 */

$pageTitle = "Create | Jokes | YM-PHP-MVC-Jokes";

loadPartial("header", ["pageTitle" => $pageTitle]);
loadPartial('navigation');

?>

<main class="container mx-auto bg-zinc-50 py-8 px-4 shadow shadow-black/25 rounded-b-lg flex flex-col flex-grow">
    <article>
        <header class="bg-zinc-700 text-zinc-200 -mx-4 -mt-8 p-8 mb-8 flex">
            <h1 class="grow text-2xl font-bold ">Jokes - Add</h1>
            <p class="text-md flex-0 px-8 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded transition ease-in-out duration-500">
                <a href="/jokes/create">Add Joke</a>
            </p>
        </header>

        <section>

            <?= loadPartial('errors', [
                'errors' => $errors ?? []
            ]) ?>

            <form id="jokeForm" method="POST" action="/jokes">

                <h2 class="text-2xl font-bold mb-6 text-left text-gray-500">
                    Joke Information
                </h2>

                <section class="mb-4">
                    <label for="Title" class="mt-4 pb-1">Joke Title:</label>
                    <input type="text" id="Title"
                           name="title" placeholder="Title"
                           class="w-full px-4 py-2 border border-b-zinc-300 rounded focus:outline-none"
                           value="<?= $joke['title'] ?? '' ?>"/>
                </section>

                <section class="mb-4">
                    <label for="Joke" class="mt-4 pb-1">Joke Content:</label>
                    <textarea id="Joke"
                              name="joke" placeholder="Joke Content"
                              class="w-full px-4 py-2 border border-b-zinc-300 rounded focus:outline-none"><?= $joke['joke'] ?? '' ?>
                      </textarea>
                </section>


                <section class="mb-4">
                    <label for="CategoryName" class="mt-4 pb-1">Category:</label>
                    <input type="text" id="CategoryName"
                           name="category_name" placeholder="Category"
                           class="w-full px-4 py-2 border border-b-zinc-300 rounded focus:outline-none"
                           value="<?= $joke['category_name'] ?? '' ?>"/>
                </section>

                <section class="mb-4">
                    <label for="Tags" class="mt-4 pb-1">Tags:</label>
                    <input type="text" id="Tags"
                           name="tags" placeholder="Tags"
                           class="w-full px-4 py-2 border border-b-zinc-300 rounded focus:outline-none"
                           value="<?= $joke['tags'] ?? '' ?>"/>
                </section>


                <section class="mb-4">
                    <label for="AuthorName" class="mt-4 pb-1">Author:</label>
                    <input type="text" id="AuthorName"
                           name="author_name" placeholder="Author"
                           class="w-full px-4 py-2 border border-b-zinc-300 rounded focus:outline-none"
                           value="<?= $joke['author_name'] ?? '' ?>"/>
                </section>


                <button type="submit"
                        class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 my-3
                               rounded focus:outline-none">
                    Save
                </button>

                <a href="/jokes"
                   class="block text-center w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded
                      focus:outline-none">
                    Cancel
                </a>

            </form>

            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
                var simplemde = new SimpleMDE({
                    element: document.getElementById("Joke"),
                    spellChecker: false,
                    autofocus: true,
                    autosave: {
                        enabled: false,
                        uniqueId: "joke",
                        delay: 1000
                    }
                });

                document.getElementById('jokeForm').addEventListener('submit', function (event) {
                    event.preventDefault();
                    var jokeMarkdown = simplemde.value();
                    $.ajax({
                        url: '/jokes',
                        type: 'POST',
                        dataType: "json",
                        data: {
                            joke: jokeMarkdown,
                            title: document.getElementById('Title').value,
                            category_name: document.getElementById('CategoryName').value,
                            tags: document.getElementById('Tags').value,
                            author_name: document.getElementById('AuthorName').value,
                        },
                        success: function (response) {
                            if (response.success) {
                                console.log('Joke saved successfully');
                                window.location.href = '/jokes';
                            } else {
                                // Clear previous errors
                                $('.error-messages').remove();

                                // Handle validation errors
                                if (response.errors) {
                                    var errorHtml = `
                                                    <div class="flex w-full shadow-lg rounded-lg my-4 error-messages">
                                                        <div class="bg-red-600 py-2 px-6 rounded-l-lg flex items-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="fill-current text-white" width="20" height="20">
                                                                <path fill-rule="evenodd" d="M4.47.22A.75.75 0 015 0h6a.75.75 0 01.53.22l4.25 4.25c.141.14.22.331.22.53v6a.75.75 0 01-.22.53l-4.25 4.25A.75.75 0 0111 16H5a.75.75 0 01-.53-.22L.22 11.53A.75.75 0 010 11V5a.75.75 0 01.22-.53L4.47.22zm.84 1.28L1.5 5.31v5.38l3.81 3.81h5.38l3.81-3.81V5.31L10.69 1.5H5.31zM8 4a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 018 4zm0 8a1 1 0 100-2 1 1 0 000 2z"></path>
                                                            </svg>
                                                        </div>
                                                        <div class="px-4 py-2 bg-white rounded-r-lg flex flex-col justify-between items-left w-full border border-l-transparent border-gray-200">
                                                    `;

                                    // Append error messages
                                    for (const [field, message] of Object.entries(response.errors)) {
                                        errorHtml += `<div class="text-red-500">${message}</div>`;
                                    }

                                    // Close the error container
                                    errorHtml += `</div></div>`;

                                    // Append new error messages before the form
                                    $('#jokeForm').before(errorHtml);
                                }
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Error saving joke:', error);
                        },
                    });
                });
            </script>

        </section>
    </article>
</main>

<?php
loadPartial("footer");
?>
