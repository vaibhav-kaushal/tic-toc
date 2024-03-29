<?php


//	if(PHP_SAPI != 'cli') {
//		echo "This script is supposed to work only via command line. There is no web interface yet.";
//		exit();
//	}


	class TicToc {
		protected static $documentTree = [];

		/** @var string Base path on which the entire TOC is to be built. Defaults to working directory */
		protected static $basePath = '.';

		/** @var bool Build TOC files inside each Folder as well */
		protected static $blnBuildIntermediateToc = true;

		/**
		 * @param string $strDirName Name of the directory from which the tree is to be extracted
		 *
		 * @return array
		 */
		public static function GetDocumentTreeForFolder($strDirName) {
			$funcName = (__FUNCTION__);
			$arrFiles = [];
			$strFileOrFolderArray = scandir($strDirName);
			foreach ($strFileOrFolderArray as $strFileName) {
				if ($strFileName != '.' && $strFileName != '..') {
					if (is_dir($strDirName . '/' . $strFileName)) {
						$arrFiles[$strFileName] = self::$funcName($strDirName . '/' . $strFileName);
					} elseif (self::isFilenameValid($strFileName)) {
						$arrFiles[$strFileName] = 'file';
					}
				}
			}

			return $arrFiles;
		}

		/**
		 * @throws Exception
		 */
		public static function BuildDocumentTree() {
			$strRootDir = self::$basePath;

			if (!is_dir($strRootDir)) {
				throw new Exception('Base path is not a directory');
			}

			self::$documentTree = self::GetDocumentTreeForFolder($strRootDir);
		}

		/**
		 * Is the file name valid?
		 *
		 * @param string $strFileName
		 *
		 * @return bool
		 */
		private static function isFilenameValid($strFileName) {
			$strFileName = strtolower($strFileName);
			// Check for MD file
			if (
				strlen($strFileName) > strlen('.md') &&
				substr($strFileName, -3, 3) == '.md'
			) {
				// Filename ends with '.md'
				return true;
			}

			return false;
		}

		public static function GenerateIntermediateToc() {

		}

		public static function GenerateIntermediateTocFragment($str) {

		}

		public static function GenerateMainToc() {
			$strToc = self::GenerateTocFragment('', 0, self::$documentTree);
			file_put_contents(self::$basePath . '/toc.md', $strToc);
		}

		public static function GenerateTocFragment($strBasePath = '', $intNestingLevel = 0, $strPathArray) {
			// Make sure that the data inputted is an array
			if (!is_array($strPathArray)) {
				throw new Exception('Inputted value is not an array. Cannot proceed');
			}

			$strMdStringToReturn = '';
			$i = 0;
			foreach ($strPathArray as $name => $value) {
				// Strip the 'MD' extension from name.
				$displayName = $name;
				if (substr($displayName, -3, 3) == '.md') {
					$displayName = substr($name, 0, -3);
				}
				//
				$displayName = str_replace('-', ' ', $displayName);
				$displayName = str_replace('_', ' - ', $displayName);

				$link = $strBasePath . '/' . $name;
				if ($value == 'file') {
					// It is a file
					// Process it if it is not the TOC file
					if ($displayName != 'toc') {
						$strMdStringToReturn .= str_repeat("\t", $intNestingLevel) . ' ' . ($i + 1) . '. [' . $displayName . '](' . $link . ')' . "\r\n";
					}
				} elseif (is_array($value)) {
					// It is a folder
					$strSubFolderLinks = self::GenerateTocFragment($strBasePath . '/' . $name, $intNestingLevel + 1, $value);

					$strMdStringToReturn .= str_repeat("\t", $intNestingLevel) . ' ' . ($i + 1) . '. [' . $displayName . '/](' . $link . '/toc.md)' . "\r\n";

					$strMdStringToReturn .= $strSubFolderLinks;
				} else {
					throw new Exception('Unexpected value in document tree');
				}
				$i++;
			}

			if (self::$blnBuildIntermediateToc) {
				file_put_contents(self::$basePath . '/' . $strBasePath . '/toc.md', $strMdStringToReturn);
			}

			return $strMdStringToReturn;
		}

		public static function Run($strPath) {
			if ($strPath) {
				// Get the full base path
				self::$basePath = realpath($strPath);
			}

			self::BuildDocumentTree();

			self::GenerateMainToc();

			if(self::$blnBuildIntermediateToc) {
				self::GenerateIntermediateToc();
			}
		}
	}

//	var_dump(TicToc::GetDocumentTreeForFolder('/home/vaibhav/code/tic-toc/docs'));
	TicToc::Run('/home/vaibhav/code/tic-toc/docs');
