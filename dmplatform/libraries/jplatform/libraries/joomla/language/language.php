<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Language
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;
define('JPATH_BASE',1) or die;

/**
 * Allows for quoting in language .ini files.
 */
define('_QQ_', '"');

/**
 * Languages/translation handler class
 *
 * @package     Joomla.Platform
 * @subpackage  Language
 * @since       11.1
 */
class JLanguage
{
	protected static $languages = array();

	/**
	 * Debug language, If true, highlights if string isn't found.
	 * @var    boolean
	 * @since  11.1
	 */
	protected $debug = false;

	/**
	 * The default language, used when a language file in the requested language does not exist.
	 * @var    string
	 * @since  11.1
	 */
	protected $default = 'en-GB';

	/**
	 * An array of orphaned text.
	 * @var    array
	 * @since  11.1
	 */
	protected $orphans = array();

	/**
	 * Array holding the language metadata.
	 * @var    array
	 * @since  11.1
	 */
	protected $metadata = null;

	/**
	 * Array holding the language locale or boolean null if none.
	 * @var    array|boolean
	 * @since  11.1
	 */
	protected $locale = null;

	/**
	 * The language to load.
	 * @var    string
	 * @since  11.1
	 */
	protected $lang = null;

	/**
	 * A nested array of language files that have been loaded
	 * @var    array
	 * @since  11.1
	 */
	protected $paths = array();

	/**
	 * List of language files that are in error state
	 * @var    array
	 * @since  11.1
	 */
	protected $errorfiles = array();

	/**
	 * Translations
	 * @var    array
	 * @since  11.1
	 */
	protected $strings = null;

	/**
	 * An array of used text, used during debugging.
	 * @var    array
	 * @since  11.1
	 */
	protected $used = array();

	/**
	 * Counter for number of loads.
	 * @var    integer
	 * @since  11.1
	 */
	protected $counter = 0;

	/**
	 * An array used to store overrides.
	 * @var    array
	 * @since  11.1
	 */
	protected $override = array();

	/**
	 * Name of the transliterator function for this language.
	 * @var    string
	 * @since  11.1
	 */
	protected $transliterator = null;

	/**
	 * Name of the pluralSuffixesCallback function for this language.
	 * @var    callable
	 * @since  11.1
	 */
	protected $pluralSuffixesCallback = null;

	/**
	 * Name of the ignoredSearchWordsCallback function for this language.
	 * @var    callable
	 * @since  11.1
	 */
	protected $ignoredSearchWordsCallback = null;

	/**
	 * Name of the lowerLimitSearchWordCallback function for this language.
	 * @var    callable
	 * @since  11.1
	 */
	protected $lowerLimitSearchWordCallback = null;

	/**
	 * Name of the uppperLimitSearchWordCallback function for this language
	 * @var    callable
	 * @since  11.1
	 */
	protected $upperLimitSearchWordCallback = null;

	/**
	 * Name of the searchDisplayedCharactersNumberCallback function for this language.
	 * @var    callable
	 * @since  11.1
	 */
	protected $searchDisplayedCharactersNumberCallback = null;

