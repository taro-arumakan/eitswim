// editor_plugin.js

(function( $, document, window ){

    tinymce.create(

        'tinymce.plugins.MyButtons', {

            init: function( ed, url ) {

                ed.addButton(
                    'blockquote_link', {
                        title: '動画を挿入',
                        image: url + "/ico.png",
                        cmd: 'blockquote_cmd'
                    }
                );

                ed.addCommand( 'blockquote_cmd', function() {

                    ed.windowManager.open({

                        url: url + "/dialog.html",
                        width: 480,
                        height: 190,
                        title: '埋め込みコードを以下にペーストしてください。'

                    }, {
                        custom_param: 1
                    });

                });
            },

            createControl: function(n,cm) {

                return null;

            }
        }
    );
    tinymce.PluginManager.add(

        'custom_button_script',
        tinymce.plugins.MyButtons

    );
})(jQuery, document, window);