<?php

// A CLASS WITH USEFUL FILE/FOLDER HANDLING FUNCTIONS

class file {

	public static $json = null;

	/* PRIVATE UTILITY */
	private static function deleteDir($dirPath) {
	    if (! is_dir($dirPath)) {
	        throw new InvalidArgumentException("$dirPath must be a directory");
	    }
	    if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
	        $dirPath .= '/';
	    }
	    $files = glob($dirPath . '*', GLOB_MARK);
	    foreach ($files as $file) {
	        if (is_dir($file)) {
	            self::deleteDir($file);
	        } else {
	            unlink($file);
	        }
	    }
	    rmdir($dirPath);
	}

	/* PUBLIC FUNCTIONS */
	/* FUNDAMENTAL FILE AND FOLDER FUNCTIONS */

	/* STATIC FUNCTIONS */

	// Check if a file/folder exists
	// Returns a boolean
	public static function fileExists($pathArray) {
		// Path array: Join paths together
		if(is_array($pathArray)) {
			$pathArray = implode('/', $pathArray);
		} 
		return file_exists($pathArray);
	}
	public static function folderExists($pathArray) {
		return file::fileExists($pathArray);
	}

	// Create a new folder
	public static function newFolder($pathArray) {
		// Folder already exists
		if(file::fileExists($pathArray)) {
			return;
		}
		// Path array: Use recursion
		if(is_array($pathArray)) {
			$p = '';
			for($i = 0, $l = count($pathArray); $i < $l; $i++) {
				$p .= $pathArray[$i] . '/';
				file::newFolder($p);
			}
			return;
		} 
		if(empty($pathArray)) {
			return;
		}
		mkdir($pathArray);
	}

	// Open/create a file
	// Returns a file resource
	public static function openFile($pathArray) {
		// Path array: Join paths together
		if(is_array($pathArray)) {
			$pathArray = implode('/', $pathArray);
		}
		return array(
			'resource' => fopen($pathArray, 'c+'),
			'path' => $pathArray
		);
	}
	public static function newFile($pathArray) {
		return file::openFile($pathArray);
	}

	// Delete folder
	public static function deleteFolder($pathArray) {
		// Path array: Delete the first folder
		if(is_array($pathArray)) {
			$pathArray = implode('/', $pathArray);
		} 
		// Check if file exists
		if(!file::fileExists($pathArray)) {
			return;
		}
		file::deleteDir($pathArray);
	}

	/* NON-STATIC FUNCTIONS */

	/* CONSTRUCT */

	public $resource;
	public $path;

	public function __construct($pathArray) {
		$info = file::openFile($pathArray);
		$this->resource = $info['resource'];
		$this->path = $info['path'];
	}

	/* BASIC FILE HANDLING FUNCTIONS */

	// Reads a file with a file resource from openFile
	// Returns a string
	public function read() {
		return fread($this->resource, filesize($this->path));
	}

	// Inserts content at a specific position of the file
	public function insert($content, $position) {
	    $fpFile = $this->resource;
	    $fpTemp = fopen('php://temp', "c+");
	    stream_copy_to_stream($fpFile, $fpTemp, -1, $position);
	    fseek($fpFile, $position);
	    fwrite($fpFile, $content);
	    rewind($fpTemp);
	    stream_copy_to_stream($fpTemp, $fpFile);

	    fclose($fpTemp);
	}

	// Adds some content to the beginning of a file
	public function prepend($content) {
		$this->insert($content, -1);
	}

	// Adds some content to the end of a file
	public function append($content) {
		$this->insert($content, filesize($this->path));
	}

	// Overwrite content of the file
	public function overwrite($content) {
		ftruncate($this->resource, 0);
		rewind($this->resource);
		fwrite($this->resource, $content);
		fflush($this->resource);
	}
	public function write($content) {
		$this->overwrite($content);
	}

	// Close file (fclose)
	public function close() {
		fclose($this->resource);
	}

	// Delete file
	public function delete() {
		// Need to fclose first
		if(is_resource($this->resource)) {
			$this->close();
		}
		unlink($this->path);
	}

	/* ADVANCED FILE HANDLING FUNCTIONS */
	/* (JSON FILE FUNCTIONS) */

	// Read a JSON file
	// Returns an array
	public function readJSON() {
		return json_decode($this->read(), true);
	}

	// Write a JSON object into a file
	public function writeJSON($object) {
		$this->overwrite(json_encode($object));
	}

	// Push an element into a JSON object
	// INEFFICIENT
	public function pushJSON($elem) {
		$object = $this->readJSON();
		$object[] = $elem;
		$this->writeJSON($object);
	}


}

// FUNDAMENTAL FILE FUNCTIONS

// var_dump(file::fileExists(['test', '1', '1']));

//	file::newFolder(['test', '1', '1']);
//	file::newFolder(['test', '1', '2']);
//	$resource = new file(['test', '1', '1', 'index.html']);

//	$resource->write('2x');
//	$resource->prepend('1x');
//	$resource->append('3x');
//	$resource->close();

//	$resource->delete();
//	file::deleteFolder('test');

//	$resource = new file('test.txt');
//	var_dump($resource->readJSON());
//	$resource->writeJSON(['a', 'b', '他']);
//	var_dump($resource->readJSON());

?>