	/**
	 * Constructor activating the default information of the language.
	 *
	 * @param   string   $lang   The language
	 * @param   boolean  $debug  Indicates if language debugging is enabled.
	 *
	 * @since   11.1
	 */
	public function __construct($lang = null, $debug = false)
	{
		$this->strings = array();

		if ($lang == null)
		{
			$lang = $this->default;
		}

		$this->setLanguage($lang);
		$this->setDebug($debug);

		$filename = JPATH_BASE . "/language/overrides/$lang.override.ini";

		if (file_exists($filename) && $contents = $this->parse($filename))
		{
			if (is_array($contents))
			{
				// Sort the underlying heap by key values to optimize merging
				ksort($contents, SORT_STRING);
				$this->override = $contents;
			}
			unset($contents);
		}

		// Look for a language specific localise class
		$class = str_replace('-', '_', $lang . 'Localise');
		$paths = array();

		if (defined('JPATH_SITE'))
		{
			// Note: Manual indexing to enforce load order.
			$paths[0] = JPATH_SITE . "/language/overrides/$lang.localise.php";
			$paths[2] = JPATH_SITE . "/language/$lang/$lang.localise.php";
		}

		if (defined('JPATH_ADMINISTRATOR'))
		{
			// Note: Manual indexing to enforce load order.
			$paths[1] = JPATH_ADMINISTRATOR . "/language/overrides/$lang.localise.php";
			$paths[3] = JPATH_ADMINISTRATOR . "/language/$lang/$lang.localise.php";
		}

		ksort($paths);
		$path = reset($paths);

		while (!class_exists($class) && $path)
		{
			if (file_exists($path))
			{
				require_once $path;
			}
			$path = next($paths);
		}

		if (class_exists($class))
		{
			/* Class exists. Try to find
			 * -a transliterate method,
			 * -a getPluralSuffixes method,
			 * -a getIgnoredSearchWords method
			 * -a getLowerLimitSearchWord method
			 * -a getUpperLimitSearchWord method
			 * -a getSearchDisplayCharactersNumber method
			 */
			if (method_exists($class, 'transliterate'))
			{
				$this->transliterator = array($class, 'transliterate');
			}

			if (method_exists($class, 'getPluralSuffixes'))
			{
				$this->pluralSuffixesCallback = array($class, 'getPluralSuffixes');
			}

			if (method_exists($class, 'getIgnoredSearchWords'))
			{
				$this->ignoredSearchWordsCallback = array($class, 'getIgnoredSearchWords');
			}

			if (method_exists($class, 'getLowerLimitSearchWord'))
			{
				$this->lowerLimitSearchWordCallback = array($class, 'getLowerLimitSearchWord');
			}

			if (method_exists($class, 'getUpperLimitSearchWord'))
			{
				$this->upperLimitSearchWordCallback = array($class, 'getUpperLimitSearchWord');
			}

			if (method_exists($class, 'getSearchDisplayedCharactersNumber'))
			{
				$this->searchDisplayedCharactersNumberCallback = array($class, 'getSearchDisplayedCharactersNumber');
			}
		}

		$this->load();
	}

	/**
	 * Returns a language object.
	 *
	 * @param   string   $lang   The language to use.
	 * @param   boolean  $debug  The debug mode.
	 *
	 * @return  JLanguage  The Language object.
	 *
	 * @since   11.1
	 */
	public static function getInstance($lang, $debug = false)
	{
		if (!isset(self::$languages[$lang . $debug]))
		{
			self::$languages[$lang . $debug] = new JLanguage($lang, $debug);
		}

		return self::$languages[$lang . $debug];
	}

	/**
	 * Translate function, mimics the php gettext (alias _) function.
	 *
	 * The function checks if $jsSafe is true, then if $interpretBackslashes is true.
	 *
	 * @param   string   $string                The string to translate
	 * @param   boolean  $jsSafe                Make the result javascript safe
	 * @param   boolean  $interpretBackSlashes  Interpret \t and \n
	 *
	 * @return  string  The translation of the string
	 *
	 * @since   11.1
	 */
	public function _($string, $jsSafe = false, $interpretBackSlashes = true)
	{
		// Detect empty string
		if ($string == '')
		{
			return '';
		}

		$key = strtoupper($string);

		if (isset($this->strings[$key]))
		{
			$string = $this->debug ? '**' . $this->strings[$key] . '**' : $this->strings[$key];

			// Store debug information
			if ($this->debug)
			{
				$caller = $this->getCallerInfo();

				if (!array_key_exists($key, $this->used))
				{
					$this->used[$key] = array();
				}

				$this->used[$key][] = $caller;
			}
		}
		else
		{
			if ($this->debug)
			{
				$caller = $this->getCallerInfo();
				$caller['string'] = $string;

				if (!array_key_exists($key, $this->orphans))
				{
					$this->orphans[$key] = array();
				}

				$this->orphans[$key][] = $caller;

				$string = '??' . $string . '??';
			}
		}

		if ($jsSafe)
		{
			// Javascript filter
			$string = addslashes($string);
		}
		elseif ($interpretBackSlashes)
		{
			// Interpret \n and \t characters
			$string = str_replace(array('\\\\', '\t', '\n'), array("\\", "\t", "\n"), $string);
		}

		return $string;
	}

	/**
	 * Transliterate function
	 *
	 * This method processes a string and replaces all accented UTF-8 characters by unaccented
	 * ASCII-7 "equivalents".
	 *
	 * @param   string  $string  The string to transliterate.
	 *
	 * @return  string  The transliteration of the string.
	 *
	 * @since   11.1
	 */
	public function transliterate($string)
	{
		if ($this->transliterator !== null)
		{
			return call_user_func($this->transliterator, $string);
		}

		$string = JLanguageTransliterate::utf8_latin_to_ascii($string);
		$string = JString::strtolower($string);

		return $string;
	}

