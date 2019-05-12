# Rate Me plugin for Craft CMS 3.x

Simple Rating plugin

![Screenshot](resources/img/plugin-logo.png)

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require ournameismud/rate-me

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Rate Me.

## Rate Me Overview

-Insert text here-

## Configuring Rate Me

-email confirmation
-logged in 


## Using Rate Me

### Sample form

	<form method="POST">
		<input type="hidden" name="action" value="rate-me/default/rate" />
		<input type="hidden" name="element" value="{{ elementId }}" />
		{{ csrfInput() }}
		{% set rating = craft.rateMe.getRating( elementId ) %}
		<fieldset>
			<legend>Rate Me</legend>
			<label for="rating">Rating</label>
			<select name="rating" id="rating">
				{% for i in 1..5 %}
				<option {{ rating == i ? 'selected' }} value="{{ i }}">{{ i }}</option>
				{% endfor %}
			</select>	
			<button" type="submit">{{'Submit'|t}}</button>
		</fieldset>
	</form>

### Variable

Pass element id into the following: `{{ craft.rateMe.getRating( elementId ) }}` to get the rating for that user (if not logged in then will generate an anonymous session ID)

To get average ratings for a particular element then use the following: `{% set average = craft.rateMe.getAverage( elementId ) %}`. This will return an object with count and average rating, eg `{{ average.rating }}` and `{{ average.count }}`. You can use the `|number_format` twig filter to round the rating.



## Rate Me Roadmap

Some things to do, and ideas for potential features:

* Release it

Brought to you by [@cole007](http://ournameismud.co.uk/)
Icon rate by Rose Alice Design from [the Noun Project](https://thenounproject.com/search/?q=rate&i=1966416)