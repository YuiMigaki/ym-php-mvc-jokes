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
 *   About Page View
 *
 *   This file is to display about page information
 *
 *   Filename:        about.view.php
 *   Location:        /App/views
 *   Project:         ym-php-mvc-jokes
 *   Date Created:    6/09/2024
 *
 *   Author:          Yui Migaki <20098757@tafe.wa.edu.au>
 *
 */

loadPartial('header');
loadPartial('navigation');

?>

<main class="container mx-auto bg-zinc-50 py-8 px-4 shadow shadow-black/25 rounded-b-lg">
    <article>
        <header class="bg-zinc-700 text-zinc-200 -mx-4 -mt-8 p-8 text-2xl font-bold mb-8">
            <h1>About This Website</h1>
        </header>

        <section class="mx-auto w-1/2 m-8 bg-zinc-200 text-sm  text-zinc-800 p-4 rounded-lg shadow">
            <header class="-mx-4 bg-zinc-700 text-zinc-200 text-md text-semibold p-4 -mt-4 mb-4 rounded-t flex-0">
                <h2>
                    Developer's Information
                </h2>
            </header>
            <dl class="grid grid-cols-5 gap-2">
                <dt class="col-span-1">Name:</dt>
                <dd class="col-span-4">
                    <p>Yui Migaki</p>
                </dd>
                <dt class="col-span-1">Email Address:</dt>
                <dd class="col-span-4">
                    <p>20098757@tafe.wa.edu.au</p>
                </dd>
                <dt class="col-span-1">Position:</dt>
                <dd class="col-span-4">
                    <p> Junior Web Application Developer</p>
                </dd>
                <dt class="col-span-1">Company:</dt>
                <dd class="col-span-4">
                    <p>RIoT Systems</p>
                </dd>
            </dl>
        </section>

        <section class="mx-auto w-1/2 m-8 bg-zinc-200 text-sm  text-zinc-800 p-4 rounded-lg shadow">
            <header class="-mx-4 bg-zinc-700 text-zinc-200 text-md text-semibold p-4 -mt-4 mb-4 rounded-t flex-0">
                <h2>
                    A Brief Overview of the Application
                </h2>
            </header>
            <p>This application is a simple web application using PHP and elements of the MVC development methodology
                owned by RIoT Systems (Robotics & Internet of Things), a Perth based educational and development company
                who specialise in IoT, Robotics and Web Application systems.</p>
        </section>

        <section class="mx-auto w-1/2 m-8 bg-zinc-200 text-sm  text-zinc-800 p-4 rounded-lg shadow">
            <header class="-mx-4 bg-zinc-700 text-zinc-200 text-md text-semibold p-4 -mt-4 mb-4 rounded-t flex-0">
                <h2>
                    Details of the Programming Language and Servers
                </h2>
            </header>
            <dl class="grid grid-cols-5 gap-2">
                <dt class="col-span-1">Programming Language:</dt>
                <dd class="col-span-4">
                    <p><strong>PHP</strong></p>
                    <p>
                        Stands for Hypertext Preprocessor. It is an open-source programming language designed for
                        developing website.</p>
                    <a href="https://www.php.net/manual/en/intro-whatis.php"
                       class="underline underline-offset-2 text-zinc-900 rounded border-2 border-transparent hover:text-white hover:bg-blue-500 hover:border-blue-500">
                        https://www.php.net/manual/en/intro-whatis.php
                    </a>

                </dd>
                <dt class="col-span-1">Servers:</dt>
                <dd class="col-span-4">
                    <p><strong>XAMPP and WAMP</strong></p>
                    <p>These are the most popular PHP web servers for the Windows operating system. They are lightweight
                        and make it easy to host applications locally on a Windows machine.</p>
                </dd>
            </dl>
        </section>

        <section class="mx-auto w-1/2 m-8 bg-zinc-200 text-sm  text-zinc-800 p-4 rounded-lg shadow">
            <header class="-mx-4 bg-zinc-700 text-zinc-200 text-md text-semibold p-4 -mt-4 mb-4 rounded-t flex-0">
                <h2>
                    Useful References
                </h2>
            </header>
            <dl class="grid grid-cols-5 gap-2">
                <dt class="col-span-1">Tutorial Part 1:</dt>
                <dd class="col-span-4">
                    <a href="https://github.com/AdyGCode/SaaS-FED-Notes/tree/main/session-07"
                       class="underline underline-offset-2 text-zinc-900 rounded border-2 border-transparent hover:text-white hover:bg-blue-500 hover:border-blue-500">
                        https://github.com/AdyGCode/SaaS-FED-Notes/tree/main/session-07
                    </a>
                </dd>
                <dt class="col-span-1">Tutorial Part 2:</dt>
                <dd class="col-span-4">
                    <a href="https://github.com/AdyGCode/SaaS-FED-Notes/tree/main/session-07"
                       class="underline underline-offset-2 text-zinc-900 rounded border-2 border-transparent hover:text-white hover:bg-purple-500 hover:border-purple-500">
                        https://github.com/AdyGCode/SaaS-FED-Notes/tree/main/session-07
                    </a>
                </dd>
                <dt class="col-span-1">Source Code:</dt>
                <dd class="col-span-4">
                    <a href="https://github.com/AdyGCode/XXX-PHP-MVC-Jokes-Demo"
                       class="underline underline-offset-2 text-zinc-900 rounded border-2 border-transparent hover:text-white hover:bg-red-500 hover:border-red-500">
                        https://github.com/AdyGCode/XXX-PHP-MVC-Jokes-Demo
                    </a>
                </dd>
                <dt class="col-span-1">HelpDesk</dt>
                <dd class="col-span-4">
                    <a href="https://help.screencraft.net.au"
                       class="underline underline-offset-2 text-zinc-900 rounded border-2 border-transparent hover:text-white hover:bg-orange-500 hover:border-orange-500">
                        https://help.screencraft.net.au
                    </a>
                </dd>
                <dt class="col-span-1">HelpDesk FAQs</dt>
                <dd class="col-span-4">
                    <a href="https://help.screencraft.net.au/hc/2680392001"
                       class="underline underline-offset-2 text-zinc-900 rounded border-2 border-transparent hover:text-white hover:bg-amber-500 hover:border-amber-500">
                        https://help.screencraft.net.au/hc/2680392001
                    </a>
                </dd>
                <dt class="col-span-1">Make a Request</dt>
                <dd class="col-span-4">
                    <a href="https://help.screencraft.net.au/help/2680392001"
                       class="underline underline-offset-2 text-zinc-900 rounded border-2 border-transparent hover:text-white hover:bg-lime-500 hover:border-lime-500">
                        https://help.screencraft.net.au/help/2680392001</a>
                    (TAFE Students only)
                </dd>
            </dl>

        </section>

    </article>
</main>


<?php
loadPartial('footer');
?>