	/**
	 * Getter for transliteration function
	 *
	 * @return  callable  The transliterator function
	 *
	 * @since   11.1
	 */
	public function getTransliterator()
	{
		return $this->transliterator;
	}

	/**
	 * Set the transliteration function.
	 *
	 * @param   callable  $function  Function name or the actual function.
	 *
	 * @return  callable  The previous function.
	 *
	 * @since   11.1
	 */
	public function setTransliterator($function)
	{
		$previous = $this->transliterator;
		$this->transliterator = $function;

		return $previous;
	}

	/**
	 * Returns an array of suffixes for plural rules.
	 *
	 * @param   integer  $count  The count number the rule is for.
	 *
	 * @return  array    The array of suffixes.
	 *
	 * @since   11.1
	 */
	public function getPluralSuffixes($count)
	{
		if ($this->pluralSuffixesCallback !== null)
		{
			return call_user_func($this->pluralSuffixesCallback, $count);
		}
		else
		{
			return array((string) $count);
		}
	}

	/**
	 * Getter for pluralSuffixesCallback function.
	 *
	 * @return  callable  Function name or the actual function.
	 *
	 * @since   11.1
	 */
	public function getPluralSuffixesCallback()
	{
		return $this->pluralSuffixesCallback;
	}

	/**
	 * Set the pluralSuffixes function.
	 *
	 * @param   callable  $function  Function name or actual function.
	 *
	 * @return  callable  The previous function.
	 *
	 * @since   11.1
	 */
	public function setPluralSuffixesCallback($function)
	{
		$previous = $this->pluralSuffixesCallback;
		$this->pluralSuffixesCallback = $function;

		return $previous;
	}

	/**
	 * Returns an array of ignored search words
	 *
	 * @return  array  The array of ignored search words.
	 *
	 * @since   11.1
	 */
	public function getIgnoredSearchWords()
	{
		if ($this->ignoredSearchWordsCallback !== null)
		{
			return call_user_func($this->ignoredSearchWordsCallback);
		}
		else
		{
			return array();
		}
	}

	/**
	 * Getter for ignoredSearchWordsCallback function.
	 *
	 * @return  callable  Function name or the actual function.
	 *
	 * @since   11.1
	 */
	public function getIgnoredSearchWordsCallback()
	{
		return $this->ignoredSearchWordsCallback;
	}

	/**
	 * Setter for the ignoredSearchWordsCallback function
	 *
	 * @param   callable  $function  Function name or actual function.
	 *
	 * @return  callable  The previous function.
	 *
	 * @since   11.1
	 */
	public function setIgnoredSearchWordsCallback($function)
	{
		$previous = $this->ignoredSearchWordsCallback;
		$this->ignoredSearchWordsCallback = $function;

		return $previous;
	}

	/**
	 * Returns a lower limit integer for length of search words
	 *
	 * @return  integer  The lower limit integer for length of search words (3 if no value was set for a specific language).
	 *
	 * @since   11.1
	 */
	public function getLowerLimitSearchWord()
	{
		if ($this->lowerLimitSearchWordCallback !== null)
		{
			return call_user_func($this->lowerLimitSearchWordCallback);
		}
		else
		{
			return 3;
		}
	}

	/**
	 * Getter for lowerLimitSearchWordCallback function
	 *
	 * @return  callable  Function name or the actual function.
	 *
	 * @since   11.1
	 */
	public function getLowerLimitSearchWordCallback()
	{
		return $this->lowerLimitSearchWordCallback;
	}

	/**
	 * Setter for the lowerLimitSearchWordCallback function.
	 *
	 * @param   callable  $function  Function name or actual function.
	 *
	 * @return  callable  The previous function.
	 *
	 * @since   11.1
	 */
	public function setLowerLimitSearchWordCallback($function)
	{
		$previous = $this->lowerLimitSearchWordCallback;
		$this->lowerLimitSearchWordCallback = $function;

		return $previous;
	}

	/**
	 * Returns an upper limit integer for length of search words
	 *
	 * @return  integer  The upper limit integer for length of search words (20 if no value was set for a specific language).
	 *
	 * @since   11.1
	 */
	public function getUpperLimitSearchWord()
	{
		if ($this->upperLimitSearchWordCallback !== null)
		{
			return call_user_func($this->upperLimitSearchWordCallback);
		}
		else
		{
			return 20;
		}
	}

