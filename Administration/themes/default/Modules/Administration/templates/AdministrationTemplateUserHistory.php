<?php
    $_noSearchBar = true;
    $_noMap = true;
    $color_download = '#BBD2E1';
    $color_insert = '#FEF86C';
    $color_create = '#3AF24B';
    $color_remove = '#FA5858';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <?php include realpath(dirname(__FILE__)) . '/../../../templates/' . 'head.php' ?>
    <body style="overflow-x: hidden;">
        
        <!-- Header -->
        <?php include realpath(dirname(__FILE__)) . '/../../../templates/' . 'header.php' ?>
        
        <!-- Breadcrumb -->
        <?php include 'breadcrumb.php' ?>
       
        <div class="row" >
            <h1><?php echo $self->context->dictionary->translate('_a_history'); ?></h1>
            <br/>
            <div class="row">
                <div class="large-12 columns">
                    <label><?php echo $self->context->dictionary->translate('_a_choose_service'); ?>
                        <select id="serviceSelector" name="serviceSelector">
                            <option value=""></option>
                            <option value="download"><?php echo $self->context->dictionary->translate('_download'); ?></option>
                            <option value="search"><?php echo $self->context->dictionary->translate('_a_search'); ?></option>
                            <option value="resource"><?php echo $self->context->dictionary->translate('_a_visualize'); ?></option>
                            <option value="insert"><?php echo $self->context->dictionary->translate('_a_insert'); ?></option>
                            <option value="create"><?php echo $self->context->dictionary->translate('_a_create'); ?></option>
                            <option value="update"><?php echo $self->context->dictionary->translate('_a_update'); ?></option>
                            <option value="remove"><?php echo $self->context->dictionary->translate('_a_remove'); ?></option>
                        </select>
                    </label>
                </div>
                <div class="large-12 columns">
                    <label><?php echo $self->context->dictionary->translate('_a_choose_collection'); ?>
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
            <ul class="small-block-grid-1 large-block-grid-2 data_container">
                <?php 
                foreach ($self->historyList as $history) {
                    ?>
                    <li>
                        <?php if ($history['service'] === 'download'){ ?>
                        <div class="panel" style="background-color: <?php echo $color_download; ?>">
                        <?php } else if ($history['service'] === 'insert'){ ?>
                        <div class="panel" style="background-color: <?php echo $color_insert; ?>">
                        <?php } else if ($history['service'] === 'create'){ ?>
                        <div class="panel" style="background-color: <?php echo $color_create; ?>">
                        <?php } else if ($history['service'] === 'remove'){ ?>
                        <div class="panel" style="background-color: <?php echo $color_remove; ?>">
                        <?php } else {?>
                        <div class="panel">
                        <?php } ?>
                            <h2><a href="<?php 
                                if ($history['collection'] === '*'){
                                    $title = 'All';
                                    $url = $self->context->baseUrl . '/collections/';
                                }else{
                                    $title = $history['collection'];
                                    $url = $self->context->baseUrl . '/collections/' .  $history['collection'];
                                }
                                echo $url;?>"><?php echo $title; ?></a></h2>
                            <p>
                                <a href="<?php echo $self->context->baseUrl . 'administration/users/' . $history['userid']; ?>"><?php echo $self->context->dictionary->translate('_a_userid') . ' : ' . $history['userid']; ?></a><br/>
                                <?php
                                echo $self->context->dictionary->translate('_a_service') . ' : ' . $history['service'] . '<br/>';
                                echo $history['querytime'];
                                ?>
                            </p>
                            <a href="<?php echo $history['url']; ?>"><?php echo $history['url']; ?></a>
                            
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <!-- Footer -->
        <?php include realpath(dirname(__FILE__)) . '/../../../templates/' . 'footer.php' ?>
        
        <script type="text/javascript" >
            $(document).ready(function() {

                service_selector = "<?php echo $self->service; ?>";
                collection_selector = "<?php echo $self->collectionFilter; ?>";
                min = <?php echo $self->startIndex; ?>;
                number = <?php echo $self->numberOfResults; ?>;
                $ajaxReady = true;


                function initialize() {
                    $('select[name=serviceSelector]').val('<?php echo (filter_input(INPUT_GET, 'service') ? filter_input(INPUT_GET, 'service') : ""); ?>');
                    $('select[name=collectionSelector]').val('<?php echo (filter_input(INPUT_GET, 'collection') ? filter_input(INPUT_GET, 'collection') : ""); ?>');
                }

                $("#serviceSelector").on('change', function() {
                    Resto.Util.showMask();
                    selector();
                    Resto.Util.hiedeMask();
                });

                $("#collectionSelector").on('change', function() {
                    Resto.Util.showMask();
                    selector();
                    Resto.Util.hiedeMask();
                });
                
                function selector(){
                    collectionSelector = $('select[name=collectionSelector]').val();
                    serviceSelector = $('select[name=serviceSelector]').val();
                    if (!serviceSelector && !collectionSelector){
                        window.location = "<?php echo $self->context->baseUrl . 'administration/users/history' ?>" ;
                    }else if (serviceSelector && !collectionSelector){
                        window.location = "<?php echo $self->context->baseUrl . 'administration/users/history?service=' ?>" + $('select[name=serviceSelector]').val();
                    }else if (!serviceSelector && collectionSelector){
                        window.location = "<?php echo $self->context->baseUrl . 'administration/users/history?' ?>" + "collection=" + $('select[name=collectionSelector]').val();
                    }else{
                        window.location = "<?php echo $self->context->baseUrl . 'administration/users/history?service=' ?>" + $('select[name=serviceSelector]').val() + "&collection=" + $('select[name=collectionSelector]').val();
                    }
                }
                
                function addToList(data){
                    
                    $.each(data, function(key, value){
                        if (value['collection'] === '*'){
                            collection = 'All';
                        }else{
                            collection = value['collection'];
                        }
                        if (value['service'] === 'download'){
                            color = 'style="background-color: <?php echo $color_download; ?>"';
                        }else if (value['service'] === 'insert'){
                            color = 'style="background-color: <?php echo $color_insert; ?>"';
                        }else if (value['service'] === 'create'){
                            color = 'style="background-color: <?php echo $color_create; ?>"';
                        }else if (value['service'] === 'remove'){
                            color = 'style="background-color: <?php echo $color_remove; ?>"';
                        }
                        
                        content = '<li><div class="panel" '
                                + color
                                + '><h2><a href="<?php echo $self->context->baseUrl . 'collections/'?>' 
                                + value['collection']
                                + '">'
                                + collection
                                + '</a></h2>' 
                                + '<p>'
                                + '<a href="<?php echo $self->context->baseUrl . 'administration/users/'; ?>' + value['userid'] + '">'
                                + '<?php echo $self->context->dictionary->translate('_a_userid') . ' : '; ?>' + value['userid'] + '</a><br/>'
                                + '<?php echo $self->context->dictionary->translate('_a_service') . ' : ';?>' + value['service'] + '<br/>'
                                + value['querytime']
                                + '</p>'
                                + '<a href="' + value['url'] + '">' + value['url'] + '</a>'
                                + '</div></li>';
                        $(".data_container").append(content);
                    });
                    
                }
                
                url = "<?php echo $self->context->baseUrl . 'administration/users/' . $self->segments[1] . '/history' ?>";
                dataType = "json";
                data = {
                        service: service_selector,
                        collection: collection_selector,
                        startIndex: min + number,
                        numberOfResults: number
                };
                
                Resto.Util.infiniteScroll(url, dataType, data, addToList, number);

                initialize();
                
                $(document).ready(function() {
                    Resto.init({
                        "translation":<?php echo json_encode($self->context->dictionary->getTranslation()) ?>,
                        "language":'<?php echo $self->context->dictionary->language; ?>',
                        "restoUrl":'<?php echo $self->context->baseUrl ?>',
                        "ssoServices":<?php echo json_encode($self->context->config['ssoServices']) ?>,
                        "userProfile":<?php echo json_encode(!isset($_SESSION['profile']) ? array('userid' => -1) : array_merge($_SESSION['profile'], array('rights' => isset($_SESSION['rights']) ? $_SESSION['rights'] : array()))) ?>
                    });
                });
                
            });
        </script>
    </body>
</html>
