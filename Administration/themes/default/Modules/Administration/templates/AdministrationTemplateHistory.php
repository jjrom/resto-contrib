<?php
$startIndex = 0;
$numberOfResults = 50;
$keyword = null;
if (filter_input(INPUT_GET, 'startIndex')) {
    $startIndex = filter_input(INPUT_GET, 'startIndex');
}
if (filter_input(INPUT_GET, 'numberOfResults')) {
    $numberOfResults = filter_input(INPUT_GET, 'numberOfResults');
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title>RESTo framework</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />
        <link rel="stylesheet" href="<?php echo $self->context->baseUrl ?>js/lib/foundation/foundation.min.css" type="text/css" />
        <script type="text/javascript" src="<?php echo $self->context->baseUrl ?>js/lib/jquery/jquery-1.11.1.min.js"></script>
        <link rel="shortcut icon" href="<?php echo $self->context->baseUrl ?>favicon.ico" />
        <link rel="stylesheet" href="<?php echo $self->context->baseUrl ?>themes/<?php echo $self->context->config['theme'] ?>/style.css" type="text/css" /> 
        <link rel="stylesheet" href="<?php echo $self->context->baseUrl ?>themes/default/style_min.css" type="text/css" />
    </head>
    <body>
        <script type="text/javascript" >
            $(document).ready(function() {

                var self = this;
                $min = <?php echo $startIndex; ?>;
                $number = <?php echo $numberOfResults; ?>;
                $keyword = "<?php echo $keyword; ?>";


                function initialize() {
                    $('select[name=serviceSelector]').val('<?php echo (filter_input(INPUT_GET, 'service') ? filter_input(INPUT_GET, 'service') : ""); ?>');
                    $('select[name=collectionSelector]').val('<?php echo (filter_input(INPUT_GET, 'collection') ? filter_input(INPUT_GET, 'collection') : ""); ?>');
                }

                $("#serviceSelector").on('change', function() {
                    window.location = "<?php echo $self->context->baseUrl . 'administration/users/history?service=' ?>" + $('select[name=serviceSelector]').val() + "&collection=" + $('select[name=collectionSelector]').val();
                });

                $("#collectionSelector").on('change', function() {
                    window.location = "<?php echo $self->context->baseUrl . 'administration/users/history?service=' ?>" + $('select[name=serviceSelector]').val() + "&collection=" + $('select[name=collectionSelector]').val();
                });

                $("#next").on('click', function() {
                    $min = $min + $number;
                    url = "<?php echo $self->context->baseUrl . 'administration/users/history?service=' ?>" + $('select[name=serviceSelector]').val() + "&collection=" + $('select[name=collectionSelector]').val() + "&startIndex=" + $min + "&numberOfResults=" + $number;
                    window.location = url;
                });

                $("#previous").on('click', function() {
                    $min = $min - $number;
                    if ($min < 0) {
                        $min = 0;
                    }
                    url = "<?php echo $self->context->baseUrl . 'administration/users/history?service=' ?>" + $('select[name=serviceSelector]').val() + "&collection=" + $('select[name=collectionSelector]').val() + "&startIndex=" + $min + "&numberOfResults=" + $number;
                    window.location = url;
                });

                initialize();
            });
        </script>
        <?php include $self->header; ?>
        <div class="row fullWidth resto-title">

        </div>

        <br/>
        <br/>
        <br/>
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
                        </select>
                    </label>
                </div>
                <div class="large-12 columns">
                    <label><?php echo $self->context->dictionary->translate('_history_choose_collection'); ?>
                        <select id="collectionSelector" name="collectionSelector">
                            <option value=""></option>
                            <?php
                            $collectionsList = $self->context->dbDriver->listCollections();
                            foreach ($collectionsList as $collectionItem) {
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
                $collection = null;
                $service = null;
                $orderBy = null;
                $ascordesc = null;
                if (filter_input(INPUT_GET, 'collection')) {
                    $collection = filter_input(INPUT_GET, 'collection');
                }
                if (filter_input(INPUT_GET, 'service')) {
                    $service = filter_input(INPUT_GET, 'service');
                }
                if (filter_input(INPUT_GET, 'orderBy')) {
                    $orderBy = filter_input(INPUT_GET, 'orderBy');
                }
                if (filter_input(INPUT_GET, 'ascordesc')) {
                    $ascordesc = filter_input(INPUT_GET, 'ascordesc');
                }
                if (filter_input(INPUT_GET, 'limit')) {
                    $limit = filter_input(INPUT_GET, 'limit');
                }

                $options = array(
                    'orderBy' => $orderBy,
                    'ascOrDesc' => $ascordesc,
                    'collectionName' => $collection,
                    'service' => $service,
                    'startIndex' => $startIndex,
                    'numberOfResults' => $numberOfResults
                );
                $historyList = $self->context->dbDriver->getHistory(null, $options);
                foreach ($historyList as $history) {
                    ?>
                    <li>
                        <div class="panel">
                            <?php
                            echo $history['userid'] . '<br/>';
                            echo $history['method'] . ' ' . $history['service'] . ' ' . $history['collection'] . ' ' . $history['querytime'] . '<br/>';
                            echo $history['query'] . '<br/>';
                            echo $history['url'] . ' ' . $history['ip'];
                            ?>
                        </div>
                    </li>
                <?php } ?>
            </ul>
            <div style="text-align: center">
                <?php
                if ($startIndex != 0) {
                    echo '<a id="previous" href="#" class="button">' . $self->context->dictionary->translate('_previousPage') . '</a>';
                }
                if (sizeof($historyList) >= $numberOfResults) {
                    echo '<a id="next" href="#" style="margin-left: 5px;" class="button">' . $self->context->dictionary->translate('_nextPage') . '</a>';
                }
                ?>
            </div>
        </div>
        <?php include $self->footer; ?>
        <?php exit; ?>
    </body>
</html>
