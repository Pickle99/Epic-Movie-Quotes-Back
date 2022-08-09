# Movies Epic api
Epic Movie Quotes is a site where you can post a movie,
create a quote for it, edit and delete both the quote and the movie,
and you can like and comment on the quote in real-time, resulting in real-time notifications.

_________________________

- **[DrawSql diagram link](https://drawsql.app/teams/redberry-22/diagrams/movies-epic-diagram)**
____

## Table of Contents
<ul>
<li><a href="#prereq">Prerequisites</a></li>
<li><a href="#tech">Tech Stack</a></li>
<li><a href="#start">Getting Started</a></li>
<li><a href="#migrate">Migration</a></li>
<li><a href="#develop">Development</a></li>
</ul>

__________________________

### Prerequisites
<ul>
<li><p id=prereq>PHP@8.1 and up</p></li>
<li><p>MYSQL@8 and up</p></li>
<li><p>npm@6.1 and up</p></li>
<li><p>composer 2.3 and up</p></li>
</ul>

__________________

### Tech Stack

<ul>
<li id="tech"><a href="https://laravel.com/docs/9.x">Laravel@9.x</a> - back-end framework</li>
<li><a href="https://github.com/spatie/laravel-translatable">Spatie Translatable</a> - package for translation</li>
<li><a href="https://v2.tailwindcss.com/docs">Tailwind</a> - CSS framework</li>
<li><a href="https://laravel.com/docs/9.x/broadcasting">Laravel echo</a> - Real Time user updating interface</li>
<li><a href="https://laravel-jwt-auth.readthedocs.io/en/latest/laravel-installation/">JWT</a> - package for Token Based authorization</li>
<li><a href="https://laravel.com/docs/9.x/socialite">Socialite</a> - Laravel package for authorization from Google,Github etc.</li>
</ul>

______________________

### Getting Started
<p id="start">1. First of all you need to clone Movie Epic repository from github:</p>
With SSH:
<code>git@github.com:RedberryInternship/Chad-movie-quotes-back-Luka-Ramishvili.git </code>
With HTTPS:
<code>https://github.com/RedberryInternship/Chad-movie-quotes-back-Luka-Ramishvili.git</code>
<p>2. Next step requires you to run composer install in order to install all the dependencies.</p>
<code>composer install</code>
<p> 3. after you have installed all the PHP dependencies, it's time to install all the JS dependencies:</p>
<code>npm install</code>
<p>and also: npm run dev</p>
<p>in order to build your JS/SaaS resources.</p>
<p>4. Now we need to set our env file. Go to the root of your project and execute this command.</p>
<code>cp .env.example .env</code>
<p>And now you should provide .env file all the necessary environment variables:</p>


________


<h4>MYSQL:</h3>
DB_CONNECTION=mysql

DB_HOST=127.0.0.1

DB_PORT=3306

DB_DATABASE=*****

DB_USERNAME=*****

DB_PASSWORD=*****
______
<p>in order to cache environment variables</p>
<p>4. Now execute in the root of your project following:</p>
<code> php artisan key:generate</code>
<p>Which generates auth key.</p>
<h5>Now you should be good to go!</h5>





____________

### Migrations
<p id="migrate">if you've completed getting started section, then migrating database if fairly simple process, just execute:</p>
<code> php artisan migrate </code>


__________


### Development
<p id="develop">You can run Laravel's built-in development server by executing:</p>
<code>php artisan serve</code>
<p>when working on JS you may run:</p>
<code>npm run dev</code>
<p>it builds your js files into executable scripts. If you want to watch files during development, execute instead:</p>
<code>npm run watch</code>
<p>it will watch JS files and on change it'll rebuild them, so you don't have to manually build them.</p>

_____
