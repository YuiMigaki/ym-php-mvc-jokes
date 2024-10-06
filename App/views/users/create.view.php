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
 *   Users create page
 *
 *   This file is to create users
 *
 *   Filename:        create.view.php
 *   Location:        /App/views/users
 *   Project:         ym-php-mvc-jokes
 *   Date Created:    6/09/2024
 *
 *   Author:          Yui Migaki <20098757@tafe.wa.edu.au>
 *
 */

$pageTitle = "Create | Users | YM-PHP-MVC-Jokes";

loadPartial("header", ["pageTitle" => $pageTitle]);
loadPartial('navigation');

?>

    <main class="container mx-auto bg-zinc-50 py-8 px-4 shadow shadow-black/25 rounded-b-lg flex flex-col flex-grow">
        <article>
            <header class="bg-zinc-700 text-zinc-200 -mx-4 -mt-8 p-8 mb-8 flex">
                <h1 class="grow text-2xl font-bold ">Users - Add</h1>
                <p class="text-md flex-0 px-8 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded transition ease-in-out duration-500">
                    <a href="/users/create">Add User</a>
                </p>
            </header>

            <section>

                <?= loadPartial('errors', [
                    'errors' => $errors ?? []
                ]) ?>

                <form method="POST" action="/users">

                    <h2 class="text-2xl font-bold mb-6 text-left text-gray-500">
                        User Information
                    </h2>

                    <section class="mb-4">
                        <label for="GivenName" class="mt-4 pb-1">Given Name:</label>
                        <input type="text" id="GivenName"
                               name="given_name" placeholder="Given Name"
                               class="w-full px-4 py-2 border border-b-zinc-300 rounded focus:outline-none"
                               value="<?= $user['given_name'] ?? '' ?>"/>
                    </section>

                    <section class="mb-4">
                        <label for="Nickname" class="mt-4 pb-1">Nickname:</label>
                        <input type="text" id="Nickname"
                               name="nickname" placeholder="Nickname"
                               class="w-full px-4 py-2 border border-b-zinc-300 rounded focus:outline-none"
                               value="<?= $user['nickname'] ?? ($user['given_name'] ?? '') ?>"/>
                    </section>

                    <section class="mb-4">
                        <label for="FamilyName" class="mt-4 pb-1">Family Name:</label>
                        <input type="text" id="FamilyName"
                               name="family_name" placeholder="Family Name"
                               class="w-full px-4 py-2 border border-b-zinc-300 rounded focus:outline-none"
                               value="<?= $user['family_name'] ?? '' ?>"/>
                    </section>


                    <section class="mb-4">
                        <label for="Email" class="mt-4 pb-1">Email:</label>
                        <input type="email" id="Email"
                               name="email" placeholder="Email Address"
                               class="w-full px-4 py-2 border border-b-zinc-300 rounded focus:outline-none"
                               value="<?= $user['email'] ?? '' ?>"/>
                    </section>

                    <section class="mb-4">
                        <label for="Password" class="mt-4 pb-1">Password:</label>
                        <input type="password" id="Password"
                               name="user_password" placeholder="Password"
                               class="w-full px-4 py-2 border border-b-zinc-300 rounded focus:outline-none"/>
                    </section>

                    <section class="mb-4">
                        <label for="PasswordConfirmation" class="mt-4 pb-1">Confirm password:</label>
                        <input type="password" id="PasswordConfirmation"
                               name="confirm_password" placeholder="Confirm Password"
                               class="w-full px-4 py-2 border border-b-zinc-300 rounded focus:outline-none"/>
                    </section>

                    <button type="submit"
                            class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 my-3
                               rounded focus:outline-none">
                        Save
                    </button>

                    <a href="/users"
                       class="block text-center w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded
                      focus:outline-none">
                        Cancel
                    </a>

                </form>

            </section>

        </article>
    </main>


<?php
loadPartial("footer");

