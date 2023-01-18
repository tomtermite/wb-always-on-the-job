<?php

defined('BASEPATH') or exit('No direct script access allowed'); ?>



<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="panel_s">
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                            <input type="hidden" id="parent_id" value="<?php echo $parent_id ?>">
                            <input type="hidden" id="id" value="<?php echo isset($id) ? $id : '' ?>">
                            <input type="hidden" id="version" value="<?php echo isset($version) ? $version : '1' ?>">
                            <?php if (isset($type) && $type == 'view') { ?>
                                <h4 class="no-margin"><?php echo isset($file_name) ? $file_name : '' ?></h4>
                            <?php } else {  ?>
                                <h4 class="no-margin <?php echo isset($file_name) && $file_name !=''  ? '' : "hidden"; ?>" id="editable_header">
                                    <?php echo isset($file_name) ? $file_name : '' ?>
                                </h4>
                                <input type="text" id="file_name" name="file_name" class="file_name full-width <?php echo !isset($file_name) || $file_name ==''  ? '' : "hidden"; ?>" placeholder="<?php echo _l('document_name'); ?>" value="<?php echo isset($file_name) ? $file_name : '' ?>" autocomplete="off">
                            <?php } ?>
                    </div>
                    <div class="col-sm-12 col-md-4">
                        <h4 class="no-margin" id="version_title">Current Version : <?php echo isset($version) ? $version : 0; ?></h4>
                    </div>
                    <div class="col-sm-12 col-md-4 text-right">
                            <?php if (isset($type) && $type == 'view') { ?>
                                <a href="<?php echo admin_url('document/new_file_view/' . $parent_id); ?>" class="btn  btn-danger">
                                    <i class="fa fa-times"></i> <?php echo _l('close'); ?>
                                </a>
                            <?php } else { ?>
                                <a id="save_file" class="btn save_file btn-info">
                                    <i class="fa fa-save"></i> <?php echo _l('save'); ?>
                                </a>
                                <a href="<?php echo admin_url('document/new_file_view/' . $parent_id); ?>" class="btn  btn-danger">
                                    <i class="fa fa-times"></i> <?php echo _l('close'); ?>
                                </a>
                            <?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12" style="margin: 15px 0px;">
                        <?php $contents = ''; if(isset($description)){$contents = $description;} ?>
                        <?php //echo render_textarea('description123','',$contents,array("height" => "500px"),array(),'height-500','tinymce'); ?>
                        <textarea id="description" name="description"><?php echo $contents; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>



</div>
<?php init_tail(); ?>
<script>
    var type = '<?php echo isset($type) ? $type : ''; ?>';
    $(document).ready(function() {

        var editor = init_editor('#description', {
            append_plugins : 'wordcount'
        });
        editor.then(function(){
            // $("#description_ifr").height(window.innerHeight);
            tinymce.get("description").setContent($("#description").val());
            if(type == 'view'){
                tinymce.activeEditor.setMode('readonly');
            }
        });

        $("#editable_header").on("click", function(){
            $(this).addClass("hidden");
            $("#file_name").removeClass("hidden");
        })
        
        $("#save_file").click(function() {
            var document_folder_id = $("#parent_id").val();
            var pad_id = window.location.hash;
            var user_id = <?php echo get_staff_user_id() ?>;
            var name = $("#file_name").val();
            var id = $("#id").val();
            var version = $("#version").val();
            var theEditor = tinymce.activeEditor;
            wordCount = theEditor.plugins.wordcount.getCount();
            var description = tinyMCE.activeEditor.getContent()
            
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
                    number_of_words: wordCount,
                    description: description
                },
                cache: false
            }).done(function(response) {
                if (response.status === 1) {
                    
                    // window.location.href = '<?php echo site_url('document/new_file_view/' . $parent_id); ?>';
                    $("#file_name").addClass("hidden");
                    $("#editable_header").text(name);
                    $("#editable_header").removeClass("hidden");
                    $("#version").val(response.version);
                    $("#version_title").html("Current Version : "+response.version);
                    alert_float('success', response.message)

                    if(id == ''){
                        window.location.href = '<?php echo site_url('document/edit_chapter/'); ?>'+response.id;
                    }

                } else {
                    alert_float('error', response.message)
                }

            });


        });

    });
</script>