	/**
	 * Getter for upperLimitSearchWordCallback function
	 *
	 * @return  callable  Function name or the actual function.
	 *
	 * @since   11.1
	 */
	public function getUpperLimitSearchWordCallback()
	{
		return $this->upperLimitSearchWordCallback;
	}

	/**
	 * Setter for the upperLimitSearchWordCallback function
	 *
	 * @param   callable  $function  Function name or the actual function.
	 *
	 * @return  callable  The previous function.
	 *
	 * @since   11.1
	 */
	public function setUpperLimitSearchWordCallback($function)
	{
		$previous = $this->upperLimitSearchWordCallback;
		$this->upperLimitSearchWordCallback = $function;

		return $previous;
	}

	/**
	 * Returns the number of characters displayed in search results.
	 *
	 * @return  integer  The number of characters displayed (200 if no value was set for a specific language).
	 *
	 * @since   11.1
	 */
	public function getSearchDisplayedCharactersNumber()
	{
		if ($this->searchDisplayedCharactersNumberCallback !== null)
		{
			return call_user_func($this->searchDisplayedCharactersNumberCallback);
		}
		else
		{
			return 200;
		}
	}

	/**
	 * Getter for searchDisplayedCharactersNumberCallback function
	 *
	 * @return  callable  Function name or the actual function.
	 *
	 * @since   11.1
	 */
	public function getSearchDisplayedCharactersNumberCallback()
	{
		return $this->searchDisplayedCharactersNumberCallback;
	}

	/**
	 * Setter for the searchDisplayedCharactersNumberCallback function.
	 *
	 * @param   callable  $function  Function name or the actual function.
	 *
	 * @return  callable  The previous function.
	 *
	 * @since   11.1
	 */
	public function setSearchDisplayedCharactersNumberCallback($function)
	{
		$previous = $this->searchDisplayedCharactersNumberCallback;
		$this->searchDisplayedCharactersNumberCallback = $function;

		return $previous;
	}

	/**
	 * Checks if a language exists.
	 *
	 * This is a simple, quick check for the directory that should contain language files for the given user.
	 *
	 * @param   string  $lang      Language to check.
	 * @param   string  $basePath  Optional path to check.
	 *
	 * @return  boolean  True if the language exists.
	 *
	 * @since   11.1
	 */
	public static function exists($lang, $basePath = JPATH_BASE)
	{
		static $paths = array();

		// Return false if no language was specified
		if (!$lang)
		{
			return false;
		}

		$path = $basePath . '/language/' . $lang;

		// Return previous check results if it exists
		if (isset($paths[$path]))
		{
			return $paths[$path];
		}

		// Check if the language exists
		$paths[$path] = is_dir($path);

		return $paths[$path];
	}

	/**
	 * Loads a single language file and appends the results to the existing strings
	 *
	 * @param   string   $extension  The extension for which a language file should be loaded.
	 * @param   string   $basePath   The basepath to use.
	 * @param   string   $lang       The language to load, default null for the current language.
	 * @param   boolean  $reload     Flag that will force a language to be reloaded if set to true.
	 * @param   boolean  $default    Flag that force the default language to be loaded if the current does not exist.
	 *
	 * @return  boolean  True if the file has successfully loaded.
	 *
	 * @since   11.1
	 */
	public function load($extension = 'joomla', $basePath = JPATH_BASE, $lang = null, $reload = false, $default = true)
	{
		if (!$lang)
		{
			$lang = $this->lang;
		}

		$path = self::getLanguagePath($basePath, $lang);

		$internal = $extension == 'joomla' || $extension == '';
		$filename = $internal ? $lang : $lang . '.' . $extension;
		$filename = "$path/$filename.ini";

		$result = false;

		if (isset($this->paths[$extension][$filename]) && !$reload)
		{
			// This file has already been tested for loading.
			$result = $this->paths[$extension][$filename];
		}
		else
		{
			// Load the language file
			$result = $this->loadLanguage($filename, $extension);

			// Check whether there was a problem with loading the file
			if ($result === false && $default)
			{
				// No strings, so either file doesn't exist or the file is invalid
				$oldFilename = $filename;

				// Check the standard file name
				$path = self::getLanguagePath($basePath, $this->default);
				$filename = $internal ? $this->default : $this->default . '.' . $extension;
				$filename = "$path/$filename.ini";

				// If the one we tried is different than the new name, try again
				if ($oldFilename != $filename)
				{
					$result = $this->loadLanguage($filename, $extension, false);
				}
			}
		}

		return $result;
	}

