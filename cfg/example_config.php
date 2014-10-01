<?php
class Config{
	/**
	*	Development mode
	*/
	public static $DEV_MODE = true;
	
	/**
	*	Database settings
	*/
	public static $DB_CONNECTION_STRING = 'mysql:host=127.0.0.1;dbname=database_name_here';
	public static $DB_USERNAME = 'database_username_here';
	public static $DB_PASSWORD = 'database_password_here';
	
	/**
	*	Router settings
	*/
	public static $DEFAULT_CONTROLLER = 'login';
	public static $DEFAULT_ACTION = 'index';
}