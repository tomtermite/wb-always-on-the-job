<?php

defined('BASEPATH') or exit('No direct script access allowed'); ?>



<?php init_head(); ?>
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
        width: 88%;
        top: 40px;
    }

    .top_button {
        float: inline-end;
    }
</style>
<div id="wrapper">
    <div class="content">
        <div class="panel_s">
            <div class="panel-body">
                <div class="top_button">
                    <input type="hidden" id="parent_id" value="<?php echo $parent_id ?>">
                    <input type="hidden" id="id" value="<?php echo isset($id) ? $id : '' ?>">
                    <input type="hidden" id="version" value="<?php echo isset($version) ? $version : '1' ?>">
                    <?php
                    if (isset($type) && $type == 'view') { ?>
                         <input type="text" id="file_name" name="file_name" class="file_name" placeholder="<?php echo _l('document_name'); ?>" value="<?php echo isset($file_name) ? $file_name : '' ?>" autocomplete="off" readonly>
                        <a href="<?php echo admin_url('document/new_file_view/' . $parent_id); ?>" class="btn  btn-danger">
                            <i class="fa fa-times"></i> <?php echo _l('close'); ?>
                        </a>
                    <?php } else {  ?>
                        <input type="text" id="file_name" name="file_name" class="file_name" placeholder="<?php echo _l('document_name'); ?>" value="<?php echo isset($file_name) ? $file_name : '' ?>" autocomplete="off">
                        <a id="save_file" class="btn save_file btn-info">
                            <i class="fa fa-save"></i> <?php echo _l('save'); ?>
                        </a>
                        <a href="<?php echo admin_url('document/new_file_view/' . $parent_id); ?>" class="btn  btn-danger">
                            <i class="fa fa-times"></i> <?php echo _l('close'); ?>
                        </a>
                    <?php } ?>

                </div>

                <div id="userlist"></div>
                <div id="firepad-container"></div>
            </div>
        </div>
    </div>



</div>
<?php init_tail(); ?>
<script>
    $(document).ready(function() {
        init();
        var count_word = '';
         
        function init() {
         <?php if(isset($type) && $type == "view"){?>
            var view_only =  true;
          <?php }else{ ?>
            view_only = false;
          <?php } ?>
          console.log('view_only:', view_only)
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
            var userId = '<?php echo get_staff_user_id() ?>';

            //// Create Firepad (with rich text features and our desired userId).
            var firepad = Firepad.fromCodeMirror(firepadRef, codeMirror, {
                richTextToolbar: true,
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
        $("#save_file").click(function() {
            var document_folder_id = $("#parent_id").val();
            var pad_id = window.location.hash;
            var user_id = <?php echo get_staff_user_id() ?>;
            var name = $("#file_name").val();
            var id = $("#id").val();
            var version = $("#version").val();

            $.ajax({
                url: admin_url + 'document/add_chapter',
                type: 'POST',
                dataType: 'json',
                data: {
                    document_folder_id: document_folder_id,
                    pad_id: pad_id,
                    user_id: user_id,
                    name: name,
                    id: id,
                    latest_version: version,
                    number_of_words: count_word,
                },
                cache: false
            }).done(function(response) {
                if (response.status === 1) {
                    window.location.href = '<?php echo site_url('document/new_file_view/' . $parent_id); ?>';
                } else {

                }

            });


        });

    });
</script>