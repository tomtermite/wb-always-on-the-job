<?php

defined('BASEPATH') or exit('No direct script access allowed'); ?>

<script src="https://www.gstatic.com/firebasejs/5.5.4/firebase.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/codemirror.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.17.0/codemirror.css" />
<link rel="stylesheet" href="https://firepad.io/releases/v1.5.9/firepad.css" />
<script src="https://firepad.io/releases/v1.5.9/firepad.min.js"></script>
<style>
    #userlist {
        position: absolute;
        left: 15px;
        top: 73px;
        bottom: 0;
        height: auto;
        width: 185px;
        background-color: #eaeaea;
    }

    #firepad-container {
        position: inherit;
        left: 190px;
        bottom: 0;
        right: 3px;
        height: 800px;
        width: 80%;
        top: 40px;
    }

    .firepad-toolbar {
        height: 80px !important;
        line-height: 25px !important;
    }

    .top_button {
        float: inline-end;
    }
</style>
<div id="wrapper">
    <div class="content">
        <div class="panel_s">
            <div class="panel-body">
                <div class="top_button" style="text-align: end;">
                    <input type="hidden" id="parent_id" value="<?php echo $parent_id ?>">
                    <input type="hidden" id="id" value="<?php echo isset($id) ? $id : '' ?>">
                
                        <input type="text" id="file_name" name="file_name" class="file_name" placeholder="<?php echo _l('document_name'); ?>" value="<?php echo isset($file_name) ? $file_name : '' ?>" autocomplete="off" readonly>
                        <a href="<?php echo site_url('document/document_client/client_file_view/' . $parent_id); ?>" class="btn  btn-danger">
                            <i class="fa fa-times"></i> <?php echo _l('close'); ?>
                        </a>
                </div>

                <div id="userlist"></div>
                <div id="firepad-container"></div>
            </div>
        </div>
    </div>



</div>
<script>
    $(document).ready(function() {
        init();
        var count_word = '';

        function init() {
            if ('<?php echo $type ?>' == "view") {
                var view_only = true;
                var richtext_tool = false; 
            } else {
                view_only = false;
                richtext_tool = true;
            }
            //// Initialize Firebase.
            //// TODO: replace with your Firebase project configuration.
            var config = {
                apiKey: 'AIzaSyCo4rEENWk-ysXHzBfKItVA6M5xt8pLEzw',
                authDomain: "fire-pad-cfc98.firebaseapp.com",
                databaseURL: "https://fire-pad-cfc98-default-rtdb.firebaseio.com"
            };
            firebase.initializeApp(config);

            //// Get Firebase Database reference.
            var firepadRef = getExampleRef();
            console.log('firepadRef:', firepadRef)

            //// Create CodeMirror (with lineWrapping on).
            var codeMirror = CodeMirror(document.getElementById('firepad-container'), {
                lineWrapping: true,
                readOnly: view_only
            });

            // Create a random ID to use as our user ID (we must give this to firepad and FirepadUserList).
            var userId = '<?php echo get_client_user_id() ?>';

            //// Create Firepad (with rich text features and our desired userId).
            var firepad = Firepad.fromCodeMirror(firepadRef, codeMirror, {
                richTextToolbar: richtext_tool,
                richTextShortcuts: true,
                userId: userId,
            });


            //// Create FirepadUserList (with our desired userId).
            var firepadUserList = FirepadUserList.fromDiv(firepadRef.child('users'),
                document.getElementById('userlist'), userId);

            //// Initialize contents.
            firepad.on('ready', function() {
                if (firepad.isHistoryEmpty()) {
                    firepad.setText('Check out the user list to the top!');
                }
            });
            firepad.on('synced', function(isSynced) {
                var get_word_count = firepad.getText();
                count_word = get_word_count.length;

            });
        }

        // Helper to get hash from end of URL or generate a random one.
        function getExampleRef() {
            var ref = firebase.database().ref();
            var hash = window.location.hash.replace(/#/g, '');
            if (hash) {
                ref = ref.child(hash);
            } else {
                ref = ref.push(); // generate unique location.
                window.location = window.location + '#' + ref.key; // add it as a hash to the URL.
            }
            if (typeof console !== 'undefined') {
                console.log('Firebase data: ', ref.toString());
            }
            return ref;
        }


    });
</script>