<?php
/**
 * Luxeritas WordPress Theme - free/libre wordpress platform
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * @copyright Copyright (C) 2015 Thought is free.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GPL v2 or later
 * @author LunaNuko
 * @link https://thk.kanzae.net/
 * @translators rakeem( http://rakeem.jp/ )
 */

if( class_exists( 'thk_zip_compress' ) === false ):
class thk_zip_compress {
	public function __construct() {
	}

	/*---------------------------------------------------------------------------
	 * ディレクトリごと ZIP 圧縮
	 *---------------------------------------------------------------------------*/
	public function all_zip( $path, $zip ) {
		if( class_exists( 'ZipArchive' ) === false ) {
			return( __( 'In the PHP environment you are using, ZipArchive could not be handled.', 'luxeritas' ) );
		}
		$arc = new ZipArchive();
		$iniset = 'ini' . '_set';
		$iniset( 'max_execution_time', 0 );
		$path = str_replace( DSEP, '/', $path );
		$path = rtrim( $path, '/' );

		if( $arc->open( $zip, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE ) === true ) {
			$this->add_zip( $arc, $path, '' );
			$arc->close();
		}
		else {
			return( __( 'Temporary file creation failed.', 'luxeritas' ) . '<br />' . $zip );
		}
		return true;
	}

	/*---------------------------------------------------------------------------
	 * ファイルをストリームに追加 (ディレクトリの場合は再帰的に配下を追加していく)
	 *---------------------------------------------------------------------------*/
	private function add_zip( $arc, $path, $zip ) {
		if( is_dir( $zip ) === false ) {
			$arc->addEmptyDir( $zip );
		}

		foreach( (array)$this->get_file_list( $path ) as $val ) {
			if( is_dir( $path . '/' . $val ) === true ) {
				$this->add_zip( $arc, $path . '/' . $val, $zip . '/' . $val );
			}
			else {
				$arc->addFile( $path . '/' . $val, $zip . '/' . $val );
			}
		}
	}

	/*---------------------------------------------------------------------------
	 * ディレクトリ内のファイル一覧取得
	 *---------------------------------------------------------------------------*/
	private function get_file_list( $path ) {
		$ret = array();
		if( is_dir( $path ) === true ) {
			foreach( (array)glob( $path . '/' . '*' ) as $val ) {
				$ret[] = basename( $val );
			}
		}
		return $ret;
	}
}
endif;