	/**
	 * Loads a language file.
	 *
	 * This method will not note the successful loading of a file - use load() instead.
	 *
	 * @param   string  $filename   The name of the file.
	 * @param   string  $extension  The name of the extension.
	 *
	 * @return  boolean  True if new strings have been added to the language
	 *
	 * @see     JLanguage::load()
	 * @since   11.1
	 */
	protected function loadLanguage($filename, $extension = 'unknown')
	{
		$this->counter++;

		$result = false;
		$strings = false;

		if (file_exists($filename))
		{
			$strings = $this->parse($filename);
		}

		if ($strings)
		{
			if (is_array($strings))
			{
				// Sort the underlying heap by key values to optimize merging
				ksort($strings, SORT_STRING);
				$this->strings = array_merge($this->strings, $strings);
			}

			if (is_array($strings) && count($strings))
			{
				// Do not bother with ksort here.  Since the originals were sorted, PHP will already have chosen the best heap.
				$this->strings = array_merge($this->strings, $this->override);
				$result = true;
			}
		}

		// Record the result of loading the extension's file.
		if (!isset($this->paths[$extension]))
		{
			$this->paths[$extension] = array();
		}

		$this->paths[$extension][$filename] = $result;

		return $result;
	}

	/**
	 * Parses a language file.
	 *
	 * @param   string  $filename  The name of the file.
	 *
	 * @return  array  The array of parsed strings.
	 *
	 * @since   11.1
	 */
	protected function parse($filename)
	{
		if ($this->debug)
		{
			// Capture hidden PHP errors from the parsing.
			$php_errormsg = null;
			$track_errors = ini_get('track_errors');
			ini_set('track_errors', true);
		}

		$contents = file_get_contents($filename);
		$contents = str_replace('_QQ_', '"\""', $contents);
		$strings = @parse_ini_string($contents);

		if (!is_array($strings))
		{
			$strings = array();
		}

		if ($this->debug)
		{
			// Restore error tracking to what it was before.
			ini_set('track_errors', $track_errors);

			// Initialise variables for manually parsing the file for common errors.
			$blacklist = array('YES', 'NO', 'NULL', 'FALSE', 'ON', 'OFF', 'NONE', 'TRUE');
			$regex = '/^(|(\[[^\]]*\])|([A-Z][A-Z0-9_\-\.]*\s*=(\s*(("[^"]*")|(_QQ_)))+))\s*(;.*)?$/';
			$this->debug = false;
			$errors = array();

			// Open the file as a stream.
			$file = new SplFileObject($filename);

			foreach ($file as $lineNumber => $line)
			{
				// Avoid BOM error as BOM is OK when using parse_ini
				if ($lineNumber == 0)
				{
					$line = str_replace("\xEF\xBB\xBF", '', $line);
				}

				// Check that the key is not in the blacklist and that the line format passes the regex.
				$key = strtoupper(trim(substr($line, 0, strpos($line, '='))));

				// Workaround to reduce regex complexity when matching escaped quotes
				$line = str_replace('\"', '_QQ_', $line);

				if (!preg_match($regex, $line) || in_array($key, $blacklist))
				{
					$errors[] = $lineNumber;
				}
			}

			// Check if we encountered any errors.
			if (count($errors))
			{
				if (basename($filename) != $this->lang . '.ini')
				{
					$this->errorfiles[$filename] = $filename . JText::sprintf('JERROR_PARSING_LANGUAGE_FILE', implode(', ', $errors));
				}
				else
				{
					$this->errorfiles[$filename] = $filename . '&#160;: error(s) in line(s) ' . implode(', ', $errors);
				}
			}
			elseif ($php_errormsg)
			{
				// We didn't find any errors but there's probably a parse notice.
				$this->errorfiles['PHP' . $filename] = 'PHP parser errors :' . $php_errormsg;
			}

			$this->debug = true;
		}

		return $strings;
	}

