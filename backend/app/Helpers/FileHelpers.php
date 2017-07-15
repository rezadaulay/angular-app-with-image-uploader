<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File as File;

class FileHelpers
{
	public function readDir( $dir,$file_types = ['gif' => 'image/gif','png' => 'image/png','jpg' => 'image/jpeg'] ){
		$files = [];
		if (is_dir($dir)) {
			foreach (File::files($dir) as $entry) {
				if (!is_dir($entry)) {
					if (in_array(mime_content_type($entry), $file_types)) {
						$files[] = $entry;
					}
				}
			}
		}
		return $files;
	}

	public function publicPath($path=null)
    {
		return asset($path);
    }
}