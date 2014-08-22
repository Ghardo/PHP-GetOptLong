<?php

require_once 'Cli/GetOptLong.php';

$opts = new Cli\GetOptLong(array(
	array(
		// --help  | -h
		'help', 'h', Cli\GetOptLong::ARGUMENT_NONE
	),
	array(
		// --file <arg>
		'file', null, Cli\GetOptLong::ARGUMENT_REQUIRED
	)
));

$opts->args(
	array(

		// --param [<arg>] |-p [<arg>]
		array('param', 'p', Cli\GetOptLong::ARGUMENT_OPTIONAL),

		// --foo [<arg>] |-f [<arg>]
		array('foo', 'f', Cli\GetOptLong::ARGUMENT_OPTIONAL)
	)
);

// -v [<arg>]
$opts->arg(null, 'v', Cli\GetOptLong::ARGUMENT_OPTIONAL);

// check option has given with has() or exists()
if ($opts->has('help')) {
	print "help here" . PHP_EOL;
}

if ($opts->has('file')) {
	print "File: " . $opts->file . PHP_EOL;
}

if ($opts->has('v')) {
	// optional returns null if no value given
	print 'v ' . $opts->v . PHP_EOL;
}

if ($opts->has('param')) {
	// optional returns null if no value given
	print 'param ' . $opts->param . PHP_EOL;
}

if ($opts->has('foo')) {
	// optional returns null if no value given
	print 'foo ' . $opts->foo . PHP_EOL;
}

