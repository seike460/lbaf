lbaf
====

## Description
* line bot api framework
* By you to edit the file of the app folder, easy product that is the goal that can be handled by the line bot api.

## Requirement
* php5.4 & mysql & apache2
* you can use my Ansible-playbook
 - https://github.com/seike460/playbook/blob/master/setup-lamp-php5.yml
* (I want to use the more fast storage and more fast WebServer.Please wait.)

## Usage
* 1. setup line bot api And set CallBackURL at webroot/line_callback.php 
* 2. edit config/lineBot.php & config/storage.php
* 3. webroot/line_callback.php is queuing MessageJson
* 4. cd bin; php lbaf_response_daemon.php ; this script process Message;
 - (Since I plan to change this php correctly deamonprogram, please wait for a while)

## Install
* git clone https://github.com/seike460/lbaf.git
* please Create Table your Databases
* CREATE TABLE line_api_json (
  id int8 AUTO_INCREMENT,
  json text,
  constraint pkey_line_api_json primary key (
    id
  )
);

## Contribution
* Since the idea later, please wait a little.

## Licence
* [MIT] https://en.wikipedia.org/wiki/MIT_License

## Author
* [seike460](https://github.com/seike460)