	/**
	 * Get a metadata language property.
	 *
	 * @param   string  $property  The name of the property.
	 * @param   mixed   $default   The default value.
	 *
	 * @return  mixed  The value of the property.
	 *
	 * @since   11.1
	 */
	public function get($property, $default = null)
	{
		if (isset($this->metadata[$property]))
		{
			return $this->metadata[$property];
		}

		return $default;
	}

	/**
	 * Determine who called JLanguage or JText.
	 *
	 * @return  array  Caller information.
	 *
	 * @since   11.1
	 */
	protected function getCallerInfo()
	{
		// Try to determine the source if none was provided
		if (!function_exists('debug_backtrace'))
		{
			return null;
		}

		$backtrace = debug_backtrace();
		$info = array();

		// Search through the backtrace to our caller
		$continue = true;

		while ($continue && next($backtrace))
		{
			$step = current($backtrace);
			$class = @ $step['class'];

			// We're looking for something outside of language.php
			if ($class != 'JLanguage' && $class != 'JText')
			{
				$info['function'] = @ $step['function'];
				$info['class'] = $class;
				$info['step'] = prev($backtrace);

				// Determine the file and name of the file
				$info['file'] = @ $step['file'];
				$info['line'] = @ $step['line'];

				$continue = false;
			}
		}

		return $info;
	}

	/**
	 * Getter for Name.
	 *
	 * @return  string  Official name element of the language.
	 *
	 * @since   11.1
	 */
	public function getName()
	{
		return $this->metadata['name'];
	}

	/**
	 * Get a list of language files that have been loaded.
	 *
	 * @param   string  $extension  An optional extension name.
	 *
	 * @return  array
	 *
	 * @since   11.1
	 */
	public function getPaths($extension = null)
	{
		if (isset($extension))
		{
			if (isset($this->paths[$extension]))
			{
				return $this->paths[$extension];
			}

			return null;
		}
		else
		{
			return $this->paths;
		}
	}

	/**
	 * Get a list of language files that are in error state.
	 *
	 * @return  array
	 *
	 * @since   11.1
	 */
	public function getErrorFiles()
	{
		return $this->errorfiles;
	}

	/**
	 * Getter for the language tag (as defined in RFC 3066)
	 *
	 * @return  string  The language tag.
	 *
	 * @since   11.1
	 */
	public function getTag()
	{
		return $this->metadata['tag'];
	}

	/**
	 * Get the RTL property.
	 *
	 * @return  boolean  True is it an RTL language.
	 *
	 * @since   11.1
	 */
	public function isRTL()
	{
		return (bool) $this->metadata['rtl'];
	}

	/**
	 * Set the Debug property.
	 *
	 * @param   boolean  $debug  The debug setting.
	 *
	 * @return  boolean  Previous value.
	 *
	 * @since   11.1
	 */
	public function setDebug($debug)
	{
		$previous = $this->debug;
		$this->debug = (boolean) $debug;

		return $previous;
	}

	/**
	 * Get the Debug property.
	 *
	 * @return  boolean  True is in debug mode.
	 *
	 * @since   11.1
	 */
	public function getDebug()
	{
		return $this->debug;
	}

	/**
	 * Get the default language code.
	 *
	 * @return  string  Language code.
	 *
	 * @since   11.1
	 */
	public function getDefault()
	{
		return $this->default;
	}

	/**
	 * Set the default language code.
	 *
	 * @param   string  $lang  The language code.
	 *
	 * @return  string  Previous value.
	 *
	 * @since   11.1
	 */
	public function setDefault($lang)
	{
		$previous = $this->default;
		$this->default = $lang;

		return $previous;
	}

	/**
	 * Get the list of orphaned strings if being tracked.
	 *
	 * @return  array  Orphaned text.
	 *
	 * @since   11.1
	 */
	public function getOrphans()
	{
		return $this->orphans;
	}

	/**
	 * Get the list of used strings.
	 *
	 * Used strings are those strings requested and found either as a string or a constant.
	 *
	 * @return  array  Used strings.
	 *
	 * @since   11.1
	 */
	public function getUsed()
	{
		return $this->used;
	}

	/**
	 * Determines is a key exists.
	 *
	 * @param   string  $string  The key to check.
	 *
	 * @return  boolean  True, if the key exists.
	 *
	 * @since   11.1
	 */
	public function hasKey($string)
	{
		$key = strtoupper($string);

		return isset($this->strings[$key]);
	}

