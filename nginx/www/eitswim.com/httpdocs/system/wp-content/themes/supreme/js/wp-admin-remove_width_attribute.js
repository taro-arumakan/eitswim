/**
 * Created by genji on 2017/07/06.
 *
 *
 *
 */
(function( $, wp, _ ) {

  if ( ! wp.media.events ) {
    return;
  }

  wp.media.events.on( 'editor:image-update', function( options ) {
    var editor = options.editor,
      dom = editor.dom,
      image  = options.image;
    dom.setAttribs( image, {'width': null, 'height': null});
    //tinyMCE.activeEditor.dom.remove(tinyMCE.activeEditor.dom.select('a'));
    tinyMCE.activeEditor.dom.setAttrib(tinyMCE.activeEditor.dom.select('a'), 'data-lightbox', 'image');
    tinyMCE.activeEditor.dom.setAttrib(tinyMCE.activeEditor.dom.select('img'), 'class', null);
  } );

})( jQuery, wp, _ );