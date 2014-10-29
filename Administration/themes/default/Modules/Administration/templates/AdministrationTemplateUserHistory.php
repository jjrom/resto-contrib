<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <?php include 'head.php' ?>
    <body>
        
        <!-- Header -->
        <?php include 'header.php' ?>
        
        <div class="row fullWidth resto-title"></div>

        <br/><br/><br/>
        <div class="row" >
            <h1><?php echo $self->context->dictionary->translate('_history'); ?></h1>
            <br/>
            <div class="row">
                <div class="large-12 columns">
                    <label><?php echo $self->context->dictionary->translate('_history_choose_service'); ?>
                        <select id="serviceSelector" name="serviceSelector">
                            <option value=""></option>
                            <option value="download"><?php echo $self->context->dictionary->translate('_download'); ?></option>
                            <option value="search"><?php echo $self->context->dictionary->translate('_search'); ?></option>
                            <option value="resource"><?php echo $self->context->dictionary->translate('_resource'); ?></option>
                            <option value="insert"><?php echo $self->context->dictionary->translate('_insert'); ?></option>
                            <option value="create"><?php echo $self->context->dictionary->translate('_create'); ?></option>
                            <option value="update"><?php echo $self->context->dictionary->translate('_update'); ?></option>
                            <option value="remove"><?php echo $self->context->dictionary->translate('_remove'); ?></option>
                        </select>
                    </label>
                </div>
                <div class="large-12 columns">
                    <label><?php echo $self->context->dictionary->translate('_history_choose_collection'); ?>
                        <select id="collectionSelector" name="collectionSelector">
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
                </div>
            </div>
            <br/>
            <ul class="small-block-grid-1 large-block-grid-1">
                <?php
                foreach ($self->historyList as $history) {
                    $userProfile = $self->user->profile;
                    ?>
                    <li>
                        <div class="panel">
                            <?php
                            echo $history['method'] . ' ' . $history['service'] . ' ' . $history['collection'] . ' ' . $history['resourceid'] . ' ' . $history['query'] . ' ' . $history['querytime'] . ' ' . $history['url'] . ' ' . $history['ip'];
                            ?>
                        </div>
                    </li>
                <?php } ?>
            </ul>
            <div style="text-align: center">
                <?php
                if ($self->startIndex != 0) {
                    echo '<a id="previous" href="#" class="button">' . $self->context->dictionary->translate('_previousPage') . '</a>';
                }
                if (sizeof($self->historyList) >= $self->numberOfResults) {
                    echo '<a id="next" href="#" style="margin-left: 5px;" class="button">' . $self->context->dictionary->translate('_nextPage') . '</a>';
                }
                ?>
            </div>
        </div>
        <!-- Footer -->
        <?php include 'footer.php' ?>
        
        <script type="text/javascript" >
            $(document).ready(function() {

                var self = this;
                $min = <?php echo $self->startIndex; ?>;
                $number = <?php echo $self->numberOfResults; ?>;
                
                function initialize() {
                    $('select[name=serviceSelector]').val('<?php echo (filter_input(INPUT_GET, 'service') ? filter_input(INPUT_GET, 'service') : ""); ?>');
                    $('select[name=collectionSelector]').val('<?php echo (filter_input(INPUT_GET, 'collection') ? filter_input(INPUT_GET, 'collection') : ""); ?>');
                }
                
                $("#serviceSelector").on('change', function() {
                    window.location = "<?php echo $self->context->baseUrl . 'administration/users/' . $self->segments[1] . '/history?service=' ?>" + $('select[name=serviceSelector]').val() + "&collection=" + $('select[name=collectionSelector]').val();
                });

                $("#collectionSelector").on('change', function() {
                    window.location = "<?php echo $self->context->baseUrl . 'administration/users/' . $self->segments[1] . '/history?service=' ?>" + $('select[name=serviceSelector]').val() + "&collection=" + $('select[name=collectionSelector]').val();
                });

                $("#next").on('click', function() {
                    $min = $min + $number;
                    url = "<?php echo $self->context->baseUrl . 'administration/users/' . $self->segments[1] . '/history?service=' ?>" + $('select[name=serviceSelector]').val() + "&collection=" + $('select[name=collectionSelector]').val() + "&startIndex=" + $min + "&numberOfResults=" + $number;
                    window.location = url;
                });

                $("#previous").on('click', function() {
                    $min = $min - $number;
                    if ($min < 0) {
                        $min = 0;
                    }
                    url = "<?php echo $self->context->baseUrl . 'administration/users/' . $self->segments[1] . '/history?service=' ?>" + $('select[name=serviceSelector]').val() + "&collection=" + $('select[name=collectionSelector]').val() + "&startIndex=" + $min + "&numberOfResults=" + $number;
                    window.location = url;
                });
                
                initialize();

            });
        </script>
    </body>
</html>
