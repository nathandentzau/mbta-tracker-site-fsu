<?php namespace system;

/**
* mbTaNOW - Realtime MBTA Data
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @author Sherwyn Cooper <scooper4@student.framingham.edu>
* @copyright 2016 Framingham State University
* @license https://opensource.org/licenses/MIT MIT License
*/

class FileHandler
{
	private $directory;
	private $file;
	private $pointer;

	public function __construct(string $directory)
	{
		$this->changeDirectory($directory);
	}

	public function changeDirectory(string $directory)
	{
		if (is_dir($directory))
		{
			$this->directory = $directory;
		}
	}

	public function closeFile()
	{
		fclose($this->pointer);
		$this->pointer = null;
		$this->file = null;
	}

	public function createFile(string $name, string $contents = "")
	{
		$this->file = $this->directory . $name;
		$this->pointer = fopen($this->file, "x+");

		if ($contents !== "")
		{
			$this->writeFile($contents);
		}
	}

	public function delete(string $name)
	{
		if (is_dir($this->directory . $name))
		{
			rmdir($this->directory . $name);
		}
		else
		{
			unlink($this->directory . $name);
		}
	}

	public function getDirectory(): string
	{
		return $this->directory;
	}

	public function getFileContents(): string
	{
		return fread($this->pointer, $this->getFileSize());
	}

	public function getFileSize(): double
	{
		return filesize($this->file);
	}

	public function mkdir(string $name, $change = true)
	{
		mkdir($this->directory . $name, 0777, true);

		if ($change)
		{
			$this->changeDirectory($this->directory . $name . "/");
		}
	}

	public function openFile(string $name, string $contents = "")
	{
		$this->file = $this->directory . $name;
		$this->pointer = fopen($this->file, "r+");

		if ($contents !== "")
		{
			$this->writeFile($contents);
		}
	}

	public function writeFile(string $contents)
	{
		fwrite($this->pointer, $contents);
	}
}