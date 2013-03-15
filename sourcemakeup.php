<?php 

	// # sourceMakeup
	//
	// sourceMakeup is a source code viewer written in PHP on the backend, and jQuery and jKit
	// on the frontend. sourceMakeup was heavely inspired by the first versions of 
	// [Docco](http://jashkenas.github.com/docco/), with some additional features that enhance
	// the frontend visually and feature wise. It was mainly developed as a source code viewer
	// for the jQuery based UI toolkit [jKit](http://jquery-jkit.com/). It's main use is to
	// create beautiful source documentation for JavaScript libraries and plugins, but it can
	// be used to document small PHP code and CSS markup as well, however, with some limitations. 
	//
	// Comments for sourceMakeup should be written in Markdown. This way sourceMakeup can correctly
	// and beautifully format the code and add a navigation menu linked to the different parts of your code.
	//
	// - Version: `0.5`
	// - Release date: `14. 3. 2013`
	// - [Documentation & Demos](http://jquery-jkit.com/sourcemakeup/)
	// - [Download](https://github.com/FrediBach/sourceMakeup/archive/master.zip)
	//
	// ## Copyright
	//
	// - (c) 2013 by *Fredi Bach*
	// - [Home](http://fredibach.ch/)
	//
	// ## License
	//
	// sourceMakeup is open source and MIT licensed. For more informations read the **license.txt** file 

	// ## Settings

	// - **$dir** contains the path to the documented files
	// - **$file** is the main file
	// - **$files** contains all the files, leave it empty if you don't want to restrict the list to certain files
	// - **$extensions** contains a comma separated list of allowed file extensions
	// - **$filter** filters out any files that contain a certain string
	// - **$dev** mode ignores the cache file and always generates a new one

	$dir = './';
	$file = 'sourcemakeup.php';
	$files = array(
				'sourcemakeup.php',
				'js/jquery.jkit.1.1.28.js',
				'js/sourcemakeup.js',
				'css/sourcemakeup.css'
	);
	$extensions = 'js,php,css';
	$filter = '.min.js';
	$dev = true;

	// ## The Source

	// Is the main file the one from the settings or another one?

	if (isset($_GET['file']) && $_GET['file'] != ''){
		$file = $_GET['file'];
	}

	// Define the path for our cached file:
	
	$cachefile = 'cache/'.md5($file).'.txt';

	// Create a list of all files we want to document. In case **$files** is not empty, those files will be used and 
	// the following code block will be ignored.

	if (count($files) == 0){
		if ($dir != ''){
			$tempfiles = scandir($dir);
			$files = array();
			foreach($tempfiles as $f){
				if ($f != '.' && $f != '..' && stripos($f, $filter) === false){
					$allowed = explode(',', $extensions);
					$ext = pathinfo($f, PATHINFO_EXTENSION);
					if (in_array($ext, $allowed)) {
						$files[] = $f;
					}
				}
			}
		} else {
			$files = array($file);
		}
	}
	
	// Do we have to load the cahed file or create a new one?

	if (file_exists($cachefile) && !$dev){
		
		// Load the cached file:
		
		$output = file_get_contents($cachefile);
	
	} else {
		
		// Ok, so we need to create new output. To do that we first need to include the Markdown class:
		
		include_once "libs/markdown/markdown.php";
		
		// Load the file and create an array of all lines:
		
		$data = file_get_contents($file);
		$lines = explode("\n", $data);
		
		// Create some variables we need later:
		
		$blocks = array();
		$iscomment = false;
		$currentblock = 0;
		$linenumber = 0;
		
		// Step through all lines and combine them into codeblocks. Each codeblock can have following contents:
		//
		// - **comment** contains all comments of this codeblock
		// - **code** contains all code of this block
		// - **lnr** contains the original linenumbers for the above code
		
		foreach($lines as $line){
		
			$linenumber++;
		
			if (substr(trim($line),0,2) == '//'){
				if (!$iscomment){
					$currentblock++;
				}
				$blocks[$currentblock]['comment'] .= "\n".substr(trim($line),3);
				$blocks[$currentblock]['code'] .= "";
				$iscomment = true;
				if (substr(trim(substr(trim($line),3)),0,3) == '{!}'){
					$currentblock++;
				}
			} else {
				$blocks[$currentblock]['code'] .= "\n".str_replace("\t", "    ", $line);
				$blocks[$currentblock]['lnr'] .= "\n".$linenumber;
				$blocks[$currentblock]['comment'] .= "";
				$iscomment = false;
			}
	
		}
		
		// If this is a PHP script with **<?php** on the first line, we're going to remove it as it's only ruining our design:
		
		if (trim($blocks[0]['code']) == '<?php'){
			$blocks[0]['code'] = '';
		}
		
		// We have now all we need to construct the table that contains all of our code. The table 
		// has two columns, the first contains all the comments and the second one contains all the code.
	
		$output = '';
		
		// First contruct the table header:
		
		$output .= '<table class="container" cellpadding="0" cellspacing="0">';
		$output .= '<thead><th class="comment"><select id="files">';
		foreach($files as $f){
			if ($f == $file){
				$output .= '<option selected>'.$f.'</option>';
			} else {
				$output .= '<option>'.$f.'</option>';
			}
		}
		$output .= '</select>Documentation</th><th class="code">Code</th>';
		$output .= '</thead><tbody>';
		
		// Loop through all blocks and construct the rows of our table:
	
		foreach($blocks as $block){
			
			// Each block is a row with a comment and a code column. Sometimes only one of them actually has content and 
			// if both have no content, simply skip this block.
			
			if (trim($block['comment']) != '' || trim($block['code']) != ''){
				
				$output .= '<tr class="docu">';
				
				if (substr(trim($block['comment']),0,3) == '{!}'){
					
					// This block is an instruction block. Instruction blocks can be created like this:
					//
					//     {!} instructiontype: instruction
					//
					// Instructions are normally hidden, but can be made visible. They are ment to contain special 
					// instructions either for a script or for code contributors. If you use them, make sure to supply the
					// icons for each of your instruction type. They have to ba named like this:
					//
					//     sourcemakeup.instructiontype.png
					//
					
					$split = explode(':', substr(trim($block['comment']),3));
					
					$block['comment'] = '<span class="itype">';
					$block['comment'] .= '<img src="imgs/sourcemakeup.'.trim($split[0]).'.png"> '.$split[0].'</span>';
					$block['comment'] .= ':<span class="iopt">'.$split[1].'</span>';
					
					$output .= '<td class="comment instruction">&nbsp;</td>';
					$output .= '<td class="code instruction">'.$block['comment'].'</td>';
					
				} else {
					
					// This is a regular codeblock. As we don't want to display any uneeded linebreaks, we
					// use a special funtion to strip them away. The function returns an array that not only
					// contains the stripped code, it tells us exactly how many lines where removed from the 
					// beginning and from the end. This way we can strip exactly the same number of lines from 
					// the linenumber string.
					
					$codedata = trimLinebreaks($block['code']);
				
					$templines = explode("\n", $block['lnr']);
				
					if ($codedata['shifted'] > 0){
						for ($i=1; $i<=$codedata['shifted']; $i++){
							array_shift($templines);
						}
					}
				
					if ($codedata['popped'] > 0){
						for ($i=1; $i<=$codedata['popped']; $i++){
							array_pop($templines);
						}
					}
				
					$lnr = implode("\n", $templines);
					
					// For the comment column, we basically do two things. We parse the Markdown formatting into nice HTML
					// with the Markdown library, and we than add ids to all HTML headers so that we can create an anchor navigation.
				
					$output .= '<td class="comment">'.addHeaderAnchors(Markdown(trim($block['comment']))).'</td>';
					
					// All we need to do for the code is escape the HTML special chars. The formatting is done by the Google Code Prettifier JavaScript.
					
					$output .= '<td class="code"><pre class="linenumbers">'.$lnr.'</pre>';
					$output .= '<pre class="prettyprint">'.htmlspecialchars($codedata['code']).'</pre></td>';
				}
				
				$output .= '</tr>';
				
			}
		}
	
		$output .= '</tbody></table>';
		
		// Now as we created our output, we save it in a cache for later use:
		
		$fh = fopen($cachefile, 'w') or die("can't open file");
		fwrite($fh, $output);
		fclose($fh);
	
	}
	
	// And lastly, we inlude the template to generate our output:

	include 'sourcemakeup.template.php';

	
	// ## Functions
	
	// The **addHeaderAnchors** function and the regex callback function **addHeaderAnchorsCallback**
	// add ids to each header in the supplied HTML based on the title of the header.
	
	function addHeaderAnchors($text){
		return preg_replace_callback('/\<h([0-6]{1})\>(.+)\<\/h[0-6]{1}\>/', 'addHeaderAnchorsCallback', $text);
	}
	
	function addHeaderAnchorsCallback($matches){
		return '<h'.$matches[1].' id="'.createURLSlug($matches[2]).'">'.$matches[2].'</h'.$matches[1].'>';
	}
	
	// The **createURLSlug** function makes a nice url slug where all special characters are removed and spaces replaced with 
	// the "-" character.

	function createURLSlug($str, $replace=array(), $delimiter='-'){
		if( !empty($replace) ) {
			$str = str_replace((array)$replace, ' ', $str);
		}

		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

		return $clean;
	}
	
	// The **trimLinebreaks** function removes all unneaded linebreaks before an after a string and tells 
	// us in the return array exactly how many of them.

	function trimLinebreaks($string){
	
		$lines = explode("\n", $string);
	
		$shifted = 0;
		$popped = 0;
	
		while(trim($lines[0]) == '' && count($lines) > 0){
			array_shift($lines);
			$shifted++;
		}
	
		while( trim( $lines[count($lines)-1] ) == '' && count($lines) > 0){
			array_pop($lines);
			$popped++;
		}
	
		$arr = array('code' => implode("\n", $lines), 'shifted' => $shifted, 'popped' => $popped);
	
		return $arr;
	
	}
