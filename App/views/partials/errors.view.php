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
 *   Error display page
 *
 *   This file is to display errors
 *
 *   Filename:        errors.view.php
 *   Location:        /App/views/partials
 *   Project:         ym-php-mvc-jokes
 *   Date Created:    23/08/2024
 *
 *   Author:          Yui Migaki <20098757@tafe.wa.edu.au>
 *
 */

if (isset($errors) && count($errors) > 0): ?>
    <div class="flex w-full shadow-lg rounded-lg my-4">
        <div class="bg-red-600 py-2 px-6 rounded-l-lg flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" class="fill-current text-white" width="20"
                 height="20">
                <path fill-rule="evenodd"
                      d="M4.47.22A.75.75 0 015 0h6a.75.75 0 01.53.22l4.25 4.25c.141.14.22.331.22.53v6a.75.75 0
                      01-.22.53l-4.25 4.25A.75.75 0 0111 16H5a.75.75 0 01-.53-.22L.22 11.53A.75.75 0 010
                      11V5a.75.75 0 01.22-.53L4.47.22zm.84 1.28L1.5 5.31v5.38l3.81 3.81h5.38l3.81-3.81V5.31L10.69
                      1.5H5.31zM8 4a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 018 4zm0 8a1 1 0
                      100-2 1 1 0 000 2z">
                </path>
            </svg>
        </div>
        <div class="px-4 py-2 bg-white rounded-r-lg flex flex-col justify-between items-left w-full
                    border border-l-transparent border-gray-200">
            <?php
            foreach ($errors as $error) : ?>

                <p>
                    <?= $error ?>
                </p>
            <?php
            endforeach;
            ?>
        </div>
    </div>
<?php
endif;