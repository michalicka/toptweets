TopTweets Twitter Client
========================

This is a sample application using the [Nette](https://nette.org) framework used to load top 100 tweets mentioning specific keywords.

Requirements
------------

- TopTweets app requires PHP 7.1


Installation
------------

The best way to install Web Project is using Composer. If you don't have Composer yet,
download it following [the instructions](https://doc.nette.org/composer). Then use command:

	composer create-project michalicka/toptweets path/to/install
	cd path/to/install


Make directories `temp/` and `log/` writable.

Update `app/config/local.neon` and add your Twitter API credentials. 
Sign-up to [Twitter Developer account](https://developer.twitter.com/) and create a new APP to get them. 
Then add and update following lines in your `app/config/local.neon` file:

```php
parameters:
	twitter:
		key: 'YOUR_TWITTER_API_KEY'
		secret: 'YOUR_TWITTER_API_KEYSECRET'

services:
	- DG\Twitter\Twitter( %twitter.key% , %twitter.secret% )
```


Dependencies
------------

TopTweets app uses [Nette](https://nette.org) 3.0 framework and [Twitter-php](https://github.com/dg/twitter-php) used to send API request to Twitter API.



Web Server Setup
----------------

The simplest way to get started is to start the built-in PHP server in the root directory of your project:

	php -S localhost:8000 -t www

Then visit `http://localhost:8000` in your browser to see TopTweets web client where you can search for tweets. Use [Twitter API seach format](https://developer.twitter.com/en/docs/twitter-api/v1/rules-and-filtering/guides/using-premium-operators) to search for other keywords and phrases.

For Apache or Nginx, setup a virtual host to point to the `www/` directory of the project and you
should be ready to go.

**It is CRITICAL that whole `app/`, `log/` and `temp/` directories are not accessible directly
via a web browser. See [security warning](https://nette.org/security-warning).**

There is also a JSON endpoint you can use to load raw API data and use it in your app at this page:

	http://localhost:8000/tweets/json

Available http query parameters:
- `q` - url encoded Twitter API search query
- `max` - number of records to return (max 100)

Example:

	http://localhost:8000/tweets/json?q=hello&max=10