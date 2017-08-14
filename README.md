# __Trivago Case Study - Marcos Goñalons__

## Database model
---
_(This model was done using mysql-workbench. The types may differ for SQLite)_
![Graph](https://drive.google.com/uc?export=download&id=0B1HUczm7Goblb01NMEUwTmpBczA)

##### __Reviews__
Stores all the reviews and their total scores.
If the review is yet to be analyzed, then total_score will be NULL.

##### __Topics__
Stores all the main topics. For example: hotel, bar, pool, staff, etc.
The priority is used for detecting the sentence topic when more than one topic is found in the sentence.
For example: _"The location of the hotel is great"_.
Since the topic "location" has more priority than the topic "hotel", it will assign correctly the criteria "great" to the topic "location"

##### __Topics aliases__
Aliases for referring to the same topic.
Example: the aliases "lavatory", "shower", "toilet" refer to the main topic "bathroom"

##### __Criteria__
The words (or group of words) with their score.
By default, all positive criteria have a score of 100, and the negative criteria a score of -100.
This way the score can be tweaked depending on the criteria.

##### __Emphasizers__
Words that emphasizes the criteria, with their score modifier.
For example, "very", "really", "most", etc. By default, all the score modifiers are 0.5.
So, if found "very good", then the total score will be 150 (100 + (0.5 * 100))

##### __Analysis__
Stores the score for a given topic for a given review.

##### __Analysis criteria__
Stores the found criteria (along with the emphasizer, if any, and with the negated flag if the criteria found is negated) for a given analysis (an analysis is the score for a given topic).



## Dependencies and extensions
---
- PHP 7.1
- SQLite with php7.1-sqlite3 extension
- php7.1-pspell extension for TypoFixer (optional)
- NodeJS (needed for Yarn package manager)
- Yarn for managing frontend dependencies
- Composer for managing backend dependencies
- php7.1-xdebug extension for phpunit code coverage (optional)
- Symfony requirements extensions
    - php7.1-mbstring
    - php7.1-xml
    - php7.1-intl (optional)


#### Backend composer dependencies (apart from symfony default ones)
- ICanBoogie/Inflector -> Used for pluralizing words
- phpunit/phpunit -> For running the tests
- jms/serializer-bundle -> For serializing doctrine entities into JSON

#### Frontend yarn dependencies (web/package.json)
- Bootstrap
- JQuery (with jsgrid and json-viewer plugins)
- Webpack (with css-loader)
 

## Setup
---
Make sure that PHP7.1 with the dependencies and extensions shown above are correctly installed.
You can now clone the repository in a folder of your choice
- _git clone git@github.com:m-gonalons-camps/trivago-case-study.git FOLDER_NAME_

or unzip the file with the code (which contains the cloned repository) in a folder of your choice.
In the root folder you will find the file "bash-setup.sh". This bash script
.....

Important: you will notice that assetic bundle is not installed, and therefore we don't execute the command "php bin/console assetic:dump".
I prefered to use webpack for bundling JS and CSS.
