Twitter-User-timeline-for-FuelPHP
=================================

Twitter user-timeline package for FuelPHP which uses OAuth. This package allows you to pull an array of a users latest tweets.

# Installation 
1. Add 'twitter' to your packages array in your config file. 
2. Download a copy of the twitter folder from this repo and place it within your packages folder. 
3. Modify the packages config file /packages/twitter/config/twitter.php 

# How to use 
In your controller just paste the following and insert the handle of the user you wish to grab tweets from (don't include the @ symbol), and the number of tweets you wish to have returned, the default is 5. 

$tweets = Twitter::fetchTweets("USERNAME", 5);

# Problems 
If you have any problems just let us know. Please feel free to contribute :)

