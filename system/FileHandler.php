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
		$this->cd($directory);
	}

	public function cd(string $directory)
	{
		if (is_dir($directory))
		{
			$this->directory = $directory . ((substr($directory, strlen($directory) - 1) !== "/") ? "/" : "");
		}
	}

	public function close($file = null)
	{
		if ($file !== null)
		{
			fclose($file);
		}
		else
		{
			fclose($this->pointer);
			$this->pointer = null;
			$this->file = null;
		}
	}

	public function create(string $name, string $contents = "")
	{
		$this->file = $this->directory . $name;
		$this->pointer = fopen($this->file, "wa+");
		chmod($this->file, 0777);

		if ($contents !== "")
		{
			$this->write($contents);
		}
	}

	public function delete(string $name)
	{
		if (is_dir($name))
		{
			$files = array_diff(scandir($name), array(".", ".."));

			foreach ($files as $file)
			{
				$this->delete($name . "/" . $file);
			}

			rmdir($name);
		}
		else
		{
			if (is_file($name))
			{
				unlink($name);
			}
		}
	}

	public function exists(string $name): bool
	{
		return file_exists($this->directory . $name);
	}

	public function getDirectory(): string
	{
		return $this->directory;
	}

	public function getFileContents(string $file = ""): string
	{
		if ($file)
		{
			$pointer = $this->open($file);
			$contents = fread($pointer, filesize($this->directory . $file));
			$this->close($pointer);
		}
		else
		{
			$contents = fread($this->pointer, $this->getFileSize());
		}

		return $contents;
	}

	public function getFileSize(): int
	{
		return filesize($this->file);
	}

	public function mkdir(string $name, $change = true)
	{
		mkdir($this->directory . $name, 0777, true);
		chmod($this->directory . $name, 0777);

		if ($change)
		{
			$this->cd($this->directory . $name);
		}
	}

	public function open(string $name, bool $store = false)
	{
		if ($store)
		{
			$this->file = $this->directory . $name;
			$this->pointer = fopen($this->file, "r+");
		}
		else
		{
			return fopen($this->directory . $name, "r+");
		}
	}

	public function write(string $contents)
	{
		fwrite($this->pointer, $contents);
	}
}