	/**
	 * Returns a associative array holding the metadata.
	 *
	 * @param   string  $lang  The name of the language.
	 *
	 * @return  mixed  If $lang exists return key/value pair with the language metadata, otherwise return NULL.
	 *
	 * @since   11.1
	 */
	public static function getMetadata($lang)
	{
		$path = self::getLanguagePath(JPATH_BASE, $lang);
		$file = $lang . '.xml';

		$result = null;

		if (is_file("$path/$file"))
		{
			$result = self::parseXMLLanguageFile("$path/$file");
		}

		if (empty($result))
		{
			return null;
		}

		return $result;
	}

	/**
	 * Returns a list of known languages for an area
	 *
	 * @param   string  $basePath  The basepath to use
	 *
	 * @return  array  key/value pair with the language file and real name.
	 *
	 * @since   11.1
	 */
	public static function getKnownLanguages($basePath = JPATH_BASE)
	{
		$dir = self::getLanguagePath($basePath);
		$knownLanguages = self::parseLanguageFiles($dir);

		return $knownLanguages;
	}

	/**
	 * Get the path to a language
	 *
	 * @param   string  $basePath  The basepath to use.
	 * @param   string  $language  The language tag.
	 *
	 * @return  string  language related path or null.
	 *
	 * @since   11.1
	 */
	public static function getLanguagePath($basePath = JPATH_BASE, $language = null)
	{
		$dir = $basePath . '/language';

		if (!empty($language))
		{
			$dir .= '/' . $language;
		}

		return $dir;
	}

	/**
	 * Set the language attributes to the given language.
	 *
	 * Once called, the language still needs to be loaded using JLanguage::load().
	 *
	 * @param   string  $lang  Language code.
	 *
	 * @return  string  Previous value.
	 *
	 * @since   11.1
	 */
	public function setLanguage($lang)
	{
		$previous = $this->lang;
		$this->lang = $lang;
		$this->metadata = $this->getMetadata($this->lang);

		return $previous;
	}

	/**
	 * Get the language locale based on current language.
	 *
	 * @return  array  The locale according to the language.
	 *
	 * @since   11.1
	 */
	public function getLocale()
	{
		if (!isset($this->locale))
		{
			$locale = str_replace(' ', '', isset($this->metadata['locale']) ? $this->metadata['locale'] : '');

			if ($locale)
			{
				$this->locale = explode(',', $locale);
			}
			else
			{
				$this->locale = false;
			}
		}

		return $this->locale;
	}

	/**
	 * Get the first day of the week for this language.
	 *
	 * @return  integer  The first day of the week according to the language
	 *
	 * @since   11.1
	 */
	public function getFirstDay()
	{
		return (int) (isset($this->metadata['firstDay']) ? $this->metadata['firstDay'] : 0);
	}

	/**
	 * Searches for language directories within a certain base dir.
	 *
	 * @param   string  $dir  directory of files.
	 *
	 * @return  array  Array holding the found languages as filename => real name pairs.
	 *
	 * @since   11.1
	 */
	public static function parseLanguageFiles($dir = null)
	{
		$languages = array();

		$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

		foreach ($iterator as $file)
		{
			$langs    = array();
			$fileName = $file->getFilename();

			if (!$file->isFile() || !preg_match("/^([-_A-Za-z]*)\.xml$/", $fileName))
			{
				continue;
			}

			try
			{
				$metadata = self::parseXMLLanguageFile($file->getRealPath());

				if ($metadata)
				{
					$lang = str_replace('.xml', '', $fileName);
					$langs[$lang] = $metadata;
				}
				$languages = array_merge($languages, $langs);
			}
			catch (RuntimeException $e)
			{
			}
		}

		return $languages;
	}

	/**
	 * Parse XML file for language information.
	 *
	 * @param   string  $path  Path to the XML files.
	 *
	 * @return  array  Array holding the found metadata as a key => value pair.
	 *
	 * @since   11.1
	 * @throws  RuntimeException
	 */
	public static function parseXMLLanguageFile($path)
	{
		if (!is_readable($path))
		{
			throw new RuntimeException('File not found or not readable');
		}

		// Try to load the file
		$xml = simplexml_load_file($path);

		if (!$xml)
		{
			return null;
		}

		// Check that it's a metadata file
		if ((string) $xml->getName() != 'metafile')
		{
			return null;
		}

		$metadata = array();

		foreach ($xml->metadata->children() as $child)
		{
			$metadata[$child->getName()] = (string) $child;
		}

		return $metadata;
	}
}
