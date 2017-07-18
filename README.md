# laravel-changelog
Change Log for Laravel 5
Inspired by https://github.com/gazugafan/laravel-changelog

1. [Features](#features)
2. [Installation](#installation)
3. [Usage](#usage)

----
<a id="features"></a>
## Features
Log each event of creating,saving,deleting for the model choosing.

You will have user_id, model context, id of model event, information of data modified

<a id="installation"></a>
## Installation

In your project base directory run

    composer require "dlouvard/laravel-laravel-changelog":"master@dev"
    
To bring up the config file run, if you want to customize

	php artisan vendor:publish
	
## Important
	Configuration of config/changelog.php to choose which table was impacted
	then:
	php artisan migrate
	
Then edit `config/app.php` and add the service provider within the `providers` array.
   
  	'providers' => array(
  		    ...
   		    Dlouvard\Changelog\ChangelogServiceProvider::class,
    
    'aliases' => array(
            ...
            'Change' => Dlouvard\Changelog\Facades\Change::class
            
<a id="usage"></a>
## Usage 
Install the Changelog

    use Dlouvard\Changelog\Changelog; 
    class `model` extends Model{
        use Changelog
        public $refColumn = []; //array to save in the table 'changes' some informations when deleting for example
    

