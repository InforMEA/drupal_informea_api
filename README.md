# InforMEA API helper for Drupal Views

This is a Drupal 8/9 module containing useful field formatters and views processors to help MEAs expose their data for InforMEA.

## API Documentation

The final views should implements the structure available here:

https://app.swaggerhub.com/apis/eaudeweb-drupal/InforMEA-D8-API/2.0

## Steps to install

1. Enable module
2. Create your REST export views
3. Use the InforMEA serializer instead of the default serializer
4. Add fields and formatters

## Available formatters

### File formatters

- file URLs in all languages, keyed by translation langcode
- file summaries in all languages, keyed by translation langcode
- image URL formatter
- image ALT formatter

### Boolean formatters

- basic boolean formatter
- boolean formatter for Virtual Meeting - compare a string or list (text) field against a configurable value, usually "virtual" or "online"

### String formatters

- string value in all languages, keyed by translation langcode
- decision number formatter - removes "decision from a string", useful when the decision number is something like "Decision 123/44" (end result will be 123/44)
- list (text) field labels array
- string to boolean (transform "yes" and "no" into boolean)

### Entity reference formatters

- Referenced entity label in all languages, keyed by translation langcode
- keywords formatter - an array of all referenced terms with their label and URL

### Date formatters

- Date range formatter to Y-m-d (uses end date, defaults to start date if end date is not available)
- Meeting status formatter

### General formatters

- NULL formatter - always returns NULL :)
- Treaty/treaties formatter - transform entity ID into a static string/array with your treaty ID
