<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <?php include 'head.php' ?>
    <body>
        <!-- Header -->
        <?php include 'header.php' ?>
        
        <div class="row fullWidth resto-title"></div>

        <br/><br/><br/>
        <div class="row" >
            <a id="_alert" href="#" class="button expand alert hide"></a>
            <form>
                <fieldset>
                    <legend><?php echo $self->context->dictionary->translate('_rights_collection_and_feature'); ?></legend>
                    <label><?php echo $self->context->dictionary->translate('_history_choose_collection'); ?>
                        <select id="collection" name="collection">
                            <option value=""></option>
                            <?php
                            foreach ($self->collectionsList as $collectionItem) {
                                ?>
                                <option value="<?php echo $collectionItem['collection']; ?>"><?php echo $collectionItem['collection']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </label>
                    <label><?php echo $self->context->dictionary->translate('_feature_id'); ?>
                        <input id="featureid" type="text" placeholder="featureid..." value="">
                    </label>
                </fieldset>
                <fieldset>
                    <legend><?php echo $self->context->dictionary->translate('_search'); ?></legend>
                    <input type="radio" name="search" value="true" id="search"><label for="search"><?php echo $self->context->dictionary->translate('_true'); ?></label>
                    <input type="radio" name="nosearch" value="false" id="nosearch"><label for="nosearch"><?php echo $self->context->dictionary->translate('_false'); ?></label>
                </fieldset>
                <fieldset>
                    <legend><?php echo $self->context->dictionary->translate('_visualize'); ?></legend>
                    <input type="radio" name="visualize" value="true" id="visualize"><label for="visualize"><?php echo $self->context->dictionary->translate('_true'); ?></label>
                    <input type="radio" name="novisualize" value="false" id="novisualize"><label for="novisualize"><?php echo $self->context->dictionary->translate('_false'); ?></label>
                </fieldset>
                <fieldset>
                    <legend><?php echo $self->context->dictionary->translate('_download'); ?></legend>
                    <input type="radio" name="download" value="true" id="download"><label for="download"><?php echo $self->context->dictionary->translate('_true'); ?></label>
                    <input type="radio" name="nodownload" value="false" id="nodownload"><label for="nodownload"><?php echo $self->context->dictionary->translate('_false'); ?></label>
                </fieldset>
                <fieldset>
                    <legend<?php echo $self->context->dictionary->translate('_can_post'); ?></legend>
                    <input type="radio" name="canpost" value="true" id="canpost"><label for="canpost"><?php echo $self->context->dictionary->translate('_true'); ?></label>
                    <input type="radio" name="cantpost" value="false" id="cantpost"><label for="cantpost"><?php echo $self->context->dictionary->translate('_false'); ?></label>
                </fieldset>
                <fieldset>
                    <legend><?php echo $self->context->dictionary->translate('_can_put'); ?></legend>
                    <input type="radio" name="canput" value="true" id="canput"><label for="canput"><?php echo $self->context->dictionary->translate('_true'); ?></label>
                    <input type="radio" name="cantput" value="false" id="cantput"><label for="cantput"><?php echo $self->context->dictionary->translate('_false'); ?></label>
                </fieldset>
                <fieldset>
                    <legend><?php echo $self->context->dictionary->translate('_can_delete'); ?></legend>
                    <input type="radio" name="candelete" value="true" id="candelete"><label for="candelete"><?php echo $self->context->dictionary->translate('_true'); ?></label>
                    <input type="radio" name="cantdelete" value="false" id="cantdelete"><label for="cantdelete"><?php echo $self->context->dictionary->translate('_false'); ?></label>
                </fieldset>

            </form>

            <a id="_save" href="#" class="button expand"><?php echo $self->context->dictionary->translate('_save_right'); ?></a>
        </div>
        <!-- Footer -->
        <?php include 'footer.php' ?>

        <script type="text/javascript" >
            $(document).ready(function() {

                var self = this;

                function initialize() {
                    $('input:radio[name=nodownload]').attr('checked', true);
                    $('input:radio[name=nosearch]').attr('checked', true);
                    $('input:radio[name=novisualize]').attr('checked', true);
                    $('input:radio[name=cantpost]').attr('checked', true);
                    $('input:radio[name=cantput]').attr('checked', true);
                    $('input:radio[name=cantdelete]').attr('checked', true);
                    $('#_alert').hide();
                }
                
                this.alert = function(element){
                    $('#_alert').text('Please set ' + element);
                    $('#_alert').show();
                }

                this.addRight = function() {
                    if ($('select[name=collection]').val() === ''){
                        self.alert('collection');
                    }else if ($("#featureid").val() === ''){
                        self.alert('featureid');
                    }else{
                        R.showMask();
                        
                        $.ajax({
                            type: "POST",
                            async: false,
                            url: "<?php echo $self->context->baseUrl . 'administration/users/' . $self->segments[1] . '/rights' ?>",
                            dataType: "json",
                            data: {
                                emailorgroup: '<?php echo $self->userProfile['email'] ?>',
                                collection: $('select[name=collection]').val(),
                                featureid: $("#featureid").val(),
                                search: $('input[name=search]:checked').val(),
                                visualize: $('input[name=visualize]:checked').val(),
                                download: $('input[name=download]:checked').val(),
                                canput: $('input[name=canput]:checked').val(),
                                canpost: $('input[name=canpost]:checked').val(),
                                candelete: $('input[name=candelete]:checked').val(),
                                filters: 'null'
                            },
                            success: function() {
                                window.location = "<?php echo $self->context->baseUrl . 'administration/users/' . $self->segments[1] ?>";
                                R.hideMask();
                            },
                            error: function() {
                                R.hideMask();
                                alert("error");
                            }
                        });
                    }
                };
                
                $("#_alert").on('click', function() {
                    $('#_alert').hide();
                });

                $("#_save").on('click', function() {
                    self.addRight();
                });

                $("#download").on('click', function() {
                    $('input:radio[name=download]').attr('checked', true);
                    $('input:radio[name=nodownload]').attr('checked', false);
                });

                $("#nodownload").on('click', function() {
                    $('input:radio[name=download]').attr('checked', false);
                    $('input:radio[name=nodownload]').attr('checked', true);
                });

                $("#search").on('click', function() {
                    $('input:radio[name=search]').attr('checked', true);
                    $('input:radio[name=nosearch]').attr('checked', false);
                });

                $("#nosearch").on('click', function() {
                    $('input:radio[name=search]').attr('checked', false);
                    $('input:radio[name=nosearch]').attr('checked', true);
                });

                $("#visualize").on('click', function() {
                    $('input:radio[name=visualize]').attr('checked', true);
                    $('input:radio[name=novisualize]').attr('checked', false);
                });

                $("#novisualize").on('click', function() {
                    $('input:radio[name=visualize]').attr('checked', false);
                    $('input:radio[name=novisualize]').attr('checked', true);
                });

                $("#canpost").on('click', function() {
                    $('input:radio[name=canpost]').attr('checked', true);
                    $('input:radio[name=cantpost]').attr('checked', false);
                });

                $("#cantpost").on('click', function() {
                    $('input:radio[name=canpost]').attr('checked', false);
                    $('input:radio[name=cantpost]').attr('checked', true);
                });

                $("#canput").on('click', function() {
                    $('input:radio[name=canput]').attr('checked', true);
                    $('input:radio[name=cantput]').attr('checked', false);
                });

                $("#cantput").on('click', function() {
                    $('input:radio[name=canput]').attr('checked', false);
                    $('input:radio[name=cantput]').attr('checked', true);
                });

                $("#candelete").on('click', function() {
                    $('input:radio[name=candelete]').attr('checked', true);
                    $('input:radio[name=cantdelete]').attr('checked', false);
                });

                $("#cantdelete").on('click', function() {
                    $('input:radio[name=candelete]').attr('checked', false);
                    $('input:radio[name=cantdelete]').attr('checked', true);
                });

                initialize();
                
                R.init({
                    language: '<?php echo $self->context->dictionary->language; ?>',
                    translation:<?php echo json_encode($self->context->dictionary->getTranslation()) ?>,
                    restoUrl: '<?php echo $self->context->baseUrl ?>',
                    ssoServices:<?php echo json_encode($self->context->config['ssoServices']) ?>,
                    userProfile:<?php echo json_encode(!isset($_SESSION['profile']) ? array('userid' => -1) : array_merge($_SESSION['profile'], array('rights' => $_SESSION['rights']))) ?> 
                });
            });
        </script>
    </body>
</html>
