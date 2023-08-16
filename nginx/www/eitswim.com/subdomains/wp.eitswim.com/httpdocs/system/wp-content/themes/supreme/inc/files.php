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

class thk_files extends thk_optimize {
	private $_js_dir	= null;
	private $_css_dir	= null;
	private $_tmpl_dir	= null;

	public function __construct() {
		$this->_js_dir = TPATH . DSEP . 'js' . DSEP;
		$this->_css_dir = TPATH . DSEP . 'css' . DSEP;
		$this->_tmpl_dir = TPATH . DSEP . 'styles' . DSEP;
	}

	protected function styles() {
		return array(
			'luxe-mode'		=> $this->_css_dir . 'luxe-mode.css',
			'bootstrap'		=> $this->_css_dir . 'bootstrap3/bootstrap.min.css',
			'bootstrap4'		=> $this->_css_dir . 'bootstrap4/bootstrap.min.css',
			'awesome'		=> $this->_css_dir . 'font-awesome.css',
			'awesome-minimum'	=> $this->_css_dir . 'font-awesome.luxe.minimum.css',
			'icomoon'		=> $this->_css_dir . 'icomoon.css',

			'style_thk'	=> TPATH . DSEP . 'style.css',
			'grid'		=> $this->_tmpl_dir . 'grid.css',
			'sns'		=> $this->_tmpl_dir . 'sns.css',
			'sns-flat'	=> $this->_tmpl_dir . 'sns-flat.css',
			'sns-icon'	=> $this->_tmpl_dir . 'sns-icon.css',
			'sns-normal'	=> $this->_tmpl_dir . 'sns-normal.css',

			'blogcard'	=> $this->_tmpl_dir . 'blogcard.css',
			'search'	=> $this->_tmpl_dir . 'widget-search.css',
			'archive'	=> $this->_tmpl_dir . 'widget-archive.css',
			'calendar'	=> $this->_tmpl_dir . 'widget-calendar.css',
			'new-post'	=> $this->_tmpl_dir . 'widget-new-post.css',
			'rcomments'	=> $this->_tmpl_dir . 'widget-rcomments.css',
			'adsense'	=> $this->_tmpl_dir . 'widget-adsense.css',
			'follow-button'	=> $this->_tmpl_dir . 'widget-follow-button.css',
			'rss-feedly'	=> $this->_tmpl_dir . 'widget-rss-feedly.css',
			'qr-code'	=> $this->_tmpl_dir . 'widget-qr-code.css',

			'head-search'	=> $this->_tmpl_dir . 'head-search.css',
			'mobile-common'	=> $this->_tmpl_dir . 'mobile-common.css',
			'mobile-menu'	=> $this->_tmpl_dir . 'mobile-menu.css',
			'mobile-luxury'	=> $this->_tmpl_dir . 'mobile-luxury.css',
		);
	}

	protected function styles_async() {
		return array(
			'awesome'		=> $this->_css_dir . 'font-awesome.css',
			'awesome-minimum'	=> $this->_css_dir . 'font-awesome.luxe.minimum.css',
			'icomoon'		=> $this->_css_dir . 'icomoon.css',
			'tosrus'		=> $this->_css_dir . 'jquery.tosrus.all.css',
			'lightcase'		=> $this->_css_dir . 'lightcase.min.css',
			'fluidbox'		=> $this->_css_dir . 'fluidbox.min.css',
			'autocomplete'		=> $this->_css_dir . 'autocomplete.css',
			'print'			=> $this->_tmpl_dir . 'print.css',
		);
	}

	protected function styles_amp() {
		return array(
			'luxe-amp'		=> $this->_css_dir . 'luxe-amp.css',

			//'awesome-minimum'	=> $this->_css_dir . 'font-awesome.luxe.minimum.css',
			'icomoon'		=> $this->_css_dir . 'icomoon.css',

			'style_amp'	=> TPATH . DSEP . 'style-amp.css',
			'sns'		=> $this->_tmpl_dir . 'sns.css',
			'sns-flat'	=> $this->_tmpl_dir . 'sns-flat.css',
			'sns-icon'	=> $this->_tmpl_dir . 'sns-icon.css',

			'blogcard'	=> $this->_tmpl_dir . 'blogcard.css',
			'search'	=> $this->_tmpl_dir . 'widget-search.css',
			'archive'	=> $this->_tmpl_dir . 'widget-archive.css',
			'calendar'	=> $this->_tmpl_dir . 'widget-calendar.css',
			'new-post'	=> $this->_tmpl_dir . 'widget-new-post.css',
			'rcomments'	=> $this->_tmpl_dir . 'widget-rcomments.css',
			'adsense'	=> $this->_tmpl_dir . 'widget-adsense-amp.css',
			'follow-button'	=> $this->_tmpl_dir . 'widget-follow-button.css',
			'rss-feedly'	=> $this->_tmpl_dir . 'widget-rss-feedly.css',
			'qr-code'	=> $this->_tmpl_dir . 'widget-qr-code.css',
		);
	}

	protected function scripts_defer() {
		return array(
			'sscroll'	=> $this->_js_dir . 'jquery.smoothScroll.min.js',
			'stickykit'	=> $this->_js_dir . 'jquery.sticky-kit.min.js',
			'lazyload'	=> $this->_js_dir . 'jquery.lazyload.min.js',
			'autosize'	=> $this->_js_dir . 'autosize.min.js',
			'tosrus'	=> $this->_js_dir . 'jquery.tosrus.all.min.js',
			'lightcase'	=> $this->_js_dir . 'lightcase.js',
			'throttle'	=> $this->_js_dir . 'jquery.ba-throttle-debounce.min.js',
			'fluidbox'	=> $this->_js_dir . 'jquery.fluidbox.min.js',
			'autocomplete'	=> $this->_js_dir . 'jquery-ui-autocomplete.js',
			'luxe'		=> true,
		);
	}

	protected function scripts_async() {
		return array(
			'async'		=> true,
			//'lazyload'	=> $this->_js_dir . 'jquery.lazyload.min.js'
			//'flexibility'	=> $this->_js_dir . 'flexibility.js'
			//'md5'		=> $this->_js_dir . 'md5.min.js',
		);
	}

	protected function scripts_search_highlight() {
		return array(
			'highlight'	=> $this->_js_dir . 'jquery.highlight.js',
			'supertext'	=> $this->_js_dir . 'jquery-supertextconverter-plugin.min.js',
			'thk-highlight'	=> $this->_js_dir . 'thk-highlight.js'
		);
	}

	protected function dir_replace() {
		return array(
			'bootstrap'		=> true,
			'awesome'		=> true,
			'awesome-minimum'	=> true,
			'icomoon'		=> true,
			'tosrus'		=> true,
			'lightcase'		=> true
		);
	}

	protected function jquery() {
		return array(
			//'jquery'    => ABSPATH . WPINC . '/js/jquery/jquery.js',
			//'migrate'   => ABSPATH . WPINC . '/js/jquery/jquery-migrate.min.js'
		);
	}
}
